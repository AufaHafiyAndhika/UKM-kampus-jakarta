@extends('admin.layouts.app')

@section('title', 'Angkat Ketua UKM')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Angkat Ketua UKM</h1>
            <p class="text-gray-600">Pilih mahasiswa untuk diangkat sebagai ketua UKM</p>
        </div>
        <a href="{{ route('admin.ketua-ukm.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.ketua-ukm.store') }}" method="POST">
            @csrf

            <!-- Student Selection -->
            <div class="mb-6">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Mahasiswa <span class="text-red-500">*</span>
                </label>
                <select id="user_id" name="user_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-300 @enderror">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->nim }}) - {{ $student->major }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Hanya mahasiswa dengan status aktif yang dapat diangkat sebagai ketua UKM.
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Mahasiswa yang dipilih akan diubah rolenya menjadi "Ketua UKM"</li>
                                <li>Ketua UKM dapat mengelola UKM yang ditugaskan kepadanya</li>
                                <li>Ketua UKM dapat membuat event untuk UKM yang dipimpin</li>
                                <li>Setelah diangkat, Anda dapat menugaskan UKM kepada ketua UKM ini</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.ketua-ukm.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>Angkat sebagai Ketua UKM
                </button>
            </div>
        </form>
    </div>

    @if($students->count() == 0)
    <!-- No Students Available -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Tidak Ada Mahasiswa Tersedia</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Semua mahasiswa aktif sudah memiliki role ketua UKM atau admin.</p>
                    <p class="mt-1">
                        <a href="{{ route('admin.users.index') }}" class="font-medium underline">
                            Kelola mahasiswa
                        </a> untuk melihat daftar lengkap.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Enhanced select with search functionality
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('user_id');
    
    // Add search functionality if there are many options
    if (select.options.length > 10) {
        // You can implement a search functionality here
        // For now, we'll just add a placeholder
        select.setAttribute('data-placeholder', 'Ketik untuk mencari mahasiswa...');
    }
});
</script>
@endsection
