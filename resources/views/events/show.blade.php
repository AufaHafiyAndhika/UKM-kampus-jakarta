@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-5xl mx-auto">
            <!-- Event Banner -->
        <div class="h-64 bg-gradient-to-r from-blue-600 to-purple-600 relative">
            @if($event->poster)
                <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                <div class="p-8 text-white w-full">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-gray-800 mr-4">
                                    {{ ucfirst($event->type) }}
                                </span>
                                @php
                                    $currentStatus = $event->getCurrentStatus();
                                @endphp
                                @if($currentStatus === 'published' && $event->isRegistrationOpen())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Buka Pendaftaran
                                    </span>
                                @elseif($currentStatus === 'ongoing')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Sedang Berlangsung
                                    </span>
                                @elseif($currentStatus === 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Selesai
                                    </span>
                                @elseif($currentStatus === 'published')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Akan Datang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        {{ ucfirst($currentStatus) }}
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
                            <p class="text-blue-100">Diselenggarakan oleh {{ $event->ukm->name }}</p>
                        </div>
                          @auth
                            @if(auth()->user()->role === 'student')
                                @if($userRegistration)
                                    <div class="text-right">
                                        @if($userRegistration->status === 'approved')
                                            <span class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-green-50 mb-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Sudah Terdaftar
                                            </span>
                                            <p class="text-sm text-blue-100 mb-3">Status: Disetujui</p>
                                        @elseif($userRegistration->status === 'pending')
                                            <span class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 bg-yellow-50 mb-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Menunggu Persetujuan
                                            </span>
                                            <p class="text-sm text-yellow-100 mb-3">Pendaftaran Anda sedang ditinjau oleh ketua UKM</p>
                                        @elseif($userRegistration->status === 'rejected')
                                            <span class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50 mb-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pendaftaran Ditolak
                                            </span>
                                            <p class="text-sm text-red-100 mb-3">Pendaftaran Anda ditolak oleh ketua UKM</p>
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-gray-50 mb-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Status: {{ ucfirst($userRegistration->status) }}
                                            </span>
                                        @endif

                                        @php
                                            $attendance = $event->attendances()->where('user_id', auth()->id())->first();
                                        @endphp

                                        @if($event->canSubmitAttendance())
                                            <!-- Event has ended, show attendance options -->
                                            @if(!$attendance)
                                                <a href="{{ route('events.attendance.form', $event->slug) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 mb-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Isi Absensi
                                                </a>
                                            @elseif($attendance->status === 'pending')
                                                <span class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 bg-yellow-50 mb-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Absensi Belum Disubmit
                                                </span>
                                                <a href="{{ route('events.attendance.form', $event->slug) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Isi Absensi
                                                </a>
                                            @elseif($attendance->status === 'present')
                                                @if($attendance->verification_status === 'pending')
                                                    <span class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 bg-yellow-50 mb-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Menunggu Verifikasi Absensi
                                                    </span>
                                                @elseif($attendance->verification_status === 'verified')
                                                    <span class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-green-50 mb-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Absensi Terverifikasi
                                                    </span>
                                                    @if($attendance->canDownloadCertificate())
                                                        <a href="{{ route('events.attendance.certificate', $event->slug) }}" class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-md text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            Download Sertifikat
                                                        </a>
                                                    @endif
                                                @elseif($attendance->verification_status === 'rejected')
                                                    <span class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50 mb-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Absensi Ditolak - Data Tidak Valid
                                                    </span>
                                                    @if($attendance->verification_notes)
                                                        <p class="text-xs text-red-600 mt-1"><strong>Alasan:</strong> {{ $attendance->verification_notes }}</p>
                                                    @endif
                                                    <p class="text-xs text-red-600 mt-1">⚠️ Sertifikat tidak dapat didownload karena absensi ditolak.</p>
                                                @endif
                                            @endif
                                        @else
                                            <!-- Event hasn't ended yet, show cancel option -->
                                            @if($userRegistration->canBeCancelled())
                                                <button onclick="openCancelModal()" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Batalkan Pendaftaran
                                                </button>
                                            @else
                                                <p class="text-xs text-gray-400">Pembatalan hanya dapat dilakukan maksimal 1 hari sebelum event</p>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    {{-- User is a student but not registered --}}
                                    {{-- Check if quota is full --}}
                                    @if($event->max_participants !== null && $event->current_participants >= $event->max_participants)
                                        <span class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50 mb-2">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Kuota Pendaftaran Penuh ({{ $event->current_participants }}/{{ $event->max_participants }})
                                        </span>
                                    @elseif($event->isRegistrationOpen())
                                        <a href="{{ route('events.register-form', $event->slug) }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Daftar Sekarang
                                        </a>                                    @endif
                                @endif
                            @endif
                        @else
                            {{-- User is not logged in --}}
                            @if($event->isRegistrationOpen())
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-blue-300 rounded-md text-base font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 mt-4">
                                    <span class="mx-auto">Login untuk Daftar</span>
                                </a>
                            @endif
                        @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>    </div>
    <!-- Content Grid -->
    <div class="max-w-5xl mx-auto mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Kegiatan</h2>
                <p class="text-gray-700 leading-relaxed mb-4">{{ $event->description }}</p>
                
                @if($event->long_description)
                    <div class="prose max-w-none">
                        {!! nl2br(e($event->long_description)) !!}
                    </div>
                @endif
            </div>

            <!-- Requirements -->
            @if($event->requirements)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Persyaratan</h2>
                    <div class="space-y-2">
                        @foreach(explode(',', $event->requirements) as $requirement)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3"></div>
                                <p class="text-gray-700">{{ trim($requirement) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Timeline Kegiatan</h2>
                <div class="space-y-4">
                    @if($event->registration_start)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full mr-4"></div>
                            <div>
                                <p class="font-medium text-gray-900">Pendaftaran Dibuka</p>
                                <p class="text-sm text-gray-500">{{ $event->registration_start->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    @endif
                    @if($event->registration_end)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-yellow-400 rounded-full mr-4"></div>
                            <div>
                                <p class="font-medium text-gray-900">Pendaftaran Ditutup</p>
                                <p class="text-sm text-gray-500">{{ $event->registration_end->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-3 h-3 bg-blue-400 rounded-full mr-4"></div>
                        <div>
                            <p class="font-medium text-gray-900">Kegiatan Dimulai</p>
                            <p class="text-sm text-gray-500">{{ $event->start_datetime->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-3 h-3 bg-red-400 rounded-full mr-4"></div>
                        <div>
                            <p class="font-medium text-gray-900">Kegiatan Selesai</p>
                            <p class="text-sm text-gray-500">{{ $event->end_datetime->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Penyelenggara</h2>
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-lg font-bold text-gray-600">{{ substr($event->ukm->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $event->ukm->name }}</h3>
                        <p class="text-gray-600">{{ ucfirst($event->ukm->category) }}</p>
                        <a href="{{ route('ukms.show', $event->ukm->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Lihat Profil UKM →
                        </a>
                    </div>
                </div>
            </div>
        </div>        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Event Info -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kegiatan</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Tanggal & Waktu</p>
                            <p class="text-sm text-gray-600">{{ $event->start_datetime->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Lokasi</p>
                            <p class="text-sm text-gray-600">{{ $event->location }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Peserta</p>
                            <p class="text-sm text-gray-600">{{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }} terdaftar</p>
                        </div>
                    </div>

                    @if($event->registration_fee > 0)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Biaya Pendaftaran</p>
                                <p class="text-sm text-gray-600">Rp {{ number_format($event->registration_fee, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Biaya Pendaftaran</p>
                                <p class="text-sm text-green-600 font-medium">Gratis</p>
                            </div>
                        </div>
                    @endif

                    @if($event->certificate_available)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Sertifikat</p>
                                <p class="text-sm text-gray-600">Tersedia untuk peserta</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Info -->
            @if($event->contact_person)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontak Person</h3>
                    @php
                        $contact = is_string($event->contact_person) ? json_decode($event->contact_person, true) : $event->contact_person;
                    @endphp
                    
                    <div class="space-y-3">
                        @if(isset($contact['name']))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $contact['name'] }}</span>
                            </div>
                        @endif
                        
                        @if(isset($contact['phone']))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $contact['phone'] }}</span>
                            </div>
                        @endif
                        
                        @if(isset($contact['email']))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $contact['email'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Share -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bagikan Kegiatan</h3>
                <div class="flex space-x-3">
                    <button onclick="shareToWhatsApp()" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-3 rounded-md text-sm font-medium">
                        WhatsApp
                    </button>
                    <button onclick="shareToTwitter()" class="flex-1 bg-blue-400 hover:bg-blue-500 text-white py-2 px-3 rounded-md text-sm font-medium">
                        Twitter
                    </button>
                    <button onclick="copyLink()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 rounded-md text-sm font-medium">
                        Copy Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
@auth
    @if(auth()->user()->role === 'student' && $event->isRegistrationOpen() && !$userRegistration)
        <div id="registrationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Kegiatan</h3>
                    <form action="{{ route('events.register', $event->slug) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="motivation" class="form-label">Motivasi Mengikuti Kegiatan *</label>
                            <textarea id="motivation" name="motivation" rows="4" required
                                      class="form-input" 
                                      placeholder="Jelaskan mengapa Anda ingin mengikuti kegiatan ini..."></textarea>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" onclick="closeRegistrationModal()" 
                                    class="btn-secondary">
                                Batal
                            </button>
                            <button type="submit" class="btn-primary">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role === 'student' && $userRegistration && $userRegistration->canBeCancelled())
        <!-- Cancel Registration Modal -->
        <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Batalkan Pendaftaran</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Apakah Anda yakin ingin membatalkan pendaftaran untuk event ini?
                    </p>
                    <form action="{{ route('events.cancel', $event->slug) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="mb-4">
                            <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Alasan Pembatalan (Opsional)
                            </label>
                            <textarea id="cancellation_reason" name="cancellation_reason" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Jelaskan alasan pembatalan..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeCancelModal()"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Ya, Batalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

<script>
function openRegistrationModal() {
    document.getElementById('registrationModal').classList.remove('hidden');
}

function closeRegistrationModal() {
    document.getElementById('registrationModal').classList.add('hidden');
}

function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

function shareToWhatsApp() {
    const text = `{{ $event->title }} - {{ $event->ukm->name }}\n{{ $event->start_datetime->format('d M Y, H:i') }} WIB\n{{ url()->current() }}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
}

function shareToTwitter() {
    const text = `{{ $event->title }} - {{ $event->ukm->name }}`;
    const url = `{{ url()->current() }}`;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link berhasil disalin!');
    });
}
</script>
@endsection
