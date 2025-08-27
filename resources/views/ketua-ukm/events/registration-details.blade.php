@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pendaftaran Event</h1>
            <p class="text-gray-600">{{ $event->title }}</p>
        </div>
        <a href="{{ route('ketua-ukm.events.registrations', $event) }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pendaftar</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0 h-16 w-16">
                            @if($registration->user->avatar)
                                <img class="h-16 w-16 rounded-full object-cover" 
                                     src="{{ asset('storage/' . $registration->user->avatar) }}" 
                                     alt="{{ $registration->user->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700">
                                        {{ strtoupper(substr($registration->user->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h3 class="text-xl font-medium text-gray-900">{{ $registration->user->name }}</h3>
                            <p class="text-gray-500">{{ $registration->user->nim }}</p>
                            <p class="text-sm text-gray-400">{{ $registration->user->major }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $registration->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <p class="text-sm text-gray-900">{{ $registration->user->phone ?? 'Tidak tersedia' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                            <p class="text-sm text-gray-900">{{ $registration->user->faculty ?? 'Tidak tersedia' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                            <p class="text-sm text-gray-900">{{ $registration->user->batch ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Detail Pendaftaran</h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($registration->motivation)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Motivasi</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $registration->motivation }}</p>
                            </div>
                        </div>
                    @endif

                    @if($registration->availability_form)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ketersediaan</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @php
                                    $availabilityData = is_array($registration->availability_form) ? $registration->availability_form : json_decode($registration->availability_form, true);
                                @endphp
                                @if(is_array($availabilityData))
                                    <div class="space-y-2">
                                        @foreach($availabilityData as $key => $value)
                                            @if($value && $value !== '')
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                    <span class="text-sm text-gray-900">
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-900">
                                        @if(is_string($registration->availability_form))
                                            {{ $registration->availability_form }}
                                        @else
                                            {{ json_encode($registration->availability_form) }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($registration->registration_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $registration->registration_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($registration->additional_data)
                        @php $additionalData = is_string($registration->additional_data) ? json_decode($registration->additional_data, true) : $registration->additional_data; @endphp
                        @if($additionalData && is_array($additionalData))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Tambahan</label>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                    @foreach($additionalData as $key => $value)
                                        @if($value && $value !== '')
                                            <div class="flex justify-between">
                                                <span class="text-sm font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                <span class="text-sm text-gray-900">
                                                    @if(is_array($value))
                                                        {{ implode(', ', $value) }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($registration->payment_proof)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <a href="{{ asset('storage/' . $registration->payment_proof) }}" 
                                   target="_blank"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-file-image mr-2"></i>
                                    Lihat Bukti Pembayaran
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pendaftaran</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            @if($registration->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Terdaftar
                                </span>
                            @elseif($registration->status === 'cancelled')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Dibatalkan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-question mr-1"></i>{{ ucfirst($registration->status) }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Tanggal Daftar:</span>
                            <span class="text-sm text-gray-900">{{ $registration->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        @if($registration->approved_at)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Disetujui:</span>
                                <span class="text-sm text-gray-900">{{ $registration->approved_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif

                        @if($registration->rejection_reason)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Alasan Penolakan:</span>
                                <span class="text-sm text-gray-900">{{ $registration->rejection_reason }}</span>
                            </div>
                        @endif

                        @if($event->registration_fee > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Biaya:</span>
                                <span class="text-sm text-gray-900">Rp {{ number_format($event->registration_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Status Bayar:</span>
                                @if($registration->payment_status === 'verified')
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Verified</span>
                                @elseif($registration->payment_status === 'pending')
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Pending</span>
                                @else
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Belum Bayar</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Event Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Event</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Event:</span>
                            <p class="font-medium text-gray-900">{{ $event->title }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">UKM:</span>
                            <p class="font-medium text-gray-900">{{ $event->ukm->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Tanggal:</span>
                            <p class="text-gray-900">{{ $event->start_datetime->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Lokasi:</span>
                            <p class="text-gray-900">{{ $event->location }}</p>
                        </div>
                        @if($event->max_participants)
                            <div>
                                <span class="text-gray-600">Kuota:</span>
                                <p class="text-gray-900">{{ $event->current_participants }}/{{ $event->max_participants }} peserta</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($registration->status === 'pending')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Pendaftaran</h3>
                <div class="flex items-center space-x-4">
                    <form action="{{ route('ketua-ukm.events.registrations.approve', [$event, $registration]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-check mr-2"></i>Setujui Pendaftaran
                        </button>
                    </form>

                    <button onclick="showRejectModal()"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>Tolak Pendaftaran
                    </button>
                </div>
            </div>
        @elseif($registration->status === 'approved')
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Pendaftaran Sudah Disetujui</h3>
                        <p class="text-sm text-green-700 mt-1">
                            Pendaftaran ini telah disetujui pada {{ $registration->approved_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif($registration->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Pendaftaran Sudah Ditolak</h3>
                        <p class="text-sm text-red-700 mt-1">
                            @if($registration->rejection_reason)
                                Alasan: {{ $registration->rejection_reason }}
                            @else
                                Pendaftaran ini telah ditolak.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pendaftaran</h3>
            <form action="{{ route('ketua-ukm.events.registrations.reject', [$event, $registration]) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Berikan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('notes').value = '';
}
</script>

@endsection
