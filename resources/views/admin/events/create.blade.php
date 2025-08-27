@extends('admin.layouts.app')

@section('title', 'Tambah Kegiatan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
                              <input type="hidden" id="registration_fee" name="registration_fee" value="0">>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Kegiatan</h1>
            <p class="text-gray-600">Buat kegiatan baru untuk UKM</p>
        </div>
        <a href="{{ route('admin.events.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                        
                        <!-- UKM -->
                        <div>
                            <label for="ukm_id" class="block text-sm font-medium text-gray-700 mb-2">
                                UKM Penyelenggara <span class="text-red-500">*</span>
                            </label>
                            <select id="ukm_id" name="ukm_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ukm_id') border-red-300 @enderror">
                                <option value="">-- Pilih UKM --</option>
                                @foreach($ukms as $ukm)
                                    <option value="{{ $ukm->id }}" {{ old('ukm_id') == $ukm->id ? 'selected' : '' }}>
                                        {{ $ukm->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ukm_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                                   placeholder="Masukkan judul kegiatan...">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="location" name="location" required
                                   value="{{ old('location') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-300 @enderror"
                                   placeholder="Contoh: Aula Utama, Gedung A">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                                  placeholder="Jelaskan tentang kegiatan ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requirements -->
                    <div>
                        <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                            Persyaratan
                        </label>
                        <textarea id="requirements" name="requirements" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requirements') border-red-300 @enderror"
                                  placeholder="Persyaratan untuk mengikuti kegiatan...">{{ old('requirements') }}</textarea>
                        @error('requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Date & Time -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Waktu & Pendaftaran</h3>
                        
                        <!-- Start DateTime -->
                        <div>
                            <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal & Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="start_datetime" name="start_datetime" required
                                   value="{{ old('start_datetime') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_datetime') border-red-300 @enderror">
                            <div class="mt-2 text-xs text-blue-600 bg-blue-50 p-2 rounded">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Testing Mode:</strong> Tanggal lampau diizinkan untuk testing status event.
                                <br>• Tanggal lampau → Status: Completed
                                <br>• Tanggal hari ini → Status: Ongoing
                                <br>• Tanggal masa depan → Status: Published
                            </div>
                            @error('start_datetime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End DateTime -->
                        <div>
                            <label for="end_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal & Waktu Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="end_datetime" name="end_datetime" required
                                   value="{{ old('end_datetime') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_datetime') border-red-300 @enderror">
                            @error('end_datetime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Registration Period -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="registration_start" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendaftaran Mulai
                                </label>
                                <input type="datetime-local" id="registration_start" name="registration_start"
                                       value="{{ old('registration_start') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_start') border-red-300 @enderror">
                                @error('registration_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="registration_end" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendaftaran Berakhir
                                </label>
                                <input type="datetime-local" id="registration_end" name="registration_end"
                                       value="{{ old('registration_end') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_end') border-red-300 @enderror">
                                @error('registration_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Peserta</h3>
                        
                        <!-- Max Participants -->
                        <div>
                            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Peserta
                            </label>
                            <input type="number" id="max_participants" name="max_participants" min="1"
                                   value="{{ old('max_participants') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_participants') border-red-300 @enderror"
                                   placeholder="Kosongkan jika tidak ada batas">
                            @error('max_participants')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Registration Fee -->
                        <div style="display: none;">
                            <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Biaya Pendaftaran (Rp)
                            </label>
                            <input type="number" id="registration_fee" name="registration_fee" min="0" step="1000"
                                   value="{{ old('registration_fee', 0) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_fee') border-red-300 @enderror"
                                   placeholder="0 untuk gratis">
                            @error('registration_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Pengaturan</h3>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status', 'draft') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden checkboxes - set default values -->
                        <input type="hidden" name="requires_approval" value="0">
                        <input type="hidden" name="certificate_available" value="0">
                    </div>

                    <!-- Poster -->
                    <div>
                        <label for="poster" class="block text-sm font-medium text-gray-700 mb-2">
                            Poster Kegiatan
                        </label>
                        <input type="file" id="poster" name="poster" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('poster') border-red-300 @enderror">
                        @error('poster')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Person -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak Person</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="contact_person_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama
                        </label>
                        <input type="text" id="contact_person_name" name="contact_person_name"
                               value="{{ old('contact_person_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nama kontak person">
                    </div>
                    <div>
                        <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text" id="contact_person_phone" name="contact_person_phone"
                               value="{{ old('contact_person_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label for="contact_person_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" id="contact_person_email" name="contact_person_email"
                               value="{{ old('contact_person_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="email@example.com">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan
                </label>
                <textarea id="notes" name="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Catatan tambahan untuk kegiatan...">{{ old('notes') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('admin.events.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Allow past dates for testing event status changes
document.addEventListener('DOMContentLoaded', function() {
    // Update end_datetime minimum when start_datetime changes
    document.getElementById('start_datetime').addEventListener('change', function() {
        document.getElementById('end_datetime').min = this.value;

        // Update registration end maximum
        if (document.getElementById('registration_end')) {
            document.getElementById('registration_end').max = this.value;
        }
    });

    // Note: Minimum date restrictions removed for testing purposes
    // This allows setting past dates to test event status transitions:
    // - Past dates → completed status
    // - Today's date → ongoing status
    // - Future dates → published status
});
</script>
@endsection
