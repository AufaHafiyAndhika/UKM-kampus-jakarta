@extends('admin.layouts.app')

@section('title', 'Detail Laporan - ' . $event->title)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Laporan Kegiatan</h1>
                <p class="text-gray-600">{{ $event->title }}</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kegiatan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Kegiatan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">UKM</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->ukm->name ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->start_datetime->format('d F Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->end_datetime->format('d F Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->location }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($event->status == 'completed') bg-green-100 text-green-800
                                @elseif($event->status == 'ongoing') bg-blue-100 text-blue-800
                                @elseif($event->status == 'published') bg-yellow-100 text-yellow-800
                                @elseif($event->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Peserta</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->max_participants ?? 'Tidak terbatas' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Biaya Pendaftaran</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($event->registration_fee > 0)
                                    Rp {{ number_format($event->registration_fee, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($event->description)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->description }}</p>
                        </div>
                    @endif
                    
                    @if($event->requirements)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Persyaratan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $event->requirements }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Laporan</h2>
                    
                    <div class="space-y-4">
                        <!-- Proposal -->
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Proposal Kegiatan</h3>
                                        <p class="text-sm text-gray-500">
                                            @if($event->proposal_file)
                                                File tersedia
                                            @else
                                                Belum ada file
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($event->proposal_file)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.reports.view', [$event, 'proposal']) }}" 
                                           target="_blank"
                                           class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.reports.download', [$event, 'proposal']) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                            Download
                                        </a>
                                    </div>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded text-sm">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>

                        <!-- RAB -->
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">RAB (Rencana Anggaran Biaya)</h3>
                                        <p class="text-sm text-gray-500">
                                            @if($event->rab_file)
                                                File tersedia
                                            @else
                                                Belum ada file
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($event->rab_file)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.reports.view', [$event, 'rab']) }}" 
                                           target="_blank"
                                           class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.reports.download', [$event, 'rab']) }}" 
                                           class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                            Download
                                        </a>
                                    </div>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded text-sm">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>

                        <!-- LPJ -->
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">LPJ (Laporan Pertanggungjawaban)</h3>
                                        <p class="text-sm text-gray-500">
                                            @if($event->lpj_file)
                                                File tersedia
                                            @else
                                                Belum ada file
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($event->lpj_file)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.reports.view', [$event, 'lpj']) }}" 
                                           target="_blank"
                                           class="px-3 py-1 bg-purple-100 text-purple-700 rounded text-sm hover:bg-purple-200">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.reports.download', [$event, 'lpj']) }}" 
                                           class="px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">
                                            Download
                                        </a>
                                    </div>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded text-sm">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Event Statistics -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Kegiatan</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Pendaftar</span>
                            <span class="text-sm font-medium text-gray-900">{{ $event->registrations->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Peserta Hadir</span>
                            <span class="text-sm font-medium text-gray-900">{{ $event->attendances->where('status', 'present')->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Sertifikat Tersedia</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($event->certificate_available)
                                    <span class="text-green-600">Ya</span>
                                @else
                                    <span class="text-red-600">Tidak</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Dibuat Pada</span>
                            <span class="text-sm font-medium text-gray-900">{{ $event->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Person -->
            @if($event->contact_person)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontak Person</h3>
                        
                        <div class="space-y-2">
                            @if(isset($event->contact_person['name']))
                                <div>
                                    <span class="text-sm text-gray-600">Nama:</span>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $event->contact_person['name'] }}</span>
                                </div>
                            @endif
                            
                            @if(isset($event->contact_person['phone']))
                                <div>
                                    <span class="text-sm text-gray-600">Telepon:</span>
                                    <a href="tel:{{ $event->contact_person['phone'] }}" class="text-sm font-medium text-blue-600 ml-2 hover:text-blue-800">
                                        {{ $event->contact_person['phone'] }}
                                    </a>
                                </div>
                            @endif
                            
                            @if(isset($event->contact_person['email']))
                                <div>
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <a href="mailto:{{ $event->contact_person['email'] }}" class="text-sm font-medium text-blue-600 ml-2 hover:text-blue-800">
                                        {{ $event->contact_person['email'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($event->notes)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h3>
                        <p class="text-sm text-gray-700">{{ $event->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
