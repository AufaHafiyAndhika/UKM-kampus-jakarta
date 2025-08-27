@extends('layouts.app')

@section('seo_title', 'UKM Telkom Jakarta - Platform Resmi Unit Kegiatan Mahasiswa')
@section('title', '- Beranda')
@section('description', 'Website resmi Unit Kegiatan Mahasiswa Telkom Jakarta. Temukan UKM yang sesuai dengan minat dan bakat Anda, ikuti berbagai kegiatan menarik untuk mengembangkan potensi diri. Bergabung dengan 25+ UKM aktif dan 1000+ mahasiswa.')
@section('keywords', 'UKM Telkom Jakarta, mahasiswa, kegiatan, organisasi, ekstrakurikuler, pengembangan diri, unit kegiatan mahasiswa, telkom university jakarta, komunitas mahasiswa, prestasi mahasiswa')
@section('og_title', 'UKM Telkom Jakarta - Platform Resmi Unit Kegiatan Mahasiswa')
@section('og_description', 'Bergabunglah dengan 25+ UKM aktif di Telkom Jakarta. Kembangkan potensi diri, perluas jaringan, dan raih prestasi bersama komunitas mahasiswa yang kreatif.')
@section('og_image', asset('storage/Banner-Telkom-University-Jakarta.jpg'))
@section('og_type', 'website')
@section('canonical', route('home'))

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "UKM Telkom Jakarta",
    "alternateName": "Unit Kegiatan Mahasiswa Telkom Jakarta",
    "url": "{{ url('/') }}",
    "description": "Platform digital untuk mengelola dan mengembangkan Unit Kegiatan Mahasiswa di Telkom Jakarta",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
    },
    "publisher": {
        "@type": "EducationalOrganization",
        "name": "Telkom University Jakarta",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('storage/Telkom.png') }}"
        }
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Beranda",
            "item": "{{ route('home') }}"
        }
    ]
}
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-telkom-600 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in relative z-20">
                <h1 class="text-4xl lg:text-6xl font-display font-bold mb-6 leading-tight">
                    Bergabung dengan
                    <span class="text-yellow-300">UKM Telkom Jakarta</span>
                </h1>
                <p class="text-xl lg:text-2xl mb-8 text-gray-100 leading-relaxed">
                    Kembangkan potensi diri, perluas jaringan, dan raih prestasi bersama komunitas mahasiswa yang aktif dan kreatif.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('ukms.index') }}" class="btn-primary bg-white text-primary-600 hover:bg-gray-100 text-lg px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        Jelajahi UKM
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-secondary bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary-600 text-lg px-8 py-4 rounded-xl font-semibold transition-all duration-300">
                        Lihat Kegiatan
                    </a>
                </div>
            </div>
            <div class="animate-slide-up relative z-10">
                <img src="{{ asset('storage/Gedunghp.png') }}" alt="Students Activities" class="w-full h-auto max-w-none scale-110 lg:scale-125">
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-300 rounded-full opacity-10 -translate-y-32 translate-x-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full opacity-10 translate-y-24 -translate-x-24"></div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center animate-bounce-in">
                <div class="text-4xl lg:text-5xl font-bold text-primary-600 mb-2">{{ $stats['total_ukms'] ?? '25+' }}</div>
                <div class="text-gray-600 font-medium">Unit Kegiatan Mahasiswa</div>
            </div>
            <div class="text-center animate-bounce-in" style="animation-delay: 0.1s;">
                <div class="text-4xl lg:text-5xl font-bold text-telkom-600 mb-2">{{ $stats['total_members'] ?? '1000+' }}</div>
                <div class="text-gray-600 font-medium">Anggota Aktif</div>
            </div>
            <div class="text-center animate-bounce-in" style="animation-delay: 0.2s;">
                <div class="text-4xl lg:text-5xl font-bold text-success-600 mb-2">{{ $stats['total_events'] ?? '150+' }}</div>
                <div class="text-gray-600 font-medium">Kegiatan per Tahun</div>
            </div>
            <div class="text-center animate-bounce-in" style="animation-delay: 0.3s;">
                <div class="text-4xl lg:text-5xl font-bold text-yellow-600 mb-2">{{ $stats['total_achievements'] ?? '50+' }}</div>
                <div class="text-gray-600 font-medium">Prestasi Diraih</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured UKMs Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-display font-bold text-gray-900 mb-4">
                UKM Unggulan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Temukan UKM yang sesuai dengan minat dan bakat Anda. Bergabunglah dengan komunitas yang tepat untuk mengembangkan potensi diri.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featured_ukms ?? [] as $ukm)
                <div class="card hover:shadow-lg transition-shadow duration-300 animate-slide-up">
                    <div class="relative">
                        @if($ukm->background_image)
                            <img src="{{ Storage::url($ukm->background_image) }}"
                                 alt="{{ $ukm->name }} Background"
                                 class="w-full h-48 object-cover">
                            <!-- Logo overlay -->
                            @if($ukm->logo)
                                <div class="absolute bottom-4 left-4">
                                    <img src="{{ Storage::url($ukm->logo) }}"
                                         alt="{{ $ukm->name }} Logo"
                                         class="w-12 h-12 rounded-lg bg-white p-1 shadow-md">
                                </div>
                            @endif
                        @elseif($ukm->logo)
                            <img src="{{ Storage::url($ukm->logo) }}"
                                 alt="{{ $ukm->name }} Logo"
                                 class="w-full h-48 object-cover">
                        @else
                            <img src="{{ $ukm->banner ? Storage::url($ukm->banner) : asset('images/ukm-placeholder.jpg') }}"
                                 alt="{{ $ukm->name }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="badge badge-info">{{ ucfirst($ukm->category) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-xl font-semibold text-gray-900 flex-1">{{ $ukm->name }}</h3>
                        </div>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($ukm->description, 120) }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                {{ $ukm->current_members }} anggota
                            </div>
                            <a href="{{ route('ukms.show', $ukm->slug) }}" class="btn-primary text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="h-16 w-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada UKM unggulan</h3>
                    <p class="text-gray-600">UKM unggulan akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('ukms.index') }}" class="btn-primary text-lg px-8 py-4">
                Lihat Semua UKM
            </a>
        </div>
    </div>
</section>

<!-- Prestasi Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-display font-bold text-gray-900 mb-4">
                Prestasi yang Diraih
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Berbagai pencapaian membanggakan yang telah diraih oleh UKM-UKM Telkom Jakarta
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featured_achievements ?? [] as $achievement)
                <div class="card hover:shadow-lg transition-shadow duration-300 animate-slide-up bg-white">
                    <div class="card-body">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="badge badge-{{ $achievement->level == 'international' ? 'success' : ($achievement->level == 'national' ? 'info' : ($achievement->level == 'regional' ? 'warning' : 'secondary')) }}">
                                        {{ $achievement->level_text }}
                                    </span>
                                    <span class="badge badge-secondary">
                                        {{ $achievement->type_text }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    {{ $achievement->title }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-university mr-1"></i>
                                    <a href="{{ route('ukms.show', $achievement->ukm->slug) }}" class="text-primary-600 hover:text-primary-800 font-medium">
                                        {{ $achievement->ukm->name }}
                                    </a>
                                </p>
                            </div>
                            @if($achievement->position)
                                <div class="flex-shrink-0 ml-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-sm">
                                        #{{ $achievement->position }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($achievement->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $achievement->description }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $achievement->achievement_date->format('d M Y') }}
                            </div>
                            @if($achievement->organizer)
                                <div class="flex items-center">
                                    <i class="fas fa-building mr-1"></i>
                                    <span class="truncate max-w-32">{{ $achievement->organizer }}</span>
                                </div>
                            @endif
                        </div>

                        @if($achievement->participants)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-users mr-1"></i>
                                    <strong>Peserta:</strong> {{ Str::limit($achievement->participants, 60) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="h-16 w-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada prestasi unggulan</h3>
                    <p class="text-gray-600">Prestasi UKM akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>

        @if($featured_achievements && $featured_achievements->count() > 0)
            <div class="text-center mt-12">
                <a href="{{ route('achievements.index') }}" class="btn-secondary text-lg px-8 py-4">
                    Lihat Semua Prestasi
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-display font-bold text-gray-900 mb-4">
                Kegiatan Mendatang
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Jangan lewatkan kegiatan menarik yang akan datang. Daftarkan diri Anda sekarang!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($upcoming_events ?? [] as $event)
                <div class="card hover:shadow-xl transition-all duration-300 animate-slide-up group">
                    <div class="relative overflow-hidden">
                        <!-- Event Poster -->
                        <div class="relative h-48 bg-gradient-to-br from-primary-100 to-primary-200">
                            @if($event->poster)
                                <img src="{{ Storage::url($event->poster) }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="h-16 w-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Event Type Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="badge badge-info shadow-lg">{{ ucfirst($event->type ?? 'Event') }}</span>
                            </div>

                            <!-- UKM Logo -->
                            @if($event->ukm && $event->ukm->logo)
                                <div class="absolute top-4 right-4">
                                    <img src="{{ Storage::url($event->ukm->logo) }}"
                                         alt="{{ $event->ukm->name }} Logo"
                                         class="w-10 h-10 object-cover rounded-full border-2 border-white shadow-lg">
                                </div>
                            @endif
                        </div>

                        <!-- Event Content -->
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-primary-600 transition-colors">
                                        {{ $event->title }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500">
                                        @if($event->ukm && $event->ukm->logo)
                                            <img src="{{ Storage::url($event->ukm->logo) }}"
                                                 alt="{{ $event->ukm->name }}"
                                                 class="w-4 h-4 object-cover rounded-full mr-2">
                                        @endif
                                        <span>{{ $event->ukm->name ?? 'UKM' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $event->start_datetime->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <span>{{ $event->current_participants ?? 0 }}/{{ $event->max_participants ?? '∞' }} peserta</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('events.show', $event->slug) }}"
                                   class="btn-primary px-6 py-2 text-sm font-semibold hover:shadow-lg transition-all duration-300">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="h-16 w-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kegiatan mendatang</h3>
                    <p class="text-gray-600">Kegiatan mendatang akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('events.index') }}" class="btn-primary text-lg px-8 py-4">
                Lihat Semua Kegiatan
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary-600 to-telkom-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-display font-bold mb-4">
            Siap Bergabung dengan Komunitas UKM?
        </h2>
        <p class="text-xl mb-8 max-w-3xl mx-auto">
            Daftarkan diri Anda sekarang dan mulai perjalanan pengembangan diri yang luar biasa bersama UKM Telkom Jakarta.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" class="btn-primary bg-white text-primary-600 hover:bg-gray-100 text-lg px-8 py-4 rounded-xl font-semibold">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-secondary bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary-600 text-lg px-8 py-4 rounded-xl font-semibold">
                    Masuk
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-primary bg-white text-primary-600 hover:bg-gray-100 text-lg px-8 py-4 rounded-xl font-semibold">
                    Dashboard Saya
                </a>
                <a href="{{ route('ukms.index') }}" class="btn-secondary bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary-600 text-lg px-8 py-4 rounded-xl font-semibold">
                    Bergabung UKM
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection
