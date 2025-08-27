@extends('layouts.app')

@section('title', 'Edit Event - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
            <p class="text-gray-600">Perbarui informasi event {{ $event->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('ketua-ukm.events.show', $event) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('ketua-ukm.events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                        
                        <!-- UKM Selection -->
                        <div>
                            <label for="ukm_id" class="block text-sm font-medium text-gray-700 mb-2">
                                UKM <span class="text-red-500">*</span>
                            </label>
                            <select id="ukm_id" name="ukm_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ukm_id') border-red-300 @enderror">
                                <option value="">-- Pilih UKM --</option>
                                @foreach($leadingUkms as $leadingUkm)
                                    <option value="{{ $leadingUkm->id }}" 
                                            {{ old('ukm_id', $event->ukm_id) == $leadingUkm->id ? 'selected' : '' }}>
                                        {{ $leadingUkm->name }}
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
                                Judul Event <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title', $event->title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                                   placeholder="Masukkan judul event...">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Event <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('type', $event->type) == $type ? 'selected' : '' }}>
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
                                   value="{{ old('location', $event->location) }}"
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
                                  placeholder="Jelaskan tentang event ini...">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Date & Time -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Waktu Event</h3>
                        
                        <!-- Start DateTime -->
                        <div>
                            <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal & Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="start_datetime" name="start_datetime" required
                                   value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_datetime') border-red-300 @enderror">
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
                                   value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_datetime') border-red-300 @enderror">
                            @error('end_datetime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Registration Period -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Periode Pendaftaran</h3>

                        <!-- Registration Start -->
                        <div>
                            <label for="registration_start" class="block text-sm font-medium text-gray-700 mb-2">
                                Pendaftaran Mulai
                            </label>
                            <input type="datetime-local" id="registration_start" name="registration_start"
                                   value="{{ old('registration_start', $event->registration_start ? $event->registration_start->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_start') border-red-300 @enderror">
                            @error('registration_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Kosongkan jika pendaftaran langsung dibuka setelah event dipublikasikan
                            </p>
                        </div>

                        <!-- Registration End -->
                        <div style="display: none;">
                            <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Biaya Pendaftaran
                            </label>
                            <input type="number" id="registration_fee" name="registration_fee" min="0"
                                   value="{{ old('registration_fee', $event->registration_fee) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_fee') border-red-300 @enderror"
                                   placeholder="Masukkan biaya pendaftaran...">
                            @error('registration_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Masukkan 0 jika gratis
                            </p>
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
                                   value="{{ old('max_participants', $event->max_participants) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_participants') border-red-300 @enderror"
                                   placeholder="Kosongkan jika tidak ada batas">
                            @error('max_participants')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Kosongkan jika tidak ada batasan jumlah peserta
                            </p>
                        </div>

                        <!-- Registration Open -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pendaftaran
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" id="registration_open" name="registration_open" value="1"
                                       {{ old('registration_open', $event->registration_open) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="registration_open" class="ml-2 block text-sm text-gray-900">
                                    Buka pendaftaran untuk event ini
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Jika dicentang, mahasiswa dapat mendaftar untuk event ini
                            </p>
                        </div>

                        <!-- Requires Approval -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Persetujuan Pendaftaran
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" id="requires_approval" name="requires_approval" value="1"
                                       {{ old('requires_approval', $event->requires_approval) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="requires_approval" class="ml-2 block text-sm text-gray-900">
                                    Pendaftaran memerlukan persetujuan ketua UKM
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Jika dicentang, pendaftaran mahasiswa akan menunggu persetujuan Anda
                            </p>
                        </div>
                    </div>

                    <!-- File Uploads -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Upload File</h3>

                        <!-- Current Files Info -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">File Saat Ini:</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="font-medium">Poster:</span>
                                    @if($event->poster)
                                        <a href="{{ asset('storage/' . $event->poster) }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-1">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-500 ml-1">Belum ada</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium">Proposal:</span>
                                    @if($event->proposal_file)
                                        <a href="{{ asset('storage/' . $event->proposal_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-1">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    @else
                                        <span class="text-gray-500 ml-1">Belum ada</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium">RAB:</span>
                                    @if($event->rab_file)
                                        <a href="{{ asset('storage/' . $event->rab_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-1">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    @else
                                        <span class="text-gray-500 ml-1">Belum ada</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium">Template Sertifikat:</span>
                                    @if($event->certificate_template)
                                        <a href="{{ asset('storage/' . $event->certificate_template) }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-1">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-500 ml-1">Belum ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Poster Event -->
                        <div>
                            <label for="poster" class="block text-sm font-medium text-gray-700 mb-2">
                                Poster Event
                            </label>

                            <!-- Current Poster Preview -->
                            @if($event->poster)
                                <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Poster Saat Ini:</p>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $event->poster) }}"
                                                 alt="Current Poster"
                                                 class="w-24 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-600 mb-1">
                                                <i class="fas fa-image mr-1"></i>{{ basename($event->poster) }}
                                            </p>
                                            <a href="{{ asset('storage/' . $event->poster) }}"
                                               target="_blank"
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>Lihat Ukuran Penuh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="file" id="poster" name="poster" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('poster') border-red-300 @enderror"
                                   onchange="previewImage(this, 'poster-preview')">
                            @error('poster')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: JPG, PNG, GIF. Maksimal 5MB. Poster akan ditampilkan di halaman event.
                            </p>

                            <!-- New Poster Preview -->
                            <div id="poster-preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview Poster Baru:</p>
                                <img id="poster-preview-img" src="" alt="New Poster Preview"
                                     class="w-24 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Proposal File -->
                        <div>
                            <label for="proposal_file" class="block text-sm font-medium text-gray-700 mb-2">
                                File Proposal
                            </label>
                            <input type="file" id="proposal_file" name="proposal_file" accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('proposal_file') border-red-300 @enderror">
                            @error('proposal_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: PDF, DOC, DOCX. Maksimal 10MB. Hanya dapat diakses oleh admin.
                            </p>
                        </div>

                        <!-- RAB File -->
                        <div>
                            <label for="rab_file" class="block text-sm font-medium text-gray-700 mb-2">
                                File RAB (Rencana Anggaran Biaya)
                            </label>
                            <input type="file" id="rab_file" name="rab_file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rab_file') border-red-300 @enderror">
                            @error('rab_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB. Hanya dapat diakses oleh admin.
                            </p>
                        </div>

                        <!-- LPJ File -->
                        <div>
                            <label for="lpj_file" class="block text-sm font-medium text-gray-700 mb-2">
                                File LPJ (Laporan Penanggung Jawab)
                            </label>
                            <input type="file" id="lpj_file" name="lpj_file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lpj_file') border-red-300 @enderror">
                            @error('lpj_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB. Hanya dapat diakses oleh admin.
                            </p>
                        </div>

                        <!-- Certificate Template -->
                        <div>
                            <label for="certificate_template" class="block text-sm font-medium text-gray-700 mb-2">
                                Template Sertifikat
                            </label>

                            <!-- Current Certificate Template Preview -->
                            @if($event->certificate_template)
                                <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Template Saat Ini:</p>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if(in_array(strtolower(pathinfo($event->certificate_template, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset('storage/' . $event->certificate_template) }}"
                                                     alt="Current Certificate Template"
                                                     class="w-32 h-24 object-cover rounded-lg border border-gray-300 shadow-sm">
                                            @else
                                                <div class="w-32 h-24 bg-gray-200 rounded-lg border border-gray-300 shadow-sm flex items-center justify-center">
                                                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-600 mb-1">
                                                <i class="fas fa-certificate mr-1"></i>{{ basename($event->certificate_template) }}
                                            </p>
                                            <a href="{{ asset('storage/' . $event->certificate_template) }}"
                                               target="_blank"
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>Lihat Template
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Template ini akan digunakan sebagai background sertifikat
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="file" id="certificate_template" name="certificate_template" accept="image/*,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('certificate_template') border-red-300 @enderror"
                                   onchange="previewImage(this, 'certificate-preview')">
                            @error('certificate_template')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: JPG, PNG, PDF. Maksimal 5MB. Template untuk generate sertifikat peserta.
                            </p>

                            <!-- New Certificate Template Preview -->
                            <div id="certificate-preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview Template Baru:</p>
                                <img id="certificate-preview-img" src="" alt="New Certificate Template Preview"
                                     class="w-32 h-24 object-cover rounded-lg border border-gray-300 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Current Status Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Status Event Saat Ini</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Status:</strong> {{ ucfirst($event->status) }}</p>
                                    <p><strong>Pendaftar:</strong> {{ $event->registrations->count() }} orang</p>
                                    <p><strong>Dibuat:</strong> {{ $event->created_at->format('d M Y, H:i') }}</p>
                                    <p><strong>Terakhir diupdate:</strong> {{ $event->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('ketua-ukm.events.show', $event) }}" 
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

<script>
// Image preview function
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById(previewId + '-img');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Check if file is an image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        } else {
            // Hide preview for non-image files (like PDF)
            preview.classList.add('hidden');
        }
    } else {
        // Hide preview if no file selected
        preview.classList.add('hidden');
    }
}

// Allow past dates for testing event status changes
document.addEventListener('DOMContentLoaded', function() {
    const startDateTime = document.getElementById('start_datetime');
    const endDateTime = document.getElementById('end_datetime');
    const registrationStart = document.getElementById('registration_start');
    const registrationEnd = document.getElementById('registration_end');

    // Update end_datetime minimum when start_datetime changes
    startDateTime.addEventListener('change', function() {
        endDateTime.min = this.value;

        // Update registration end maximum to event start
        if (registrationEnd.value && this.value && registrationEnd.value > this.value) {
            registrationEnd.value = '';
        }
        registrationEnd.max = this.value;
    });

    // Update registration_end minimum when registration_start changes
    registrationStart.addEventListener('change', function() {
        if (this.value) {
            registrationEnd.min = this.value;
        }
    });

    // Validate registration_end doesn't exceed event start
    registrationEnd.addEventListener('change', function() {
        if (this.value && startDateTime.value && this.value > startDateTime.value) {
            alert('Pendaftaran berakhir tidak boleh melebihi waktu mulai event');
            this.value = '';
        }
    });

    // Initialize validation on page load
    if (startDateTime.value) {
        endDateTime.min = startDateTime.value;
        registrationEnd.max = startDateTime.value;
    }

    if (registrationStart.value) {
        registrationEnd.min = registrationStart.value;
    }

    // Note: Date restrictions removed for testing event status transitions
    // Past dates will trigger automatic status updates to 'completed'
    // Today's dates will trigger status updates to 'ongoing'
});
</script>
@endsection
