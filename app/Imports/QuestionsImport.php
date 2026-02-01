<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows->skip(1) as $index => $row) {

                $questionText = trim($row[3] ?? '');

                if ($questionText === '') {
                    continue;
                }

                $agency = Agency::firstOrCreate([
                    'name' => trim($row[0]),
                ]);

                $topic = Topic::firstOrCreate([
                    'agency_id' => $agency->id,
                    'name' => trim($row[1]),
                ]);

                $question = Question::create([
                    'topic_id' => $topic->id,
                    'question_text' => $questionText,
                    'explanation' => trim($row[9] ?? ''),
                ]);

                // เฉลย - ทำความสะอาดและแปลง
                $correctAnswerRaw = trim($row[8] ?? '');
                $correctAnswer = $this->normalizeAnswer($correctAnswerRaw);

                $choices = [
                    'ก' => $row[4] ?? '',
                    'ข' => $row[5] ?? '',
                    'ค' => $row[6] ?? '',
                    'ง' => $row[7] ?? '',
                ];

                foreach ($choices as $label => $text) {
                    // ข้ามถ้าไม่มีข้อความ
                    if (trim($text) === '') {
                        continue;
                    }

                    // เปรียบเทียบ
                    $isCorrect = $label === $correctAnswer;

                    Choice::create([
                        'question_id' => $question->id,
                        'choice_label' => $label,
                        'choice_text' => trim($text),
                        'is_correct' => $isCorrect,
                    ]);
                }
            }
        });
    }

    /**
     * แปลงคำตอบให้เป็น ก ข ค ง
     * รองรับทั้ง 1,2,3,4 และ ก,ข,ค,ง และมีจุดต่อท้าย
     */
    private function normalizeAnswer($answer)
    {
        // แปลงเป็น string
        $answer = (string)$answer;
        
        // ลบ whitespace
        $answer = trim($answer);
        
        // ลบจุด . ออก (สำคัญ!)
        $answer = str_replace('.', '', $answer);
        
        // แปลงเป็นตัวพิมพ์เล็ก
        $answer = mb_strtolower($answer);

        // แผนที่แปลง
        $map = [
            '1' => 'ก',
            '2' => 'ข',
            '3' => 'ค',
            '4' => 'ง',
            'ก' => 'ก',
            'ข' => 'ข',
            'ค' => 'ค',
            'ง' => 'ง',
            'a' => 'ก',
            'b' => 'ข',
            'c' => 'ค',
            'd' => 'ง',
        ];

        return $map[$answer] ?? '';
    }
}