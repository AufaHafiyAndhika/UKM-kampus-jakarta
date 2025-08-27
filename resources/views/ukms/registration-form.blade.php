@extends('layouts.app')

@section('title', 'Daftar UKM - ' . $ukm->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('ukms.show', $ukm->slug) }}" 
               class="text-blue-600 hover:text-blue-800 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pendaftaran UKM</h1>
                <p class="text-gray-600">{{ $ukm->name }}</p>
            </div>
        </div>
        
        <!-- UKM Info Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($ukm->logo)
                        <img src="{{ asset('storage/' . $ukm->logo) }}" 
                             alt="{{ $ukm->name }}" 
                             class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl font-bold text-blue-600">{{ substr($ukm->name, 0, 2) }}</span>
                        </div>
                    @endif
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-blue-900">{{ $ukm->name }}</h3>
                    <p class="text-blue-700 text-sm mt-1">{{ Str::limit($ukm->description, 150) }}</p>
                    <div class="flex items-center mt-2 text-sm text-blue-600">
                        <span class="mr-4">
                            <i class="fas fa-users mr-1"></i>
                            {{ $ukm->current_members }}/{{ $ukm->max_members }} anggota
                        </span>
                        <span>
                            <i class="fas fa-tag mr-1"></i>
                            {{ ucfirst($ukm->category) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <form action="{{ route('ukms.submit-registration', $ukm->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Formulir Pendaftaran</h2>
                <p class="text-sm text-gray-600 mt-1">Lengkapi informasi berikut untuk mendaftar sebagai anggota UKM</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Personal Info (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-md font-medium text-gray-900 mb-3">Informasi Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium ml-2">{{ auth()->user()->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">NIM:</span>
                            <span class="font-medium ml-2">{{ auth()->user()->nim }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium ml-2">{{ auth()->user()->email }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Jurusan:</span>
                            <span class="font-medium ml-2">{{ auth()->user()->major }}</span>
                        </div>
                    </div>
                </div>

                <!-- Previous Experience -->
                <div>
                    <label for="previous_experience" class="form-label">
                        Pengalaman Organisasi Sebelumnya
                        <span class="text-sm text-gray-500 font-normal">(jika ada)</span>
                    </label>
                    <textarea id="previous_experience" name="previous_experience" rows="4"
                              class="form-input @error('previous_experience') border-red-300 @enderror"
                              placeholder="Contoh:&#10;- Ketua OSIS SMA XYZ (2020-2021)&#10;- Anggota Pramuka Penggalang (2018-2020)&#10;- Volunteer Event ABC (2021)">{{ old('previous_experience') }}</textarea>
                    @error('previous_experience')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Tuliskan pengalaman organisasi, kepanitiaan, atau volunteer yang pernah Anda ikuti. Kosongkan jika tidak ada.</p>
                </div>

                <!-- Skills & Interests -->
                <div>
                    <label for="skills_interests" class="form-label">
                        Keahlian / Minat Khusus <span class="text-red-500">*</span>
                    </label>
                    <textarea id="skills_interests" name="skills_interests" rows="4" required
                              class="form-input @error('skills_interests') border-red-300 @enderror"
                              placeholder="Contoh:&#10;- Programming (Python, JavaScript)&#10;- Desain Grafis (Photoshop, Illustrator)&#10;- Public Speaking&#10;- Fotografi dan Videografi">{{ old('skills_interests') }}</textarea>
                    @error('skills_interests')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Tuliskan keahlian, minat, atau hobi yang Anda miliki yang relevan dengan UKM ini.</p>
                </div>

                <!-- Reason for Joining -->
                <div>
                    <label for="reason_joining" class="form-label">
                        Alasan Bergabung dengan UKM Ini <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason_joining" name="reason_joining" rows="4" required
                              class="form-input @error('reason_joining') border-red-300 @enderror"
                              placeholder="Jelaskan motivasi dan alasan Anda ingin bergabung dengan UKM ini...">{{ old('reason_joining') }}</textarea>
                    @error('reason_joining')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Jelaskan motivasi, tujuan, dan harapan Anda setelah bergabung dengan UKM ini.</p>
                </div>

                <!-- Preferred Division -->
                <div>
                    <label for="preferred_division" class="form-label">
                        Divisi yang Diminati <span class="text-red-500">*</span>
                    </label>
                    <select id="preferred_division" name="preferred_division" required
                            class="form-input @error('preferred_division') border-red-300 @enderror">
                        <option value="">Pilih Divisi</option>
                        <option value="programming" {{ old('preferred_division') == 'programming' ? 'selected' : '' }}>Programming</option>
                        <option value="design" {{ old('preferred_division') == 'design' ? 'selected' : '' }}>Design</option>
                        <option value="marketing" {{ old('preferred_division') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="event" {{ old('preferred_division') == 'event' ? 'selected' : '' }}>Event Organizer</option>
                        <option value="research" {{ old('preferred_division') == 'research' ? 'selected' : '' }}>Research & Development</option>
                        <option value="media" {{ old('preferred_division') == 'media' ? 'selected' : '' }}>Media & Documentation</option>
                        <option value="finance" {{ old('preferred_division') == 'finance' ? 'selected' : '' }}>Finance</option>
                        <option value="other" {{ old('preferred_division') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('preferred_division')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Pilih divisi yang paling sesuai dengan minat dan keahlian Anda.</p>
                </div>

                <!-- CV Upload -->
                <div>
                    <label for="cv_file" class="form-label">
                        Upload Curriculum Vitae (CV) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="cv_file" name="cv_file" required
                           accept=".pdf,.doc,.docx"
                           class="form-input @error('cv_file') border-red-300 @enderror">
                    @error('cv_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Upload CV Anda dalam format PDF, DOC, atau DOCX. Maksimal ukuran file 5MB.</p>
                </div>

                <!-- Terms & Conditions -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan semua informasi yang Anda berikan adalah benar dan akurat</li>
                                    <li>Proses seleksi akan dilakukan oleh pengurus UKM</li>
                                    <li>Anda akan mendapat notifikasi hasil seleksi melalui email</li>
                                    <li>Dengan mendaftar, Anda menyetujui untuk mengikuti aturan dan kegiatan UKM</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agreement Checkbox -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="agreement" name="agreement" type="checkbox" required
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="agreement" class="text-gray-700">
                            Saya menyetujui <a href="#" class="text-blue-600 hover:text-blue-500">syarat dan ketentuan</a> 
                            yang berlaku dan bersedia mengikuti seluruh kegiatan UKM dengan penuh tanggung jawab.
                            <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('ukms.show', $ukm->slug) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
