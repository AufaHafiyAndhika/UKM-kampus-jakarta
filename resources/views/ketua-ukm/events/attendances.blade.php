@extends('layouts.app')

@section('title', 'Verifikasi Absensi - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Absensi Event</h1>
                <p class="text-gray-600">{{ $event->title }}</p>
            </div>
            <a href="{{ route('ketua-ukm.events') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Event
            </a>
        </div>
    </div>

    <!-- Event Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Informasi Event</h3>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><i class="fas fa-calendar mr-2"></i>{{ $event->start_datetime->format('d M Y, H:i') }} - {{ $event->end_datetime->format('d M Y, H:i') }}</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $event->location }}</p>
                    <p><i class="fas fa-users mr-2"></i>{{ $event->current_participants }} peserta terdaftar</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Status Absensi</h3>
                <div class="space-y-1 text-sm">
                    @php
                        $totalAttendances = $attendances->total();
                        $submittedCount = $attendances->where('status', 'present')->count();
                        $verifiedCount = $attendances->where('verification_status', 'verified')->count();
                        $pendingCount = $attendances->where('verification_status', 'pending')->count();
                    @endphp
                    <p class="text-blue-600"><i class="fas fa-clock mr-2"></i>{{ $pendingCount }} menunggu verifikasi</p>
                    <p class="text-green-600"><i class="fas fa-check mr-2"></i>{{ $verifiedCount }} sudah diverifikasi</p>
                    <p class="text-gray-600"><i class="fas fa-list mr-2"></i>{{ $totalAttendances }} total absensi</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aksi Cepat</h3>
                <div class="space-y-2">
                    <button onclick="openBulkModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-check-double mr-2"></i>Validasi Serentak
                    </button>
                    <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>Lihat Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('ketua-ukm.events.attendances', $event) }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-48">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Peserta</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Nama atau NIM mahasiswa...">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Absensi</label>
                <select id="status" name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Submit</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Sudah Submit</option>
                </select>
            </div>
            <div>
                <label for="verification" class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                <select id="verification" name="verification" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Verifikasi</option>
                    <option value="pending" {{ request('verification') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="verified" {{ request('verification') === 'verified' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="rejected" {{ request('verification') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('ketua-ukm.events.attendances', $event) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Attendances List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Absensi Peserta</h2>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="selectAll" class="text-sm text-gray-700">Pilih Semua</label>
                </div>
            </div>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Absensi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Kehadiran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Submit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="attendance_ids[]" value="{{ $attendance->id }}" 
                                           class="attendance-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ substr($attendance->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->user->student_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($attendance->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-clock mr-1"></i>Belum Submit
                                        </span>
                                    @elseif($attendance->status === 'present')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Tidak Hadir
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($attendance->proof_file)
                                        <button onclick="viewProof('{{ asset('storage/' . $attendance->proof_file) }}', '{{ $attendance->user->name }}')" 
                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-image mr-1"></i>Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">Belum upload</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $attendance->submitted_at ? $attendance->submitted_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($attendance->verification_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Menunggu
                                        </span>
                                    @elseif($attendance->verification_status === 'verified')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Diverifikasi
                                        </span>
                                    @elseif($attendance->verification_status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($attendance->status === 'present' && $attendance->verification_status === 'pending')
                                        <div class="flex gap-2">
                                            <button onclick="verifyAttendance({{ $attendance->id }}, 'verify')" 
                                                    class="text-green-600 hover:text-green-800">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="verifyAttendance({{ $attendance->id }}, 'reject')" 
                                                    class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button onclick="viewDetails({{ $attendance->id }})" 
                                                    class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    @elseif($attendance->verification_status !== 'pending')
                                        <button onclick="viewDetails({{ $attendance->id }})" 
                                                class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <i class="fas fa-clipboard-list text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Absensi</h3>
                <p class="text-gray-500">Belum ada peserta yang mengisi absensi untuk event ini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Proof Modal -->
<div id="proofModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="proofModalTitle">Bukti Kehadiran</h3>
            <div class="text-center">
                <img id="proofImage" src="" alt="Bukti Kehadiran" class="max-w-full h-64 object-contain mx-auto rounded">
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeProofModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div id="verificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="verificationForm" method="POST">
            @csrf
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="verificationModalTitle">Verifikasi Absensi</h3>
                <div class="mb-4">
                    <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea id="verification_notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan verifikasi..."></textarea>
                </div>
                <input type="hidden" name="action" id="verificationAction">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeVerificationModal()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" id="verificationSubmitBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Verifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Absensi Peserta</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Student Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <h4 class="font-medium text-gray-900 mb-2">Informasi Peserta</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Nama:</span>
                        <span id="detailStudentName" class="font-medium ml-2">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600">NIM:</span>
                        <span id="detailStudentId" class="font-medium ml-2">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Waktu Submit:</span>
                        <span id="detailSubmittedAt" class="font-medium ml-2">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <span id="detailStatus" class="font-medium ml-2">-</span>
                    </div>
                </div>
            </div>

            <!-- Proof Image -->
            <div class="mb-4">
                <h4 class="font-medium text-gray-900 mb-2">Bukti Kehadiran</h4>
                <div class="border rounded-lg p-4 text-center">
                    <img id="detailProofImage" src="" alt="Bukti Kehadiran"
                         class="max-w-full max-h-96 mx-auto rounded-lg hidden">
                    <p id="noProofText" class="text-gray-500 hidden">Tidak ada bukti kehadiran</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <h4 class="font-medium text-gray-900 mb-2">Catatan Peserta</h4>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p id="detailNotes" class="text-sm text-gray-700">-</p>
                </div>
            </div>

            <!-- Verification Notes (if already verified/rejected) -->
            <div id="verificationNotesSection" class="mb-4 hidden">
                <h4 class="font-medium text-gray-900 mb-2">Catatan Verifikasi</h4>
                <div class="bg-yellow-50 rounded-lg p-3">
                    <p id="detailVerificationNotes" class="text-sm text-gray-700">-</p>
                </div>
            </div>

            <!-- Verification Section (for pending status) -->
            <div id="verificationSection" class="hidden">
                <h4 class="font-medium text-gray-900 mb-2">Verifikasi Absensi</h4>
                <form id="verificationFormDetail" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="verification_notes_detail" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Verifikasi (Opsional)
                        </label>
                        <textarea id="verification_notes_detail" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDetailModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                            Batal
                        </button>
                        <button type="submit" name="action" value="reject"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Tolak
                        </button>
                        <button type="submit" name="action" value="verify"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// View proof image
function viewProof(imageUrl, userName) {
    document.getElementById('proofModalTitle').textContent = `Bukti Kehadiran - ${userName}`;
    document.getElementById('proofImage').src = imageUrl;
    document.getElementById('proofModal').classList.remove('hidden');
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
}

// Verify attendance
function verifyAttendance(attendanceId, action) {
    let message, confirmText;

    if (action === 'verify') {
        message = 'Apakah Anda yakin ingin memverifikasi absensi ini?\nPeserta akan dapat mendownload sertifikat setelah diverifikasi.';
        confirmText = 'Ya, Verifikasi';
    } else {
        message = 'Apakah Anda yakin ingin menolak absensi ini?\nPeserta tidak akan dapat mendownload sertifikat.';
        confirmText = 'Ya, Tolak';
    }

    if (confirm(message)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/ketua-ukm/events/{{ $event->slug }}/attendances/${attendanceId}/verify`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);

        // Add notes if rejecting
        if (action === 'reject') {
            const notes = prompt('Alasan penolakan (opsional):');
            if (notes && notes.trim()) {
                const notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'notes';
                notesInput.value = notes;
                form.appendChild(notesInput);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }
}

function closeVerificationModal() {
    document.getElementById('verificationModal').classList.add('hidden');
    document.getElementById('verification_notes').value = '';
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.attendance-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// View details
function viewDetails(attendanceId) {
    // Find attendance data
    const attendance = @json($attendances->items()).find(a => a.id === attendanceId);
    if (!attendance) {
        alert('Data absensi tidak ditemukan');
        return;
    }

    // Populate modal with attendance details
    document.getElementById('detailStudentName').textContent = attendance.user.name;
    document.getElementById('detailStudentId').textContent = attendance.user.student_id || attendance.user.nim || '-';
    document.getElementById('detailSubmittedAt').textContent = attendance.submitted_at ?
        new Date(attendance.submitted_at).toLocaleString('id-ID') : '-';
    document.getElementById('detailStatus').textContent = attendance.verification_status || 'pending';
    document.getElementById('detailNotes').textContent = attendance.notes || 'Tidak ada catatan';

    // Handle proof image
    const proofImage = document.getElementById('detailProofImage');
    const noProofText = document.getElementById('noProofText');
    if (attendance.proof_file) {
        proofImage.src = `/storage/${attendance.proof_file}`;
        proofImage.classList.remove('hidden');
        noProofText.classList.add('hidden');
    } else {
        proofImage.classList.add('hidden');
        noProofText.classList.remove('hidden');
    }

    // Set up verification form
    const verificationForm = document.getElementById('verificationFormDetail');
    verificationForm.action = `/ketua-ukm/events/{{ $event->slug }}/attendances/${attendanceId}/verify`;

    // Show/hide verification section based on status
    const verificationSection = document.getElementById('verificationSection');
    if (attendance.verification_status === 'pending') {
        verificationSection.classList.remove('hidden');
    } else {
        verificationSection.classList.add('hidden');
        if (attendance.verification_notes) {
            document.getElementById('detailVerificationNotes').textContent = attendance.verification_notes;
            document.getElementById('verificationNotesSection').classList.remove('hidden');
        } else {
            document.getElementById('verificationNotesSection').classList.add('hidden');
        }
    }

    // Show modal
    document.getElementById('detailModal').classList.remove('hidden');
}

// Close detail modal
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('verification_notes_detail').value = '';
}

// Bulk verification
function openBulkModal() {
    const selectedCheckboxes = document.querySelectorAll('.attendance-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        alert('Pilih minimal satu absensi untuk validasi serentak');
        return;
    }

    const attendanceIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    const message = `Apakah Anda yakin ingin memvalidasi ${attendanceIds.length} absensi sekaligus?\nSemua peserta yang dipilih akan dapat mendownload sertifikat.`;

    if (confirm(message)) {
        // Create form for bulk verification
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("ketua-ukm.events.bulk-verify-attendances", $event) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add attendance IDs
        attendanceIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'attendance_ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });

        // Add action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'verify';
        form.appendChild(actionInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
