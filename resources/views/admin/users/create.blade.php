@extends('admin.layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Mahasiswa</h1>
                <p class="text-gray-600 mt-2">Tambahkan data mahasiswa baru ke sistem</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Mahasiswa</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIM -->
                    <div>
                        <label for="nim" class="form-label">NIM *</label>
                        <input type="text" id="nim" name="nim" required 
                               value="{{ old('nim') }}"
                               class="form-input @error('nim') border-red-300 @enderror"
                               placeholder="Nomor Induk Mahasiswa">
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" required 
                               value="{{ old('name') }}"
                               class="form-input @error('name') border-red-300 @enderror"
                               placeholder="Nama lengkap mahasiswa">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" required 
                               value="{{ old('email') }}"
                               class="form-input @error('email') border-red-300 @enderror"
                               placeholder="email@student.telkomuniversity.ac.id">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" 
                               value="{{ old('phone') }}"
                               class="form-input @error('phone') border-red-300 @enderror"
                               placeholder="081234567890">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="form-label">Jenis Kelamin *</label>
                        <select id="gender" name="gender" required 
                                class="form-input @error('gender') border-red-300 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Batch -->
                    <div>
                        <label for="batch" class="form-label">Angkatan *</label>
                        <input type="text" id="batch" name="batch" required 
                               value="{{ old('batch') }}"
                               class="form-input @error('batch') border-red-300 @enderror"
                               placeholder="2024">
                        @error('batch')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akademik</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Faculty -->
                        <div>
                            <label for="faculty" class="form-label">Fakultas *</label>
                            <select id="faculty" name="faculty" required
                                    class="form-input @error('faculty') border-red-300 @enderror">
                                <option value="">Pilih Fakultas</option>
                                <option value="Fakultas Teknik Elektro" {{ old('faculty') == 'Fakultas Teknik Elektro' ? 'selected' : '' }}>Fakultas Teknik Elektro</option>
                                <option value="Fakultas Informatika" {{ old('faculty') == 'Fakultas Informatika' ? 'selected' : '' }}>Fakultas Informatika</option>
                                <option value="Fakultas Rekayasa Industri" {{ old('faculty') == 'Fakultas Rekayasa Industri' ? 'selected' : '' }}>Fakultas Rekayasa Industri</option>
                                <option value="Fakultas Industri Kreatif" {{ old('faculty') == 'Fakultas Industri Kreatif' ? 'selected' : '' }}>Fakultas Industri Kreatif</option>
                            </select>
                            @error('faculty')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Major -->
                        <div>
                            <label for="major" class="form-label">Program Studi *</label>
                            <select id="major" name="major" required disabled
                                    class="form-input @error('major') border-red-300 @enderror">
                                <option value="">Pilih program studi</option>
                            </select>
                            @error('major')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Keamanan Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" id="password" name="password" required 
                                   class="form-input @error('password') border-red-300 @enderror"
                                   placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="form-input"
                                   placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                        Batal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Mahasiswa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Faculty-Major mapping (same as register page)
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

    // Initialize on page load
    updateMajorOptions();

    // Update when faculty changes
    facultySelect.addEventListener('change', updateMajorOptions);

    // Auto-generate email based on NIM
    document.getElementById('nim').addEventListener('input', function() {
        const nim = this.value;
        const emailField = document.getElementById('email');

        if (nim && !emailField.value.includes('@')) {
            emailField.value = nim.toLowerCase() + '@student.telkomuniversity.ac.id';
        }
    });
});
</script>
@endsection
