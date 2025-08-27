@extends('layouts.app')

@section('title', $ukm->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <!-- Banner -->
        <div class="h-64 relative overflow-hidden">
            @if($ukm->background_image)
                <!-- Use UKM background image -->
                <img src="{{ asset('storage/' . $ukm->background_image) }}"
                     alt="{{ $ukm->name }} Background"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            @elseif($ukm->banner)
                <!-- Fallback to banner if no background image -->
                <img src="{{ asset('storage/' . $ukm->banner) }}"
                     alt="{{ $ukm->name }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            @else
                <!-- Default gradient if no images -->
                <div class="w-full h-full bg-gradient-to-r from-blue-600 to-blue-800"></div>
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            @endif
            <div class="absolute inset-0 flex items-end">
                <div class="p-8 text-white">
                    <div class="flex items-center mb-4">
                        @if($ukm->logo)
                            <div class="relative w-16 h-16 mr-4">
                                <!-- Background image behind logo -->
                                @if($ukm->background_image)
                                    <div class="absolute inset-0 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $ukm->background_image) }}"
                                             alt="{{ $ukm->name }} Background"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                                    </div>
                                @else
                                    <div class="absolute inset-0 bg-white rounded-lg"></div>
                                @endif
                                <!-- Logo on top -->
                                <img src="{{ asset('storage/' . $ukm->logo) }}"
                                     alt="{{ $ukm->name }}"
                                     class="relative w-full h-full object-contain p-2 z-10">
                            </div>
                        @else
                            <div class="relative w-16 h-16 mr-4">
                                <!-- Background image behind text logo -->
                                @if($ukm->background_image)
                                    <div class="absolute inset-0 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $ukm->background_image) }}"
                                             alt="{{ $ukm->name }} Background"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                    </div>
                                @else
                                    <div class="absolute inset-0 bg-white rounded-lg"></div>
                                @endif
                                <!-- Text logo on top -->
                                <div class="relative w-full h-full rounded-lg flex items-center justify-center z-10">
                                    <span class="text-2xl font-bold {{ $ukm->background_image ? 'text-white' : 'text-gray-800' }}">
                                        {{ substr($ukm->name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-3xl font-bold">{{ $ukm->name }}</h1>
                            <p class="text-blue-100">{{ ucfirst($ukm->category) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Bar -->
        <div class="px-8 py-4 bg-gray-50 border-b">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ $ukm->current_members }}/{{ $ukm->max_members }} Anggota
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Didirikan {{ $ukm->established_date ? $ukm->established_date->format('Y') : 'N/A' }}
                    </div>
                    <div class="flex items-center">
                        @if($ukm->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @endif
                        @if($ukm->is_recruiting)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                Buka Pendaftaran
                            </span>
                        @endif
                    </div>
                </div>

                @auth
                    @if(auth()->user()->role === 'student')
                        <div class="mt-4 sm:mt-0">
                            @if($membershipStatus === 'active')
                                @if($membershipStatus === 'active')
                                    <span class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-green-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Sudah Bergabung
                                    </span>
                                @elseif($membershipStatus === 'pending')
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-yellow-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Pendaftaran Sedang Diproses</h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>Pendaftaran Anda sedang dalam proses review oleh ketua UKM. Mohon tunggu konfirmasi lebih lanjut.</p>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="flex items-center text-xs text-yellow-600">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Estimasi waktu review: 1-3 hari kerja
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($membershipStatus === 'rejected')
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">Pendaftaran Ditolak</h3>
                                                <div class="mt-2 text-sm text-red-700">
                                                    <p>Maaf, pendaftaran Anda untuk bergabung dengan UKM ini tidak dapat diterima saat ini.</p>
                                                </div>
                                                <div class="mt-3">
                                                    @if($ukm->status === 'active' && $ukm->registration_status === 'open' && $ukm->current_members < $ukm->max_members)
                                                        <a href="{{ route('ukms.registration-form', $ukm->slug) }}"
                                                           class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                            Daftar Ulang
                                                        </a>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-gray-50">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                            </svg>
                                                            Pendaftaran Ditutup
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-gray-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status Tidak Diketahui
                                    </span>
                                @endif
                            @elseif($membershipStatus === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-yellow-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Menunggu Approval</h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>Pendaftaran Anda sedang dalam proses review oleh ketua UKM. Mohon tunggu konfirmasi lebih lanjut.</p>
                                            </div>
                                            <div class="mt-3">
                                                <div class="flex items-center text-xs text-yellow-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Estimasi waktu review: 1-3 hari kerja
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($ukm->status === 'active' && $ukm->registration_status === 'open' && $ukm->current_members < $ukm->max_members)
                                @if(!$membershipStatus || $membershipStatus === 'inactive' || $membershipStatus === 'alumni')
                                    <a href="{{ route('ukms.registration-form', $ukm->slug) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ ($membershipStatus === 'inactive' || $membershipStatus === 'alumni') ? 'Daftar Ulang' : 'Daftar Keanggotaan' }}
                                    </a>
                                @endif
                            @elseif($ukm->current_members >= $ukm->max_members)
                                <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-500 bg-gray-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Kapasitas Penuh
                                </span>
                            @elseif($ukm->status !== 'active')
                                <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-500 bg-gray-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                    </svg>
                                    UKM Tidak Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pendaftaran Ditutup
                                </span>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                            Login untuk Bergabung
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Description -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tentang UKM</h2>
                <p class="text-gray-700 leading-relaxed">{{ $ukm->description }}</p>
            </div>

            <!-- Vision & Mission -->
            @if($ukm->vision || $ukm->mission)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Visi & Misi</h2>
                    @if($ukm->vision)
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Visi</h3>
                            <p class="text-gray-700">{{ $ukm->vision }}</p>
                        </div>
                    @endif
                    @if($ukm->mission)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Misi</h3>
                            <p class="text-gray-700">{{ $ukm->mission }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Achievements -->
            @php
                $achievementCount = isset($achievements) ? $achievements->count() : 0;
            @endphp
            @if($achievementCount > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Prestasi UKM</h2>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">{{ $achievementCount }} prestasi</span>
                            <a href="{{ route('achievements.by-ukm', $ukm) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-trophy mr-2"></i>Lihat Semua Prestasi
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(collect($achievements ?? [])->sortByDesc('achievement_date')->take(4) as $achievement)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                                            <span class="badge badge-{{ $achievement->level == 'international' ? 'success' : ($achievement->level == 'national' ? 'info' : ($achievement->level == 'regional' ? 'warning' : 'secondary')) }}">
                                                {{ $achievement->level_text }}
                                            </span>
                                            <span class="badge badge-secondary">
                                                {{ $achievement->type_text }}
                                            </span>
                                            @if($achievement->is_featured)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-star mr-1"></i>Unggulan
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="font-semibold text-gray-900 mb-1">
                                            {{ $achievement->title }}
                                        </h3>
                                    </div>
                                    @if($achievement->position)
                                        <div class="flex-shrink-0 ml-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-xs">
                                                #{{ $achievement->position }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($achievement->description)
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                        {{ $achievement->description }}
                                    </p>
                                @endif

                                <div class="space-y-2 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar w-4 mr-2"></i>
                                        {{ $achievement->achievement_date->format('d M Y') }}
                                    </div>
                                    @if($achievement->organizer)
                                        <div class="flex items-center">
                                            <i class="fas fa-building w-4 mr-2"></i>
                                            <span class="truncate">{{ $achievement->organizer }}</span>
                                        </div>
                                    @endif
                                    @if($achievement->participants)
                                        <div class="flex items-start">
                                            <i class="fas fa-users w-4 mr-2 mt-0.5"></i>
                                            <span class="flex-1 line-clamp-2">{{ $achievement->participants }}</span>
                                        </div>
                                    @endif
                                    @if($achievement->certificate_file)
                                        <div class="flex items-center">
                                            <i class="fas fa-certificate w-4 mr-2"></i>
                                            <a href="{{ asset('storage/' . $achievement->certificate_file) }}" target="_blank" class="text-primary-600 hover:text-primary-800 underline">
                                                Lihat Sertifikat
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($achievementCount > 4)
                        <div class="mt-6 text-center">
                            <a href="{{ route('achievements.by-ukm', $ukm) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Lihat {{ $achievementCount - 4 }} prestasi lainnya <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State for Achievements -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Prestasi UKM</h2>
                        <a href="{{ route('achievements.index') }}"
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-trophy mr-2"></i>Lihat Prestasi UKM Lain
                        </a>
                    </div>
                    <div class="text-center py-8">
                        <i class="fas fa-trophy text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada prestasi</h3>
                        <p class="text-gray-500">{{ $ukm->name }} belum memiliki prestasi yang terdaftar.</p>
                    </div>
                </div>
            @endif

            <!-- Organization Structure -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Struktur Organisasi</h2>
                @if($ukm->organization_structure)
                    <div class="rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $ukm->organization_structure) }}"
                             alt="Struktur Organisasi {{ $ukm->name }}"
                             class="w-full h-auto">
                    </div>
                @else
                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">Struktur organisasi belum tersedia</p>
                        <p class="text-sm text-gray-400 mt-2">Admin dapat mengunggah gambar struktur organisasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                @php
                    $contactInfo = json_decode($ukm->contact_info, true) ?? [];
                @endphp

                <div class="space-y-3">
                    <!-- Ketua UKM -->
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Ketua UKM</div>
                            @if($ukm->leader)
                                <div class="text-sm text-gray-900 font-medium">{{ $ukm->leader->name }}</div>
                                @if($ukm->leader->nim)
                                    <div class="text-xs text-gray-500">NIM: {{ $ukm->leader->nim }}</div>
                                @endif
                            @else
                                <div class="text-sm text-gray-500 italic">Ketua belum ada, mungkin pendaftaran anggota akan tertunda</div>
                            @endif
                        </div>
                    </div>

                    @if(isset($contactInfo['email']))
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">{{ $contactInfo['email'] }}</span>
                        </div>
                    @endif

                    @if(isset($contactInfo['phone']))
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">{{ $contactInfo['phone'] }}</span>
                        </div>
                    @endif

                    @if(isset($contactInfo['instagram']))
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.875 2.026-1.297 3.323-1.297s2.448.422 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297z"/>
                            </svg>
                            <span class="text-sm text-gray-700">{{ $contactInfo['instagram'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Meeting Schedule -->
            @if($ukm->meeting_schedule)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Pertemuan</h3>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-700">{{ $ukm->meeting_schedule }}</p>
                            @if($ukm->meeting_location)
                                <p class="text-sm text-gray-500 mt-1">{{ $ukm->meeting_location }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Anggota</span>
                        <span class="text-sm font-medium text-gray-900">{{ $ukm->current_members }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kapasitas Maksimal</span>
                        <span class="text-sm font-medium text-gray-900">{{ $ukm->max_members }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status Rekrutmen</span>
                        @if($ukm->status === 'active' && $ukm->registration_status === 'open')
                            <span class="text-sm font-medium text-green-600">Buka</span>
                        @else
                            <span class="text-sm font-medium text-red-600">Tutup</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status UKM</span>
                        <span class="text-sm font-medium {{ $ukm->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($ukm->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
