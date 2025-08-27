@extends('layouts.app')

@section('title', 'Dashboard Ketua UKM')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Ketua UKM</h1>
        <p class="text-gray-600">Kelola UKM yang Anda pimpin</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Kegiatan Mendatang</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Sedang Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['ongoing_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-list text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Kegiatan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <i class="fas fa-building text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">UKM yang Dipimpin</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ukms'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Member</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_members'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($leadingUkms->count() > 0)
    <!-- UKM yang Dipimpin -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($leadingUkms as $ukm)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $ukm->name }}</h3>
                    <p class="text-sm text-gray-500">{{ ucfirst($ukm->category) }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $ukm->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($ukm->status) }}
                </span>
            </div>

            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $ukm->description }}</p>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500">Member Aktif</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $ukm->activeMembers()->count() }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Event</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $ukm->events()->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <a href="{{ route('ketua-ukm.manage', $ukm->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-cog mr-1"></i>Kelola UKM
                </a>
                <a href="{{ route('ketua-ukm.pending-members') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors relative">
                    <i class="fas fa-user-clock mr-1"></i>Pendaftar Baru
                    @if($pendingCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('ketua-ukm.members') }}"
                   class="bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-users mr-1"></i>Anggota
                </a>
                <a href="{{ route('ketua-ukm.create-event', $ukm->id) }}"
                   class="bg-green-600 hover:bg-green-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-1"></i>Event
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- No UKM Assigned -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <div class="text-gray-500">
            <i class="fas fa-building text-6xl mb-4"></i>
            <h3 class="text-xl font-medium mb-2">Belum Ada UKM yang Ditugaskan</h3>
            <p class="text-gray-400 mb-6">Anda belum ditugaskan untuk memimpin UKM manapun.</p>
            <p class="text-sm text-gray-500">
                Hubungi admin untuk mendapatkan assignment UKM yang akan Anda pimpin.
            </p>
        </div>
    </div>
    @endif
</div>
@endsection
