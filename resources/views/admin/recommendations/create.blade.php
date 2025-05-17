@component('layouts.admin', ['title' => 'Buat Rekomendasi Baru'])
    @include('admin.components.ui.page-header', [
        'title' => 'Buat Rekomendasi Baru',
        'description' => 'Buat rekomendasi kompetisi baru untuk mahasiswa secara manual.',
        'backUrl' => route('admin.recommendations.index'),
        'backText' => 'Kembali ke Daftar Rekomendasi'
    ])

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.recommendations.store') }}" method="POST">
            @csrf

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                    <select id="user_id" name="user_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }} ({{ $student->nim ?? 'NIM tidak tersedia' }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="competition_id" class="block text-sm font-medium text-gray-700 mb-1">Kompetisi</label>
                    <select id="competition_id" name="competition_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Kompetisi --</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}">
                                {{ $competition->name }} ({{ ucfirst($competition->level) }} - {{ $competition->organizer }})
                            </option>
                        @endforeach
                    </select>
                    @error('competition_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="match_score" class="block text-sm font-medium text-gray-700 mb-1">
                    Skor Kecocokan (0-100)
                </label>
                <div class="flex items-center">
                    <input type="range" id="match_score" name="match_score" min="0" max="100" value="70"
                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <span id="score_display" class="ml-3 w-14 text-center font-medium">70%</span>
                </div>
                @error('match_score')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="recommended_by" class="block text-sm font-medium text-gray-700 mb-1">Direkomendasi Oleh</label>
                <select id="recommended_by" name="recommended_by" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="admin">Admin</option>
                    <option value="lecturer">Dosen</option>
                    <option value="system">Sistem (Otomatis)</option>
                </select>
                @error('recommended_by')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Catatan atau alasan untuk rekomendasi ini..."></textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <h3 class="font-medium text-gray-900 mb-2">Faktor Kecocokan (Opsional)</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Tentukan faktor-faktor yang memengaruhi tingkat kecocokan antara mahasiswa dengan kompetisi.
                </p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="factor_skills" class="block text-sm font-medium text-gray-700 mb-1">
                            Faktor Keahlian (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="factor_skills" name="factor_skills" min="0" max="100" value="70"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="skills_display" class="ml-3 w-14 text-center font-medium">70%</span>
                        </div>
                    </div>

                    <div>
                        <label for="factor_achievements" class="block text-sm font-medium text-gray-700 mb-1">
                            Faktor Prestasi Sebelumnya (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="factor_achievements" name="factor_achievements" min="0" max="100" value="60"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="achievements_display" class="ml-3 w-14 text-center font-medium">60%</span>
                        </div>
                    </div>

                    <div>
                        <label for="factor_academic" class="block text-sm font-medium text-gray-700 mb-1">
                            Faktor Akademik (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="factor_academic" name="factor_academic" min="0" max="100" value="50"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="academic_display" class="ml-3 w-14 text-center font-medium">50%</span>
                        </div>
                    </div>

                    <div>
                        <label for="factor_experience" class="block text-sm font-medium text-gray-700 mb-1">
                            Faktor Pengalaman Kompetisi (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="factor_experience" name="factor_experience" min="0" max="100" value="40"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="experience_display" class="ml-3 w-14 text-center font-medium">40%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.recommendations.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 mr-2">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Simpan Rekomendasi
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main score slider
            const matchScore = document.getElementById('match_score');
            const scoreDisplay = document.getElementById('score_display');
            
            matchScore.addEventListener('input', function() {
                scoreDisplay.textContent = this.value + '%';
            });
            
            // Factor sliders
            const factorSliders = [
                { slider: 'factor_skills', display: 'skills_display' },
                { slider: 'factor_achievements', display: 'achievements_display' },
                { slider: 'factor_academic', display: 'academic_display' },
                { slider: 'factor_experience', display: 'experience_display' }
            ];
            
            factorSliders.forEach(item => {
                const slider = document.getElementById(item.slider);
                const display = document.getElementById(item.display);
                
                if (slider && display) {
                    slider.addEventListener('input', function() {
                        display.textContent = this.value + '%';
                    });
                }
            });
        });
    </script>
@endcomponent 