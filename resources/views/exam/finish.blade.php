@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    <!-- Confetti Animation Background -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-32 h-32 rounded-full mb-6 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 shadow-2xl shadow-purple-500/50 animate-pulse">
            <span class="text-6xl">🎉</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-3">
            ทำข้อสอบเสร็จสิ้น!
        </h1>
        <p class="text-gray-600 text-lg">
            ขอบคุณที่ทำแบบทดสอบ นี่คือผลคะแนนของคุณ
        </p>
    </div>

    <!-- Score Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-indigo-100 overflow-hidden mb-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-8 py-6">
            <h2 class="text-2xl font-bold text-white text-center">สรุปผลคะแนน</h2>
        </div>

        <!-- Score Display -->
        <div class="p-8">
            <div class="text-center mb-8">
                <div class="inline-block">
                    <div class="text-7xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                        {{ $scorePercent }}%
                    </div>
                    <div class="text-gray-600 text-lg">คะแนนรวม</div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Questions -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-xl border-2 border-indigo-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-indigo-600 text-sm font-semibold uppercase">จำนวนข้อ</span>
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-indigo-600">{{ $total }}</div>
                </div>

                <!-- Correct Answers -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border-2 border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-green-600 text-sm font-semibold uppercase">ตอบถูก</span>
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-green-600">{{ $correctCount }}</div>
                </div>

                <!-- Wrong Answers -->
                <div class="bg-gradient-to-br from-red-50 to-rose-50 p-6 rounded-xl border-2 border-red-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-red-600 text-sm font-semibold uppercase">ตอบผิด</span>
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-red-600">{{ $wrongCount }}</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">อัตราความถูกต้อง</span>
                    <span class="text-sm font-bold text-indigo-600">{{ $correctCount }}/{{ $total }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 h-4 rounded-full transition-all duration-1000 shadow-lg" 
                         style="width: {{ $scorePercent }}%">
                    </div>
                </div>
            </div>

            <!-- Performance Message -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        @if($scorePercent >= 80)
                            <span class="text-2xl">🌟</span>
                        @elseif($scorePercent >= 60)
                            <span class="text-2xl">👍</span>
                        @else
                            <span class="text-2xl">💪</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 mb-1">
                            @if($scorePercent >= 80)
                                ยอดเยี่ยม!
                            @elseif($scorePercent >= 60)
                                ดีมาก!
                            @else
                                พยายามต่อไป!
                            @endif
                        </h3>
                        <p class="text-blue-800 text-sm">
                            @if($scorePercent >= 80)
                                คุณมีความเข้าใจในเนื้อหาเป็นอย่างดี ทำได้ดีมาก!
                            @elseif($scorePercent >= 60)
                                คุณมีความเข้าใจในระดับดี ลองทบทวนเพิ่มเติมจะดีขึ้นอีก
                            @else
                                แนะนำให้ทบทวนเนื้อหาและลองทำแบบทดสอบอีกครั้ง
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="/"
           class="group flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-indigo-300 text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all duration-300 transform hover:scale-[1.02]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>กลับหน้าหลัก</span>
        </a>

        <a href="{{ url()->previous() }}"
           class="group flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white rounded-xl font-bold text-lg shadow-lg shadow-indigo-500/50 hover:shadow-xl hover:shadow-purple-500/50 transition-all duration-300 transform hover:scale-[1.02]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>ทำแบบทดสอบอีกครั้ง</span>
        </a>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    .animate-pulse {
        animation: pulse 2s ease-in-out infinite;
    }
</style>
@endsection