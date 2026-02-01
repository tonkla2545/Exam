<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // หน้าเลือกหน่วยงาน
    public function selectAgency()
    {
        // ดึงข้อมูลหน่วยงานทั้งหมด พร้อมนับจำนวนหัวข้อในแต่ละหน่วยงาน
        $agencies = Agency::withCount('topics')->get();

        // ส่งข้อมูลหน่วยงานไปแสดงที่ view exam.index
        return view('exam.index', compact('agencies'));
    }

    // หน้าเลือกเรื่อง
    public function selectTopic(Agency $agency)
    {
        // ดึงข้อมูลหัวข้อทั้งหมดของหน่วยงานนี้ พร้อมนับจำนวนคำถามในแต่ละหัวข้อ
        $topics = $agency->topics()->withCount('questions')->get();

        // ส่งข้อมูลหน่วยงานและหัวข้อไปแสดงที่ view exam.topics
        return view('exam.topics', compact('agency', 'topics'));
    }

    // เริ่มสอบ (สุ่ม 20 ข้อ ครั้งเดียว)
    public function start(Topic $topic)
    {
        // สุ่มคำถาม 20 ข้อจากหัวข้อที่เลือก และเก็บเฉพาะ ID ของคำถาม
        $questionIds = Question::where('topic_id', $topic->id) // กรองคำถามจากหัวข้อที่เลือก
            ->inRandomOrder() // สุ่มลำดับคำถาม
            ->limit(20) // จำกัดไม่เกิน 20 ข้อ
            ->pluck('id') // ดึงเฉพาะคอลัมน์ id
            ->toArray(); // แปลงเป็น array

        // ตรวจสอบว่ามีข้อสอบเพียงพอหรือไม่
        if (empty($questionIds)) {
            // ถ้าไม่มีคำถาม ให้กลับไปหน้าเลือกหัวข้อพร้อมแสดงข้อความแจ้งเตือน
            return redirect()->route('topic.select', $topic->agency_id)
                ->with('error', 'ไม่มีข้อสอบในหัวข้อนี้');
        }

        // เก็บข้อมูลการสอบลงใน session
        session([
            'exam.topic_id' => $topic->id, // เก็บ ID ของหัวข้อ
            'exam.topic_name' => $topic->name, // เก็บชื่อหัวข้อ
            'exam.questions' => $questionIds, // เก็บ array ของ ID คำถามที่สุ่มได้
            'exam.current' => 0, // กำหนดข้อปัจจุบันเป็นข้อที่ 0 (ข้อแรก)
            'exam.correct' => 0, // กำหนดจำนวนข้อที่ตอบถูกเป็น 0
            'exam.wrong' => 0, // กำหนดจำนวนข้อที่ตอบผิดเป็น 0
            'exam.answered' => [], // เก็บ ID ของคำถามที่ตอบไปแล้ว (ป้องกันตอบซ้ำ)
        ]);

        // ไปหน้าแสดงคำถามข้อแรก
        return redirect()->route('exam.question');
    }

    // แสดงข้อสอบทีละข้อ
    public function question(Request $request)
    {
        // ดึง array ของ ID คำถามจาก session
        $questions = session('exam.questions');

        // ตรวจสอบว่ามี session ข้อสอบหรือไม่
        if (! $questions) {
            // ถ้าไม่มี ให้กลับไปหน้าเลือกหน่วยงานพร้อมแสดงข้อความแจ้งเตือน
            return redirect()->route('agency.select')
                ->with('error', 'กรุณาเริ่มทำข้อสอบใหม่');
        }

        // รองรับการข้ามไปข้อที่ระบุ (สำหรับกรณีต้องการกลับไปดูข้อก่อนหน้า)
        if ($request->has('goto')) {
            // ดึงค่าพารามิเตอร์ goto จาก request
            $goto = (int) $request->get('goto');
            // ตรวจสอบว่าค่าที่ระบุอยู่ในช่วงที่ถูกต้องหรือไม่
            if ($goto >= 0 && $goto < count($questions)) {
                // อัพเดทข้อปัจจุบันใน session
                session(['exam.current' => $goto]);
            }
        }

        // ดึงหมายเลขข้อปัจจุบันจาก session (ถ้าไม่มีให้เป็น 0)
        $current = session('exam.current', 0);

        // ตรวจสอบว่าทำข้อสอบครบทุกข้อแล้วหรือยัง
        if ($current >= count($questions)) {
            // ถ้าครบแล้ว ไปหน้าสรุปผล
            return redirect()->route('exam.finish');
        }

        // ดึงข้อมูลคำถามข้อปัจจุบัน พร้อมตัวเลือกทั้งหมด
        $question = Question::with('choices') // โหลดความสัมพันธ์ choices มาด้วย
            ->findOrFail($questions[$current]); // หาคำถามจาก ID ที่เก็บไว้ใน array

        // ตรวจสอบว่ามีการสุ่มลำดับตัวเลือกของข้อนี้ไว้แล้วหรือยัง
        $storedOrder = session("exam.choice_order.{$question->id}");

        if ($storedOrder) {
            // ถ้ามีการสุ่มไว้แล้ว ให้เรียงตามลำดับเดิม (กรณีกลับมาดูข้อนี้อีกครั้ง)
            $sortedChoices = $question->choices
                ->sortBy(function ($choice) use ($storedOrder) {
                    // หาตำแหน่งของ choice นี้ในลำดับที่สุ่มไว้
                    return array_search($choice->id, $storedOrder);
                })
                ->values(); // รีเซ็ต key ของ collection ให้เป็น 0,1,2,3...

            // นำลำดับตัวเลือกที่เรียงแล้วกลับไปใส่ใน relation
            $question->setRelation('choices', $sortedChoices);
        } else {
            // ถ้ายังไม่เคยสุ่ม ให้สุ่มลำดับตัวเลือกใหม่
            $shuffled = $question->choices->shuffle()->values(); // สุ่มและรีเซ็ต key

            // เก็บลำดับ ID ของตัวเลือกที่สุ่มได้ลงใน session (เพื่อใช้ในครั้งถัดไป)
            session()->put(
                "exam.choice_order.{$question->id}", // key สำหรับเก็บลำดับของคำถามนี้
                $shuffled->pluck('id')->toArray() // array ของ ID ตัวเลือกตามลำดับที่สุ่ม
            );

            // นำลำดับตัวเลือกที่สุ่มแล้วกลับไปใส่ใน relation
            $question->setRelation('choices', $shuffled);
        }

        // นับจำนวนข้อสอบทั้งหมด
        $total = count($questions);

        // ดึงชื่อหัวข้อจาก session
        $topicName = session('exam.topic_name', 'ไม่ระบุหัวข้อ');

        // ส่งข้อมูลไปแสดงที่ view exam.question
        return view('exam.question', compact('question', 'current', 'total', 'topicName'));
    }

    // หน้าสรุปผล
    public function finish()
    {
        // ดึงจำนวนข้อที่ตอบถูกจาก session
        $correctCount = session('exam.correct', 0);

        // ดึงจำนวนข้อที่ตอบผิดจาก session
        $wrongCount = session('exam.wrong', 0);

        // นับจำนวนข้อสอบทั้งหมด
        $total = count(session('exam.questions', []));

        // ดึงชื่อหัวข้อจาก session
        $topicName = session('exam.topic_name', 'ไม่ระบุ');

        // คำนวณคะแนนเป็นเปอร์เซ็นต์ (ปัดเศษ)
        $scorePercent = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        // เช็คว่ามี session หรือไม่
        if (! session('exam.questions')) {
            // ถ้าไม่มี ให้กลับไปหน้าเลือกหน่วยงาน
            return redirect()->route('agency.select')
                ->with('error', 'ไม่พบข้อมูลการสอบ');
        }

        // ลบข้อมูลการสอบทั้งหมดออกจาก session
        session()->forget([
            'exam.topic_id',
            'exam.topic_name',
            'exam.questions',
            'exam.current',
            'exam.correct',
            'exam.wrong',
            'exam.answered',
            // ลบลำดับตัวเลือกที่สุ่มไว้ทั้งหมดด้วย (key ที่ขึ้นต้นด้วย exam.choice_order)
        ]);

        // ลบ choice_order ของทุกข้อที่เคยสุ่มไว้
        $allKeys = array_keys(session()->all());
        foreach ($allKeys as $key) {
            if (str_starts_with($key, 'exam.choice_order.')) {
                session()->forget($key);
            }
        }

        // ส่งข้อมูลผลสอบไปแสดงที่ view exam.finish
        return view('exam.finish', compact('correctCount', 'wrongCount', 'total', 'scorePercent', 'topicName'));
    }

    // ตรวจคำตอบ + เฉลยทันที
    public function answer(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา ต้องมี choice_id และต้องมีอยู่ในตาราง choices
        $request->validate([
            'choice_id' => 'required|exists:choices,id',
        ]);

        // ดึงข้อมูลตัวเลือกที่ผู้ใช้เลือก พร้อมโหลดคำถามและตัวเลือกทั้งหมดมาด้วย
        $choice = Choice::with('question.choices')->findOrFail($request->choice_id);
        
        // ดึงข้อมูลคำถามจากตัวเลือกที่เลือก
        $question = $choice->question;

        // ดึงลำดับตัวเลือกที่เคยสุ่มไว้จาก session
        $order = session("exam.choice_order.{$question->id}", []);

        // เรียงตัวเลือกตามลำดับที่สุ่มไว้ (เพื่อแสดงเฉลยในลำดับเดียวกับที่ผู้ใช้เห็นตอนทำข้อสอบ)
        $sortedChoices = $question->choices
            ->sortBy(function ($c) use ($order) {
                // หาตำแหน่งของตัวเลือกนี้ในลำดับที่สุ่มไว้
                return array_search($c->id, $order);
            })
            ->values(); // รีเซ็ต key ของ collection

        // นำลำดับตัวเลือกที่เรียงแล้วกลับไปใส่ใน relation
        $question->setRelation('choices', $sortedChoices);

        // ดึงรายการคำถามที่ตอบไปแล้ว
        $answered = session('exam.answered', []);

        // ตรวจสอบว่าข้อนี้เคยตอบไปแล้วหรือยัง (ป้องกันการตอบซ้ำ)
        if (in_array($question->id, $answered)) {
            // ถ้าตอบไปแล้ว ให้กลับไปหน้าคำถามข้อถัดไป
            return redirect()->route('exam.question');
        }

        // เพิ่ม ID คำถามนี้เข้าไปในรายการที่ตอบแล้ว
        $answered[] = $question->id;
        session(['exam.answered' => $answered]);

        // ตรวจสอบว่าตัวเลือกที่เลือกถูกหรือผิด
        $isCorrect = (bool) $choice->is_correct;

        // อัพเดทสถิติการตอบคำถาม
        if ($isCorrect) {
            // ถ้าตอบถูก เพิ่มจำนวนข้อถูก
            session([
                'exam.correct' => session('exam.correct', 0) + 1,
            ]);
        } else {
            // ถ้าตอบผิด เพิ่มจำนวนข้อผิด
            session([
                'exam.wrong' => session('exam.wrong', 0) + 1,
            ]);
        }

        // เพิ่มหมายเลขข้อปัจจุบันไป 1 (ไปข้อถัดไป)
        session([
            'exam.current' => session('exam.current', 0) + 1,
        ]);

        // เตรียมข้อมูลสำหรับแสดงผล
        $current = session('exam.current'); // ข้อปัจจุบัน (หลังเพิ่มไปแล้ว)
        $total = count(session('exam.questions', [])); // จำนวนข้อทั้งหมด
        $correctCount = session('exam.correct', 0); // จำนวนข้อถูก
        $wrongCount = session('exam.wrong', 0); // จำนวนข้อผิด
        
        // คำนวณคะแนนจากข้อที่ตอบไปแล้ว
        $scorePercent = ($correctCount + $wrongCount) > 0
            ? round(($correctCount / ($correctCount + $wrongCount)) * 100)
            : 0;

        // ส่งข้อมูลไปแสดงที่ view exam.result (หน้าเฉลย)
        return view('exam.result', compact(
            'question',      // ข้อมูลคำถาม พร้อมตัวเลือกที่เรียงตามลำดับที่สุ่มไว้
            'choice',        // ตัวเลือกที่ผู้ใช้เลือก
            'isCorrect',     // ผลการตอบ (ถูก/ผิด)
            'current',       // ข้อปัจจุบัน (ข้อถัดไป)
            'total',         // จำนวนข้อทั้งหมด
            'correctCount',  // จำนวนข้อถูกสะสม
            'wrongCount',    // จำนวนข้อผิดสะสม
            'scorePercent'   // คะแนนเป็นเปอร์เซ็นต์
        ));
    }
}