@extends('admin.layouts.app')

@section('title', 'Detail Mahasiswa - ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Mahasiswa</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap data mahasiswa</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Data
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8 text-center">
                    @if($user->avatar)
                        <img class="h-24 w-24 rounded-full mx-auto border-4 border-white shadow-lg object-cover"
                             src="{{ asset('storage/' . $user->avatar) }}"
                             alt="{{ $user->name }}">
                    @else
                        <div class="h-24 w-24 bg-white rounded-full mx-auto border-4 border-white shadow-lg flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-blue-100">{{ $user->nim }}</p>
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                   {{ $user->status === 'active' ? 'bg-green-100 text-green-800' :
                                      ($user->status === 'inactive' ? 'bg-red-100 text-red-800' :
                                       ($user->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="p-6 space-y-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $user->phone }}</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700">Bergabung {{ $user->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700">Terakhir login {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}</span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 gap-3">
                        <button onclick="resetPassword({{ $user->id }})" class="btn-secondary text-sm">
                            Reset Password
                        </button>
                    </div>
                    <div class="mt-3 text-center">
                        <p class="text-xs text-gray-500">
                            Untuk mengubah status akun, gunakan tombol Aktifkan/Suspend di
                            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">Kelola Mahasiswa</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Personal</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">NIM</label>
                            <p class="mt-1 text-gray-900">{{ $user->nim }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                            <p class="mt-1 text-gray-900">{{ $user->phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                            <p class="mt-1 text-gray-900">{{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Role</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                           {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Akademik</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Fakultas</label>
                            <p class="mt-1 text-gray-900">{{ $user->faculty }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Program Studi</label>
                            <p class="mt-1 text-gray-900">{{ $user->major }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Angkatan</label>
                            <p class="mt-1 text-gray-900">{{ $user->batch }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                           {{ $user->status === 'active' ? 'bg-green-100 text-green-800' :
                                              ($user->status === 'inactive' ? 'bg-red-100 text-red-800' :
                                               ($user->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">UKM Diikuti</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $user->ukms ? $user->ukms->count() : 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Event Terdaftar</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $user->eventRegistrations ? $user->eventRegistrations->count() : 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Event Dihadiri</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $user->attendances ? $user->attendances->where('status', 'verified')->count() : 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- UKM Membership -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Keanggotaan UKM</h3>
                </div>
                <div class="p-6">
                    @if($user->ukms && $user->ukms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->ukms as $ukm)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $ukm->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ ucfirst($ukm->category) }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                   {{ $ukm->pivot->status === 'active' ? 'bg-green-100 text-green-800' :
                                                      ($ukm->pivot->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($ukm->pivot->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p>Bergabung: {{ $ukm->pivot->joined_date ? \Carbon\Carbon::parse($ukm->pivot->joined_date)->format('d M Y') : '-' }}</p>
                                        @if($ukm->pivot->role)
                                            <p>Role: {{ ucfirst($ukm->pivot->role) }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.ukms.show', $ukm) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            Lihat Detail UKM
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum bergabung UKM</h3>
                            <p class="mt-1 text-sm text-gray-500">Mahasiswa ini belum bergabung dengan UKM manapun.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Event Participation -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Partisipasi Event</h3>
                </div>
                <div class="p-6">
                    @if($user->eventRegistrations && $user->eventRegistrations->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->eventRegistrations->take(5) as $registration)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $registration->event->title }}</h4>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $registration->event->start_datetime->format('d M Y, H:i') }} WIB
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $registration->event->ukm->name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                       {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' :
                                                          ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between text-sm text-gray-600">
                                        <span>Daftar: {{ $registration->created_at->format('d M Y') }}</span>

                                        <!-- Check if user has attendance record -->
                                        @php
                                            $attendance = $user->attendances->where('event_id', $registration->event_id)->first();
                                        @endphp

                                        @if($attendance)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs
                                                       {{ $attendance->status === 'verified' ? 'bg-blue-100 text-blue-800' :
                                                          ($attendance->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Absensi: {{ ucfirst($attendance->status) }}
                                            </span>
                                        @elseif($registration->event->canSubmitAttendance())
                                            <span class="text-orange-600 text-xs">
                                                <i class="fas fa-clock mr-1"></i>
                                                Belum absen
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($user->eventRegistrations->count() > 5)
                                <div class="text-center">
                                    <p class="text-sm text-gray-500">Dan {{ $user->eventRegistrations->count() - 5 }} event lainnya...</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum mengikuti event</h3>
                            <p class="mt-1 text-sm text-gray-500">Mahasiswa ini belum pernah mendaftar event.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals and Scripts -->
<script>
function resetPassword(userId) {
    if (confirm('Apakah Anda yakin ingin mereset password mahasiswa ini?')) {
        // Implementation for password reset
        fetch(`/admin/users/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Password berhasil direset. Password baru: ' + data.new_password);
            } else {
                alert('Gagal mereset password.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
        });
    }
}

// Status management moved to Kelola Mahasiswa page
// Use activate/suspend buttons in the user list for status changes
</script>
@endsection
