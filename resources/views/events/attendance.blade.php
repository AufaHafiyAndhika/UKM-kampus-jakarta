@extends('layouts.app')

@section('title', 'Absensi Event - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('events.show', $event->slug) }}" 
               class="text-blue-600 hover:text-blue-800 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Form Absensi Event</h1>
                <p class="text-gray-600">{{ $event->title }}</p>
            </div>
        </div>
    </div>

    <!-- Event Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Event</h3>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        {{ $event->start_datetime->format('d M Y, H:i') }} - {{ $event->end_datetime->format('d M Y, H:i') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $event->location }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-building mr-2"></i>
                        {{ $event->ukm->name }}
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Event</h3>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Event Selesai
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Event telah berakhir. Silakan isi form absensi untuk mendapatkan sertifikat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Form Absensi</h2>
            <p class="text-gray-600">
                Upload bukti kehadiran Anda untuk mendapatkan sertifikat event. 
                Bukti dapat berupa foto selfie di lokasi event, foto bersama peserta lain, atau dokumentasi lainnya.
            </p>
        </div>

        <form action="{{ route('events.attendance.submit', $event->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Proof File Upload -->
                <div>
                    <label for="proof_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Kehadiran <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="proof_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload foto bukti kehadiran</span>
                                    <input id="proof_file" name="proof_file" type="file" accept="image/*" required class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 5MB</p>
                        </div>
                    </div>
                    @error('proof_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Area -->
                <div id="preview-area" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Bukti Kehadiran</label>
                    <div class="border border-gray-300 rounded-lg p-4">
                        <img id="preview-image" src="" alt="Preview" class="max-w-full h-64 object-contain mx-auto rounded">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea id="notes" name="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror"
                              placeholder="Ceritakan pengalaman Anda mengikuti event ini, kesan dan pesan, atau hal menarik lainnya...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Maksimal 1000 karakter</p>
                </div>

                <!-- Important Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan bukti kehadiran jelas dan dapat diverifikasi</li>
                                    <li>Absensi akan diverifikasi oleh admin atau ketua UKM</li>
                                    <li>Sertifikat dapat didownload setelah absensi diverifikasi</li>
                                    <li>Anda hanya dapat mengisi absensi sekali untuk setiap event</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agreement -->
                <div>
                    <label class="flex items-start">
                        <input type="checkbox" name="agreement" value="1" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                        <span class="ml-2 text-sm text-gray-700">
                            Saya menyatakan bahwa bukti kehadiran yang saya upload adalah benar dan saya benar-benar menghadiri event ini. 
                            Saya memahami bahwa memberikan informasi palsu dapat mengakibatkan pembatalan sertifikat.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('events.show', $event->slug) }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-check mr-2"></i>Submit Absensi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('proof_file');
    const previewArea = document.getElementById('preview-area');
    const previewImage = document.getElementById('preview-image');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewArea.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewArea.classList.add('hidden');
        }
    });
    
    // Character counter for notes
    const notesTextarea = document.getElementById('notes');
    const maxLength = 1000;
    
    notesTextarea.addEventListener('input', function() {
        const remaining = maxLength - this.value.length;
        // You can add a character counter here if needed
    });
});
</script>
@endsection
