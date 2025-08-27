<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('seo_title', config('app.name', 'UKM Telkom Jakarta') . ' @yield("title")')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Website resmi Unit Kegiatan Mahasiswa Telkom Jakarta. Bergabunglah dengan berbagai UKM dan ikuti kegiatan menarik untuk mengembangkan potensi diri.')">
    <meta name="keywords" content="@yield('keywords', 'UKM, Telkom Jakarta, mahasiswa, kegiatan, organisasi, ekstrakurikuler, pengembangan diri')">
    <meta name="author" content="UKM Telkom Jakarta">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="language" content="id">
    <meta name="revisit-after" content="7 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Alternate Language -->
    <link rel="alternate" hreflang="id" href="{{ url()->current() }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', config('app.name') . ' @yield("title")')">
    <meta property="og:description" content="@yield('og_description', '@yield("description", "Website resmi Unit Kegiatan Mahasiswa Telkom Jakarta")')">
    <meta property="og:image" content="@yield('og_image', asset('storage/Telkom.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="UKM Telkom Jakarta">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@telkomuniv_jkt">
    <meta name="twitter:creator" content="@telkomuniv_jkt">
    <meta name="twitter:title" content="@yield('twitter_title', '@yield("og_title", config("app.name") . " @yield(\"title\")")')">
    <meta name="twitter:description" content="@yield('twitter_description', '@yield("og_description", "@yield(\"description\", \"Website resmi Unit Kegiatan Mahasiswa Telkom Jakarta\")")')">
    <meta name="twitter:image" content="@yield('twitter_image', '@yield("og_image", asset("storage/Telkom.png"))')">

    <!-- Additional Meta Tags -->
    <meta name="geo.region" content="ID-JB">
    <meta name="geo.placename" content="Bandung">
    <meta name="geo.position" content="-6.8734;107.5755">
    <meta name="ICBM" content="-6.8734, 107.5755">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#dc2626">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Structured Data -->
    @stack('structured_data')

    <!-- Default Organization Schema -->
    @if(!View::hasSection('structured_data'))
    <script type="application/ld+json">
    {!! json_encode(\App\Helpers\SeoHelper::getOrganizationSchema(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    <!-- Additional Head Content -->
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <img src="{{ asset('storage/Telkom.PNG') }}" alt="Logo" class="h-8 w-auto">
                            <span class="font-display font-bold text-xl text-gray-900">UKM Telkom</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors">
                            Beranda
                        </a>
                        <a href="{{ route('ukms.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors">
                            UKM
                        </a>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors">
                            Kegiatan
                        </a>
                        @auth
                            @if(auth()->user()->role === 'student')
                                <a href="{{ route('ukms.my-applications') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors">
                                    Status UKM
                                </a>
                            @endif
                        @endauth
                        <a href="{{ route('about') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors">
                            Tentang
                        </a>

                        @auth
                            <!-- Notifications -->
                            <div class="relative mr-4" x-data="{ open: false }">
                                <button @click="open = !open" class="relative p-2 text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <i class="fas fa-bell text-lg"></i>
                                    @if(auth()->user()->unreadNotificationsCount() > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ auth()->user()->unreadNotificationsCount() }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Notifications Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                    <div class="py-2">
                                        <div class="px-4 py-2 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium text-gray-900">Notifikasi</h3>
                                                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Lihat Semua</a>
                                            </div>
                                        </div>

                                        @php
                                            $recentNotifications = auth()->user()->notifications()->limit(3)->get();
                                        @endphp

                                        @if($recentNotifications->count() > 0)
                                            @foreach($recentNotifications as $notification)
                                                <div class="px-4 py-3 hover:bg-gray-50 {{ !$notification->isRead() ? 'bg-blue-50' : '' }}">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 mr-3">
                                                            @if($notification->type === 'ukm_application_approved')
                                                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                                                </div>
                                                            @elseif($notification->type === 'ukm_application_rejected')
                                                                <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-times text-red-600 text-xs"></i>
                                                                </div>
                                                            @else
                                                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-bell text-blue-600 text-xs"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs font-medium text-gray-900 truncate">{{ $notification->title }}</p>
                                                            <p class="text-xs text-gray-600 truncate">{{ Str::limit($notification->message, 50) }}</p>
                                                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="px-4 py-6 text-center">
                                                <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
                                                <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                             alt="Avatar" class="h-6 w-6 rounded-full object-cover">
                                    @else
                                        <div class="h-6 w-6 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                Admin Panel
                                            </span>
                                        </a>
                                    @elseif(auth()->user()->isKetuaUkm())
                                        <a href="{{ route('ketua-ukm.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                Kelola UKM
                                            </span>
                                        </a>
                                        <a href="{{ route('ketua-ukm.events') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Kelola Event
                                            </span>
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard Mahasiswa</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary">
                                Daftar
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="text-gray-700 hover:text-primary-600 p-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                        Beranda
                    </a>
                    <a href="{{ route('ukms.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                        UKM
                    </a>
                    <a href="{{ route('events.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                        Kegiatan
                    </a>
                    <a href="{{ route('about') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                        Tentang
                    </a>

                    @auth
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex items-center px-3 py-2">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                         alt="Avatar" class="h-8 w-8 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-gray-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="text-base font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        Admin Panel
                                    </span>
                                </a>
                            @elseif(auth()->user()->isKetuaUkm())
                                <a href="{{ route('ketua-ukm.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Kelola UKM
                                    </span>
                                </a>
                                <a href="{{ route('ketua-ukm.events') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Kelola Event
                                    </span>
                                </a>
                                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    Dashboard Mahasiswa
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    Dashboard
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="border-t border-gray-200 pt-4">
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md mx-3">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3 rounded-md mx-4 mt-4 alert-auto-hide">
                <div class="flex">
                    <svg class="h-5 w-5 text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mx-4 mt-4 alert-auto-hide">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="{{ asset('storage/Telkom.png') }}" alt="Logo" class="h-8 w-auto">
                            <span class="font-display font-bold text-xl">UKM Telkom Jakarta</span>
                        </div>
                        <p class="text-gray-300 mb-4">
                            Platform digital untuk mengelola dan mengembangkan Unit Kegiatan Mahasiswa di Telkom Jakarta.
                            Bergabunglah dengan komunitas mahasiswa yang aktif dan kreatif.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="font-semibold text-lg mb-4">Tautan Cepat</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('ukms.index') }}" class="text-gray-300 hover:text-white transition-colors">Daftar UKM</a></li>
                            <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white transition-colors">Kegiatan</a></li>
                            <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors">Tentang Kami</a></li>
                            <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors">Kontak</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="font-semibold text-lg mb-4">Kontak</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>📧 ukm@telkomuniversity.ac.id</li>
                            <li>📞 (021) 1234-5678</li>
                            <li>📍 Jl. Telekomunikasi No. 1, Jakarta</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} UKM Telkom Jakarta. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Notification System -->
    <script src="{{ asset('js/toast-notifications.js') }}"></script>

    <!-- Toast Data from Session -->
    @if(session('toast'))
    <script>
        window.toastData = @json(session('toast'));
    </script>
    @endif

    <!-- Legacy Flash Messages as Toast -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.success('Berhasil!', '{{ session('success') }}');
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.error('Error!', '{{ session('error') }}');
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.warning('Peringatan!', '{{ session('warning') }}');
        });
    </script>
    @endif

    @if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.info('Informasi', '{{ session('info') }}');
        });
    </script>
    @endif

    <!-- Additional Scripts -->
    @stack('scripts')

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
