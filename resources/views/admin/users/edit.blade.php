@extends('admin.layouts.app')

@section('title', 'Edit Mahasiswa - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Mahasiswa</h1>
                <p class="text-gray-600 mt-2">Ubah informasi data mahasiswa</p>
            </div>
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                               value="{{ old('nim', $user->nim) }}"
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
                               value="{{ old('name', $user->name) }}"
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
                               value="{{ old('email', $user->email) }}"
                               class="form-input @error('email') border-red-300 @enderror"
                               placeholder="email@student.telkomuniversity.ac.id">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="form-label">Nomor Telepon *</label>
                        <input type="text" id="phone" name="phone" required
                               value="{{ old('phone', $user->phone) }}"
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
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Faculty -->
                    <div>
                        <label for="faculty" class="form-label">Fakultas *</label>
                        <select id="faculty" name="faculty" required
                                class="form-input @error('faculty') border-red-300 @enderror">
                            <option value="">Pilih Fakultas</option>
                            <option value="Fakultas Teknik Elektro" {{ old('faculty', $user->faculty) == 'Fakultas Teknik Elektro' ? 'selected' : '' }}>Fakultas Teknik Elektro</option>
                            <option value="Fakultas Informatika" {{ old('faculty', $user->faculty) == 'Fakultas Informatika' ? 'selected' : '' }}>Fakultas Informatika</option>
                            <option value="Fakultas Rekayasa Industri" {{ old('faculty', $user->faculty) == 'Fakultas Rekayasa Industri' ? 'selected' : '' }}>Fakultas Rekayasa Industri</option>
                            <option value="Fakultas Industri Kreatif" {{ old('faculty', $user->faculty) == 'Fakultas Industri Kreatif' ? 'selected' : '' }}>Fakultas Industri Kreatif</option>
                        </select>
                        @error('faculty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Major -->
                    <div>
                        <label for="major" class="form-label">Program Studi *</label>
                        <input type="text" id="major" name="major" required
                               value="{{ old('major', $user->major) }}"
                               class="form-input @error('major') border-red-300 @enderror"
                               placeholder="Sistem Informasi">
                        @error('major')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Batch -->
                    <div>
                        <label for="batch" class="form-label">Angkatan *</label>
                        <select id="batch" name="batch" required
                                class="form-input @error('batch') border-red-300 @enderror">
                            <option value="">Pilih Angkatan</option>
                            @for($year = date('Y'); $year >= 2018; $year--)
                                <option value="{{ $year }}" {{ old('batch', $user->batch) == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        @error('batch')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="form-label">Role *</label>
                        <select id="role" name="role" required
                                class="form-input @error('role') border-red-300 @enderror">
                            <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="ketua_ukm" {{ old('role', $user->role) == 'ketua_ukm' ? 'selected' : '' }}>Ketua UKM</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            <strong>Mahasiswa:</strong> Akses dashboard mahasiswa, join UKM, daftar event<br>
                            <strong>Ketua UKM:</strong> Dapat ditugaskan sebagai ketua UKM untuk mengelola UKM<br>
                            <strong>Admin:</strong> Akses penuh ke admin panel
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" required
                                class="form-input @error('status') border-red-300 @enderror">
                            <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="graduated" {{ old('status', $user->status) == 'graduated' ? 'selected' : '' }}>Lulus</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Profile Photo -->
                <div>
                    <label for="avatar" class="form-label">Foto Profil</label>
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            @if($user->avatar)
                                <img class="h-16 w-16 object-cover rounded-full"
                                     src="{{ asset('storage/' . $user->avatar) }}"
                                     alt="{{ $user->name }}">
                            @else
                                <div class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" id="avatar" name="avatar"
                                   accept="image/*"
                                   class="form-input @error('avatar') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau GIF. Maksimal 2MB.</p>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Reset -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reset Password</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" id="password" name="password"
                                   class="form-input @error('password') border-red-300 @enderror"
                                   placeholder="Kosongkan jika tidak ingin mengubah">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-input"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Kosongkan field password jika tidak ingin mengubah password mahasiswa.
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-secondary">
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
// Auto-generate email based on NIM
document.getElementById('nim').addEventListener('input', function() {
    const nim = this.value;
    const emailField = document.getElementById('email');

    if (nim && !emailField.value.includes('@')) {
        emailField.value = nim.toLowerCase() + '@student.telkomuniversity.ac.id';
    }
});

// Faculty-Major mapping (same as register page)
const facultyMajorMap = {
    'Fakultas Teknik Elektro': ['Program Studi D3 Teknik Telekomunikasi', 'Program Studi S1 Teknik Telekomunikasi'],
    'Fakultas Informatika': ['Program Studi S1 Teknologi Informasi'],
    'Fakultas Rekayasa Industri': ['Program Studi S1 Sistem Informasi'],
    'Fakultas Industri Kreatif': ['Program Studi S1 Desain Komunikasi Visual']
};

document.getElementById('faculty').addEventListener('change', function() {
    const faculty = this.value;
    const majorField = document.getElementById('major');

    if (faculty && facultyMajorMap[faculty]) {
        // Convert input to datalist for suggestions
        majorField.setAttribute('list', 'major-options');

        // Create or update datalist
        let datalist = document.getElementById('major-options');
        if (!datalist) {
            datalist = document.createElement('datalist');
            datalist.id = 'major-options';
            majorField.parentNode.appendChild(datalist);
        }

        datalist.innerHTML = '';
        facultyMajorMap[faculty].forEach(major => {
            const option = document.createElement('option');
            option.value = major;
            datalist.appendChild(option);
        });
    }
});
</script>
@endsection
