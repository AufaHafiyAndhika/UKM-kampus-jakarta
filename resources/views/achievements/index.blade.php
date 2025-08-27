@extends('layouts.app')

@section('title', 'Prestasi UKM')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Prestasi UKM</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Kumpulan prestasi membanggakan yang telah diraih oleh Unit Kegiatan Mahasiswa Telkom University
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('achievements.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Prestasi</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nama prestasi, deskripsi, atau penyelenggara..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- UKM Filter -->
                    <div>
                        <label for="ukm" class="block text-sm font-medium text-gray-700 mb-1">UKM</label>
                        <select id="ukm" name="ukm" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua UKM</option>
                            @foreach($ukms as $ukm)
                                <option value="{{ $ukm->slug }}" {{ request('ukm') == $ukm->slug ? 'selected' : '' }}>
                                    {{ $ukm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Jenis</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Level Filter -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                        <select id="level" name="level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Tingkat</option>
                            @foreach($levels as $key => $label)
                                <option value="{{ $key }}" {{ request('level') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="lg:col-span-4 flex items-end space-x-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('achievements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <div class="flex items-center justify-between mb-6">
            <div class="text-gray-600">
                Menampilkan {{ $achievements->firstItem() ?? 0 }} - {{ $achievements->lastItem() ?? 0 }} 
                dari {{ $achievements->total() }} prestasi
            </div>
            <div class="text-sm text-gray-500">
                {{ $achievements->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Achievements Grid -->
        @if($achievements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($achievements as $achievement)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Achievement Header -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $achievement->title }}
                                    </h3>
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $achievement->type_text }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $achievement->level_text }}
                                        </span>
                                    </div>
                                </div>
                                @if($achievement->position)
                                    <div class="flex-shrink-0 ml-4">
                                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                            {{ $achievement->position_text }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- UKM Info -->
                            <div class="flex items-center space-x-2 mb-3">
                                <img src="{{ $achievement->ukm->logo ? asset('storage/' . $achievement->ukm->logo) : asset('images/default-ukm.png') }}" 
                                     alt="{{ $achievement->ukm->name }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $achievement->ukm->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $achievement->year }}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($achievement->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $achievement->description }}</p>
                            @endif

                            <!-- Organizer -->
                            @if($achievement->organizer)
                                <div class="text-xs text-gray-500 mb-4">
                                    <i class="fas fa-building mr-1"></i>{{ $achievement->organizer }}
                                </div>
                            @endif

                            <!-- Date -->
                            <div class="text-xs text-gray-500 mb-4">
                                <i class="fas fa-calendar mr-1"></i>{{ $achievement->achievement_date->format('d M Y') }}
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between">
                                <a href="{{ route('achievements.show', $achievement) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                                @if($achievement->certificate_file)
                                    <a href="{{ asset('storage/' . $achievement->certificate_file) }}" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-800 text-sm">
                                        <i class="fas fa-certificate mr-1"></i>Sertifikat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $achievements->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-trophy text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada prestasi ditemukan</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request()->hasAny(['search', 'ukm', 'year', 'type', 'level']))
                            Coba ubah filter pencarian atau hapus beberapa filter untuk melihat lebih banyak hasil.
                        @else
                            Belum ada prestasi yang terdaftar dalam sistem.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'ukm', 'year', 'type', 'level']))
                        <a href="{{ route('achievements.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                            Lihat Semua Prestasi
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
