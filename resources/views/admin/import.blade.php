@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-indigo-100 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">📥 นำเข้าข้อมูลข้อสอบ</h1>
            <p class="text-white/80 text-sm mt-1">อัพโหลดไฟล์ Excel เพื่อนำเข้าข้อสอบ</p>
        </div>

        <div class="p-8">
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-xl">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="text-red-700 font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Instructions -->
            <div class="mb-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                    <span class="text-xl">📋</span>
                    รูปแบบไฟล์ Excel
                </h3>
                <p class="text-blue-800 mb-3">ไฟล์ Excel ต้องมีคอลัมน์ตามลำดับดังนี้:</p>
                <div class="bg-white/60 backdrop-blur-sm p-4 rounded-lg">
                    <table class="text-sm w-full">
                        <thead>
                            <tr class="border-b-2 border-blue-200">
                                <th class="text-left py-2 px-3 text-blue-900">คอลัมน์</th>
                                <th class="text-left py-2 px-3 text-blue-900">ชื่อฟิลด์</th>
                                <th class="text-left py-2 px-3 text-blue-900">ตัวอย่าง</th>
                            </tr>
                        </thead>
                        <tbody class="text-blue-800">
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">A (0)</td>
                                <td class="py-2 px-3 font-semibold">หน่วยงาน</td>
                                <td class="py-2 px-3">กรมสรรพากร</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">B (1)</td>
                                <td class="py-2 px-3 font-semibold">เรื่อง</td>
                                <td class="py-2 px-3">กฎหมายภาษี</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">C (2)</td>
                                <td class="py-2 px-3 font-semibold">ข้อ</td>
                                <td class="py-2 px-3">1</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">D (3)</td>
                                <td class="py-2 px-3 font-semibold">โจทย์</td>
                                <td class="py-2 px-3">อัตราภาษีมูลค่าเพิ่มคือเท่าไร</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">E (4)</td>
                                <td class="py-2 px-3 font-semibold">ตัวเลือก ก</td>
                                <td class="py-2 px-3">5%</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">F (5)</td>
                                <td class="py-2 px-3 font-semibold">ตัวเลือก ข</td>
                                <td class="py-2 px-3">7%</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">G (6)</td>
                                <td class="py-2 px-3 font-semibold">ตัวเลือก ค</td>
                                <td class="py-2 px-3">10%</td>
                            </tr>
                            <tr class="border-b border-blue-100">
                                <td class="py-2 px-3 font-mono">H (7)</td>
                                <td class="py-2 px-3 font-semibold">ตัวเลือก ง</td>
                                <td class="py-2 px-3">15%</td>
                            </tr>
                            <tr class="border-b border-blue-100 bg-yellow-50">
                                <td class="py-2 px-3 font-mono">I (8)</td>
                                <td class="py-2 px-3 font-semibold text-yellow-900">เฉลย ⭐</td>
                                <td class="py-2 px-3 text-yellow-900 font-bold">ข</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 font-mono">J (9)</td>
                                <td class="py-2 px-3 font-semibold">คำอธิบาย</td>
                                <td class="py-2 px-3">อัตราปัจจุบันคือ 7%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-yellow-800 text-sm font-medium">⚠️ สำคัญ:</p>
                    <ul class="text-yellow-700 text-sm mt-1 ml-4 space-y-1">
                        <li>• แถวแรกต้องเป็นหัวตาราง (จะถูกข้าม)</li>
                        <li>• คอลัมน์เฉลย (I) ต้องใส่เป็น <strong>ก</strong>, <strong>ข</strong>, <strong>ค</strong>, หรือ <strong>ง</strong> เท่านั้น</li>
                        <li>• ใช้ตัวพิมพ์เล็กหรือใหญ่ก็ได้</li>
                    </ul>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('admin.import.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">เลือกไฟล์ Excel</label>
                    <div class="relative">
                        <input type="file" 
                               name="file" 
                               accept=".xlsx,.xls,.csv" 
                               required
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-semibold hover:file:bg-indigo-100">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white py-4 px-6 rounded-xl font-bold text-lg shadow-lg shadow-indigo-500/50 hover:shadow-xl hover:shadow-purple-500/50 transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span>อัพโหลดและนำเข้าข้อมูล</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="/" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            กลับหน้าหลัก
        </a>
    </div>
</div>
@endsection