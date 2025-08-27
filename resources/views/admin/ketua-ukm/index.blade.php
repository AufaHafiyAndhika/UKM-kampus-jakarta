@extends('admin.layouts.app')

@section('title', 'Kelola Ketua UKM')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Ketua UKM</h1>
            <p class="text-gray-600">Kelola mahasiswa dengan role Ketua UKM</p>
        </div>
        <a href="{{ route('admin.ketua-ukm.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Angkat Ketua UKM
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.ketua-ukm.index') }}" class="flex flex-wrap gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama, NIM, atau email..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-1"></i>Cari
                </button>
                <a href="{{ route('admin.ketua-ukm.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-refresh mr-1"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Ketua UKM</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $ketuaUkms->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Aktif</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $ketuaUkms->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-building text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Memimpin UKM</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $ketuaUkms->filter(function($user) { return $user->ledUkms->count() > 0; })->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-user-slash text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Belum Ditugaskan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $ketuaUkms->filter(function($user) { return $user->ledUkms->count() == 0; })->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketua UKM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UKM yang Dipimpin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ketuaUkms as $ketuaUkm)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($ketuaUkm->avatar)
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ asset('storage/' . $ketuaUkm->avatar) }}"
                                             alt="{{ $ketuaUkm->name }}">
                                    @else
                                        <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($ketuaUkm->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $ketuaUkm->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $ketuaUkm->nim }}</div>
                                    <div class="text-xs text-gray-400">{{ $ketuaUkm->major }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $ketuaUkm->email }}</div>
                            <div class="text-sm text-gray-500">{{ $ketuaUkm->phone ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($ketuaUkm->ledUkms->count() > 0)
                                @foreach($ketuaUkm->ledUkms as $ukm)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                        {{ $ukm->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-sm text-gray-400 italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <!-- Status Actions -->
                                @if($ketuaUkm->status === 'suspended')
                                    <form action="{{ route('admin.ketua-ukm.activate', $ketuaUkm) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Aktifkan kembali akun {{ $ketuaUkm->name }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium shadow-sm">
                                            Aktifkan
                                        </button>
                                    </form>
                                @elseif($ketuaUkm->status === 'inactive')
                                    <form action="{{ route('admin.ketua-ukm.activate', $ketuaUkm) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Aktifkan akun {{ $ketuaUkm->name }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium shadow-sm">
                                            Aktifkan
                                        </button>
                                    </form>
                                @elseif($ketuaUkm->status === 'active')
                                    <form action="{{ route('admin.ketua-ukm.suspend', $ketuaUkm) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Suspend akun {{ $ketuaUkm->name }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium">
                                            Suspend
                                        </button>
                                    </form>
                                @endif

                                <!-- Regular Actions -->
                                <a href="{{ route('admin.ketua-ukm.show', $ketuaUkm) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    Lihat
                                </a>
                                <a href="{{ route('admin.ketua-ukm.edit', $ketuaUkm) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </a>
                                @if($ketuaUkm->ledUkms()->count() === 0)
                                    <form action="{{ route('admin.ketua-ukm.destroy', $ketuaUkm) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menurunkan {{ $ketuaUkm->name }} dari ketua UKM? User akan dikembalikan ke role student.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-user-minus mr-1"></i>Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="Tidak dapat dihapus karena masih memimpin {{ $ketuaUkm->ledUkms()->count() }} UKM">
                                        <i class="fas fa-lock mr-1"></i>Hapus
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Belum ada ketua UKM</p>
                                <p class="text-sm">Angkat mahasiswa sebagai ketua UKM untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($ketuaUkms->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $ketuaUkms->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
