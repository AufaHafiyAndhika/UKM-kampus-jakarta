@extends('layouts.app')

@section('title', 'Kelola Anggota UKM')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Anggota UKM</h1>
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
                        Pending Count: {{ $pendingMembers->count() }}<br>
                        Active Count: {{ $activeMembers->count() }}<br>
                        Rejected Count: {{ $rejectedMembers->count() }}
                    </div>
                @endif
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">
                    {{ $ukm->current_members }}/{{ $ukm->max_members }} anggota
                </span>
                <a href="{{ route('ketua-ukm.dashboard') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Anggota</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeMembers->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Menunggu Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingMembers->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Diterima Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentlyApproved->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Ditolak Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentlyRejected->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('pending')" id="tab-pending" class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Menunggu Review ({{ $pendingMembers->count() }})
                </button>
                <button onclick="showTab('active')" id="tab-active" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Anggota Aktif ({{ $activeMembers->count() }})
                </button>
                <button onclick="showTab('rejected')" id="tab-rejected" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Ditolak ({{ $rejectedMembers->count() }})
                </button>
            </nav>
        </div>

        <!-- Pending Members Tab -->
        <div id="content-pending" class="tab-content">
            <div class="p-6">
                @if($pendingMembers->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingMembers as $member)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Menunggu Review
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-3">
                                            <div><strong>NIM:</strong> {{ $member->nim }}</div>
                                            <div><strong>Email:</strong> {{ $member->email }}</div>
                                            <div><strong>Jurusan:</strong> {{ $member->major }}</div>
                                            <div><strong>Divisi Diminati:</strong> {{ ucfirst($member->pivot->preferred_division) }}</div>
                                            <div><strong>Tanggal Daftar:</strong> {{ $member->pivot->applied_at ? $member->pivot->applied_at->format('d M Y H:i') : '-' }}</div>
                                        </div>
                                        
                                        <!-- Expandable Details -->
                                        <div class="border-t pt-3 mt-3">
                                            <button onclick="toggleDetails('member-{{ $member->id }}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-chevron-down mr-1" id="chevron-{{ $member->id }}"></i>Lihat Detail Pendaftaran
                                            </button>
                                            <div id="member-{{ $member->id }}" class="hidden mt-3 space-y-4 bg-gray-50 p-4 rounded-lg">
                                                @if($member->pivot->previous_experience)
                                                    <div class="border-b border-gray-200 pb-3">
                                                        <strong class="text-gray-700 block mb-2">📋 Pengalaman Organisasi Sebelumnya:</strong>
                                                        <div class="bg-white p-3 rounded border text-gray-600 whitespace-pre-line">{{ $member->pivot->previous_experience }}</div>
                                                    </div>
                                                @endif
                                                <div class="border-b border-gray-200 pb-3">
                                                    <strong class="text-gray-700 block mb-2">🎯 Keahlian/Minat Khusus:</strong>
                                                    <div class="bg-white p-3 rounded border text-gray-600 whitespace-pre-line">{{ $member->pivot->skills_interests }}</div>
                                                </div>
                                                <div class="border-b border-gray-200 pb-3">
                                                    <strong class="text-gray-700 block mb-2">💭 Alasan Bergabung:</strong>
                                                    <div class="bg-white p-3 rounded border text-gray-600 whitespace-pre-line">{{ $member->pivot->reason_joining }}</div>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <strong class="text-gray-700 block mb-2">🏢 Divisi yang Diminati:</strong>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                            {{ ucfirst($member->pivot->preferred_division) }}
                                                        </span>
                                                    </div>
                                                    @if($member->pivot->cv_file)
                                                        <div>
                                                            <strong class="text-gray-700 block mb-2">📄 Curriculum Vitae:</strong>
                                                            <a href="{{ asset('storage/' . $member->pivot->cv_file) }}" target="_blank"
                                                               class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                                                <i class="fas fa-download mr-2"></i>Download CV
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                                                        <div class="text-sm">
                                                            <strong class="text-blue-800">Informasi Pendaftaran:</strong>
                                                            <p class="text-blue-700 mt-1">
                                                                Mendaftar pada: {{ $member->pivot->applied_at ? $member->pivot->applied_at->format('d M Y, H:i') : '-' }} WIB
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2 ml-4">
                                        <button onclick="showApproveModal({{ $member->id }}, '{{ $member->name }}')" class="btn-success">
                                            <i class="fas fa-check mr-1"></i>Terima
                                        </button>
                                        <button onclick="showRejectModal({{ $member->id }}, '{{ $member->name }}')" class="btn-danger">
                                            <i class="fas fa-times mr-1"></i>Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Tidak ada pendaftaran yang menunggu review</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Active Members Tab -->
        <div id="content-active" class="tab-content hidden">
            <div class="p-6">
                @if($activeMembers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($activeMembers as $member)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-blue-600">{{ substr($member->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $member->nim }} - {{ $member->major }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($member->pivot->preferred_division ?? 'Umum') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $member->pivot->approved_at ? $member->pivot->approved_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="showMemberDetails({{ $member->id }}, '{{ $member->name }}', '{{ $member->nim }}', '{{ $member->major }}', '{{ $member->pivot->preferred_division ?? 'Umum' }}', '{{ $member->pivot->joined_date ? $member->pivot->joined_date->format('d M Y') : '-' }}')"
                                                        class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye mr-1"></i>Detail
                                                </button>
                                                <button onclick="showRemoveModal({{ $member->id }}, '{{ $member->name }}')" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-user-minus mr-1"></i>Keluarkan
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Belum ada anggota aktif</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rejected Members Tab -->
        <div id="content-rejected" class="tab-content hidden">
            <div class="p-6">
                @if($rejectedMembers->count() > 0)
                    <div class="space-y-4">
                        @foreach($rejectedMembers as $member)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $member->nim }} - {{ $member->major }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Ditolak: {{ $member->pivot->rejected_at ? $member->pivot->rejected_at->format('d M Y H:i') : '-' }}
                                        </p>
                                        @if($member->pivot->rejection_reason)
                                            <p class="text-sm text-red-600 mt-1">
                                                <strong>Alasan:</strong> {{ $member->pivot->rejection_reason }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        Ditolak
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-times text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Tidak ada anggota yang ditolak</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Terima Anggota</h3>
        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menerima <span id="approveMemberName" class="font-semibold"></span> sebagai anggota UKM?</p>
        <form id="approveForm" method="POST">
            @csrf
            @method('PUT')
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideApproveModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-success">Terima</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Anggota</h3>
        <p class="text-sm text-gray-600 mb-4">Berikan alasan penolakan untuk <span id="rejectMemberName" class="font-semibold"></span>:</p>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Alasan penolakan..." required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideRejectModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Member Detail Modal -->
<div id="memberDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Detail Anggota</h3>
            <button onclick="hideMemberDetailModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="memberDetailContent" class="space-y-4">
            <!-- Content will be populated by JavaScript -->
        </div>

        <div class="flex justify-end mt-6">
            <button onclick="hideMemberDetailModal()" class="btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<!-- Remove Modal -->
<div id="removeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Keluarkan Anggota</h3>
        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin mengeluarkan <span id="removeMemberName" class="font-semibold"></span> dari UKM?</p>
        <form id="removeForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan (opsional):</label>
                <textarea name="removal_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Alasan mengeluarkan anggota..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideRemoveModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-danger">Keluarkan</button>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

function toggleDetails(elementId) {
    const element = document.getElementById(elementId);
    const chevron = document.getElementById('chevron-' + elementId.replace('member-', ''));

    element.classList.toggle('hidden');

    if (element.classList.contains('hidden')) {
        chevron.classList.remove('fa-chevron-up');
        chevron.classList.add('fa-chevron-down');
    } else {
        chevron.classList.remove('fa-chevron-down');
        chevron.classList.add('fa-chevron-up');
    }
}

function showApproveModal(memberId, memberName) {
    document.getElementById('approveMemberName').textContent = memberName;
    document.getElementById('approveForm').action = `/ketua-ukm/members/${memberId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveModal').classList.add('flex');
}

function hideApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approveModal').classList.remove('flex');
}

function showRejectModal(memberId, memberName) {
    document.getElementById('rejectMemberName').textContent = memberName;
    document.getElementById('rejectForm').action = `/ketua-ukm/members/${memberId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

function showRemoveModal(memberId, memberName) {
    document.getElementById('removeMemberName').textContent = memberName;
    document.getElementById('removeForm').action = `/ketua-ukm/members/${memberId}/remove`;
    document.getElementById('removeModal').classList.remove('hidden');
    document.getElementById('removeModal').classList.add('flex');
}

function hideRemoveModal() {
    document.getElementById('removeModal').classList.add('hidden');
    document.getElementById('removeModal').classList.remove('flex');
}

function showMemberDetails(memberId, memberName, memberNim, memberMajor, memberDivision, joinedDate) {
    // Fetch member details via AJAX
    fetch(`/ketua-ukm/members/${memberId}/details`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('memberDetailContent');
            content.innerHTML = `
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold text-blue-600">${memberName.substring(0, 2).toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-blue-900">${memberName}</h4>
                            <p class="text-blue-700">${memberNim} - ${memberMajor}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded">
                        <strong class="text-gray-700">🏢 Divisi:</strong>
                        <p class="text-gray-600 mt-1">${memberDivision}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <strong class="text-gray-700">📅 Bergabung:</strong>
                        <p class="text-gray-600 mt-1">${joinedDate}</p>
                    </div>
                </div>

                ${data.previous_experience ? `
                    <div>
                        <strong class="text-gray-700 block mb-2">📋 Pengalaman Organisasi:</strong>
                        <div class="bg-white p-3 rounded border text-gray-600 whitespace-pre-line">${data.previous_experience}</div>
                    </div>
                ` : ''}

                <div>
                    <strong class="text-gray-700 block mb-2">🎯 Keahlian/Minat:</strong>
                    <div class="bg-white p-3 rounded border text-gray-600 whitespace-pre-line">${data.skills_interests || 'Tidak ada data'}</div>
                </div>

                <div>
                    <strong class="text-gray-700 block mb-2">💭 Alasan Bergabung:</strong>
                    <div class="bg-white p-3 rounded border text-gray-600 whitespace-pre-line">${data.reason_joining || 'Tidak ada data'}</div>
                </div>

                ${data.cv_file ? `
                    <div>
                        <strong class="text-gray-700 block mb-2">📄 Curriculum Vitae:</strong>
                        <a href="/storage/${data.cv_file}" target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                            <i class="fas fa-download mr-2"></i>Download CV
                        </a>
                    </div>
                ` : ''}

                <div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        <div class="text-sm">
                            <strong class="text-green-800">Status:</strong>
                            <p class="text-green-700 mt-1">Anggota Aktif sejak ${joinedDate}</p>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('memberDetailModal').classList.remove('hidden');
            document.getElementById('memberDetailModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail anggota');
        });
}

function hideMemberDetailModal() {
    document.getElementById('memberDetailModal').classList.add('hidden');
    document.getElementById('memberDetailModal').classList.remove('flex');
}
</script>
@endsection
