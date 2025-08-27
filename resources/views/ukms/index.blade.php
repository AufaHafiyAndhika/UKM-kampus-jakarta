@extends('layouts.app')

@section('seo_title', $seoData['title'] ?? 'Daftar UKM - Unit Kegiatan Mahasiswa Telkom Jakarta')
@section('title', 'Daftar UKM')
@section('description', $seoData['description'] ?? 'Temukan dan bergabung dengan Unit Kegiatan Mahasiswa aktif di Telkom Jakarta. Pilih UKM sesuai minat dan bakat Anda untuk mengembangkan potensi diri.')
@section('keywords', $seoData['keywords'] ?? 'daftar UKM, unit kegiatan mahasiswa, telkom jakarta, organisasi mahasiswa, ekstrakurikuler')
@section('canonical', $seoData['canonical'] ?? route('ukms.index'))
@section('og_title', $seoData['title'] ?? 'Daftar UKM - Unit Kegiatan Mahasiswa Telkom Jakarta')
@section('og_description', $seoData['description'] ?? 'Temukan dan bergabung dengan Unit Kegiatan Mahasiswa aktif di Telkom Jakarta.')
@section('og_type', 'website')

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Daftar Unit Kegiatan Mahasiswa",
    "description": "{{ $seoData['description'] ?? 'Daftar lengkap Unit Kegiatan Mahasiswa di Telkom Jakarta' }}",
    "url": "{{ route('ukms.index') }}",
    "mainEntity": {
        "@type": "ItemList",
        "numberOfItems": {{ $ukms->total() }},
        "itemListElement": [
            @foreach($ukms->take(10) as $index => $ukm)
            {
                "@type": "ListItem",
                "position": {{ $index + 1 }},
                "item": {
                    "@type": "Organization",
                    "name": "{{ $ukm->name }}",
                    "description": "{{ \App\Helpers\SeoHelper::cleanText($ukm->description, 100) }}",
                    "url": "{{ route('ukms.show', $ukm->slug) }}",
                    @if($ukm->logo)
                    "logo": "{{ asset('storage/' . $ukm->logo) }}",
                    @endif
                    "memberOf": {
                        "@type": "EducationalOrganization",
                        "name": "Telkom University Jakarta"
                    }
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
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
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Daftar UKM",
            "item": "{{ route('ukms.index') }}"
        }
    ]
}
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Unit Kegiatan Mahasiswa</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Temukan dan bergabunglah dengan UKM yang sesuai dengan minat dan bakat Anda.
            Kembangkan potensi diri melalui berbagai kegiatan yang menarik.
        </p>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('ukms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="form-label">Cari UKM</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama UKM, deskripsi..."
                       class="form-input">
            </div>
            <div>
                <label class="form-label">Kategori</label>
                <select name="category" class="form-input">
                    <option value="">Semua Kategori</option>
                    <option value="academic" {{ request('category') == 'academic' ? 'selected' : '' }}>Akademik</option>
                    <option value="sports" {{ request('category') == 'sports' ? 'selected' : '' }}>Olahraga</option>
                    <option value="arts" {{ request('category') == 'arts' ? 'selected' : '' }}>Seni</option>
                    <option value="religion" {{ request('category') == 'religion' ? 'selected' : '' }}>Keagamaan</option>
                    <option value="social" {{ request('category') == 'social' ? 'selected' : '' }}>Sosial</option>
                    <option value="technology" {{ request('category') == 'technology' ? 'selected' : '' }}>Teknologi</option>
                    <option value="entrepreneurship" {{ request('category') == 'entrepreneurship' ? 'selected' : '' }}>Kewirausahaan</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-primary flex-1">Cari</button>
                <a href="{{ route('ukms.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- UKM Grid -->
    @if($ukms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($ukms as $ukm)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- UKM Banner -->
                    <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                        @if($ukm->background_image)
                            <img src="{{ asset('storage/' . $ukm->background_image) }}" alt="{{ $ukm->name }} Background" class="w-full h-full object-cover">
                        @elseif($ukm->banner)
                            <img src="{{ asset('storage/' . $ukm->banner) }}" alt="{{ $ukm->name }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                            <div class="p-4 text-white">
                                <div class="flex items-center mb-2">
                                    @if($ukm->logo)
                                        <img src="{{ asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}" class="w-10 h-10 rounded-lg bg-white p-1 mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-gray-800">{{ substr($ukm->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-bold text-lg">{{ $ukm->name }}</h3>
                                        <p class="text-blue-100 text-sm">{{ ucfirst($ukm->category) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UKM Content -->
                    <div class="p-6">
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ Str::limit($ukm->description, 120) }}
                        </p>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-blue-600">{{ $ukm->current_members }}</div>
                                <div class="text-xs text-gray-500">Anggota</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-green-600">{{ $ukm->max_members }}</div>
                                <div class="text-xs text-gray-500">Kapasitas</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-yellow-600">{{ $ukm->achievements ? $ukm->achievements->count() : 0 }}</div>
                                <div class="text-xs text-gray-500">Prestasi</div>
                            </div>
                        </div>

                        <!-- Status and Actions -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex space-x-2">
                                @if($ukm->status === 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif

                                @if($ukm->status === 'active' && $ukm->registration_status === 'open')
                                    <span class="badge badge-info">Buka Pendaftaran</span>
                                @else
                                    <span class="badge badge-warning">Tutup Pendaftaran</span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <a href="{{ route('ukms.show', $ukm->slug) }}"
                               class="flex-1 btn-primary text-center">
                                Lihat Detail
                            </a>

                            @auth
                                @if(auth()->user()->role === 'student')
                                    @php
                                        $membership = auth()->user()->ukms()->where('ukm_id', $ukm->id)->first();
                                        $isMember = $membership && $membership->pivot->status === 'active';
                                        $membershipStatus = $membership ? $membership->pivot->status : null;
                                    @endphp

                                    @if($isMember)
                                        <span class="flex-1 text-center py-2 px-4 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-green-50">
                                            Sudah Bergabung
                                        </span>
                                    @elseif($membershipStatus === 'pending')
                                        <span class="flex-1 text-center py-2 px-4 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 bg-yellow-50">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($ukm->status === 'active' && $ukm->registration_status === 'open' && $ukm->current_members < $ukm->max_members)
                                        <a href="{{ route('ukms.registration-form', $ukm->slug) }}" class="flex-1 btn-telkom text-center">
                                            {{ ($membershipStatus === 'rejected' || $membershipStatus === 'inactive') ? 'Daftar Ulang' : 'Daftar' }}
                                        </a>
                                    @elseif($ukm->current_members >= $ukm->max_members)
                                        <span class="flex-1 text-center py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-500 bg-gray-50">
                                            Penuh
                                        </span>
                                    @elseif($ukm->status !== 'active')
                                        <span class="flex-1 text-center py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-500 bg-gray-50">
                                            UKM Tidak Aktif
                                        </span>
                                    @else
                                        <span class="flex-1 text-center py-2 px-4 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50">
                                            Pendaftaran Ditutup
                                        </span>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $ukms->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada UKM ditemukan</h3>
            <p class="text-gray-500 mb-6">Coba ubah filter pencarian atau kata kunci Anda.</p>
            <a href="{{ route('ukms.index') }}" class="btn-primary">
                Lihat Semua UKM
            </a>
        </div>
    @endif

    <!-- Categories Section -->
    <div class="bg-gray-50 rounded-lg p-8 mt-16">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Kategori UKM</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('ukms.index', ['category' => 'academic']) }}"
               class="text-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Akademik</h3>
                <p class="text-sm text-gray-500">Himpunan & Organisasi</p>
            </a>

            <a href="{{ route('ukms.index', ['category' => 'sports']) }}"
               class="text-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Olahraga</h3>
                <p class="text-sm text-gray-500">Berbagai Cabang Olahraga</p>
            </a>

            <a href="{{ route('ukms.index', ['category' => 'arts']) }}"
               class="text-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Seni</h3>
                <p class="text-sm text-gray-500">Musik, Tari, Teater</p>
            </a>

            <a href="{{ route('ukms.index', ['category' => 'technology']) }}"
               class="text-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Teknologi</h3>
                <p class="text-sm text-gray-500">IT & Programming</p>
            </a>
        </div>
    </div>
</div>
@endsection
