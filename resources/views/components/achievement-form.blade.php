@php
    // Ensure achievements is always a collection
    $achievements = $achievements ?: collect();
    // Handle case where achievements might be null or not a collection
    if (!is_object($achievements) || !method_exists($achievements, 'count')) {
        $achievements = collect($achievements ?: []);
    }
@endphp

<!-- Dynamic Achievement Form Component -->
<div class="border-t border-gray-200 pt-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900">Prestasi UKM</h3>
        <button type="button" id="add-achievement" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Tambah Prestasi
        </button>
    </div>

    <div id="achievements-container">
        @if(is_iterable($achievements) && count($achievements) > 0)
            @foreach(is_iterable($achievements) ? $achievements : [] as $index => $achievement)
                <div class="achievement-item border border-gray-200 rounded-lg p-4 mb-4" data-index="{{ $index }}">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-gray-900">Prestasi #<span class="achievement-number">{{ $index + 1 }}</span></h4>
                        <button type="button" class="remove-achievement text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label class="form-label">Nama Prestasi</label>
                            <input type="text" name="achievements[{{ $index }}][title]"
                                   value="{{ old('achievements.'.$index.'.title', is_object($achievement) ? $achievement->title : '') }}"
                                   class="form-input" placeholder="Contoh: Juara 1 Lomba Programming">
                        </div>

                        <!-- Type and Level -->
                        <div>
                            <label class="form-label">Jenis Prestasi</label>
                            <select name="achievements[{{ $index }}][type]" class="form-input">
                                <option value="competition" {{ old('achievements.'.$index.'.type', is_object($achievement) ? $achievement->type : '') == 'competition' ? 'selected' : '' }}>Kompetisi</option>
                                <option value="award" {{ old('achievements.'.$index.'.type', is_object($achievement) ? $achievement->type : '') == 'award' ? 'selected' : '' }}>Penghargaan</option>
                                <option value="certification" {{ old('achievements.'.$index.'.type', is_object($achievement) ? $achievement->type : '') == 'certification' ? 'selected' : '' }}>Sertifikasi</option>
                                <option value="recognition" {{ old('achievements.'.$index.'.type', is_object($achievement) ? $achievement->type : '') == 'recognition' ? 'selected' : '' }}>Pengakuan</option>
                                <option value="other" {{ old('achievements.'.$index.'.type', is_object($achievement) ? $achievement->type : '') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Tingkat</label>
                            <select name="achievements[{{ $index }}][level]" class="form-input">
                                <option value="local" {{ old('achievements.'.$index.'.level', is_object($achievement) ? $achievement->level : '') == 'local' ? 'selected' : '' }}>Lokal</option>
                                <option value="regional" {{ old('achievements.'.$index.'.level', is_object($achievement) ? $achievement->level : '') == 'regional' ? 'selected' : '' }}>Regional</option>
                                <option value="national" {{ old('achievements.'.$index.'.level', is_object($achievement) ? $achievement->level : '') == 'national' ? 'selected' : '' }}>Nasional</option>
                                <option value="international" {{ old('achievements.'.$index.'.level', is_object($achievement) ? $achievement->level : '') == 'international' ? 'selected' : '' }}>Internasional</option>
                            </select>
                        </div>

                        <!-- Position and Organizer -->
                        <div>
                            <label class="form-label">Posisi/Ranking</label>
                            <input type="number" name="achievements[{{ $index }}][position]"
                                   value="{{ old('achievements.'.$index.'.position', is_object($achievement) ? $achievement->position : '') }}"
                                   class="form-input" placeholder="1 untuk Juara 1, 2 untuk Juara 2, dst" min="1">
                        </div>

                        <div>
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" name="achievements[{{ $index }}][organizer]"
                                   value="{{ old('achievements.'.$index.'.organizer', is_object($achievement) ? $achievement->organizer : '') }}"
                                   class="form-input" placeholder="Nama penyelenggara">
                        </div>

                        <!-- Date and Year -->
                        <div>
                            <label class="form-label">Tanggal Prestasi</label>
                            <input type="date" name="achievements[{{ $index }}][achievement_date]"
                                   value="{{ old('achievements.'.$index.'.achievement_date', (is_object($achievement) && !empty($achievement->achievement_date)) ? \Carbon\Carbon::parse($achievement->achievement_date)->format('Y-m-d') : '') }}"
                                   class="form-input">
                        </div>

                        <div>
                            <label class="form-label">Tahun</label>
                            <input type="number" name="achievements[{{ $index }}][year]"
                                   value="{{ old('achievements.'.$index.'.year', is_object($achievement) ? $achievement->year : date('Y')) }}"
                                   class="form-input" min="2000" max="{{ date('Y') + 1 }}">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="achievements[{{ $index }}][description]" rows="3"
                                      class="form-input" placeholder="Deskripsi prestasi...">{{ old('achievements.'.$index.'.description', is_object($achievement) ? $achievement->description : '') }}</textarea>
                        </div>

                        <!-- Participants -->
                        <div class="md:col-span-2">
                            <label class="form-label">Peserta yang Terlibat</label>
                            <textarea name="achievements[{{ $index }}][participants]" rows="2"
                                      class="form-input" placeholder="Nama-nama peserta yang terlibat...">{{ old('achievements.'.$index.'.participants', is_object($achievement) ? $achievement->participants : '') }}</textarea>
                        </div>

                        <!-- Featured and Certificate -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="achievements[{{ $index }}][is_featured]" value="1"
                                       {{ old('achievements.'.$index.'.is_featured', (is_object($achievement) ? $achievement->is_featured : false)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Tampilkan di homepage</span>
                            </label>
                        </div>

                        <div>
                            <label class="form-label">File Sertifikat</label>
                            <input type="file" name="achievements[{{ $index }}][certificate_file]"
                                   class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                            @if(is_object($achievement) && !empty($achievement->certificate_file))
                                <p class="text-sm text-gray-500 mt-1">
                                    File saat ini: <a href="{{ asset('storage/' . $achievement->certificate_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800">Lihat file</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- No default form - user can add if needed -->
        @endif
    </div>

    <!-- Show empty state when no achievements -->
    <div id="no-achievements-message" class="text-center py-8 text-gray-500 {{ $achievements->count() > 0 ? 'hidden' : '' }}">
        <i class="fas fa-trophy text-4xl mb-4 text-gray-300"></i>
        <p class="text-lg font-medium mb-2">Belum ada prestasi</p>
        <p class="text-sm">Klik "Tambah Prestasi" untuk menambahkan prestasi UKM</p>
    </div>

    <div class="text-sm text-gray-500 mt-4">
        <p><strong>Tips:</strong></p>
        <ul class="list-disc list-inside space-y-1">
            <li>Prestasi bersifat opsional - tidak wajib diisi</li>
            <li>Tambahkan prestasi yang paling membanggakan dan relevan</li>
            <li>Centang "Tampilkan di homepage" untuk prestasi yang ingin ditampilkan di halaman utama</li>
            <li>Upload file sertifikat dalam format PDF, JPG, atau PNG (maksimal 2MB)</li>
        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let achievementIndex = {{ $achievements->count() }};

    // Add achievement button
    document.getElementById('add-achievement').addEventListener('click', function() {
        const container = document.getElementById('achievements-container');
        const noMessage = document.getElementById('no-achievements-message');
        const newAchievement = createAchievementForm(achievementIndex);
        container.appendChild(newAchievement);
        achievementIndex++;
        updateAchievementNumbers();

        // Hide no achievements message
        if (noMessage) {
            noMessage.classList.add('hidden');
        }
    });

    // Remove achievement functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-achievement')) {
            const achievementItem = e.target.closest('.achievement-item');
            const container = document.getElementById('achievements-container');
            const noMessage = document.getElementById('no-achievements-message');

            achievementItem.remove();
            updateAchievementNumbers();

            // Show no achievements message if no items left
            if (container.querySelectorAll('.achievement-item').length === 0 && noMessage) {
                noMessage.classList.remove('hidden');
            }
        }
    });

    function createAchievementForm(index) {
        const div = document.createElement('div');
        div.className = 'achievement-item border border-gray-200 rounded-lg p-4 mb-4';
        div.setAttribute('data-index', index);

        div.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-900">Prestasi #<span class="achievement-number">${index + 1}</span></h4>
                <button type="button" class="remove-achievement text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Nama Prestasi</label>
                    <input type="text" name="achievements[${index}][title]" class="form-input" placeholder="Contoh: Juara 1 Lomba Programming">
                </div>

                <div>
                    <label class="form-label">Jenis Prestasi</label>
                    <select name="achievements[${index}][type]" class="form-input">
                        <option value="competition">Kompetisi</option>
                        <option value="award">Penghargaan</option>
                        <option value="certification">Sertifikasi</option>
                        <option value="recognition">Pengakuan</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tingkat</label>
                    <select name="achievements[${index}][level]" class="form-input">
                        <option value="local">Lokal</option>
                        <option value="regional">Regional</option>
                        <option value="national">Nasional</option>
                        <option value="international">Internasional</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Posisi/Ranking</label>
                    <input type="number" name="achievements[${index}][position]" class="form-input" placeholder="1 untuk Juara 1, 2 untuk Juara 2, dst" min="1">
                </div>

                <div>
                    <label class="form-label">Penyelenggara</label>
                    <input type="text" name="achievements[${index}][organizer]" class="form-input" placeholder="Nama penyelenggara">
                </div>

                <div>
                    <label class="form-label">Tanggal Prestasi</label>
                    <input type="date" name="achievements[${index}][achievement_date]" class="form-input">
                </div>

                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" name="achievements[${index}][year]" value="${new Date().getFullYear()}" class="form-input" min="2000" max="${new Date().getFullYear() + 1}">
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="achievements[${index}][description]" rows="3" class="form-input" placeholder="Deskripsi prestasi..."></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Peserta yang Terlibat</label>
                    <textarea name="achievements[${index}][participants]" rows="2" class="form-input" placeholder="Nama-nama peserta yang terlibat..."></textarea>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="achievements[${index}][is_featured]" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Tampilkan di homepage</span>
                    </label>
                </div>

                <div>
                    <label class="form-label">File Sertifikat</label>
                    <input type="file" name="achievements[${index}][certificate_file]" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        `;

        return div;
    }

    function updateAchievementNumbers() {
        const achievements = document.querySelectorAll('.achievement-item');
        achievements.forEach((item, index) => {
            const numberSpan = item.querySelector('.achievement-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }
});
</script>
