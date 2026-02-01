@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <span class="text-3xl">🏢</span>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    เลือกหน่วยงาน
                </h1>
                <p class="text-gray-600 mt-1">กรุณาเลือกหน่วยงานที่ต้องการดำเนินการ</p>
            </div>
        </div>
        
        @if($agencies->count() > 0)
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 rounded-full text-sm font-medium border border-indigo-100">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ $agencies->count() }} หน่วยงาน</span>
        </div>
        @endif
    </div>

    <!-- Agencies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($agencies as $agency)
        <a href="{{ route('topic.select', $agency) }}"
           class="group relative p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-md border border-indigo-100 hover:shadow-2xl hover:shadow-indigo-200/50 hover:border-indigo-300 transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
            
            <!-- Background Gradient Animation -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-indigo-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        {{ mb_substr($agency->name, 0, 1) }}
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-indigo-500 group-hover:to-purple-500 transition-all duration-300">
                        <svg class="w-5 h-5 text-indigo-400 group-hover:text-white group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-300">
                    {{ $agency->name }}
                </h2>

                <div class="flex items-center text-sm text-gray-500 group-hover:text-indigo-600 transition-colors duration-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    <span class="font-medium">เข้าสู่ระบบทดสอบ</span>
                </div>
            </div>

            <!-- Bottom Gradient Bar -->
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-b-2xl transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
            
            <!-- Corner Decoration -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-bl-full opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
        </a>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($agencies->isEmpty())
    <div class="text-center py-20">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full mb-6 border-4 border-white shadow-lg">
            <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
            ไม่พบหน่วยงาน
        </h3>
        <p class="text-gray-600 text-lg">ยังไม่มีหน่วยงานในระบบ</p>
    </div>
    @endif
</div>
@endsection