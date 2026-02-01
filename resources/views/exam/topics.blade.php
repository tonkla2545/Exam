@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <a href="{{ route('agency.select') }}" class="hover:text-blue-600 transition-colors">หน่วยงาน</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 font-medium">{{ $agency->name }}</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-start gap-4 mb-3">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg flex-shrink-0">
                📚
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    {{ $agency->name }}
                </h1>
                <p class="text-gray-600">เลือกเรื่องที่ต้องการทำข้อสอบ</p>
            </div>
        </div>
        
        @if($topics->count() > 0)
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
            </svg>
            <span>{{ $topics->count() }} เรื่อง</span>
        </div>
        @endif
    </div>

    <!-- Topics List -->
    <div class="space-y-3">
        @forelse($topics as $index => $topic)
        <a href="/exam/start/{{ $topic->id }}"
           class="group relative flex items-center gap-4 p-5 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-0.5">
            
            <!-- Number Badge -->
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center text-gray-700 font-bold text-sm group-hover:from-blue-500 group-hover:to-blue-600 group-hover:text-white transition-all duration-300">
                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
            </div>

            <!-- Topic Name -->
            <div class="flex-grow">
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">
                    {{ $topic->name }}
                </h3>
            </div>

            <!-- Arrow Icon -->
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center group-hover:bg-blue-500 transition-all duration-300">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Bottom Border Animation -->
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-600 rounded-b-2xl transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </a>
        @empty
        <!-- Empty State -->
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">ยังไม่มีหัวข้อสอบ</h3>
            <p class="text-gray-600">ไม่พบหัวข้อสำหรับหน่วยงานนี้</p>
        </div>
        @endforelse
    </div>
</div>
@endsection