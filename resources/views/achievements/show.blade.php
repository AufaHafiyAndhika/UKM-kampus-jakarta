@extends('layouts.app')

@section('title', $achievement->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('achievements.index') }}" class="text-gray-700 hover:text-blue-600">Prestasi</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('achievements.by-ukm', $achievement->ukm) }}" class="text-gray-700 hover:text-blue-600">{{ $achievement->ukm->name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">{{ Str::limit($achievement->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Achievement Header -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $achievement->title }}</h1>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $achievement->type_text }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $achievement->level_text }}
                                </span>
                                @if($achievement->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($achievement->position)
                            <div class="flex-shrink-0 ml-6">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    #{{ $achievement->position }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- UKM Info -->
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <img src="{{ $achievement->ukm->logo ? asset('storage/' . $achievement->ukm->logo) : asset('images/default-ukm.png') }}" 
                             alt="{{ $achievement->ukm->name }}" 
                             class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $achievement->ukm->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $achievement->ukm->category_text ?? 'UKM' }}</p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('ukms.show', $achievement->ukm) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Profil UKM <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Achievement Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Prestasi</h2>
                    
                    @if($achievement->description)
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $achievement->description }}</p>
                        </div>
                    @endif

                    @if($achievement->participants)
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-2">Peserta yang Terlibat</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $achievement->participants }}</p>
                        </div>
                    @endif

                    <!-- Achievement Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Informasi Prestasi</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-5 text-gray-400 mr-3"></i>
                                    <div>
                                        <span class="text-sm text-gray-500">Tanggal</span>
                                        <p class="font-medium">{{ $achievement->achievement_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-5 text-gray-400 mr-3"></i>
                                    <div>
                                        <span class="text-sm text-gray-500">Tahun</span>
                                        <p class="font-medium">{{ $achievement->year }}</p>
                                    </div>
                                </div>
                                @if($achievement->organizer)
                                    <div class="flex items-center">
                                        <i class="fas fa-building w-5 text-gray-400 mr-3"></i>
                                        <div>
                                            <span class="text-sm text-gray-500">Penyelenggara</span>
                                            <p class="font-medium">{{ $achievement->organizer }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($achievement->certificate_file)
                            <div>
                                <h3 class="font-medium text-gray-900 mb-3">Sertifikat</h3>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-certificate text-2xl text-green-600"></i>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">Sertifikat Prestasi</p>
                                            <p class="text-sm text-gray-500">File tersedia untuk diunduh</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $achievement->certificate_file) }}" 
                                           target="_blank"
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                            <i class="fas fa-download mr-2"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Achievements -->
                @if($relatedAchievements->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Prestasi Lainnya dari {{ $achievement->ukm->name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($relatedAchievements as $related)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <h3 class="font-medium text-gray-900 line-clamp-2">{{ $related->title }}</h3>
                                        @if($related->position)
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium ml-2">
                                                #{{ $related->position }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $related->type_text }}</span>
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">{{ $related->level_text }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-3">{{ $related->achievement_date->format('d M Y') }}</p>
                                    <a href="{{ route('achievements.show', $related) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('achievements.by-ukm', $achievement->ukm) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors text-center block">
                            <i class="fas fa-trophy mr-2"></i>Prestasi {{ $achievement->ukm->name }}
                        </a>
                        <a href="{{ route('ukms.show', $achievement->ukm) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors text-center block">
                            <i class="fas fa-users mr-2"></i>Profil UKM
                        </a>
                        <a href="{{ route('achievements.index') }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors text-center block">
                            <i class="fas fa-list mr-2"></i>Semua Prestasi
                        </a>
                    </div>
                </div>

                <!-- Achievement Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Jenis</span>
                            <span class="text-sm font-medium text-gray-900">{{ $achievement->type_text }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tingkat</span>
                            <span class="text-sm font-medium text-gray-900">{{ $achievement->level_text }}</span>
                        </div>
                        @if($achievement->position)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Posisi</span>
                                <span class="text-sm font-medium text-gray-900">{{ $achievement->position_text }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tahun</span>
                            <span class="text-sm font-medium text-gray-900">{{ $achievement->year }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Featured</span>
                            <span class="text-sm font-medium {{ $achievement->is_featured ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $achievement->is_featured ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Share -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bagikan</h3>
                    <div class="flex space-x-2">
                        <button onclick="copyToClipboard()" 
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-copy mr-2"></i>Salin Link
                        </button>
                        <a href="https://wa.me/?text={{ urlencode($achievement->title . ' - ' . request()->url()) }}" 
                           target="_blank"
                           class="flex-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors text-center">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link berhasil disalin!');
    });
}
</script>
@endsection
