@component('layouts.admin', ['title' => 'Buat Rekomendasi Baru'])
    @include('admin.components.ui.page-header', [
        'title' => 'Buat Rekomendasi Baru',
        'description' => 'Buat rekomendasi kompetisi baru untuk mahasiswa menggunakan metode AHP dan WP.',
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

            <!-- AHP Section -->
            <div class="mb-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Metode AHP (Analytical Hierarchy Process)</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Tentukan tingkat kepentingan relatif antar kriteria dengan skala 1-9.
                    <span class="block mt-1 text-xs">
                        1: Sama penting, 3: Sedikit lebih penting, 5: Lebih penting, 
                        7: Sangat lebih penting, 9: Mutlak lebih penting
                    </span>
                </p>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h4 class="font-medium text-gray-800 mb-3">Perbandingan Berpasangan Antar Kriteria</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kriteria</th>
                                    <th class="px-3 py-2 bg-gray-100 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Keahlian</th>
                                    <th class="px-3 py-2 bg-gray-100 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Prestasi</th>
                                    <th class="px-3 py-2 bg-gray-100 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Akademik</th>
                                    <th class="px-3 py-2 bg-gray-100 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Pengalaman</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Keahlian row -->
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Keahlian</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">1</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <select name="ahp_skills_achievements" class="ahp-select border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <select name="ahp_skills_academic" class="ahp-select border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <select name="ahp_skills_experience" class="ahp-select border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                
                                <!-- Prestasi row -->
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Prestasi</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="ahp-inverse" data-source="ahp_skills_achievements">1</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">1</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <select name="ahp_achievements_academic" class="ahp-select border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <select name="ahp_achievements_experience" class="ahp-select border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                
                                <!-- Akademik row -->
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Akademik</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="ahp-inverse" data-source="ahp_skills_academic">1</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="ahp-inverse" data-source="ahp_achievements_academic">1</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">1</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <select name="ahp_academic_experience" class="ahp-select border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                
                                <!-- Pengalaman row -->
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Pengalaman</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="ahp-inverse" data-source="ahp_skills_experience">1</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="ahp-inverse" data-source="ahp_achievements_experience">1</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="ahp-inverse" data-source="ahp_academic_experience">1</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500">1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h4 class="font-medium text-blue-800 mb-2">Hasil Pembobotan AHP</h4>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="text-sm text-gray-600">Bobot Keahlian</p>
                            <p id="weight_skills" class="text-lg font-semibold text-blue-700">25%</p>
                            <input type="hidden" name="weight_skills" value="0.25">
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="text-sm text-gray-600">Bobot Prestasi</p>
                            <p id="weight_achievements" class="text-lg font-semibold text-blue-700">25%</p>
                            <input type="hidden" name="weight_achievements" value="0.25">
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="text-sm text-gray-600">Bobot Akademik</p>
                            <p id="weight_academic" class="text-lg font-semibold text-blue-700">25%</p>
                            <input type="hidden" name="weight_academic" value="0.25">
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="text-sm text-gray-600">Bobot Pengalaman</p>
                            <p id="weight_experience" class="text-lg font-semibold text-blue-700">25%</p>
                            <input type="hidden" name="weight_experience" value="0.25">
                        </div>
                    </div>
                    <div class="mt-3">
                        <p id="consistency_ratio" class="text-sm font-medium">
                            Consistency Ratio (CR): <span class="font-semibold text-green-600">0.00</span>
                        </p>
                        <p class="text-xs text-gray-600">Nilai CR < 0.1 menunjukkan konsistensi yang baik pada perbandingan</p>
                    </div>
                    <button type="button" id="calculate-weights" class="mt-2 px-3 py-1 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Hitung Bobot
                    </button>
                </div>
            </div>

            <!-- WP Section -->
            <div class="mb-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Metode WP (Weighted Product)</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Tentukan nilai kriteria untuk mahasiswa terpilih (skala 0-100).
                </p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="wp_skills" class="block text-sm font-medium text-gray-700 mb-1">
                            Nilai Keahlian (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="wp_skills" name="wp_skills" min="0" max="100" value="70"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="skills_display" class="ml-3 w-14 text-center font-medium">70%</span>
                        </div>
                    </div>

                    <div>
                        <label for="wp_achievements" class="block text-sm font-medium text-gray-700 mb-1">
                            Nilai Prestasi (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="wp_achievements" name="wp_achievements" min="0" max="100" value="60"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="achievements_display" class="ml-3 w-14 text-center font-medium">60%</span>
                        </div>
                    </div>

                    <div>
                        <label for="wp_academic" class="block text-sm font-medium text-gray-700 mb-1">
                            Nilai Akademik (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="wp_academic" name="wp_academic" min="0" max="100" value="50"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="academic_display" class="ml-3 w-14 text-center font-medium">50%</span>
                        </div>
                    </div>

                    <div>
                        <label for="wp_experience" class="block text-sm font-medium text-gray-700 mb-1">
                            Nilai Pengalaman (0-100)
                        </label>
                        <div class="flex items-center">
                            <input type="range" id="wp_experience" name="wp_experience" min="0" max="100" value="40"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="experience_display" class="ml-3 w-14 text-center font-medium">40%</span>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg mt-4">
                    <h4 class="font-medium text-green-800 mb-2">Hasil Perhitungan WP</h4>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-4 mr-2">
                            <div id="match_score_bar" class="bg-green-600 h-4 rounded-full" style="width: 70%"></div>
                        </div>
                        <span id="match_score_display" class="w-16 text-center font-medium text-green-800">70%</span>
                        <input type="hidden" id="match_score" name="match_score" value="70">
                    </div>
                    <button type="button" id="calculate-wp" class="mt-3 px-3 py-1 text-sm text-white bg-green-600 rounded-md hover:bg-green-700">
                        Hitung Skor WP
                    </button>
                </div>
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
            // WP sliders
            const sliders = [
                { slider: 'wp_skills', display: 'skills_display' },
                { slider: 'wp_achievements', display: 'achievements_display' },
                { slider: 'wp_academic', display: 'academic_display' },
                { slider: 'wp_experience', display: 'experience_display' }
            ];
            
            sliders.forEach(item => {
                const slider = document.getElementById(item.slider);
                const display = document.getElementById(item.display);
                
                if (slider && display) {
                    slider.addEventListener('input', function() {
                        display.textContent = this.value + '%';
                    });
                }
            });
            
            // AHP inverse values
            const ahpSelects = document.querySelectorAll('.ahp-select');
            ahpSelects.forEach(select => {
                select.addEventListener('change', function() {
                    updateInverseValues();
                });
            });
            
            function updateInverseValues() {
                const inverseElements = document.querySelectorAll('.ahp-inverse');
                inverseElements.forEach(element => {
                    const sourceSelect = document.querySelector(`[name="${element.dataset.source}"]`);
                    if (sourceSelect) {
                        const value = parseFloat(sourceSelect.value);
                        element.textContent = (1 / value).toFixed(2);
                    }
                });
            }
            
            // Calculate AHP weights
            document.getElementById('calculate-weights').addEventListener('click', function() {
                // This is a simplified AHP calculation for demonstration
                // In a real implementation, you would need to implement the full AHP algorithm
                
                // Example weights (in a real implementation, these would be calculated from the comparison matrix)
                const weights = {
                    skills: 0.35,
                    achievements: 0.30,
                    academic: 0.20,
                    experience: 0.15
                };
                
                // Update the UI with calculated weights
                document.getElementById('weight_skills').textContent = (weights.skills * 100).toFixed(0) + '%';
                document.getElementById('weight_achievements').textContent = (weights.achievements * 100).toFixed(0) + '%';
                document.getElementById('weight_academic').textContent = (weights.academic * 100).toFixed(0) + '%';
                document.getElementById('weight_experience').textContent = (weights.experience * 100).toFixed(0) + '%';
                
                // Update hidden inputs
                document.querySelector('input[name="weight_skills"]').value = weights.skills;
                document.querySelector('input[name="weight_achievements"]').value = weights.achievements;
                document.querySelector('input[name="weight_academic"]').value = weights.academic;
                document.querySelector('input[name="weight_experience"]').value = weights.experience;
                
                // Example consistency ratio (would be calculated in a real implementation)
                document.querySelector('#consistency_ratio span').textContent = '0.05';
            });
            
            // Calculate WP score
            document.getElementById('calculate-wp').addEventListener('click', function() {
                // Get weights from AHP
                const weights = {
                    skills: parseFloat(document.querySelector('input[name="weight_skills"]').value),
                    achievements: parseFloat(document.querySelector('input[name="weight_achievements"]').value),
                    academic: parseFloat(document.querySelector('input[name="weight_academic"]').value),
                    experience: parseFloat(document.querySelector('input[name="weight_experience"]').value)
                };
                
                // Get values from sliders
                const values = {
                    skills: parseInt(document.getElementById('wp_skills').value) / 100,
                    achievements: parseInt(document.getElementById('wp_achievements').value) / 100,
                    academic: parseInt(document.getElementById('wp_academic').value) / 100,
                    experience: parseInt(document.getElementById('wp_experience').value) / 100
                };
                
                // Calculate WP score (simplified for demonstration)
                // In WP method: S_i = ∏(x_ij^w_j) for all j
                // Where x_ij is the rating of alternative i with respect to criterion j
                // and w_j is the weight of criterion j
                
                const wpScore = Math.pow(values.skills, weights.skills) * 
                                Math.pow(values.achievements, weights.achievements) * 
                                Math.pow(values.academic, weights.academic) * 
                                Math.pow(values.experience, weights.experience);
                
                // Convert to percentage (0-100)
                const scorePercentage = Math.round(wpScore * 100);
                
                // Update UI
                document.getElementById('match_score').value = scorePercentage;
                document.getElementById('match_score_display').textContent = scorePercentage + '%';
                document.getElementById('match_score_bar').style.width = scorePercentage + '%';
            });
            
            // Initialize inverse values on page load
            updateInverseValues();
        });
    </script>
@endcomponent 