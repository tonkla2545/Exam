<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Exam System') }}</title>
    @production
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    
    @if($cssFile)
        <link rel="stylesheet" href="{{ secure_url('build/' . $cssFile) }}">
    @endif
    
    @if($jsFile)
        <script type="module" src="{{ secure_url('build/' . $jsFile) }}"></script>
    @endif
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endproduction
</head>
<body class="bg-linear-to-br from-indigo-100 via-purple-100 to-pink-100 min-h-screen flex flex-col">
    
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-xl shadow-lg sticky top-0 z-50 border-b border-indigo-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/50 group-hover:shadow-xl group-hover:shadow-purple-500/50 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        ระบบข้อสอบออนไลน์
                    </span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 font-medium relative group">
                        หน้าแรก
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 font-medium relative group">
                        คำแนะนำ
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    
                    @auth
                        <div class="flex items-center gap-3 ml-4 pl-4 border-l border-indigo-200">
                            <div class="w-8 h-8 bg-gradient-to-br from-pink-500 via-rose-500 to-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md shadow-pink-500/30">
                                {{ mb_substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-gray-800 font-medium">{{ Auth::user()->name ?? 'ผู้ใช้งาน' }}</span>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-8 bg-white/60 backdrop-blur-md border-t border-indigo-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <p class="text-gray-600 text-sm font-medium">
                        © {{ date('Y') }} ระบบข้อสอบออนไลน์ <span class="text-indigo-600">•</span> สงวนลิขสิทธิ์
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors text-sm font-medium relative group">
                        นโยบายความเป็นส่วนตัว
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors text-sm font-medium relative group">
                        เงื่อนไขการใช้งาน
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors text-sm font-medium relative group">
                        ติดต่อเรา
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white rounded-full shadow-lg shadow-indigo-500/50 hover:shadow-xl hover:shadow-purple-500/50 transition-all duration-300 opacity-0 invisible hover:scale-110 hover:rotate-12 z-40">
        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        // Scroll to top functionality
        const scrollBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollBtn.classList.remove('opacity-0', 'invisible');
            } else {
                scrollBtn.classList.add('opacity-0', 'invisible');
            }
        });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>