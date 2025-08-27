@extends('admin.layouts.app')

@section('title', 'Detail Ketua UKM - ' . $ketuaUkm->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Ketua UKM</h1>
            <p class="text-gray-600">Informasi lengkap ketua UKM {{ $ketuaUkm->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ketua-ukm.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('admin.ketua-ukm.edit', $ketuaUkm) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Personal</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->name }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">NIM</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->nim }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->email }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">No. Telepon</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->phone ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                        @if($ketuaUkm->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @elseif($ketuaUkm->status === 'inactive')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-pause-circle mr-1"></i>Tidak Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-ban mr-1"></i>Suspended
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akademik</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Fakultas</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->faculty }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Program Studi</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->major }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Angkatan</h3>
                        <p class="text-gray-900">{{ $ketuaUkm->batch }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Role</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-crown mr-1"></i>Ketua UKM
                        </span>
                    </div>
                </div>
            </div>

            <!-- UKM yang Dipimpin -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">UKM yang Dipimpin</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $ketuaUkm->ledUkms->count() }} UKM
                    </span>
                </div>
                
                @if($ketuaUkm->ledUkms->count() > 0)
                    <div class="space-y-4">
                        @foreach($ketuaUkm->ledUkms as $ukm)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $ukm->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ ucfirst($ukm->category) }}</p>
                                    <p class="text-xs text-gray-400">{{ $ukm->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">{{ $ukm->activeMembers->count() }} anggota</p>
                                    <p class="text-sm text-gray-900">{{ $ukm->events->count() }} event</p>
                                    @if($ukm->status === 'active')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-3 flex space-x-2">
                                <a href="{{ route('admin.ukms.show', $ukm) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-sm">
                                    Lihat UKM
                                </a>
                                <form action="{{ route('admin.ketua-ukm.remove-ukm', [$ketuaUkm, $ukm]) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 text-sm"
                                            onclick="return confirm('Yakin ingin menghapus assignment UKM {{ $ukm->name }}?')">
                                        Hapus Assignment
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Belum memimpin UKM manapun</p>
                        <p class="text-sm text-gray-400">Tugaskan UKM untuk ketua UKM ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.ketua-ukm.edit', $ketuaUkm) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Data
                    </a>
                    
                    @if($ketuaUkm->status === 'active')
                        <form action="{{ route('admin.ketua-ukm.suspend', $ketuaUkm) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                    onclick="return confirm('Yakin ingin suspend {{ $ketuaUkm->name }}?')">
                                <i class="fas fa-ban mr-2"></i>Suspend
                            </button>
                        </form>
                    @elseif($ketuaUkm->status === 'suspended')
                        <form action="{{ route('admin.ketua-ukm.activate', $ketuaUkm) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                    onclick="return confirm('Yakin ingin mengaktifkan {{ $ketuaUkm->name }}?')">
                                <i class="fas fa-check-circle mr-2"></i>Aktifkan
                            </button>
                        </form>
                    @endif
                    
                    @if($ketuaUkm->ledUkms->count() === 0)
                        <form action="{{ route('admin.ketua-ukm.destroy', $ketuaUkm) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                    onclick="return confirm('Yakin ingin menurunkan {{ $ketuaUkm->name }} dari ketua UKM?')">
                                <i class="fas fa-user-minus mr-2"></i>Turunkan dari Ketua UKM
                            </button>
                        </form>
                    @else
                        <div class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium text-center">
                            <i class="fas fa-lock mr-2"></i>Tidak dapat diturunkan
                            <p class="text-xs mt-1">Masih memimpin {{ $ketuaUkm->ledUkms->count() }} UKM</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assign UKM -->
            @php
                $availableUkms = \App\Models\Ukm::whereNull('leader_id')->where('status', 'active')->get();
            @endphp
            
            @if($availableUkms->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tugaskan UKM</h2>
                
                <form action="{{ route('admin.ketua-ukm.assign-ukm', $ketuaUkm) }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <select name="ukm_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih UKM --</option>
                            @foreach($availableUkms as $ukm)
                                <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                            @endforeach
                        </select>
                        
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Tugaskan UKM
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Account Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Info Akun</h2>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="text-gray-900">{{ $ketuaUkm->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">Diperbarui:</span>
                        <span class="text-gray-900">{{ $ketuaUkm->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">ID:</span>
                        <span class="text-gray-900 font-mono text-xs">{{ $ketuaUkm->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
