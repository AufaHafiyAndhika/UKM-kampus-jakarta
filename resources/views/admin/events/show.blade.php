@extends('admin.layouts.app')

@section('title', 'Detail Kegiatan - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kegiatan</h1>
            <p class="text-gray-600">{{ $event->title }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.events.edit', $event) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.events.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $event->title }}</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($event->type) }}
                            </span>
                            @if($event->status === 'published')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Published
                                </span>
                            @elseif($event->status === 'draft')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Draft
                                </span>
                            @elseif($event->status === 'ongoing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-play mr-1"></i>Ongoing
                                </span>
                            @elseif($event->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-flag-checkered mr-1"></i>Completed
                                </span>
                            @elseif($event->status === 'waiting')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Persetujuan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Cancelled
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($event->status === 'draft')
                        <form action="{{ route('admin.events.publish', $event) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors"
                                    onclick="return confirm('Yakin ingin mempublikasikan event ini?')">
                                <i class="fas fa-check mr-1"></i>Publish
                            </button>
                        </form>
                        @endif
                        @if(in_array($event->status, ['draft', 'published']))
                        <form action="{{ route('admin.events.cancel', $event) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors"
                                    onclick="return confirm('Yakin ingin membatalkan event ini?')">
                                <i class="fas fa-ban mr-1"></i>Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                @if($event->poster)
                <div class="mb-6">
                    <img src="{{ asset('storage/' . $event->poster) }}" 
                         alt="{{ $event->title }}" 
                         class="w-full h-64 object-cover rounded-lg">
                </div>
                @endif

                <div class="prose max-w-none">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Deskripsi</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $event->description }}</p>
                </div>

                @if($event->requirements)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Persyaratan</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $event->requirements }}</p>
                </div>
                @endif

                @if($event->notes)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $event->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Registrations -->
            @if($event->registrations->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pendaftar ({{ $event->registrations->count() }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($event->registrations as $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ $registration->user->avatar ? asset('storage/' . $registration->user->avatar) : asset('images/default-avatar.png') }}" 
                                                 alt="{{ $registration->user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $registration->user->nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $registration->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($registration->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($registration->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
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
            <!-- Event Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kegiatan</h3>
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">UKM Penyelenggara</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->ukm->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $event->start_datetime->format('d M Y, H:i') }} - 
                            {{ $event->end_datetime->format('H:i') }} WIB
                        </dd>
                    </div>
                    @if($event->registration_start || $event->registration_end)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Periode Pendaftaran</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($event->registration_start)
                                {{ $event->registration_start->format('d M Y, H:i') }}
                            @else
                                Segera
                            @endif
                            -
                            @if($event->registration_end)
                                {{ $event->registration_end->format('d M Y, H:i') }}
                            @else
                                Sampai event dimulai
                            @endif
                        </dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Peserta</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Biaya</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($event->registration_fee > 0)
                                Rp {{ number_format($event->registration_fee, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Persetujuan Admin</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $event->requires_approval ? 'Diperlukan' : 'Tidak diperlukan' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sertifikat</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $event->certificate_available ? 'Tersedia' : 'Tidak tersedia' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Contact Person -->
            @if($event->contact_person && is_array($event->contact_person) && count($event->contact_person) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak Person</h3>
                <div class="space-y-3">
                    @if(isset($event->contact_person['name']))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->contact_person['name'] }}</dd>
                    </div>
                    @endif
                    @if(isset($event->contact_person['phone']))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="tel:{{ $event->contact_person['phone'] }}" class="text-blue-600 hover:text-blue-800">
                                {{ $event->contact_person['phone'] }}
                            </a>
                        </dd>
                    </div>
                    @endif
                    @if(isset($event->contact_person['email']))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="mailto:{{ $event->contact_person['email'] }}" class="text-blue-600 hover:text-blue-800">
                                {{ $event->contact_person['email'] }}
                            </a>
                        </dd>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Pendaftar</span>
                        <span class="text-sm font-medium text-gray-900">{{ $event->registrations->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Approved</span>
                        <span class="text-sm font-medium text-green-600">{{ $event->registrations->where('status', 'approved')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Pending</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $event->registrations->where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Rejected</span>
                        <span class="text-sm font-medium text-red-600">{{ $event->registrations->where('status', 'rejected')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Kegiatan</h3>
                <div class="space-y-3">
                    @if($event->proposal_file)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-blue-600 text-lg mr-3"></i>
                                <div>
                                    <p class="font-medium text-blue-900">Proposal Kegiatan</p>
                                    <p class="text-sm text-blue-600">File proposal tersedia</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.download', ['event' => $event, 'type' => 'proposal']) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                <i class="fas fa-download mr-1"></i>Unduh
                            </a>
                        </div>
                    @else
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-file-alt text-gray-400 text-lg mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-600">Proposal Kegiatan</p>
                                <p class="text-sm text-gray-500">Belum ada file proposal</p>
                            </div>
                        </div>
                    @endif

                    @if($event->rab_file)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <i class="fas fa-calculator text-green-600 text-lg mr-3"></i>
                                <div>
                                    <p class="font-medium text-green-900">RAB (Rencana Anggaran Biaya)</p>
                                    <p class="text-sm text-green-600">File RAB tersedia</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.download', ['event' => $event, 'type' => 'rab']) }}"
                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                <i class="fas fa-download mr-1"></i>Unduh
                            </a>
                        </div>
                    @else
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-calculator text-gray-400 text-lg mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-600">RAB (Rencana Anggaran Biaya)</p>
                                <p class="text-sm text-gray-500">Belum ada file RAB</p>
                            </div>
                        </div>
                    @endif

                    @if($event->lpj_file)
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice text-purple-600 text-lg mr-3"></i>
                                <div>
                                    <p class="font-medium text-purple-900">LPJ (Laporan Pertanggungjawaban)</p>
                                    <p class="text-sm text-purple-600">File LPJ tersedia</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.download', ['event' => $event, 'type' => 'lpj']) }}"
                               class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                <i class="fas fa-download mr-1"></i>Unduh
                            </a>
                        </div>
                    @else
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-file-invoice text-gray-400 text-lg mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-600">LPJ (Laporan Pertanggungjawaban)</p>
                                <p class="text-sm text-gray-500">Belum ada file LPJ</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Kegiatan
                    </a>
                    @if($event->status === 'waiting')
                    <form action="{{ route('admin.events.publish', $event) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                onclick="return confirm('Yakin ingin mempublikasikan event ini?')">
                            <i class="fas fa-check mr-2"></i>Publish Event
                        </button>
                    </form>
                    @endif
                    @if(in_array($event->status, ['waiting', 'published']))
                    <form action="{{ route('admin.events.cancel', $event) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                onclick="return confirm('Yakin ingin membatalkan event ini?')">
                            <i class="fas fa-ban mr-2"></i>Cancel Event
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                onclick="return confirm('Yakin ingin menghapus event {{ $event->title }}? Aksi ini tidak dapat dibatalkan.')">
                            <i class="fas fa-trash mr-2"></i>Hapus Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
