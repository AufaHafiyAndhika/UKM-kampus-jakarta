@extends('admin.layouts.app')

@section('title', 'Kelola UKM')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola UKM</h1>
            <p class="text-gray-600">Manajemen Unit Kegiatan Mahasiswa</p>
        </div>
        <a href="{{ route('admin.ukms.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah UKM
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.ukms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nama UKM, deskripsi..." 
                       class="form-input">
            </div>
            <div>
                <label class="form-label">Kategori</label>
                <select name="category" class="form-input">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-primary">Filter</button>
                <a href="{{ route('admin.ukms.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- UKMs Grid -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Daftar UKM ({{ $ukms->total() }} total)
            </h3>
        </div>
        
        @if($ukms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($ukms as $ukm)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <!-- UKM Header -->
                        <div class="p-4 bg-gray-50 border-b">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 overflow-hidden">
                                    @if($ukm->logo)
                                        <img src="{{ asset('storage/' . $ukm->logo) }}"
                                             alt="{{ $ukm->name }}"
                                             class="w-full h-full object-cover rounded-lg"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="text-lg font-bold text-blue-600 hidden">{{ substr($ukm->name, 0, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-blue-600">{{ substr($ukm->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ $ukm->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ ucfirst($ukm->category) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- UKM Content -->
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ Str::limit($ukm->description, 100) }}
                            </p>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-gray-900">{{ $ukm->current_members }}</div>
                                    <div class="text-xs text-gray-500">Anggota</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-gray-900">{{ $ukm->max_members }}</div>
                                    <div class="text-xs text-gray-500">Kapasitas</div>
                                </div>
                            </div>

                            <!-- Ketua UKM Info -->
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="text-xs font-medium text-gray-500 mb-1">Ketua UKM</div>
                                @if($ukm->leader)
                                    <div class="text-sm font-medium text-gray-900">{{ $ukm->leader->name }}</div>
                                    @if($ukm->leader->nim)
                                        <div class="text-xs text-gray-500">{{ $ukm->leader->nim }}</div>
                                    @endif
                                @else
                                    <div class="text-xs text-gray-500 italic">Belum ada ketua</div>
                                @endif
                            </div>

                            <!-- Status Badges -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex space-x-2">
                                    @if($ukm->status === 'active')
                                        <span class="badge badge-success">Aktif</span>
                                    @elseif($ukm->status === 'inactive')
                                        <span class="badge badge-warning">Tidak Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Suspended</span>
                                    @endif
                                    
                                    @if($ukm->is_recruiting)
                                        <span class="badge badge-info">Rekrut</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.ukms.show', $ukm) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Lihat
                                    </a>
                                    <a href="{{ route('admin.ukms.edit', $ukm) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        Edit
                                    </a>
                                </div>
                                <form action="{{ route('admin.ukms.destroy', $ukm) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus UKM ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $ukms->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada UKM</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan UKM baru.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.ukms.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah UKM
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
@endif
@endsection
