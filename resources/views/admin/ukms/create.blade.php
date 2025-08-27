@extends('admin.layouts.app')

@section('title', 'Tambah UKM')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah UKM</h1>
                <p class="text-gray-600 mt-2">Tambahkan Unit Kegiatan Mahasiswa baru</p>
            </div>
            <a href="{{ route('admin.ukms.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <form action="{{ route('admin.ukms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Informasi UKM</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="form-label">Nama UKM *</label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name') }}"
                               class="form-input @error('name') border-red-300 @enderror"
                               placeholder="Nama lengkap UKM">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="form-label">Kategori *</label>
                        <select id="category" name="category" required
                                class="form-input @error('category') border-red-300 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Members -->
                    <div>
                        <label for="max_members" class="form-label">Kapasitas Maksimal *</label>
                        <input type="number" id="max_members" name="max_members" required
                               value="{{ old('max_members', 50) }}"
                               class="form-input @error('max_members') border-red-300 @enderror"
                               placeholder="50" min="1">
                        @error('max_members')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="form-label">Logo UKM</label>
                        <input type="file" id="logo" name="logo"
                               accept="image/*"
                               class="form-input @error('logo') border-red-300 @enderror">
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Upload logo UKM. Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</p>
                    </div>

                    <!-- Background Image Upload -->
                    <div>
                        <label for="background_image" class="form-label">Background Image UKM</label>
                        <input type="file" id="background_image" name="background_image"
                               accept="image/*"
                               class="form-input @error('background_image') border-red-300 @enderror">
                        @error('background_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Upload background image untuk kartu UKM. Format yang didukung: JPG, PNG, GIF. Maksimal 2MB. Ukuran optimal: 400x200px.</p>
                    </div>

                    <!-- Leader -->
                    <div>
                        <label for="leader_id" class="form-label">Ketua UKM</label>
                        <select id="leader_id" name="leader_id"
                                class="form-input @error('leader_id') border-red-300 @enderror">
                            <option value="">Pilih Ketua UKM</option>
                            @foreach($ketuaUkmUsers as $user)
                                <option value="{{ $user->id }}" {{ old('leader_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->nim }}) - {{ $user->major }}
                                </option>
                            @endforeach
                        </select>
                        @error('leader_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Hanya mahasiswa dengan role "Ketua UKM" yang dapat dipilih.
                            <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-500">
                                Kelola role mahasiswa
                            </a>
                        </p>
                    </div>

                    <!-- Established Date -->
                    <div>
                        <label for="established_date" class="form-label">Tanggal Berdiri</label>
                        <input type="date" id="established_date" name="established_date"
                               value="{{ old('established_date') }}"
                               class="form-input @error('established_date') border-red-300 @enderror">
                        @error('established_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Deskripsi *</label>
                    <textarea id="description" name="description" rows="4" required
                              class="form-input @error('description') border-red-300 @enderror"
                              placeholder="Deskripsi singkat tentang UKM...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vision & Mission -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vision -->
                    <div>
                        <label for="vision" class="form-label">Visi</label>
                        <textarea id="vision" name="vision" rows="3"
                                  class="form-input @error('vision') border-red-300 @enderror"
                                  placeholder="Visi UKM...">{{ old('vision') }}</textarea>
                        @error('vision')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mission -->
                    <div>
                        <label for="mission" class="form-label">Misi</label>
                        <textarea id="mission" name="mission" rows="3"
                                  class="form-input @error('mission') border-red-300 @enderror"
                                  placeholder="Misi UKM...">{{ old('mission') }}</textarea>
                        @error('mission')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kontak</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="contact_email" class="form-label">Email UKM</label>
                            <input type="email" id="contact_email" name="contact_email"
                                   value="{{ old('contact_email') }}"
                                   class="form-input @error('contact_email') border-red-300 @enderror"
                                   placeholder="ukm@telkomuniversity.ac.id">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="contact_phone" class="form-label">Nomor Telepon</label>
                            <input type="text" id="contact_phone" name="contact_phone"
                                   value="{{ old('contact_phone') }}"
                                   class="form-input @error('contact_phone') border-red-300 @enderror"
                                   placeholder="081234567890">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="contact_instagram" class="form-label">Instagram</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    @
                                </span>
                                <input type="text" id="contact_instagram" name="contact_instagram"
                                       value="{{ old('contact_instagram') }}"
                                       class="form-input rounded-l-none @error('contact_instagram') border-red-300 @enderror"
                                       placeholder="username_ukm">
                            </div>
                            @error('contact_instagram')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="contact_website" class="form-label">Website</label>
                            <input type="url" id="contact_website" name="contact_website"
                                   value="{{ old('contact_website') }}"
                                   class="form-input @error('contact_website') border-red-300 @enderror"
                                   placeholder="https://ukm.telkomuniversity.ac.id">
                            @error('contact_website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Meeting Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pertemuan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Meeting Schedule -->
                        <div>
                            <label for="meeting_schedule" class="form-label">Jadwal Pertemuan</label>
                            <input type="text" id="meeting_schedule" name="meeting_schedule"
                                   value="{{ old('meeting_schedule') }}"
                                   class="form-input @error('meeting_schedule') border-red-300 @enderror"
                                   placeholder="Setiap Jumat, 16:00 - 18:00">
                            @error('meeting_schedule')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meeting Location -->
                        <div>
                            <label for="meeting_location" class="form-label">Lokasi Pertemuan</label>
                            <input type="text" id="meeting_location" name="meeting_location"
                                   value="{{ old('meeting_location') }}"
                                   class="form-input @error('meeting_location') border-red-300 @enderror"
                                   placeholder="Ruang 101, Gedung Tokong Nanas">
                            @error('meeting_location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Prestasi UKM -->
                @include('components.achievement-form', ['achievements' => collect()])

                <!-- Struktur Organisasi -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Struktur Organisasi</h3>
                    <div>
                        <label for="organization_structure" class="form-label">Gambar Struktur Organisasi</label>
                        <input type="file" id="organization_structure" name="organization_structure"
                               accept="image/*"
                               class="form-input @error('organization_structure') border-red-300 @enderror">
                        @error('organization_structure')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Upload gambar struktur organisasi UKM. Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.ukms.index') }}" class="btn-secondary">
                        Batal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan UKM
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-')
                     .trim('-');
});
</script>
@endsection
