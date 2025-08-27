@extends('admin.layouts.app')

@section('title', 'Edit Ketua UKM - ' . $ketuaUkm->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Ketua UKM</h1>
            <p class="text-gray-600">Perbarui informasi ketua UKM {{ $ketuaUkm->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ketua-ukm.show', $ketuaUkm) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.ketua-ukm.update', $ketuaUkm) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Personal</h3>
                        
                        <!-- NIM -->
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nim" name="nim" required
                                   value="{{ old('nim', $ketuaUkm->nim) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nim') border-red-300 @enderror"
                                   placeholder="Masukkan NIM...">
                            @error('nim')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name', $ketuaUkm->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                                   placeholder="Masukkan nama lengkap...">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   value="{{ old('email', $ketuaUkm->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                                   placeholder="Masukkan email...">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                No. Telepon
                            </label>
                            <input type="text" id="phone" name="phone"
                                   value="{{ old('phone', $ketuaUkm->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror"
                                   placeholder="Masukkan no. telepon...">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-300 @enderror">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="male" {{ old('gender', $ketuaUkm->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', $ketuaUkm->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Academic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Akademik</h3>
                        
                        <!-- Faculty -->
                        <div>
                            <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="faculty" name="faculty" required
                                   value="{{ old('faculty', $ketuaUkm->faculty) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('faculty') border-red-300 @enderror"
                                   placeholder="Masukkan fakultas...">
                            @error('faculty')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Major -->
                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-2">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="major" name="major" required
                                   value="{{ old('major', $ketuaUkm->major) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('major') border-red-300 @enderror"
                                   placeholder="Masukkan program studi...">
                            @error('major')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Batch -->
                        <div>
                            <label for="batch" class="block text-sm font-medium text-gray-700 mb-2">
                                Angkatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="batch" name="batch" required
                                   value="{{ old('batch', $ketuaUkm->batch) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('batch') border-red-300 @enderror"
                                   placeholder="Contoh: 2020"
                                   maxlength="4">
                            @error('batch')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror">
                                <option value="">-- Pilih Status --</option>
                                <option value="active" {{ old('status', $ketuaUkm->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $ketuaUkm->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="suspended" {{ old('status', $ketuaUkm->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-300 @enderror"
                                    onchange="handleRoleChange()">
                                <option value="">-- Pilih Role --</option>
                                <option value="student" {{ old('role', $ketuaUkm->role) === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="ketua_ukm" {{ old('role', $ketuaUkm->role) === 'ketua_ukm' ? 'selected' : '' }}>Ketua UKM</option>
                                <option value="admin" {{ old('role', $ketuaUkm->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="role-warning" class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Peringatan:</strong> Mengubah role ke "Mahasiswa" akan menghapus akses ketua UKM dan menghapus assignment dari semua UKM yang dipimpin.
                                        </p>
                                    </div>
                                </div>
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
                                <h3 class="text-sm font-medium text-blue-800">Status Ketua UKM Saat Ini</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $ketuaUkm->role)) }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($ketuaUkm->status) }}</p>
                                    <p><strong>UKM yang Dipimpin:</strong> {{ $ketuaUkm->ledUkms->count() }} UKM</p>
                                    @if($ketuaUkm->ledUkms->count() > 0)
                                        <div class="mt-2">
                                            <p><strong>Daftar UKM:</strong></p>
                                            <ul class="list-disc list-inside ml-2">
                                                @foreach($ketuaUkm->ledUkms as $ukm)
                                                    <li>{{ $ukm->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <p><strong>Terdaftar:</strong> {{ $ketuaUkm->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($ketuaUkm->role === 'ketua_ukm' && $ketuaUkm->ledUkms->count() > 0)
                    <!-- Warning for Role Change -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Peringatan Perubahan Role</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>User ini saat ini memimpin <strong>{{ $ketuaUkm->ledUkms->count() }} UKM</strong>.</p>
                                    <p>Jika role diubah ke "Mahasiswa", maka:</p>
                                    <ul class="list-disc list-inside ml-2 mt-1">
                                        <li>User akan kehilangan akses ketua UKM</li>
                                        <li>Semua UKM yang dipimpin akan kehilangan ketua</li>
                                        <li>Perlu menunjuk ketua baru untuk setiap UKM</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('admin.ketua-ukm.show', $ketuaUkm) }}" 
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
@endsection

@push('scripts')
<script>
function handleRoleChange() {
    const roleSelect = document.getElementById('role');
    const warningDiv = document.getElementById('role-warning');
    const currentRole = '{{ $ketuaUkm->role }}';
    const selectedRole = roleSelect.value;

    // Show warning if changing from ketua_ukm to student
    if (currentRole === 'ketua_ukm' && selectedRole === 'student') {
        warningDiv.classList.remove('hidden');
    } else {
        warningDiv.classList.add('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleRoleChange();
});
</script>
@endpush
