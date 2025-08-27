@extends('admin.layouts.app')

@section('title', 'Detail UKM - ' . $ukm->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail UKM</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap Unit Kegiatan Mahasiswa</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.ukms.edit', $ukm->slug) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit UKM
                </a>
                <a href="{{ route('admin.ukms.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- UKM Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- UKM Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8 text-center">
                    @if($ukm->logo)
                        <img class="h-24 w-24 rounded-lg mx-auto border-4 border-white shadow-lg object-cover"
                             src="{{ asset('storage/' . $ukm->logo) }}"
                             alt="{{ $ukm->name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-24 w-24 bg-white rounded-lg mx-auto border-4 border-white shadow-lg flex items-center justify-center" style="display: none;">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @else
                        <div class="h-24 w-24 bg-white rounded-lg mx-auto border-4 border-white shadow-lg flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $ukm->name }}</h2>
                    <p class="text-blue-100">{{ ucfirst($ukm->category) }}</p>
                    <div class="mt-3 flex justify-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                   {{ $ukm->status === 'active' ? 'bg-green-100 text-green-800' :
                                      ($ukm->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($ukm->status) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                   {{ $ukm->registration_status === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ukm->registration_status === 'open' ? 'Buka Pendaftaran' : 'Tutup Pendaftaran' }}
                        </span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $ukm->members_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Anggota</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $ukm->events_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Event</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @if($ukm->email)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700 text-sm">{{ $ukm->email }}</span>
                            </div>
                        @endif

                        @if($ukm->phone)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-700 text-sm">{{ $ukm->phone }}</span>
                            </div>
                        @endif

                        @if($ukm->instagram)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                                <span class="text-gray-700 text-sm">@{{ $ukm->instagram }}</span>
                            </div>
                        @endif

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">Dibuat {{ $ukm->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 gap-3">
                        <button onclick="toggleStatus({{ $ukm->id }})" class="btn-secondary text-sm">
                            {{ $ukm->status === 'active' ? 'Nonaktifkan UKM' : 'Aktifkan UKM' }}
                        </button>
                        <button onclick="toggleRegistration({{ $ukm->id }})" class="btn-secondary text-sm">
                            {{ $ukm->registration_status === 'open' ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}
                        </button>
                        <a href="{{ route('ukms.show', $ukm->slug) }}" target="_blank" class="btn-secondary text-sm text-center">
                            Lihat Halaman Publik
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama UKM</label>
                            <p class="mt-1 text-gray-900">{{ $ukm->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Slug URL</label>
                            <p class="mt-1 text-gray-900">/ukm/{{ $ukm->slug }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kategori</label>
                            <p class="mt-1 text-gray-900">{{ ucfirst($ukm->category) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                           {{ $ukm->status === 'active' ? 'bg-green-100 text-green-800' :
                                              ($ukm->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($ukm->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Ketua UKM</label>
                            @if($ukm->leader)
                                <div class="mt-1 flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-gray-900">{{ $ukm->leader->name }}</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-crown mr-1"></i>Ketua UKM
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $ukm->leader->email }} • {{ $ukm->leader->nim }} • {{ $ukm->leader->major }}</p>
                                    </div>
                                    <form action="{{ route('admin.ukms.remove-leader', $ukm) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium"
                                                onclick="return confirm('Yakin ingin menurunkan {{ $ukm->leader->name }} dari ketua UKM? Role akan dikembalikan ke mahasiswa.')">
                                            Turunkan Ketua
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mt-1 flex items-center justify-between">
                                    <p class="text-gray-500">Belum ada ketua UKM</p>
                                    <a href="{{ route('admin.ketua-ukm.index') }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium">
                                        Angkat Ketua UKM
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="mt-1 text-gray-900">{{ $ukm->description }}</p>
                    </div>

                    @if($ukm->long_description)
                        <div class="mt-6">
                            <label class="text-sm font-medium text-gray-500">Deskripsi Lengkap</label>
                            <div class="mt-1 text-gray-900 prose max-w-none">
                                {!! nl2br(e($ukm->long_description)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact & Meeting Information -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kontak & Pertemuan</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ketua UKM -->
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Ketua UKM</label>
                            @if($ukm->leader)
                                <div class="mt-1">
                                    <p class="text-gray-900 font-medium">{{ $ukm->leader->name }}</p>
                                    @if($ukm->leader->nim)
                                        <p class="text-sm text-gray-500">NIM: {{ $ukm->leader->nim }}</p>
                                    @endif
                                    @if($ukm->leader->email)
                                        <p class="text-sm text-gray-500">Email: {{ $ukm->leader->email }}</p>
                                    @endif
                                </div>
                            @else
                                <p class="mt-1 text-gray-500 italic">Ketua belum ada, mungkin pendaftaran anggota akan tertunda</p>
                            @endif
                        </div>

                        @php
                            $contactInfo = is_array($ukm->contact_info) ? $ukm->contact_info : json_decode($ukm->contact_info, true) ?? [];
                        @endphp

                        @if(isset($contactInfo['email']))
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email UKM</label>
                                <p class="mt-1 text-gray-900">{{ $contactInfo['email'] }}</p>
                            </div>
                        @endif

                        @if(isset($contactInfo['phone']))
                            <div>
                                <label class="text-sm font-medium text-gray-500">Telepon UKM</label>
                                <p class="mt-1 text-gray-900">{{ $contactInfo['phone'] }}</p>
                            </div>
                        @endif

                        @if(isset($contactInfo['instagram']))
                            <div>
                                <label class="text-sm font-medium text-gray-500">Instagram UKM</label>
                                <p class="mt-1 text-gray-900">{{ $contactInfo['instagram'] }}</p>
                            </div>
                        @endif

                        @if(isset($contactInfo['website']))
                            <div>
                                <label class="text-sm font-medium text-gray-500">Website UKM</label>
                                <p class="mt-1 text-gray-900">
                                    <a href="{{ $contactInfo['website'] }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        {{ $contactInfo['website'] }}
                                    </a>
                                </p>
                            </div>
                        @endif



                        @if($ukm->meeting_day)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Hari Pertemuan</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($ukm->meeting_day) }}</p>
                            </div>
                        @endif

                        @if($ukm->meeting_time)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Waktu Pertemuan</label>
                                <p class="mt-1 text-gray-900">{{ $ukm->meeting_time }} WIB</p>
                            </div>
                        @endif
                    </div>

                    @if($ukm->meeting_location)
                        <div class="mt-6">
                            <label class="text-sm font-medium text-gray-500">Lokasi Pertemuan</label>
                            <p class="mt-1 text-gray-900">{{ $ukm->meeting_location }}</p>
                        </div>
                    @endif

                    @if($ukm->requirements)
                        <div class="mt-6">
                            <label class="text-sm font-medium text-gray-500">Persyaratan Bergabung</label>
                            <div class="mt-1 text-gray-900">
                                @foreach(explode(',', $ukm->requirements) as $requirement)
                                    <div class="flex items-start mt-1">
                                        <div class="flex-shrink-0 w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3"></div>
                                        <p>{{ trim($requirement) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Members List -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Anggota</h3>
                        <span class="text-sm text-gray-500">{{ $ukm->members_count ?? 0 }} anggota</span>
                    </div>
                </div>
                <div class="p-6">
                    @if($ukm->members && $ukm->members->count() > 0)
                        <div class="space-y-4">
                            @foreach($ukm->members->take(10) as $member)
                                @if($member)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            @if($member->avatar)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ asset('storage/' . $member->avatar) }}"
                                                     alt="{{ $member->name }}">
                                            @else
                                                <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <h4 class="font-medium text-gray-900">{{ $member->name }}</h4>
                                                <p class="text-sm text-gray-500">{{ $member->nim ?? 'N/A' }} - {{ $member->major ?? 'N/A' }}</p>
                                                @if($member->pivot && $member->pivot->role)
                                                    <p class="text-sm text-blue-600">{{ ucfirst($member->pivot->role) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                       {{ ($member->pivot && $member->pivot->status === 'active') ? 'bg-green-100 text-green-800' :
                                                          (($member->pivot && $member->pivot->status === 'pending') ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $member->pivot ? ucfirst($member->pivot->status) : 'Unknown' }}
                                            </span>
                                            <a href="{{ route('admin.users.show', $member->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg bg-red-50">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-red-200 rounded-full flex items-center justify-center">
                                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="font-medium text-red-900">Data Anggota Tidak Valid</h4>
                                                <p class="text-sm text-red-600">User tidak ditemukan atau telah dihapus</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Error
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($ukm->members->count() > 10)
                                <div class="text-center">
                                    <p class="text-sm text-gray-500">Dan {{ $ukm->members->count() - 10 }} anggota lainnya...</p>
                                    <a href="{{ route('admin.ukms.members', $ukm) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Lihat Semua Anggota
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada anggota</h3>
                            <p class="mt-1 text-sm text-gray-500">UKM ini belum memiliki anggota yang terdaftar.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Events -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Event Terbaru</h3>
                        <a href="{{ route('admin.events.index', ['ukm' => $ukm->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Lihat Semua Event
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($ukm->events && $ukm->events->count() > 0)
                        <div class="space-y-4">
                            @foreach($ukm->events->take(5) as $event)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                                            <p class="text-sm text-gray-500">{{ $event->start_datetime->format('d M Y, H:i') }} WIB</p>
                                            <p class="text-sm text-gray-600">{{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }} peserta</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                       {{ $event->status === 'published' ? 'bg-green-100 text-green-800' :
                                                          ($event->status === 'waiting' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                            <a href="{{ route('admin.events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada event</h3>
                            <p class="mt-1 text-sm text-gray-500">UKM ini belum mengadakan event apapun.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.events.create', ['ukm' => $ukm->id]) }}" class="btn-primary text-sm">
                                    Buat Event Baru
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
function toggleStatus(ukmId) {
    const currentStatus = '{{ $ukm->status }}';
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    if (confirm(`Apakah Anda yakin ingin ${newStatus === 'active' ? 'mengaktifkan' : 'menonaktifkan'} UKM ini?`)) {
        fetch(`/admin/ukms/${ukmId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status UKM.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
        });
    }
}

function toggleRegistration(ukmId) {
    const currentStatus = '{{ $ukm->registration_status }}';
    const newStatus = currentStatus === 'open' ? 'closed' : 'open';

    if (confirm(`Apakah Anda yakin ingin ${newStatus === 'open' ? 'membuka' : 'menutup'} pendaftaran UKM ini?`)) {
        fetch(`/admin/ukms/${ukmId}/toggle-registration`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ registration_status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status pendaftaran.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
        });
    }
}
</script>
@endsection
