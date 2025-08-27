@extends('layouts.app')

@section('title', 'Tambah Event UKM')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Error!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Terdapat kesalahan dalam pengisian form:</p>
                    <ul class="text-sm list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Event UKM</h1>
            <p class="text-gray-600">Buat event baru untuk UKM yang Anda pimpin</p>
        </div>
        <a href="{{ route('ketua-ukm.events') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('ketua-ukm.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>

                        <!-- UKM Selection -->
                        @if($leadingUkms->count() > 1)
                            <div>
                                <label for="ukm_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    UKM <span class="text-red-500">*</span>
                                </label>
                                <select id="ukm_id" name="ukm_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ukm_id') border-red-300 @enderror">
                                    <option value="">-- Pilih UKM --</option>
                                    @foreach($leadingUkms as $leadingUkm)
                                        <option value="{{ $leadingUkm->id }}"
                                                {{ old('ukm_id', $ukm ? $ukm->id : '') == $leadingUkm->id ? 'selected' : '' }}>
                                            {{ $leadingUkm->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ukm_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <!-- Single UKM - Hidden field -->
                            <input type="hidden" id="ukm_id" name="ukm_id" value="{{ $ukm ? $ukm->id : '' }}" />
                            @if($ukm)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-blue-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Event akan dibuat untuk UKM: <strong>{{ $ukm->name }}</strong>
                                    </p>
                                </div>
                            @endif
                        @endif

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Event <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
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
                                  placeholder="Jelaskan tentang event ini...">{{ old('description') }}</textarea>
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
                                   value="{{ old('start_datetime') }}"
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
                                   value="{{ old('end_datetime') }}"
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
                                   value="{{ old('registration_start') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_start') border-red-300 @enderror">
                            @error('registration_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Kosongkan jika pendaftaran langsung dibuka setelah event dipublikasikan
                            </p>
                        </div>

                        <!-- Registration End -->
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
                            <p class="mt-1 text-sm text-gray-500">
                                Kosongkan jika pendaftaran tetap terbuka sampai event dimulai
                            </p>
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
                                       {{ old('registration_open', true) ? 'checked' : '' }}
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
                                       {{ old('requires_approval', true) ? 'checked' : '' }}
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

                        <!-- Poster Event -->
                        <div>
                            <label for="poster" class="block text-sm font-medium text-gray-700 mb-2">
                                Poster Event
                            </label>
                            <input type="file" id="poster" name="poster" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('poster') border-red-300 @enderror">
                            @error('poster')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: JPG, PNG, GIF. Maksimal 5MB. Poster akan ditampilkan di halaman event.
                            </p>
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

                        <!-- Certificate Template -->
                        <div>
                            <label for="certificate_template" class="block text-sm font-medium text-gray-700 mb-2">
                                Template Sertifikat
                            </label>
                            <input type="file" id="certificate_template" name="certificate_template" accept="image/*,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('certificate_template') border-red-300 @enderror">
                            @error('certificate_template')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Format: JPG, PNG, PDF. Maksimal 5MB. Template untuk generate sertifikat peserta.
                            </p>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Event akan dibuat dengan status "Draft"</li>
                                        <li>Admin dapat mempublikasikan event Anda</li>
                                        <li>File proposal dan RAB hanya dapat diakses oleh admin</li>
                                        <li>Poster event akan ditampilkan di halaman publik</li>
                                        <li>LPJ dapat diupload setelah event selesai</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('ketua-ukm.events') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

    // Set default registration period if event dates are set
    function setDefaultRegistrationPeriod() {
        if (startDateTime.value && !registrationStart.value) {
            // Default: registration starts 7 days before event
            const eventStart = new Date(startDateTime.value);
            const regStart = new Date(eventStart.getTime() - (7 * 24 * 60 * 60 * 1000));
            registrationStart.value = regStart.toISOString().slice(0, 16);
        }

        if (startDateTime.value && !registrationEnd.value) {
            // Default: registration ends 1 day before event
            const eventStart = new Date(startDateTime.value);
            const regEnd = new Date(eventStart.getTime() - (24 * 60 * 60 * 1000));
            registrationEnd.value = regEnd.toISOString().slice(0, 16);
        }
    }

    // Auto-set registration period when event start is set
    startDateTime.addEventListener('blur', setDefaultRegistrationPeriod);

    // Note: Minimum date restrictions removed for testing purposes
    // This allows ketua UKM to create events with past dates for testing
});
</script>
@endsection
