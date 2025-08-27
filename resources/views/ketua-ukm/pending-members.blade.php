@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Penerimaan Member Baru</h1>
                <p class="text-gray-600">{{ $ukm->name }}</p>
                
                <!-- Debug Info -->
                @if(config('app.debug'))
                    <div class="mt-2 p-3 bg-yellow-100 border border-yellow-300 rounded text-xs">
                        <strong>DEBUG INFO:</strong><br>
                        UKM ID: {{ $ukm->id }}<br>
                        UKM Name: {{ $ukm->name }}<br>
                        Leader ID: {{ $ukm->leader_id }}<br>
                        Current User ID: {{ auth()->id() }}<br>
                        Current User Email: {{ auth()->user()->email }}<br>
                        Pending Count: {{ $pendingMembers->count() }}
                    </div>
                @endif
            </div>
            <a href="{{ route('ketua-ukm.dashboard') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-user-clock text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Menunggu Persetujuan</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $pendingMembers->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Anggota Aktif</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $ukm->activeMembers()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Kapasitas</p>
                    <p class="text-2xl font-bold text-green-900">{{ $ukm->activeMembers()->count() }}/{{ $ukm->max_members ?? 'Unlimited' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Members List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user-clock mr-2 text-orange-600"></i>
                Pendaftar Menunggu Persetujuan ({{ $pendingMembers->count() }})
            </h2>
        </div>

        @if($pendingMembers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mahasiswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kontak
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Daftar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Divisi Pilihan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingMembers as $member)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->student_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $member->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->phone ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \App\Helpers\DateHelper::tableFormat($member->pivot->applied_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->pivot->preferred_division ?? 'Tidak disebutkan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Detail Button -->
                                        <button onclick="showMemberDetails({{ $member->id }})" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>
                                        
                                        <!-- Approve Button -->
                                        <form action="{{ route('ketua-ukm.pending-members.approve', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin menerima {{ $member->name }}?')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                                <i class="fas fa-check mr-1"></i>Terima
                                            </button>
                                        </form>
                                        
                                        <!-- Reject Button -->
                                        <form action="{{ route('ketua-ukm.pending-members.reject', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin menolak {{ $member->name }}? Tindakan ini tidak dapat dibatalkan.')"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-user-clock text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pendaftar baru</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada mahasiswa yang mendaftar ke UKM Anda.</p>
            </div>
        @endif
    </div>
</div>

<!-- Member Details Modal -->
<div id="memberDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Pendaftar</h3>
                <button onclick="closeMemberDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="memberDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>



<script>
function showMemberDetails(memberId) {
    const url = `/ketua-ukm/pending-members/${memberId}/details`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pengalaman Organisasi Sebelumnya:</label>
                        <p class="mt-1 text-sm text-gray-900">${data.previous_experience || 'Tidak ada'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keahlian/Minat:</label>
                        <p class="mt-1 text-sm text-gray-900">${data.skills_interests || 'Tidak disebutkan'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alasan Bergabung:</label>
                        <p class="mt-1 text-sm text-gray-900">${data.reason_joining || 'Tidak disebutkan'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Divisi yang Diminati:</label>
                        <p class="mt-1 text-sm text-gray-900">${data.preferred_division || 'Tidak disebutkan'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mendaftar:</label>
                        <p class="mt-1 text-sm text-gray-900">${data.applied_at || 'N/A'}</p>
                    </div>
                    ${data.cv_file ? `
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CV:</label>
                        <a href="/storage/${data.cv_file}" target="_blank" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-download mr-1"></i>Download CV
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('memberDetailsContent').innerHTML = content;
            document.getElementById('memberDetailsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail anggota');
        });
}

function closeMemberDetailsModal() {
    document.getElementById('memberDetailsModal').classList.add('hidden');
}



// Close modals when clicking outside
window.onclick = function(event) {
    const memberModal = document.getElementById('memberDetailsModal');

    if (event.target === memberModal) {
        closeMemberDetailsModal();
    }
}
</script>
@endsection
