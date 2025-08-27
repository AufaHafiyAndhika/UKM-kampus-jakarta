@extends('layouts.app')

@section('title', 'Tentang Kami')

@push('head')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<style>
    #map {
        border-radius: 0.5rem;
        height: 400px;
        width: 100%;
    }
    .custom-popup {
        font-family: 'Inter', sans-serif;
    }
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .campus-marker {
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-red-500 to-red-600 text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Tentang Kami</h1>
            <p class="text-xl md:text-2xl text-red-100 max-w-3xl mx-auto">
                Mengenal lebih dekat Telkom University Jakarta dan ekosistem Unit Kegiatan Mahasiswa yang dinamis
            </p>
        </div>
    </div>
</div>

<!-- Campus Overview -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Telkom University Jakarta</h2>
                <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                    Telkom University Jakarta merupakan kampus yang berlokasi strategis di ibu kota Indonesia,
                    menjadi pusat pendidikan teknologi dan inovasi yang menggabungkan keunggulan akademik
                    dengan pengembangan karakter mahasiswa melalui berbagai kegiatan ekstrakurikuler.
                </p>
                <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                    Sebagai bagian dari Telkom University yang telah dikenal sebagai universitas teknologi
                    terdepan di Indonesia, kampus Jakarta berkomitmen untuk menghasilkan lulusan yang tidak
                    hanya unggul secara akademik, tetapi juga memiliki soft skills dan leadership yang kuat.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">2018</div>
                        <div class="text-sm text-gray-600">Tahun Berdiri</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">5000+</div>
                        <div class="text-sm text-gray-600">Mahasiswa Aktif</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-w-16 aspect-h-12 rounded-lg overflow-hidden shadow-xl">
                    <img src="{{ asset('images/student telu.jpg') }}"
                         alt="Telkom University Jakarta Campus"
                         class="w-full h-96 object-cover bg-gray-200">
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white rounded-lg flex items-center justify-center shadow-lg p-2">
                    <img src="{{ asset('storage/Telkom.png') }}" alt="Telkom University" class="w-full h-full object-contain">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Visi & Misi</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Komitmen kami dalam membangun masa depan teknologi Indonesia
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Vision -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Visi</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    Menjadi universitas riset kelas dunia yang menghasilkan insan cerdas, kreatif, dan berkarakter
                    dalam bidang teknologi informasi dan komunikasi untuk kemajuan peradaban bangsa.
                </p>
            </div>

            <!-- Mission -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Misi</h3>
                </div>
                <ul class="text-gray-700 space-y-2">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Menyelenggarakan pendidikan tinggi berkualitas dunia
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Mengembangkan riset dan inovasi teknologi
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Membentuk karakter dan kepemimpinan mahasiswa
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Berkontribusi pada kemajuan masyarakat dan bangsa
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Campus Facilities -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Fasilitas Kampus</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Fasilitas modern dan lengkap untuk mendukung kegiatan akademik dan non-akademik
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Facility 1 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laboratorium Komputer</h3>
                <p class="text-gray-600">Laboratorium dengan perangkat terkini untuk praktikum dan penelitian teknologi informasi</p>
            </div>

            <!-- Facility 2 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Perpustakaan Digital</h3>
                <p class="text-gray-600">Perpustakaan modern dengan koleksi digital dan ruang belajar yang nyaman</p>
            </div>

            <!-- Facility 3 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Auditorium</h3>
                <p class="text-gray-600">Auditorium berkapasitas besar untuk seminar, konferensi, dan acara kampus</p>
            </div>

            <!-- Facility 4 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Studio Multimedia</h3>
                <p class="text-gray-600">Studio lengkap untuk produksi konten digital, fotografi, dan videografi</p>
            </div>

            <!-- Facility 5 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Innovation Lab</h3>
                <p class="text-gray-600">Laboratorium inovasi untuk pengembangan startup dan proyek teknologi</p>
            </div>

            <!-- Facility 6 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Student Center</h3>
                <p class="text-gray-600">Pusat kegiatan mahasiswa dengan ruang UKM dan fasilitas rekreasi</p>
            </div>
        </div>
    </div>
</section>

<!-- UKM Ecosystem -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ekosistem UKM</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Unit Kegiatan Mahasiswa sebagai wadah pengembangan bakat, minat, dan kepemimpinan
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- UKM Category 1 -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Teknologi</h3>
                <p class="text-gray-600 text-sm mb-4">UKM yang fokus pada pengembangan teknologi dan inovasi digital</p>
                <div class="text-2xl font-bold text-blue-600">8+</div>
                <div class="text-sm text-gray-500">UKM Aktif</div>
            </div>

            <!-- UKM Category 2 -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Seni & Budaya</h3>
                <p class="text-gray-600 text-sm mb-4">UKM seni, musik, tari, dan pelestarian budaya Indonesia</p>
                <div class="text-2xl font-bold text-green-600">12+</div>
                <div class="text-sm text-gray-500">UKM Aktif</div>
            </div>

            <!-- UKM Category 3 -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Olahraga</h3>
                <p class="text-gray-600 text-sm mb-4">UKM olahraga untuk menjaga kesehatan dan prestasi atlet</p>
                <div class="text-2xl font-bold text-red-600">15+</div>
                <div class="text-sm text-gray-500">UKM Aktif</div>
            </div>

            <!-- UKM Category 4 -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Sosial</h3>
                <p class="text-gray-600 text-sm mb-4">UKM yang fokus pada kegiatan sosial dan pengabdian masyarakat</p>
                <div class="text-2xl font-bold text-purple-600">6+</div>
                <div class="text-sm text-gray-500">UKM Aktif</div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('ukms.index') }}" class="btn-primary">
                Jelajahi Semua UKM
            </a>
        </div>
    </div>
</section>

<!-- Campus Location -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Lokasi Kampus</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Telkom University Jakarta memiliki 3 kampus yang tersebar strategis di Jakarta
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="space-y-8">
                    <!-- Kampus A (Daan Mogot) -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kampus A (Daan Mogot)</h3>
                            <p class="text-gray-700">
                                Jl. Daan Mogot Km. 11 Cengkareng<br>
                                Jakarta Barat 11710
                            </p>
                        </div>
                    </div>

                    <!-- Kampus B (Halimun) -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kampus B (Halimun)</h3>
                            <p class="text-gray-700">
                                Jl. Halimun Raya No. 2, Guntur<br>
                                Kecamatan Setia Budi, Kota Jakarta Selatan<br>
                                DKI Jakarta
                            </p>
                        </div>
                    </div>

                    <!-- Kampus C (Minangkabau) -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kampus C (Minangkabau)</h3>
                            <p class="text-gray-700">
                                Jl. Minangkabau Barat No.50, RT.1/RW.1<br>
                                Ps. Manggis, Kecamatan Setiabudi<br>
                                Kota Jakarta Selatan, DKI Jakarta 12970
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <!-- Google Maps -->
                <div class="aspect-w-16 aspect-h-12 rounded-lg overflow-hidden shadow-xl">
                    <div id="map" class="w-full h-96"></div>
                </div>

                <!-- Map Legend -->
                <div class="absolute top-4 right-4 bg-white rounded-lg shadow-lg p-4 max-w-xs">
                    <h4 class="font-semibold text-gray-900 mb-3">Lokasi Kampus</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            <span>Kampus A (Daan Mogot)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span>Kampus B (Halimun)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                            <span>Kampus C (Minangkabau)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-16 bg-gradient-to-r from-red-500 to-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Telkom University Jakarta dalam Angka</h2>
            <p class="text-xl text-red-100 max-w-3xl mx-auto">
                Pencapaian dan kontribusi kami dalam dunia pendidikan dan teknologi
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">5000+</div>
                <div class="text-red-100">Mahasiswa Aktif</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">50+</div>
                <div class="text-red-100">Unit Kegiatan Mahasiswa</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">200+</div>
                <div class="text-red-100">Kegiatan per Tahun</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">95%</div>
                <div class="text-red-100">Tingkat Kepuasan</div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Punya pertanyaan tentang UKM atau ingin bergabung? Jangan ragu untuk menghubungi kami
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Phone -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Telepon</h3>
                <p class="text-gray-600">(022) 7566456</p>
                <p class="text-gray-600">(022) 7564108</p>
            </div>

            <!-- Email -->
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                <p class="text-gray-600">ukm@telkomuniversity.ac.id</p>
                <p class="text-gray-600">info@telkomuniversity.ac.id</p>
            </div>

            <!-- Social Media -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v3M7 4H5a1 1 0 00-1 1v16a1 1 0 001 1h14a1 1 0 001-1V5a1 1 0 00-1-1h-2M7 4h10M9 9h6m-6 4h6m-6 4h6"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Media Sosial</h3>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-pink-600 hover:text-pink-800">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('contact') }}" class="btn-primary">
                Kirim Pesan
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Koordinat pusat Jakarta (rata-rata dari ketiga kampus yang diperbarui)
    const jakartaCenter = [-6.191516133338945, 106.80925203544124];

    // Inisialisasi peta Leaflet
    const map = L.map('map').setView(jakartaCenter, 12);

    // Tambahkan tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Data lokasi kampus dengan koordinat yang akurat (diperbarui)
    const campusLocations = [
        {
            name: "Kampus A (Daan Mogot)",
            position: [-6.155238605127154, 106.75067397689125],
            address: "Jl. Daan Mogot Km. 11, Cengkareng Barat, Jakarta Barat 11840",
            color: "#3B82F6", // Blue
            icon: "🏛️",
            phone: "(021) 5020-9200",
            facilities: ["Laboratorium Komputer", "Perpustakaan", "Auditorium"]
        },
        {
            name: "Kampus B (Halimun)",
            position: [-6.205891032507967, 106.8336975024358],
            address: "Jl. Halimun Raya No. 2, Guntur, Setia Budi, Jakarta Selatan 12980",
            color: "#10B981", // Green
            icon: "🏫",
            phone: "(021) 5296-0999",
            facilities: ["Studio Multimedia", "Lab Jaringan", "Ruang Seminar"]
        },
        {
            name: "Kampus C (Minangkabau)",
            position: [-6.213418762381815, 106.84338246703668],
            address: "Jl. Minangkabau Barat No.50, Ps. Manggis, Setiabudi, Jakarta Selatan 12970",
            color: "#8B5CF6", // Purple
            icon: "🏢",
            phone: "(021) 5296-1000",
            facilities: ["Ruang Kuliah Modern", "Lab Penelitian", "Coworking Space"]
        }
    ];

    // Array untuk menyimpan markers
    const markers = [];

    // Membuat marker untuk setiap kampus
    campusLocations.forEach((campus, index) => {
        // Membuat custom icon
        const customIcon = L.divIcon({
            className: 'campus-marker',
            html: `<div style="
                background-color: ${campus.color};
                width: 24px;
                height: 24px;
                border-radius: 50%;
                border: 3px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                color: white;
                font-weight: bold;
            ">${campus.icon}</div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        // Membuat marker
        const marker = L.marker(campus.position, { icon: customIcon }).addTo(map);

        // Popup content untuk setiap marker
        const facilitiesList = campus.facilities.map(facility => `<li style="font-size: 12px; color: #6b7280; margin: 2px 0;">• ${facility}</li>`).join('');

        const popupContent = `
            <div style="padding: 16px; max-width: 280px; font-family: 'Inter', sans-serif;">
                <h3 style="font-weight: bold; font-size: 18px; color: #111827; margin-bottom: 8px;">${campus.icon} ${campus.name}</h3>
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; align-items: flex-start; font-size: 14px; color: #374151; margin-bottom: 8px;">
                        <span style="margin-right: 8px;">📍</span>
                        <span>${campus.address}</span>
                    </div>
                    <div style="display: flex; align-items: center; font-size: 14px; color: #374151;">
                        <span style="margin-right: 8px;">📞</span>
                        <span>${campus.phone}</span>
                    </div>
                </div>
                <div style="border-top: 1px solid #e5e7eb; padding-top: 8px;">
                    <p style="font-size: 12px; font-weight: 600; color: #1f2937; margin-bottom: 4px;">Fasilitas:</p>
                    <ul style="margin: 0; padding: 0; list-style: none;">
                        ${facilitiesList}
                    </ul>
                </div>
                <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                    <a href="https://maps.google.com/?q=${campus.position[0]},${campus.position[1]}"
                       target="_blank"
                       style="display: inline-flex; align-items: center; font-size: 12px; color: #2563eb; text-decoration: none; font-weight: 500;">
                        <span style="margin-right: 4px;">🔗</span>
                        Buka di Google Maps
                    </a>
                </div>
            </div>
        `;

        // Bind popup ke marker
        marker.bindPopup(popupContent, {
            maxWidth: 300,
            className: 'custom-popup'
        });

        // Event listener untuk marker
        marker.on('click', () => {
            // Zoom ke lokasi saat diklik
            map.setView(campus.position, 15);
        });

        // Simpan marker untuk kontrol nanti
        markers.push(marker);
    });

    // Membuat custom control untuk Leaflet
    const CustomControl = L.Control.extend({
        onAdd: function(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            container.style.backgroundColor = 'white';
            container.style.padding = '8px';
            container.style.borderRadius = '8px';
            container.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';

            // Reset button
            const resetButton = L.DomUtil.create('button', '', container);
            resetButton.innerHTML = '🔄 Reset';
            resetButton.style.cssText = `
                display: block;
                width: 100%;
                padding: 6px 12px;
                margin-bottom: 4px;
                border: none;
                border-radius: 4px;
                background: #f3f4f6;
                color: #374151;
                font-size: 12px;
                cursor: pointer;
                transition: background-color 0.2s;
            `;

            resetButton.onmouseover = () => resetButton.style.backgroundColor = '#e5e7eb';
            resetButton.onmouseout = () => resetButton.style.backgroundColor = '#f3f4f6';

            resetButton.onclick = (e) => {
                L.DomEvent.stopPropagation(e);
                map.setView(jakartaCenter, 12);
                markers.forEach(marker => marker.closePopup());
            };

            // Show all button
            const showAllButton = L.DomUtil.create('button', '', container);
            showAllButton.innerHTML = '🏫 Semua';
            showAllButton.style.cssText = `
                display: block;
                width: 100%;
                padding: 6px 12px;
                margin-bottom: 4px;
                border: none;
                border-radius: 4px;
                background: #3b82f6;
                color: white;
                font-size: 12px;
                cursor: pointer;
                transition: background-color 0.2s;
            `;

            showAllButton.onmouseover = () => showAllButton.style.backgroundColor = '#2563eb';
            showAllButton.onmouseout = () => showAllButton.style.backgroundColor = '#3b82f6';

            showAllButton.onclick = (e) => {
                L.DomEvent.stopPropagation(e);
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            };

            // Satellite toggle button
            const satelliteButton = L.DomUtil.create('button', '', container);
            satelliteButton.innerHTML = '🛰️ Satelit';
            satelliteButton.style.cssText = `
                display: block;
                width: 100%;
                padding: 6px 12px;
                border: none;
                border-radius: 4px;
                background: #f3f4f6;
                color: #374151;
                font-size: 12px;
                cursor: pointer;
                transition: background-color 0.2s;
            `;

            let satelliteLayer = null;
            let isSatellite = false;

            satelliteButton.onmouseover = () => satelliteButton.style.backgroundColor = '#e5e7eb';
            satelliteButton.onmouseout = () => satelliteButton.style.backgroundColor = isSatellite ? '#10b981' : '#f3f4f6';

            satelliteButton.onclick = (e) => {
                L.DomEvent.stopPropagation(e);
                if (isSatellite) {
                    map.removeLayer(satelliteLayer);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);
                    satelliteButton.innerHTML = '🛰️ Satelit';
                    satelliteButton.style.backgroundColor = '#f3f4f6';
                    isSatellite = false;
                } else {
                    satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '© Esri'
                    }).addTo(map);
                    satelliteButton.innerHTML = '🗺️ Peta';
                    satelliteButton.style.backgroundColor = '#10b981';
                    isSatellite = true;
                }
            };

            return container;
        },

        onRemove: function(map) {
            // Nothing to do here
        }
    });

    // Tambahkan custom control ke peta
    new CustomControl({ position: 'topright' }).addTo(map);
});
</script>
@endpush
