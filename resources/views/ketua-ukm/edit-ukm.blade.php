@extends('layouts.app')

@section('title', 'Edit UKM - ' . $ukm->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit {{ $ukm->name }}</h1>
            <p class="text-gray-600">Perbarui informasi UKM Anda</p>
        </div>
        <a href="{{ route('ketua-ukm.manage', $ukm->id) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('ketua-ukm.update-ukm', $ukm->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi UKM <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                                  placeholder="Jelaskan tentang UKM Anda...">{{ old('description', $ukm->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vision -->
                    <div>
                        <label for="vision" class="block text-sm font-medium text-gray-700 mb-2">
                            Visi UKM
                        </label>
                        <textarea id="vision" name="vision" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vision') border-red-300 @enderror"
                                  placeholder="Visi UKM...">{{ old('vision', $ukm->vision) }}</textarea>
                        @error('vision')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mission -->
                    <div>
                        <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">
                            Misi UKM
                        </label>
                        <textarea id="mission" name="mission" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mission') border-red-300 @enderror"
                                  placeholder="Misi UKM...">{{ old('mission', $ukm->mission) }}</textarea>
                        @error('mission')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo UKM
                        </label>
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                @if($ukm->logo)
                                    <img class="h-16 w-16 object-cover rounded-lg"
                                         src="{{ asset('storage/' . $ukm->logo) }}"
                                         alt="{{ $ukm->name }}">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" id="logo" name="logo"
                                       accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('logo') border-red-300 @enderror">
                                <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau GIF. Maksimal 2MB. Ukuran optimal: 200x200px.</p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Meeting Schedule -->
                    <div>
                        <label for="meeting_schedule" class="block text-sm font-medium text-gray-700 mb-2">
                            Jadwal Pertemuan
                        </label>
                        <input type="text" id="meeting_schedule" name="meeting_schedule"
                               value="{{ old('meeting_schedule', $ukm->meeting_schedule) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meeting_schedule') border-red-300 @enderror"
                               placeholder="Contoh: Setiap Jumat, 16:00-18:00">
                        @error('meeting_schedule')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meeting Location -->
                    <div>
                        <label for="meeting_location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Pertemuan
                        </label>
                        <input type="text" id="meeting_location" name="meeting_location" 
                               value="{{ old('meeting_location', $ukm->meeting_location) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meeting_location') border-red-300 @enderror"
                               placeholder="Contoh: Ruang 301, Gedung A">
                        @error('meeting_location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Kontak</h3>
                        
                        @php
                            $contactInfo = is_string($ukm->contact_info) ? json_decode($ukm->contact_info, true) : $ukm->contact_info;
                        @endphp

                        <!-- Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email UKM
                            </label>
                            <input type="email" id="contact_email" name="contact_info[email]" 
                                   value="{{ old('contact_info.email', $contactInfo['email'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="email@ukm.com">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" id="contact_phone" name="contact_info[phone]" 
                                   value="{{ old('contact_info.phone', $contactInfo['phone'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="contact_instagram" class="block text-sm font-medium text-gray-700 mb-2">
                                Instagram
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    @
                                </span>
                                <input type="text" id="contact_instagram" name="contact_info[instagram]" 
                                       value="{{ old('contact_info.instagram', $contactInfo['instagram'] ?? '') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="username_ukm">
                            </div>
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="contact_website" class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <input type="url" id="contact_website" name="contact_info[website]" 
                                   value="{{ old('contact_info.website', $contactInfo['website'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://website-ukm.com">
                        </div>
                    </div>
                </div>

                <!-- Prestasi UKM -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    @include('components.achievement-form', ['achievements' => $ukm->achievements ?: collect()])
                </div>

                <!-- Struktur Organisasi -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Struktur Organisasi</h3>
                    <div>
                        <label for="organization_structure" class="block text-sm font-medium text-gray-700 mb-2">
                            Gambar Struktur Organisasi
                        </label>
                        <input type="file" id="organization_structure" name="organization_structure"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Upload gambar struktur organisasi UKM. Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</p>

                        @if($ukm->organization_structure)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                <img src="{{ asset('storage/' . $ukm->organization_structure) }}"
                                     alt="Struktur Organisasi"
                                     class="max-w-xs h-auto border border-gray-300 rounded">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Perubahan akan langsung terlihat di halaman publik UKM</li>
                                <li>Pastikan informasi yang diisi akurat dan up-to-date</li>
                                <li>Informasi kontak akan membantu mahasiswa menghubungi UKM</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('ketua-ukm.manage', $ukm->id) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Logo preview functionality
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('img');
            if (preview) {
                preview.src = e.target.result;
            } else {
                // Create new preview if doesn't exist
                const container = document.querySelector('.shrink-0');
                container.innerHTML = `<img class="h-16 w-16 object-cover rounded-lg" src="${e.target.result}" alt="Logo Preview">`;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
