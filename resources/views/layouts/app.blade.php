<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Website resmi SMP Negeri 01 Namrole - Sekolah menengah pertama terbaik di Namrole">
    <meta name="keywords" content="SMP, Sekolah, Namrole, Pendidikan, Siswa, Guru, E-Learning">
    <meta name="author" content="SMP Negeri 01 Namrole">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'SMP Negeri 01 Namrole') - Website Resmi">
    <meta property="og:description" content="Website resmi SMP Negeri 01 Namrole - Sekolah menengah pertama terbaik di Namrole">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('logo.png') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>@yield('title', 'SMP Negeri 01 Namrole') - Website Resmi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Loading Indicator -->
    <style>
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #3B82F6, #1D4ED8);
            z-index: 9999;
            transform: translateX(-100%);
            animation: loading 2s ease-in-out infinite;
        }
        
        @keyframes loading {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(100%); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Professional styling improvements */
        .header-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1D4ED8, #1E40AF);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #3B82F6;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #1D4ED8;
        }

        /* Mobile menu improvements */
        @media (max-width: 1023px) {
            .mobile-menu-item {
                min-height: 48px; /* Touch-friendly minimum height */
            }
            
            .mobile-menu-item:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }
            
            .mobile-dropdown {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-in-out;
            }
            
            .mobile-dropdown.show {
                max-height: 500px;
            }

            /* Mobile menu sticky behavior */
            #mobile-menu {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 9999;
                background: #1E40AF;
                max-height: 100vh;
                overflow-y: auto;
                transform: translateY(-100%);
                transition: transform 0.3s ease-in-out;
            }

            #mobile-menu.show {
                transform: translateY(0);
            }

            /* Mobile menu content */
            #mobile-menu .mobile-menu-content {
                padding-top: 80px; /* Space for header */
                padding-bottom: 20px;
            }

            /* Mobile menu header */
            .mobile-menu-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 10000;
                background: #1E40AF;
                padding: 1rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            /* Mobile menu close button */
            .mobile-menu-close {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: rgba(255, 255, 255, 0.1);
                border: none;
                color: white;
                padding: 0.5rem;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .mobile-menu-close:hover {
                background: rgba(255, 255, 255, 0.2);
            }
        }

        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            .touch-target {
                min-height: 44px;
                min-width: 44px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset("css/ultimate-force-white.css") }}">
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Loading Indicator -->
    <div class="loading-indicator" id="loading-indicator"></div>
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-primary-500/95 backdrop-blur-sm header-shadow sticky top-0 z-50 transition-all duration-300" style="transform: translateY(0);">
            <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex justify-between items-center py-2">
                    <!-- Logo -->
                    <div class="flex items-center ml-2 md:ml-4">
                        <img src="{{ asset('logo.png') }}" alt="Logo SMP Negeri 01 Namrole" class="h-10 md:h-12 w-auto mr-2 md:mr-4">
                        <div class="hidden sm:block">
                            <h1 class="text-white text-lg md:text-xl font-bold leading-none">SMP NEGERI 01</h1>
                            <h2 class="text-white text-xs font-medium mt-0.5">NAMROLE</h2>
                        </div>
                        <div class="sm:hidden">
                            <h1 class="text-white text-sm font-bold leading-none">SMP NEGERI 01</h1>
                            <h2 class="text-white text-xs font-medium mt-0.5">NAMROLE</h2>
                        </div>
                    </div>

                    <!-- Navigation -->
                <nav class="hidden lg:flex space-x-10 ml-20">
                    <a href="{{ route('home') }}" class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors {{ request()->routeIs('home') ? 'text-primary-200' : '' }}">Beranda</a>
                    
                    <!-- Profil Dropdown -->
                    <div class="relative group">
                        <button class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors flex items-center {{ request()->routeIs('profil') ? 'text-primary-200' : '' }}">
                            Profil
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('profil') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Sekolah</a>
                        </div>
                    </div>

                    <a href="{{ route('ppdb.index') }}" class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors {{ request()->routeIs('ppdb.*') ? 'text-primary-200' : '' }}">PPDB</a>
                    
                    <!-- Informasi Dropdown -->
                    <div class="relative group">
                        <button class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors flex items-center {{ request()->routeIs('academic-calendar.*', 'news.*') ? 'text-primary-200' : '' }}">
                            Informasi
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('academic-calendar.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kalender Akademik</a>
                            <a href="{{ route('news.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Berita & Pengumuman</a>
                            <a href="https://asesmen.erlanggaonline.co.id/" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Asesmen</a>
                        </div>
                    </div>

                    <!-- Media Dropdown -->
                    <div class="relative group">
                        <button class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors flex items-center {{ request()->routeIs('gallery.*', 'documents.*', 'library', 'staff') ? 'text-primary-200' : '' }}">
                            Media
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('gallery.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Galeri Foto</a>
                            <a href="{{ route('documents.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download Center</a>
                            <a href="{{ route('library') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perpustakaan</a>
                            <a href="{{ route('staff') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tenaga Pendidik</a>
                            <a href="{{ route('facilities') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fasilitas</a>
                            <a href="https://saranaguru.erlanggaonline.co.id/user/login" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sarana Guru</a>
                            <a href="https://e-library.erlanggaonline.co.id/user/TWpVMk56RT0" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">E-Library</a>
                        </div>
                    </div>

                    <a href="{{ route('contact.index') }}" class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors {{ request()->routeIs('contact.*') ? 'text-primary-200' : '' }}">Kontak</a>
                </nav>

                    <!-- Auth Links -->
                    <div class="hidden md:flex items-center space-x-8 mr-12">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="bg-primary-700 hover:bg-primary-800 text-white px-4 py-2 rounded-md text-base font-bold transition-colors">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                                </a>
                            @elseif(auth()->user()->role === 'teacher')
                                <a href="{{ route('teacher.dashboard') }}" class="bg-primary-700 hover:bg-primary-800 text-white px-4 py-2 rounded-md text-base font-bold transition-colors">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Dashboard Guru
                                </a>
                            @elseif(auth()->user()->role === 'student')
                                <a href="{{ route('student.dashboard') }}" class="bg-primary-700 hover:bg-primary-800 text-white px-4 py-2 rounded-md text-base font-bold transition-colors">
                                    <i class="fas fa-user-graduate mr-2"></i>Dashboard Siswa
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-primary-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-primary-200 px-3 py-2 text-base font-bold transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-white text-primary-600 hover:bg-primary-50 px-4 py-2 rounded-md text-base font-bold transition-colors">
                                <i class="fas fa-user-plus mr-2"></i>Daftar
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden mr-2 md:mr-6">
                        <button type="button" class="text-white hover:text-primary-200 focus:outline-none focus:text-primary-200 p-2 rounded-md hover:bg-primary-600 transition-colors" id="mobile-menu-button">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div class="lg:hidden hidden" id="mobile-menu">
                    <!-- Mobile Menu Header -->
                    <div class="mobile-menu-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ asset('logo.png') }}" alt="Logo SMP Negeri 01 Namrole" class="h-8 w-auto mr-3">
                                <div>
                                    <h1 class="text-white text-lg font-bold leading-none">SMP NEGERI 01</h1>
                                    <h2 class="text-white text-xs font-medium mt-0.5">NAMROLE</h2>
                                </div>
                            </div>
                            <button class="mobile-menu-close" id="mobile-menu-close">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Content -->
                    <div class="mobile-menu-content">
                        <div class="px-4 pt-4 pb-6 space-y-1 sm:px-4">
                        <!-- Main Navigation -->
                        <a href="{{ route('home') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-3 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('home') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-home mr-3"></i>Beranda
                        </a>
                        
                        <!-- Profil Section -->
                        <div class="space-y-1">
                            <button onclick="toggleMobileDropdown('profil')" class="w-full text-left text-white hover:text-primary-200 hover:bg-primary-700 text-base font-medium px-4 py-3 flex items-center justify-between rounded-lg transition-colors">
                                <span><i class="fas fa-school mr-3"></i>Profil</span>
                                <svg id="profil-arrow" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="profil-dropdown" class="hidden ml-4 space-y-1">
                                <a href="{{ route('profil') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('profil') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-info-circle mr-2"></i>Profil Sekolah
                                </a>
                            </div>
                        </div>
                        
                        <a href="{{ route('ppdb.index') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-3 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('ppdb.*') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-user-plus mr-3"></i>PPDB
                        </a>
                        
                        <!-- Informasi Section -->
                        <div class="space-y-1">
                            <button onclick="toggleMobileDropdown('informasi')" class="w-full text-left text-white hover:text-primary-200 hover:bg-primary-700 text-base font-medium px-4 py-3 flex items-center justify-between rounded-lg transition-colors">
                                <span><i class="fas fa-info-circle mr-3"></i>Informasi</span>
                                <svg id="informasi-arrow" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="informasi-dropdown" class="hidden ml-4 space-y-1">
                                <a href="{{ route('academic-calendar.index') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('academic-calendar.*') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-calendar-alt mr-2"></i>Kalender Akademik
                                </a>
                                <a href="{{ route('news.index') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('news.*') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-newspaper mr-2"></i>Berita & Pengumuman
                                </a>
                                <a href="https://asesmen.erlanggaonline.co.id/" target="_blank" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-clipboard-check mr-2"></i>Asesmen
                                </a>
                            </div>
                        </div>
                        
                        <!-- Media Section -->
                        <div class="space-y-1">
                            <button onclick="toggleMobileDropdown('media')" class="w-full text-left text-white hover:text-primary-200 hover:bg-primary-700 text-base font-medium px-4 py-3 flex items-center justify-between rounded-lg transition-colors">
                                <span><i class="fas fa-images mr-3"></i>Media</span>
                                <svg id="media-arrow" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="media-dropdown" class="hidden ml-4 space-y-1">
                                <a href="{{ route('gallery.index') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('gallery.*') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-camera mr-2"></i>Galeri Foto
                                </a>
                                <a href="{{ route('documents.index') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('documents.*') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-download mr-2"></i>Download Center
                                </a>
                                <a href="{{ route('library') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('library') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-book mr-2"></i>Perpustakaan
                                </a>
                                <a href="{{ route('staff') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('staff') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Tenaga Pendidik
                                </a>
                                <a href="{{ route('facilities') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('facilities') ? 'bg-primary-700' : '' }}">
                                    <i class="fas fa-building mr-2"></i>Fasilitas
                                </a>
                                <a href="https://saranaguru.erlanggaonline.co.id/user/login" target="_blank" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-tools mr-2"></i>Sarana Guru
                                </a>
                                <a href="https://e-library.erlanggaonline.co.id/user/TWpVMk56RT0" target="_blank" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-book-open mr-2"></i>E-Library
                                </a>
                            </div>
                        </div>
                        
                        <a href="{{ route('contact.index') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-3 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('contact.*') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-phone mr-3"></i>Kontak
                        </a>
                        
                        <!-- Auth Section -->
                        <div class="border-t border-primary-500 pt-4 mt-4">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="bg-primary-700 hover:bg-primary-800 text-white block px-4 py-3 rounded-lg text-base font-medium mb-3 transition-colors">
                                        <i class="fas fa-tachometer-alt mr-3"></i>Admin Dashboard
                                    </a>
                                @elseif(auth()->user()->role === 'teacher')
                                    <a href="{{ route('teacher.dashboard') }}" class="bg-primary-700 hover:bg-primary-800 text-white block px-4 py-3 rounded-lg text-base font-medium mb-3 transition-colors">
                                        <i class="fas fa-chalkboard-teacher mr-3"></i>Dashboard Guru
                                    </a>
                                @elseif(auth()->user()->role === 'student')
                                    <a href="{{ route('student.dashboard') }}" class="bg-primary-700 hover:bg-primary-800 text-white block px-4 py-3 rounded-lg text-base font-medium mb-3 transition-colors">
                                        <i class="fas fa-user-graduate mr-3"></i>Dashboard Siswa
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="text-white hover:text-primary-200 hover:bg-primary-700 w-full text-left px-4 py-3 rounded-lg text-base font-medium transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-white hover:text-primary-200 hover:bg-primary-700 block px-4 py-3 rounded-lg text-base font-medium mb-3 transition-colors">
                                    <i class="fas fa-sign-in-alt mr-3"></i>Login
                                </a>
                                <a href="{{ route('register') }}" class="bg-white text-primary-600 hover:bg-primary-50 block px-4 py-3 rounded-lg text-base font-medium transition-colors">
                                    <i class="fas fa-user-plus mr-3"></i>Daftar
                                </a>
                            @endauth
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-primary-600 text-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- School Info -->
                    <div>
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('logo.png') }}" alt="Logo SMP Negeri 01 Namrole" class="h-10 w-auto mr-3">
                            <h3 class="text-lg font-semibold">SMP Negeri 01 Namrole</h3>
                        </div>
                        <p class="text-primary-100 text-sm leading-relaxed">
                            Sekolah Menengah Pertama yang berkomitmen untuk memberikan pendidikan berkualitas 
                            dan membentuk karakter siswa yang unggul.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Menu Cepat</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-primary-100 hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="{{ route('profil') }}" class="text-primary-100 hover:text-white transition-colors">Profil Sekolah</a></li>
                            <li><a href="#" class="text-primary-100 hover:text-white transition-colors">Visi & Misi</a></li>
                            <li><a href="#" class="text-primary-100 hover:text-white transition-colors">Struktur Organisasi</a></li>
                            <li><a href="#" class="text-primary-100 hover:text-white transition-colors">Prestasi</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Kontak Kami</h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $contact = \App\Models\Contact::getActive();
                            @endphp
                            <p class="text-primary-100">
                                <span class="font-medium">Alamat:</span><br>
                                {!! $contact->formatted_address ?? 'Jl. Pendidikan No. 123<br>Namrole, Maluku Tengah' !!}
                            </p>
                            <p class="text-primary-100">
                                <span class="font-medium">Telepon:</span> 
                                <a href="{{ $contact->phone_link ?? 'tel:(0911)123456' }}" class="hover:text-white transition-colors">
                                    {{ $contact->phone ?? '(0911) 123456' }}
                                </a>
                            </p>
                            <p class="text-primary-100">
                                <span class="font-medium">Email:</span> 
                                <a href="{{ $contact->email_link ?? 'mailto:smp01namrole@email.com' }}" class="hover:text-white transition-colors">
                                    {{ $contact->email ?? 'smp01namrole@email.com' }}
                                </a>
                            </p>
                        </div>
                        
                        <!-- Social Media Links -->
                        <div class="mt-6">
                            <h4 class="text-sm font-semibold text-primary-200 mb-3">Ikuti Kami</h4>
                            <div class="flex space-x-3">
                                @php
                                    $socialMedia = \App\Models\SocialMedia::getActive();
                                @endphp
                                @forelse($socialMedia as $social)
                                    <a href="{{ $social->url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-500 hover:bg-primary-400 transition-all duration-300 transform hover:scale-110 hover:shadow-lg"
                                       style="background-color: {{ $social->color }}"
                                       title="{{ $social->name }}">
                                        {!! $social->icon_html !!}
                                    </a>
                                @empty
                                    <div class="text-primary-200 text-sm">Sosial media belum tersedia</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-primary-500 mt-8 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-primary-100 text-sm mb-2 md:mb-0">
                            &copy; {{ date('Y') }} SMP Negeri 01 Namrole. Semua hak dilindungi.
                        </p>
                        <div class="flex space-x-4 text-sm">
                            <a href="{{ route('privacy-policy') }}" class="text-primary-100 hover:text-white transition-colors">Kebijakan Privasi</a>
                            <a href="{{ route('terms-conditions') }}" class="text-primary-100 hover:text-white transition-colors">Syarat & Ketentuan</a>
                            <a href="{{ route('sitemap') }}" class="text-primary-100 hover:text-white transition-colors">Peta Situs</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('show');
            
            // Prevent body scroll when menu is open
            if (mobileMenu.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Mobile Menu Close Button
        document.getElementById('mobile-menu-close').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('show');
            document.body.style.overflow = '';
        });

        // Mobile dropdown toggle function
        window.toggleMobileDropdown = function(dropdownId) {
            const dropdown = document.getElementById(dropdownId + '-dropdown');
            const arrow = document.getElementById(dropdownId + '-arrow');
            
            // Close all other dropdowns first
            document.querySelectorAll('[id$="-dropdown"]').forEach(d => {
                if (d.id !== dropdownId + '-dropdown') {
                    d.classList.add('hidden');
                }
            });
            document.querySelectorAll('[id$="-arrow"]').forEach(a => {
                if (a.id !== dropdownId + '-arrow') {
                    a.classList.remove('rotate-180');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        };

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        let lastScrollTop = 0;
        const header = document.querySelector('header');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const mobileMenu = document.getElementById('mobile-menu');
            
            // Only hide header on desktop, keep mobile menu visible when open
            if (window.innerWidth >= 1024) {
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    header.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up
                    header.style.transform = 'translateY(0)';
                }
            } else {
                // On mobile, keep header visible when mobile menu is open
                if (mobileMenu && mobileMenu.classList.contains('show')) {
                    header.style.transform = 'translateY(0)';
                } else {
                    if (scrollTop > lastScrollTop && scrollTop > 100) {
                        header.style.transform = 'translateY(-100%)';
                    } else {
                        header.style.transform = 'translateY(0)';
                    }
                }
            }
            
            lastScrollTop = scrollTop;
        });

        // Loading indicator control
        window.addEventListener('load', function() {
            const loadingIndicator = document.getElementById('loading-indicator');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
        });

        // Add fade-in animation to main content
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.classList.add('fade-in');
            }
        });

        // Add smooth transitions for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to navigation links
            const navLinks = document.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenuClose = document.getElementById('mobile-menu-close');
                
                if (mobileMenu && !mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target) && !mobileMenuClose.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });

            // Close mobile menu when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu) {
                        mobileMenu.classList.add('hidden');
                        mobileMenu.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                }
            });
        });
    </script>
    <script src="{{ asset("js/ultimate-force-white.js") }}"></script>
</body>
</html>
