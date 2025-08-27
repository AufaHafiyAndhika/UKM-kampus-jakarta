@extends('layouts.app')

@section('title', 'Status Pendaftaran UKM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Status Pendaftaran UKM</h1>
        <p class="text-gray-600">Pantau status pendaftaran dan keanggotaan UKM Anda</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Aktif</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $activeApplications->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800">Menunggu</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $pendingApplications->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-800">Ditolak</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $rejectedApplications->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-gray-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Keluar</h3>
                    <p class="text-2xl font-bold text-gray-600">{{ $inactiveApplications->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Memberships -->
    @if($activeApplications->count() > 0)
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                    UKM Aktif ({{ $activeApplications->count() }})
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeApplications as $ukm)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-3">
                                @if($ukm->logo)
                                    <img src="{{ asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}" class="w-12 h-12 rounded-lg object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-lg font-bold text-gray-600">{{ substr($ukm->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $ukm->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ ucfirst($ukm->pivot->role ?? 'Member') }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ukm->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('ukms.show', $ukm->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Detail
                                    </a>
                                    <form action="{{ route('ukms.leave', $ukm->slug) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin keluar dari UKM ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Bergabung: {{ $ukm->pivot->joined_date ? \Carbon\Carbon::parse($ukm->pivot->joined_date)->format('d M Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Pending Applications -->
    @if($pendingApplications->count() > 0)
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></span>
                    Pendaftaran Menunggu ({{ $pendingApplications->count() }})
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingApplications as $ukm)
                        <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                            <div class="flex items-center mb-3">
                                @if($ukm->logo)
                                    <img src="{{ asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}" class="w-12 h-12 rounded-lg object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-lg font-bold text-gray-600">{{ substr($ukm->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $ukm->name }}</h3>
                                    <p class="text-sm text-gray-500">Pendaftar</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ukm->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Review
                                </span>
                                <a href="{{ route('ukms.show', $ukm->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Detail
                                </a>
                            </div>
                            <div class="mt-3 pt-3 border-t border-yellow-200">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Mendaftar: {{ $ukm->pivot->applied_at ? \Carbon\Carbon::parse($ukm->pivot->applied_at)->format('d M Y') : 'N/A' }}
                                </p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pendaftaran sedang diproses, mohon tunggu
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Rejected Applications -->
    @if($rejectedApplications->count() > 0)
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                    Pendaftaran Ditolak ({{ $rejectedApplications->count() }})
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rejectedApplications as $ukm)
                        <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                            <div class="flex items-center mb-3">
                                @if($ukm->logo)
                                    <img src="{{ asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}" class="w-12 h-12 rounded-lg object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-lg font-bold text-gray-600">{{ substr($ukm->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $ukm->name }}</h3>
                                    <p class="text-sm text-gray-500">Ditolak</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ukm->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('ukms.show', $ukm->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Detail
                                    </a>
                                    <a href="{{ route('ukms.registration-form', $ukm->slug) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Daftar Ulang
                                    </a>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-red-200">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Ditolak: {{ $ukm->pivot->rejected_at ? \Carbon\Carbon::parse($ukm->pivot->rejected_at)->format('d M Y') : 'N/A' }}
                                </p>
                                @if($ukm->pivot->rejection_reason)
                                    <p class="text-xs text-red-600 mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Alasan: {{ $ukm->pivot->rejection_reason }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- No Applications -->
    @if($applications->count() === 0)
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Belum ada pendaftaran UKM</h3>
            <p class="text-gray-500 mb-6">Mulai bergabung dengan UKM untuk mengembangkan minat dan bakat Anda.</p>
            <a href="{{ route('ukms.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Jelajahi UKM
            </a>
        </div>
    @endif
</div>
@endsection
