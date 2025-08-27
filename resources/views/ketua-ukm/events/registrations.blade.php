@extends('layouts.app')

@section('title', 'Kelola Pendaftaran - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Pendaftaran Event</h1>
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
                    <p><i class="fas fa-users mr-2"></i>{{ $event->max_participants ? $event->current_participants . '/' . $event->max_participants : $event->current_participants }} peserta</p>
                    <p><i class="fas fa-cog mr-2"></i>{{ $event->requires_approval ? 'Memerlukan Persetujuan' : 'Auto Approve (Langsung Terdaftar)' }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Status Pendaftaran</h3>
                <div class="space-y-1 text-sm">
                    @php
                        $totalRegistrations = $registrations->total();
                        $approvedCount = $registrations->where('status', 'approved')->count();
                        $cancelledCount = $registrations->where('status', 'cancelled')->count();
                    @endphp
                    <p class="text-green-600"><i class="fas fa-check mr-2"></i>{{ $approvedCount }} terdaftar</p>
                    <p class="text-red-600"><i class="fas fa-ban mr-2"></i>{{ $cancelledCount }} dibatalkan</p>
                    <p class="text-gray-600"><i class="fas fa-list mr-2"></i>{{ $totalRegistrations }} total pendaftar</p>
                    @if($event->max_participants)
                        <p class="text-blue-600"><i class="fas fa-users mr-2"></i>Kuota: {{ $approvedCount }}/{{ $event->max_participants }}</p>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aksi Cepat</h3>
                <div class="space-y-2">
                    @php
                        $pendingCount = $registrations->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <button onclick="bulkApproveSelected()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-check-double mr-2"></i>Setujui Terpilih
                        </button>
                        <button onclick="bulkApproveAll()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>Setujui Semua Pending
                        </button>
                    @endif
                    <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>Lihat Event
                    </a>
                    <button onclick="exportRegistrations()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-download mr-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('ketua-ukm.events.registrations', $event) }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-48">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pendaftar</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Nama atau NIM mahasiswa...">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pendaftaran</label>
                <select id="status" name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('ketua-ukm.events.registrations', $event) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Registrations List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pendaftar Event</h2>
        </div>

        @if($registrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendaftar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Daftar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registrations as $index => $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ($registrations->currentPage() - 1) * $registrations->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($registration->user->avatar)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ asset('storage/' . $registration->user->avatar) }}"
                                                     alt="{{ $registration->user->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ strtoupper(substr($registration->user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $registration->user->nim ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-400">{{ $registration->user->major ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <p><i class="fas fa-envelope mr-1 text-gray-400"></i>{{ $registration->user->email }}</p>
                                        <p><i class="fas fa-phone mr-1 text-gray-400"></i>{{ $registration->user->phone ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $registration->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($registration->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Terdaftar
                                        </span>
                                    @elseif($registration->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i>Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-question mr-1"></i>{{ ucfirst($registration->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewDetails({{ $registration->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>

                                        @if($registration->status === 'pending')
                                            <button onclick="approveRegistration({{ $registration->id }})"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors"
                                                    title="Setujui Pendaftaran">
                                                <i class="fas fa-check mr-1"></i>Setujui
                                            </button>
                                            <button onclick="rejectRegistration({{ $registration->id }})"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors"
                                                    title="Tolak Pendaftaran">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        @elseif($registration->status === 'approved')
                                            <span class="text-xs text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Sudah Disetujui
                                            </span>
                                        @elseif($registration->status === 'rejected')
                                            <span class="text-xs text-red-600 font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>Sudah Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $registrations->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <i class="fas fa-user-plus text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pendaftar</h3>
                <p class="text-gray-500">Belum ada mahasiswa yang mendaftar untuk event ini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Motivation Modal -->
<div id="motivationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="motivationModalTitle">Motivasi Pendaftar</h3>
            <div class="text-sm text-gray-700" id="motivationContent"></div>
            <div class="flex justify-end mt-4">
                <button onclick="closeMotivationModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="approvalForm" method="POST">
            @csrf
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="approvalModalTitle">Konfirmasi Aksi</h3>
                <p class="text-sm text-gray-600 mb-4" id="approvalModalMessage"></p>
                <div class="mb-4">
                    <label for="approval_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea id="approval_notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApprovalModal()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" id="approvalSubmitBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// View motivation
function viewMotivation(userName, motivation) {
    document.getElementById('motivationModalTitle').textContent = `Motivasi - ${userName}`;
    document.getElementById('motivationContent').textContent = motivation;
    document.getElementById('motivationModal').classList.remove('hidden');
}

function closeMotivationModal() {
    document.getElementById('motivationModal').classList.add('hidden');
}

// Approve registration
function approveRegistration(registrationId) {
    if (confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/ketua-ukm/events/{{ $event->slug }}/registrations/${registrationId}/approve`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}

// Reject registration
function rejectRegistration(registrationId) {
    const reason = prompt('Alasan penolakan (opsional):');
    if (reason !== null) { // User didn't cancel
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/ketua-ukm/events/{{ $event->slug }}/registrations/${registrationId}/reject`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        if (reason.trim()) {
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'notes';
            notesInput.value = reason;
            form.appendChild(notesInput);
        }

        document.body.appendChild(form);
        form.submit();
    }
}

function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.getElementById('approval_notes').value = '';
}

// View details
function viewDetails(registrationId) {
    window.location.href = `/ketua-ukm/events/{{ $event->slug }}/registrations/${registrationId}`;
}

// Bulk approve selected registrations
function bulkApproveSelected() {
    const checkboxes = document.querySelectorAll('input[name="registration_ids[]"]:checked');
    if (checkboxes.length === 0) {
        alert('Pilih minimal satu pendaftaran untuk disetujui');
        return;
    }

    if (confirm(`Apakah Anda yakin ingin menyetujui ${checkboxes.length} pendaftaran yang dipilih?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/ketua-ukm/events/{{ $event->slug }}/registrations/bulk-approve`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'registration_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}

// Bulk approve all pending registrations
function bulkApproveAll() {
    const pendingCount = {{ $registrations->where('status', 'pending')->count() }};
    if (pendingCount === 0) {
        alert('Tidak ada pendaftaran yang menunggu persetujuan');
        return;
    }

    if (confirm(`Apakah Anda yakin ingin menyetujui SEMUA ${pendingCount} pendaftaran yang menunggu?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/ketua-ukm/events/{{ $event->slug }}/registrations/bulk-approve`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add all pending registration IDs
        @foreach($registrations->where('status', 'pending') as $registration)
            const input{{ $registration->id }} = document.createElement('input');
            input{{ $registration->id }}.type = 'hidden';
            input{{ $registration->id }}.name = 'registration_ids[]';
            input{{ $registration->id }}.value = '{{ $registration->id }}';
            form.appendChild(input{{ $registration->id }});
        @endforeach

        document.body.appendChild(form);
        form.submit();
    }
}

// Export registrations (placeholder)
function exportRegistrations() {
    alert('Fitur export akan diimplementasikan');
}
</script>
@endsection
