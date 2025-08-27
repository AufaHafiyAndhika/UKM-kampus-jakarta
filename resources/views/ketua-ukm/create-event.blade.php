@extends('layouts.app')

@section('title', 'Buat Event - ' . $ukm->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Event untuk {{ $ukm->name }}</h1>
            <p class="text-gray-600">Buat event baru untuk UKM Anda</p>
        </div>
        <a href="{{ route('ketua-ukm.manage', $ukm->id) }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('ketua-ukm.store-event', $ukm->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
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

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Event <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                                  placeholder="Jelaskan tentang event ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Event <span class="text-red-500">*</span>
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

                <!-- Right Column -->
                <div class="space-y-6">
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

                    <!-- Event Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Event <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror">
                            <option value="">-- Pilih Jenis Event --</option>
                            <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="seminar" {{ old('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="competition" {{ old('type') == 'competition' ? 'selected' : '' }}>Kompetisi</option>
                            <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>Pertemuan</option>
                            <option value="social" {{ old('type') == 'social' ? 'selected' : '' }}>Acara Sosial</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
                </div>
            </div>

            <!-- UKM Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Event</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Event akan dibuat untuk UKM: <strong>{{ $ukm->name }}</strong></li>
                                <li>Event akan berstatus "Upcoming" setelah dibuat</li>
                                <li>Pastikan tanggal dan waktu sudah benar</li>
                                <li>Informasi event dapat diedit setelah dibuat</li>
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
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

    document.getElementById('start_datetime').min = minDateTime;
    document.getElementById('end_datetime').min = minDateTime;

    // Update end_datetime minimum when start_datetime changes
    document.getElementById('start_datetime').addEventListener('change', function() {
        document.getElementById('end_datetime').min = this.value;
    });
});
</script>
@endsection
