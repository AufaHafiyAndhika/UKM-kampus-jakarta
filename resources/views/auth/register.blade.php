@extends('layouts.app')

@section('title', '- Daftar')
@section('description', 'Daftar akun baru UKM Telkom Jakarta untuk bergabung dengan berbagai Unit Kegiatan Mahasiswa.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UKM Telkom" class="h-12 w-auto">
            </div>
            <h2 class="mt-6 text-3xl font-display font-bold text-gray-900">
                Daftar Akun Baru
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                    Masuk di sini
                </a>
            </p>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nim" class="form-label">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="nim" 
                                name="nim" 
                                type="text" 
                                required 
                                class="form-input @error('nim') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Contoh: 1234567890"
                                value="{{ old('nim') }}"
                            >
                            @error('nim')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="name" 
                                name="name" 
                                type="text" 
                                required 
                                class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Nama lengkap Anda"
                                value="{{ old('name') }}"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="form-label">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="nama@student.telkomuniversity.ac.id"
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="form-label">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="phone" 
                                name="phone" 
                                type="tel" 
                                required 
                                class="form-input @error('phone') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="08123456789"
                                value="{{ old('phone') }}"
                            >
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="form-label">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="gender" 
                                name="gender" 
                                required 
                                class="form-input @error('gender') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            >
                                <option value="">Pilih jenis kelamin</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="batch" class="form-label">
                                Angkatan <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="batch" 
                                name="batch" 
                                type="text" 
                                required 
                                class="form-input @error('batch') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="2024"
                                value="{{ old('batch') }}"
                                maxlength="4"
                            >
                            @error('batch')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akademik</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="faculty" class="form-label">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="faculty"
                                name="faculty"
                                required
                                class="form-input @error('faculty') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            >
                                <option value="">Pilih fakultas</option>
                                <option value="Fakultas Teknik Elektro" {{ old('faculty') == 'Fakultas Teknik Elektro' ? 'selected' : '' }}>Fakultas Teknik Elektro</option>
                                <option value="Fakultas Rekayasa Industri" {{ old('faculty') == 'Fakultas Rekayasa Industri' ? 'selected' : '' }}>Fakultas Rekayasa Industri</option>
                                <option value="Fakultas Informatika" {{ old('faculty') == 'Fakultas Informatika' ? 'selected' : '' }}>Fakultas Informatika</option>
                                <option value="Fakultas Industri Kreatif" {{ old('faculty') == 'Fakultas Industri Kreatif' ? 'selected' : '' }}>Fakultas Industri Kreatif</option>
                                <option value="Fakultas Ekonomi dan Bisnis" {{ old('faculty') == 'Fakultas Ekonomi dan Bisnis' ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis</option>
                                <option value="Fakultas Komunikasi dan Bisnis" {{ old('faculty') == 'Fakultas Komunikasi dan Bisnis' ? 'selected' : '' }}>Fakultas Komunikasi dan Bisnis</option>
                                <option value="Fakultas Industri Kreatif" {{ old('faculty') == 'Fakultas Industri Kreatif' ? 'selected' : '' }}>Fakultas Industri Kreatif</option>
                                <option value="Fakultas Ilmu Terapan" {{ old('faculty') == 'Fakultas Ilmu Terapan' ? 'selected' : '' }}>Fakultas Ilmu Terapan</option>
                            </select>
                            @error('faculty')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="major" class="form-label">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="major"
                                name="major"
                                required
                                class="form-input @error('major') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            >
                                <option value="">Pilih fakultas terlebih dahulu</option>
                            </select>
                            @error('major')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Keamanan Akun</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="form-label">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="form-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Minimal 8 karakter"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                class="form-input"
                                placeholder="Ulangi password"
                            >
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="terms" 
                            name="terms" 
                            type="checkbox" 
                            required
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-700">
                            Saya menyetujui 
                            <a href="#" class="text-primary-600 hover:text-primary-500 font-medium">Syarat dan Ketentuan</a> 
                            serta 
                            <a href="#" class="text-primary-600 hover:text-primary-500 font-medium">Kebijakan Privasi</a>
                            <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
                    >
                        Daftar Akun
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Persyaratan Pendaftaran
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Hanya mahasiswa aktif Telkom University yang dapat mendaftar</li>
                            <li>Gunakan email resmi @student.telkomuniversity.ac.id</li>
                            <li>NIM harus valid dan belum terdaftar</li>
                            <li>Semua data yang diisi harus sesuai dengan data akademik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Faculty-Major mapping
    const facultyMajorMap = {
        'Fakultas Teknik Elektro': ['Program Studi D3 Teknik Telekomunikasi', 'Program Studi S1 Teknik Telekomunikasi'],
        'Fakultas Informatika': ['Program Studi S1 Teknologi Informasi'],
        'Fakultas Rekayasa Industri': ['Program Studi S1 Sistem Informasi'],
        'Fakultas Industri Kreatif': ['Program Studi S1 Desain Komunikasi Visual']
    };

    const facultySelect = document.getElementById('faculty');
    const majorSelect = document.getElementById('major');
    const oldMajor = '{{ old("major") }}';

    function updateMajorOptions() {
        const selectedFaculty = facultySelect.value;

        // Clear current options
        majorSelect.innerHTML = '<option value="">Pilih program studi</option>';

        if (selectedFaculty && facultyMajorMap[selectedFaculty]) {
            const majors = facultyMajorMap[selectedFaculty];
            majors.forEach(function(major) {
                const option = document.createElement('option');
                option.value = major;
                option.textContent = major;

                // Restore old value if exists
                if (oldMajor === major) {
                    option.selected = true;
                }

                majorSelect.appendChild(option);
            });
            majorSelect.disabled = false;
        } else {
            majorSelect.disabled = true;
        }
    }

    // Listen for faculty changes
    facultySelect.addEventListener('change', updateMajorOptions);

    // Initialize on page load
    updateMajorOptions();
});
</script>

@endsection
