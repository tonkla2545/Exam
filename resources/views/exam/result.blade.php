@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    <!-- Debug (ลบออกหลังจากแก้เสร็จ) -->
    {{-- 
    <div class="p-4 bg-yellow-100 mb-4 rounded">
        <p>Choice ID ที่เลือก: {{ $choice->id }}</p>
        <p>Is Correct: {{ $choice->is_correct ? 'true' : 'false' }}</p>
        @foreach($question->choices as $c)
            <p>Choice {{ $c->id }}: {{ $c->choice_text }} - is_correct = {{ $c->is_correct ? 'TRUE' : 'FALSE' }}</p>
        @endforeach
    </div>
    --}}

    <!-- Result Header -->
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-4 {{ $isCorrect ? 'bg-gradient-to-br from-green-400 to-emerald-500' : 'bg-gradient-to-br from-red-400 to-rose-500' }} shadow-2xl {{ $isCorrect ? 'shadow-green-500/50' : 'shadow-red-500/50' }} animate-bounce">
            <span class="text-5xl">{{ $isCorrect ? '✅' : '❌' }}</span>
        </div>
        <h1 class="text-4xl font-bold mb-2 {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
            {{ $isCorrect ? 'ยินดีด้วย! คำตอบถูกต้อง' : 'เสียใจด้วย คำตอบไม่ถูกต้อง' }}
        </h1>
        <p class="text-gray-600 text-lg">
            {{ $isCorrect ? 'คุณตอบคำถามข้อนี้ได้อย่างถูกต้อง' : 'ลองอ่านคำอธิบายเพื่อเรียนรู้เพิ่มเติม' }}
        </p>
    </div>

    <!-- Question Review Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
        
        <!-- Question Header -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                    <span class="text-2xl">❓</span>
                </div>
                <h2 class="text-lg font-bold text-white">คำถาม</h2>
            </div>
        </div>

        <!-- Question Text -->
        <div class="p-6 border-b border-gray-100">
            <p class="text-lg text-gray-800 font-medium leading-relaxed">
                {{ $question->question_text }}
            </p>
        </div>

        <!-- Choices Review -->
        <div class="p-6 space-y-3">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">ตัวเลือกทั้งหมด</h3>
            @foreach($question->choices as $index => $c)
            @php
                // แปลงเป็น boolean ให้ชัดเจน
                $isThisCorrect = (bool)$c->is_correct;
                $isUserChoice = $c->id == $choice->id;
                $showAsCorrect = $isThisCorrect;
                $showAsWrong = $isUserChoice && !$isThisCorrect;
                $showAsNeutral = !$isThisCorrect && !$isUserChoice;
            @endphp
            
            <div class="relative flex items-start gap-4 p-4 rounded-xl border-2 transition-all duration-300
                {{ $showAsCorrect ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-300 shadow-md shadow-green-100' : '' }}
                {{ $showAsWrong ? 'bg-gradient-to-r from-red-50 to-rose-50 border-red-300 shadow-md shadow-red-100' : '' }}
                {{ $showAsNeutral ? 'bg-gray-50 border-gray-200' : '' }}">
                
                <!-- Choice Letter -->
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0
                    {{ $showAsCorrect ? 'bg-green-500 text-white' : '' }}
                    {{ $showAsWrong ? 'bg-red-500 text-white' : '' }}
                    {{ $showAsNeutral ? 'bg-gray-300 text-gray-700' : '' }}">
                    {{ chr(65 + $index) }}
                </div>

                <!-- Choice Text -->
                <div class="flex-grow pt-1">
                    <p class="text-gray-800 leading-relaxed">{{ $c->choice_text }}</p>
                </div>

                <!-- Status Icons -->
                @if($showAsCorrect)
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                        คำตอบที่ถูกต้อง
                    </div>
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                @elseif($showAsWrong)
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                        คำตอบของคุณ
                    </div>
                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Explanation Section -->
        @if($question->explanation)
        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-t border-blue-100">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30">
                    <span class="text-xl">📖</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-blue-900 mb-1">คำอธิบาย</h3>
                    <p class="text-sm text-blue-700">ศึกษาเพื่อเพิ่มความเข้าใจ</p>
                </div>
            </div>
            <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-blue-200">
                <p class="text-gray-800 leading-relaxed">{{ $question->explanation }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Review Button -->
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="group flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-indigo-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-300 transform hover:scale-[1.02]">
            <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
            <span>ดูคำถามอีกครั้ง</span>
        </button>

        <!-- Next Question Button -->
        <a href="{{ route('exam.question') }}"
           class="group flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white rounded-xl font-bold text-lg shadow-lg shadow-indigo-500/50 hover:shadow-xl hover:shadow-purple-500/50 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1">
            <span>ข้อถัดไป</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>

    <!-- Stats -->
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-gray-200 text-center">
            <div class="text-2xl font-bold text-indigo-600">{{ $current + 1 }}</div>
            <div class="text-sm text-gray-600">ข้อที่ทำ</div>
        </div>
        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-gray-200 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $correctCount }}</div>
            <div class="text-sm text-gray-600">ตอบถูก</div>
        </div>
        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-gray-200 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $wrongCount }}</div>
            <div class="text-sm text-gray-600">ตอบผิด</div>
        </div>
        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-gray-200 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $scorePercent }}%</div>
            <div class="text-sm text-gray-600">คะแนน</div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    .animate-bounce {
        animation: bounce 1s ease-in-out 2;
    }
</style>
@endsection