@extends('layouts.app')

@section('title', 'Kelola UKM - ' . $ukm->name)

@php
    // Ensure achievements is always a collection in this view
    if (!($ukm->achievements instanceof \Illuminate\Database\Eloquent\Collection)) {
        $ukm->achievements = collect($ukm->achievements);
    }
@endphp

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola {{ $ukm->name }}</h1>
            <p class="text-gray-600">Kelola informasi dan member UKM Anda</p>
        </div>
        <a href="{{ route('ketua-ukm.dashboard') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- UKM Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $ukm->name }}</h2>
                <p class="text-gray-500">{{ ucfirst($ukm->category) }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('ketua-ukm.edit-ukm', $ukm->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit UKM
                </a>
                <a href="{{ route('ketua-ukm.create-event', $ukm->id) }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-1"></i>Buat Event
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Deskripsi</h3>
                <p class="text-gray-600 text-sm">{{ $ukm->description }}</p>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Visi</h3>
                <p class="text-gray-600 text-sm">{{ $ukm->vision ?? 'Belum diisi' }}</p>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Misi</h3>
                <p class="text-gray-600 text-sm">{{ $ukm->mission ?? 'Belum diisi' }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Member</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $members->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-user-check text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Member Aktif</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $members->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Event</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $events->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Event Mendatang</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $events->where('start_datetime', '>', now())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-trophy text-orange-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Prestasi</p>
                    <p class="text-lg font-semibold text-gray-900">{{ is_iterable($ukm->achievements) ? count($ukm->achievements) : 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active"
                        data-tab="members">
                    <i class="fas fa-users mr-2"></i>Member UKM
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="events">
                    <i class="fas fa-calendar mr-2"></i>Event
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="achievements">
                    <i class="fas fa-trophy mr-2"></i>Prestasi
                </button>
            </nav>
        </div>

        <!-- Members Tab -->
        <div id="members-tab" class="tab-content p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($member->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                 src="{{ asset('storage/' . $member->avatar) }}"
                                                 alt="{{ $member->name }}">
                                        @else
                                            <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $member->email }}</div>
                                <div class="text-sm text-gray-500">{{ $member->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->pivot->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->pivot->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($member->pivot->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p>Belum ada member yang bergabung</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Events Tab -->
        <div id="events-tab" class="tab-content p-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-medium text-gray-900">{{ $event->title }}</h3>
                        @php
                            $currentStatus = $event->getCurrentStatus();
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $currentStatus === 'published' ? 'bg-blue-100 text-blue-800' :
                               ($currentStatus === 'ongoing' ? 'bg-green-100 text-green-800' :
                               ($currentStatus === 'waiting' ? 'bg-yellow-100 text-yellow-800' :
                               ($currentStatus === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                            @if($currentStatus === 'published')
                                @if(now() < $event->start_datetime)
                                    Akan Datang
                                @else
                                    Published
                                @endif
                            @elseif($currentStatus === 'waiting')
                                Menunggu Persetujuan
                            @elseif($currentStatus === 'ongoing')
                                Sedang Berlangsung
                            @elseif($currentStatus === 'completed')
                                Selesai
                            @else
                                {{ ucfirst($currentStatus) }}
                            @endif
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $event->description }}</p>
                    <div class="text-xs text-gray-500 space-y-1">
                        <p><i class="fas fa-calendar mr-1"></i>{{ $event->start_datetime->format('d M Y H:i') }}</p>
                        <p><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location }}</p>
                        @if($event->max_participants)
                        <p><i class="fas fa-users mr-1"></i>Max: {{ $event->max_participants }} peserta</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fas fa-calendar text-4xl mb-4"></i>
                    <p>Belum ada event yang dibuat</p>
                    <a href="{{ route('ketua-ukm.create-event', $ukm->id) }}"
                       class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>Buat Event Pertama
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Achievements Tab -->
        <div id="achievements-tab" class="tab-content p-6 hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Prestasi UKM</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('achievements.by-ukm', $ukm) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-eye mr-1"></i>Lihat Semua
                    </a>
                    <a href="{{ route('ketua-ukm.edit-ukm', $ukm->id) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah Prestasi
                    </a>
                </div>
            </div>

            {{-- @if(is_iterable($ukm->achievements) && count($ukm->achievements) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($ukm->achievements->sortByDesc('achievement_date') as $achievement)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $achievement->title }}</h4>
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $achievement->type_text }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $achievement->level_text }}
                                        </span>
                                        @if($achievement->is_featured)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($achievement->position)
                                    <div class="flex-shrink-0 ml-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-xs">
                                            #{{ $achievement->position }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($achievement->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $achievement->description }}</p>
                            @endif

                            <div class="text-xs text-gray-500 space-y-1">
                                <p><i class="fas fa-calendar mr-1"></i>{{ $achievement->achievement_date->format('d M Y') }}</p>
                                @if($achievement->organizer)
                                    <p><i class="fas fa-building mr-1"></i>{{ $achievement->organizer }}</p>
                                @endif
                                @if($achievement->participants)
                                    <p><i class="fas fa-users mr-1"></i>{{ Str::limit($achievement->participants, 50) }}</p>
                                @endif
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <a href="{{ route('achievements.show', $achievement) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                                @if($achievement->certificate_file)
                                    <a href="{{ asset('storage/' . $achievement->certificate_file) }}"
                                       target="_blank"
                                       class="text-green-600 hover:text-green-800 text-sm">
                                        <i class="fas fa-certificate mr-1"></i>Sertifikat
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-trophy text-4xl mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada prestasi</h4>
                    <p class="mb-6">{{ $ukm->name }} belum memiliki prestasi yang terdaftar.</p>
                    <a href="{{ route('ketua-ukm.edit-ukm', $ukm->id) }}"
                       class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Prestasi Pertama
                    </a>
                </div>
            @endif --}}
        </div>
    </div>
</div>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked button
            button.classList.add('active', 'border-blue-500', 'text-blue-600');
            button.classList.remove('border-transparent', 'text-gray-500');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
    });
});
</script>
@endsection
