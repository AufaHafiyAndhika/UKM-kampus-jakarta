@extends('layouts.app')

@section('title', 'Detail Event - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="text-gray-600">Detail event UKM {{ $event->ukm->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('ketua-ukm.events') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('ketua-ukm.events.edit', $event) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Event
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Event</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Jenis Event</h3>
                        <p class="text-gray-900">{{ ucfirst($event->type) }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                        <div class="flex items-center space-x-2">
                            @php
                                $currentStatus = $event->getCurrentStatus();
                            @endphp
                            @if($currentStatus === 'published')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Published
                                </span>
                            @elseif($currentStatus === 'draft')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Draft
                                </span>
                            @elseif($currentStatus === 'ongoing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-play mr-1"></i>Ongoing
                                </span>
                            @elseif($currentStatus === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-flag-checkered mr-1"></i>Completed
                                </span>
                            @elseif($currentStatus === 'waiting')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Persetujuan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Cancelled
                                </span>
                            @endif

                            @if($currentStatus !== $event->status)
                                <form action="{{ route('ketua-ukm.events.refresh-status', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs bg-orange-100 hover:bg-orange-200 text-orange-800 px-2 py-1 rounded-md transition-colors"
                                            title="Status tidak sinkron, klik untuk refresh">
                                        <i class="fas fa-sync mr-1"></i>Refresh
                                    </button>
                                </form>
                            @endif
                        </div>
                        @if($currentStatus !== $event->status)
                            <p class="text-xs text-orange-600 mt-1">
                                Status database: {{ $event->status }} | Status aktual: {{ $currentStatus }}
                            </p>
                        @endif
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Lokasi</h3>
                        <p class="text-gray-900">{{ $event->location }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Maksimal Peserta</h3>
                        <p class="text-gray-900">{{ $event->max_participants ?? 'Tidak terbatas' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Mulai</h3>
                        <p class="text-gray-900">{{ $event->start_datetime->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Selesai</h3>
                        <p class="text-gray-900">{{ $event->end_datetime->format('d M Y, H:i') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Status Pendaftaran</h3>
                        @if($event->registration_open)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Buka
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Tutup
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-900">{{ $event->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Registrations -->
            @if($event->registrations->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Pendaftar</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($event->registrations as $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($registration->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @elseif($registration->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Pendaftar</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $event->registrations->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Disetujui</span>
                        <span class="text-lg font-semibold text-green-600">{{ $event->registrations->where('status', 'approved')->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Menunggu</span>
                        <span class="text-lg font-semibold text-yellow-600">{{ $event->registrations->where('status', 'pending')->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Ditolak</span>
                        <span class="text-lg font-semibold text-red-600">{{ $event->registrations->where('status', 'rejected')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                
                <div class="space-y-3">
                    <a href="{{ route('ketua-ukm.events.edit', $event) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Event
                    </a>

                    @php
                        $currentStatus = $event->getCurrentStatus();
                    @endphp

                    @if(in_array($currentStatus, ['ongoing', 'completed']))
                        <a href="{{ route('ketua-ukm.events.attendances', $event) }}"
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                            <i class="fas fa-clipboard-check mr-2"></i>Kelola Absensi
                        </a>
                    @endif

                    @if($currentStatus === 'published')
                        <a href="{{ route('ketua-ukm.events.registrations', $event) }}"
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                            <i class="fas fa-users mr-2"></i>Kelola Pendaftar
                        </a>
                    @endif

                    <form action="{{ route('ketua-ukm.events.destroy', $event) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                onclick="return confirm('Yakin ingin menghapus event {{ $event->title }}?')">
                            <i class="fas fa-trash mr-2"></i>Hapus Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- Event Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Info Event</h2>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="text-gray-900">{{ $event->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">Diperbarui:</span>
                        <span class="text-gray-900">{{ $event->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">Slug:</span>
                        <span class="text-gray-900 font-mono text-xs">{{ $event->slug }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
