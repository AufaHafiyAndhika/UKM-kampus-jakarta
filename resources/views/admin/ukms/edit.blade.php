@extends('admin.layouts.app')

@section('title', 'Edit UKM - ' . $ukm->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit UKM</h1>
                <p class="text-gray-600 mt-2">Ubah informasi Unit Kegiatan Mahasiswa</p>
            </div>
            <a href="{{ route('admin.ukms.show', $ukm->slug) }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <form action="{{ route('admin.ukms.update', $ukm->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                               value="{{ old('name', $ukm->name) }}"
                               class="form-input @error('name') border-red-300 @enderror"
                               placeholder="Nama lengkap UKM">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="form-label">Slug URL *</label>
                        <input type="text" id="slug" name="slug" required
                               value="{{ old('slug', $ukm->slug) }}"
                               class="form-input @error('slug') border-red-300 @enderror"
                               placeholder="url-friendly-name">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">URL akan menjadi: /ukm/<span id="slug-preview">{{ $ukm->slug }}</span></p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="form-label">Kategori *</label>
                        <select id="category" name="category" required
                                class="form-input @error('category') border-red-300 @enderror">
                            <option value="">Pilih Kategori</option>
                            <option value="academic" {{ old('category', $ukm->category) == 'academic' ? 'selected' : '' }}>Akademik</option>
                            <option value="sports" {{ old('category', $ukm->category) == 'sports' ? 'selected' : '' }}>Olahraga</option>
                            <option value="arts" {{ old('category', $ukm->category) == 'arts' ? 'selected' : '' }}>Seni & Budaya</option>
                            <option value="religion" {{ old('category', $ukm->category) == 'religion' ? 'selected' : '' }}>Keagamaan</option>
                            <option value="social" {{ old('category', $ukm->category) == 'social' ? 'selected' : '' }}>Sosial & Kemasyarakatan</option>
                            <option value="technology" {{ old('category', $ukm->category) == 'technology' ? 'selected' : '' }}>Teknologi</option>
                            <option value="entrepreneurship" {{ old('category', $ukm->category) == 'entrepreneurship' ? 'selected' : '' }}>Kewirausahaan</option>
                            <option value="other" {{ old('category', $ukm->category) == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" required
                                class="form-input @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status', $ukm->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $ukm->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="suspended" {{ old('status', $ukm->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Registration Status -->
                    <div>
                        <label for="registration_status" class="form-label">Status Pendaftaran *</label>
                        <select id="registration_status" name="registration_status" required
                                class="form-input @error('registration_status') border-red-300 @enderror">
                            <option value="open" {{ old('registration_status', $ukm->registration_status) == 'open' ? 'selected' : '' }}>Buka</option>
                            <option value="closed" {{ old('registration_status', $ukm->registration_status) == 'closed' ? 'selected' : '' }}>Tutup</option>
                        </select>
                        @error('registration_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ketua UKM -->
                    <div>
                        <label for="leader_id" class="form-label">Ketua UKM</label>
                        <select id="leader_id" name="leader_id"
                                class="form-input @error('leader_id') border-red-300 @enderror">
                            <option value="">Pilih Ketua UKM</option>
                            @foreach($ketuaUkmUsers as $user)
                                <option value="{{ $user->id }}" {{ old('leader_id', $ukm->leader_id) == $user->id ? 'selected' : '' }}>
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
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Deskripsi Singkat *</label>
                    <textarea id="description" name="description" rows="3" required
                              class="form-input @error('description') border-red-300 @enderror"
                              placeholder="Deskripsi singkat tentang UKM...">{{ old('description', $ukm->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vision -->
                <div>
                    <label for="vision" class="form-label">Visi</label>
                    <textarea id="vision" name="vision" rows="3"
                              class="form-input @error('vision') border-red-300 @enderror"
                              placeholder="Visi UKM...">{{ old('vision', $ukm->vision) }}</textarea>
                    @error('vision')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mission -->
                <div>
                    <label for="mission" class="form-label">Misi</label>
                    <textarea id="mission" name="mission" rows="4"
                              class="form-input @error('mission') border-red-300 @enderror"
                              placeholder="Misi UKM...">{{ old('mission', $ukm->mission) }}</textarea>
                    @error('mission')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="form-label">Logo UKM</label>
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            @if($ukm->logo)
                                <img class="h-16 w-16 object-cover rounded-lg"
                                     src="{{ asset('storage/' . $ukm->logo) }}"
                                     alt="{{ $ukm->name }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center" style="display: none;">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
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
                                   class="form-input @error('logo') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau GIF. Maksimal 2MB. Ukuran optimal: 200x200px.</p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Background Image Upload -->
                <div>
                    <label for="background_image" class="form-label">Background Image UKM</label>
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            @if($ukm->background_image)
                                <img class="h-16 w-32 object-cover rounded-lg"
                                     src="{{ asset('storage/' . $ukm->background_image) }}"
                                     alt="{{ $ukm->name }} Background">
                            @else
                                <div class="h-16 w-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" id="background_image" name="background_image"
                                   accept="image/*"
                                   class="form-input @error('background_image') border-red-300 @enderror">
                            @error('background_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau GIF. Maksimal 2MB. Ukuran optimal: 400x200px untuk background kartu UKM.</p>
                        </div>
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
                                   value="{{ old('contact_email', isset($ukm->contact_info['email']) ? $ukm->contact_info['email'] : '') }}"
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
                                   value="{{ old('contact_phone', isset($ukm->contact_info['phone']) ? $ukm->contact_info['phone'] : '') }}"
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
                                       value="{{ old('contact_instagram', isset($ukm->contact_info['instagram']) ? $ukm->contact_info['instagram'] : '') }}"
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
                                   value="{{ old('contact_website', isset($ukm->contact_info['website']) ? $ukm->contact_info['website'] : '') }}"
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
                        <div class="md:col-span-2">
                            <label for="meeting_schedule" class="form-label">Jadwal Pertemuan</label>
                            <input type="text" id="meeting_schedule" name="meeting_schedule"
                                   value="{{ old('meeting_schedule', $ukm->meeting_schedule) }}"
                                   class="form-input @error('meeting_schedule') border-red-300 @enderror"
                                   placeholder="Setiap Senin, 19:00 - 21:00">
                            @error('meeting_schedule')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Contoh: "Setiap Senin, 19:00 - 21:00" atau "Setiap Rabu dan Jumat, 16:00 - 18:00"</p>
                        </div>

                        <!-- Meeting Location -->
                        <div class="md:col-span-2">
                            <label for="meeting_location" class="form-label">Lokasi Pertemuan</label>
                            <input type="text" id="meeting_location" name="meeting_location"
                                   value="{{ old('meeting_location', $ukm->meeting_location) }}"
                                   class="form-input @error('meeting_location') border-red-300 @enderror"
                                   placeholder="Ruang 101, Gedung Tokong Nanas">
                            @error('meeting_location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Persyaratan Bergabung</h3>
                    <div>
                        <label for="requirements" class="form-label">Persyaratan</label>
                        <textarea id="requirements" name="requirements" rows="4"
                                  class="form-input @error('requirements') border-red-300 @enderror"
                                  placeholder="Contoh: Mahasiswa aktif, Memiliki minat di bidang teknologi, Bersedia mengikuti kegiatan rutin">{{ old('requirements', $ukm->requirements) }}</textarea>
                        @error('requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Pisahkan setiap persyaratan dengan koma (,)</p>
                    </div>
                </div>

                <!-- Achievements -->
                @include('components.achievement-form', ['achievements' => $ukm->achievements])

                <!-- Organization Structure -->
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

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.ukms.show', $ukm->slug) }}" class="btn-secondary">
                        Batal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
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

    document.getElementById('slug').value = slug;
    document.getElementById('slug-preview').textContent = slug;
});

// Update slug preview
document.getElementById('slug').addEventListener('input', function() {
    document.getElementById('slug-preview').textContent = this.value;
});

// Logo preview
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('img');
            if (preview) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
