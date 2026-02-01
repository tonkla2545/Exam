@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    
    <!-- Topic Header -->
    <div class="mb-6 p-6 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                    <span class="text-3xl">📚</span>
                </div>
                <div>
                    <p class="text-white/80 text-sm font-medium">กำลังทำข้อสอบ</p>
                    <h2 class="text-2xl font-bold text-white">{{ session('exam.topic_name', 'ไม่ระบุหัวข้อ') }}</h2>
                </div>
            </div>
            <a href="/" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-lg font-medium transition-all duration-300">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ออกจากการทดสอบ
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-gray-700">ความคืบหน้า</span>
            <span class="text-sm font-bold text-indigo-600">ข้อที่ {{ $current + 1 }} จาก {{ $total }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500 shadow-lg" 
                 style="width: {{ (($current + 1) / $total) * 100 }}%">
            </div>
        </div>
        <div class="mt-2 flex justify-between text-xs text-gray-500">
            <span>เริ่มต้น</span>
            <span>{{ number_format((($current + 1) / $total) * 100, 0) }}%</span>
            <span>เสร็จสิ้น</span>
        </div>
    </div>

    <!-- Question Navigation -->
    <div class="mb-6 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-gray-200">
        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            <span class="text-sm font-semibold text-gray-700 mr-2 flex-shrink-0">ข้อที่:</span>
            @for($i = 0; $i < $total; $i++)
                <a href="{{ route('exam.question', ['goto' => $i]) }}" 
                   class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all duration-300
                   {{ $i == $current ? 'bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-lg scale-110' : 'bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-600' }}">
                    {{ $i + 1 }}
                </a>
            @endfor
        </div>
    </div>

    <!-- Question Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-indigo-100 overflow-hidden">
        <!-- Question Header -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                    <span class="text-2xl">❓</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">
                        ข้อที่ {{ $current + 1 }}
                    </h1>
                    <p class="text-white/80 text-sm">เลือกคำตอบที่ถูกต้อง</p>
                </div>
            </div>
        </div>

        <!-- Question Content -->
        <div class="p-8">
            <p class="text-lg text-gray-800 mb-8 leading-relaxed font-medium">
                {{ $question->question_text }}
            </p>

            <form method="POST" action="{{ route('exam.answer') }}">
                @csrf
                <input type="hidden" name="current" value="{{ $current }}">

                <div class="space-y-4">
                    @foreach($question->choices as $index => $choice)
                    <label class="group relative flex items-start gap-4 p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-300 transform hover:scale-[1.02]">
                        <!-- Radio Button -->
                        <div class="flex items-center h-6">
                            <input type="radio" 
                                   name="choice_id"
                                   value="{{ $choice->id }}"
                                   class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                                   required>
                        </div>

                        <!-- Choice Number & Text -->
                        <div class="flex-grow flex items-start gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm group-hover:from-indigo-500 group-hover:to-purple-500 group-hover:text-white transition-all duration-300">
                                {{ chr(65 + $index) }}
                            </div>
                            <span class="text-gray-800 leading-relaxed pt-1 group-hover:text-gray-900">
                                {{ $choice->choice_text }}
                            </span>
                        </div>

                        <!-- Checkmark Icon -->
                        <div class="absolute top-5 right-5 w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </label>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <!-- Previous Button -->
                    @if($current > 0)
                    <a href="{{ route('exam.question', ['goto' => $current - 1]) }}" 
                       class="flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-indigo-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-300 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>ข้อก่อนหน้า</span>
                    </a>
                    @else
                    <div></div>
                    @endif

                    <!-- Submit/Next Button -->
                    <button type="submit" class="group flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white rounded-xl font-bold text-lg shadow-lg shadow-indigo-500/50 hover:shadow-xl hover:shadow-purple-500/50 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1">
                        <span>{{ $current + 1 < $total ? 'ตอบและไปข้อถัดไป' : 'ตอบข้อสุดท้าย' }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hint -->
    <div class="mt-6 flex items-center justify-center gap-2 text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-medium">💡 คุณสามารถกดที่หมายเลขข้อด้านบนเพื่อกลับไปทำข้อก่อนหน้าได้</span>
    </div>
</div>

<style>
    /* Custom Radio Button Style */
    input[type="radio"]:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }
    
    input[type="radio"]:checked + div {
        background: linear-gradient(to bottom right, #6366f1, #a855f7);
        color: white;
    }
</style>
@endsection