@extends('layouts.app')

@section('title', 'Daftar Event - ' . $event->title)

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
                <h1 class="text-2xl font-bold text-gray-900">Pendaftaran Event</h1>
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
                        <i class="fas fa-users mr-2"></i>
                        @if($event->max_participants)
                            {{ $event->current_participants }}/{{ $event->max_participants }} peserta
                        @else
                            {{ $event->current_participants }} peserta terdaftar
                        @endif
                    </div>
                    @if($event->registration_fee > 0)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-money-bill mr-2"></i>
                            Rp {{ number_format($event->registration_fee, 0, ',', '.') }}
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi</h3>
                <p class="text-sm text-gray-700">{{ Str::limit($event->description, 200) }}</p>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('events.register', $event->slug) }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Motivation -->
                <div>
                    <label for="motivation" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivasi Mengikuti Event <span class="text-red-500">*</span>
                    </label>
                    <textarea id="motivation" name="motivation" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('motivation') border-red-300 @enderror"
                              placeholder="Jelaskan mengapa Anda ingin mengikuti event ini...">{{ old('motivation') }}</textarea>
                    @error('motivation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Availability Form -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Form Ketersediaan <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="space-y-4">
                        <!-- Full Attendance -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Apakah Anda dapat menghadiri event dari awal hingga akhir?
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="availability[full_attendance]" value="yes" 
                                           {{ old('availability.full_attendance') == 'yes' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Ya, saya dapat menghadiri dari awal hingga akhir</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="availability[full_attendance]" value="no"
                                           {{ old('availability.full_attendance') == 'no' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Tidak, saya hanya dapat menghadiri sebagian</span>
                                </label>
                            </div>
                        </div>

                        <!-- Partial Attendance Details -->
                        <div id="partial-details" style="display: none;">
                            <label for="partial_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Jelaskan bagian mana yang tidak dapat Anda hadiri dan alasannya
                            </label>
                            <textarea id="partial_reason" name="availability[partial_reason]" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Contoh: Tidak dapat menghadiri sesi terakhir karena ada kuliah...">{{ old('availability.partial_reason') }}</textarea>
                        </div>

                        <!-- Transportation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bagaimana Anda akan datang ke lokasi event?
                            </label>
                            <select name="availability[transportation]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Transportasi --</option>
                                <option value="pribadi" {{ old('availability.transportation') == 'pribadi' ? 'selected' : '' }}>Kendaraan Pribadi</option>
                                <option value="umum" {{ old('availability.transportation') == 'umum' ? 'selected' : '' }}>Transportasi Umum</option>
                                <option value="jalan_kaki" {{ old('availability.transportation') == 'jalan_kaki' ? 'selected' : '' }}>Jalan Kaki</option>
                                <option value="lainnya" {{ old('availability.transportation') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Emergency Contact -->
                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">
                                Kontak Darurat (Nama & No. HP)
                            </label>
                            <input type="text" id="emergency_contact" name="availability[emergency_contact]"
                                   value="{{ old('availability.emergency_contact') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: Budi (081234567890)">
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label for="registration_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea id="registration_notes" name="registration_notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Catatan tambahan, pertanyaan, atau hal khusus yang perlu diketahui...">{{ old('registration_notes') }}</textarea>
                </div>

                <!-- Agreement -->
                <div>
                    <label class="flex items-start">
                        <input type="checkbox" name="agreement" value="1" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                        <span class="ml-2 text-sm text-gray-700">
                            Saya menyetujui untuk mengikuti event ini dan akan mematuhi semua aturan yang berlaku. 
                            Saya memahami bahwa pembatalan hanya dapat dilakukan maksimal 1 hari sebelum event dimulai.
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
                    <i class="fas fa-paper-plane mr-2"></i>Daftar Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fullAttendanceRadios = document.querySelectorAll('input[name="availability[full_attendance]"]');
    const partialDetails = document.getElementById('partial-details');
    
    fullAttendanceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'no') {
                partialDetails.style.display = 'block';
            } else {
                partialDetails.style.display = 'none';
            }
        });
    });
    
    // Check initial state
    const checkedRadio = document.querySelector('input[name="availability[full_attendance]"]:checked');
    if (checkedRadio && checkedRadio.value === 'no') {
        partialDetails.style.display = 'block';
    }
});
</script>
@endsection
