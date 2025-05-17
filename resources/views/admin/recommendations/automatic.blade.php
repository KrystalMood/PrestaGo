@component('layouts.admin', ['title' => 'Rekomendasi Otomatis'])
    @include('admin.components.ui.page-header', [
        'title' => 'Rekomendasi Otomatis',
        'description' => 'Gunakan sistem DSS untuk menghasilkan rekomendasi kompetisi yang sesuai dengan profil mahasiswa secara otomatis.'
    ])

    <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-2">
            <a href="{{ route('admin.recommendations.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                Semua Rekomendasi
            </a>
            <a href="{{ route('admin.recommendations.automatic') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                Rekomendasi Otomatis
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2">
            @component('admin.components.cards.card', ['title' => 'Konfigurasi Generator Rekomendasi'])
                <form id="recommendation-form" action="{{ route('admin.recommendations.generate') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="competition" class="block text-sm font-medium text-gray-700 mb-1">Kompetisi</label>
                            <select id="competition" name="competition_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">-- Pilih Kompetisi --</option>
                                @foreach($competitions as $competition)
                                    <option value="{{ $competition->id }}">{{ $competition->name }} ({{ ucfirst($competition->level) }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih kompetisi spesifik, atau biarkan kosong untuk menghasilkan rekomendasi untuk semua kompetisi aktif
                            </p>
                        </div>
                        
                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <select id="program" name="program_studi_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">-- Semua Program Studi --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="threshold" class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Skor Kecocokan (%)</label>
                            <div class="flex items-center">
                                <input type="range" id="threshold" name="threshold" min="0" max="100" value="60" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <span id="threshold-value" class="ml-2 text-sm text-gray-600 w-10">60%</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Hanya rekomendasikan mahasiswa dengan skor kecocokan di atas nilai ini
                            </p>
                        </div>
                        
                        <div>
                            <label for="max_recommendations" class="block text-sm font-medium text-gray-700 mb-1">Maksimum Rekomendasi per Mahasiswa</label>
                            <select id="max_recommendations" name="max_recommendations" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="1">1 rekomendasi</option>
                                <option value="2">2 rekomendasi</option>
                                <option value="3" selected>3 rekomendasi</option>
                                <option value="5">5 rekomendasi</option>
                                <option value="10">10 rekomendasi</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Berapa banyak rekomendasi kompetisi yang dapat diterima oleh satu mahasiswa
                            </p>
                        </div>
                        
                        <div>
                            <fieldset>
                                <legend class="text-sm font-medium text-gray-700">Bobot Faktor Rekomendasi</legend>
                                <div class="mt-2 space-y-4">
                                    <div>
                                        <label for="weight_skills" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Keterampilan</span>
                                            <span id="weight_skills_value">30%</span>
                                        </label>
                                        <input type="range" id="weight_skills" name="weight_skills" min="0" max="100" value="30" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="weight_achievements" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Prestasi Sebelumnya</span>
                                            <span id="weight_achievements_value">25%</span>
                                        </label>
                                        <input type="range" id="weight_achievements" name="weight_achievements" min="0" max="100" value="25" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="weight_academic" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Prestasi Akademik</span>
                                            <span id="weight_academic_value">25%</span>
                                        </label>
                                        <input type="range" id="weight_academic" name="weight_academic" min="0" max="100" value="25" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="weight_experience" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Pengalaman Lomba</span>
                                            <span id="weight_experience_value">20%</span>
                                        </label>
                                        <input type="range" id="weight_experience" name="weight_experience" min="0" max="100" value="20" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-amber-600 flex items-center" id="weight-warning" style="display: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Total bobot harus berjumlah 100%
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" id="generate-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Hasilkan Rekomendasi
                            </button>
                        </div>
                    </div>
                </form>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Threshold slider
                        const thresholdSlider = document.getElementById('threshold');
                        const thresholdValue = document.getElementById('threshold-value');
                        
                        thresholdSlider.addEventListener('input', function() {
                            thresholdValue.textContent = this.value + '%';
                        });
                        
                        // Weight sliders
                        const weightSliders = [
                            document.getElementById('weight_skills'),
                            document.getElementById('weight_achievements'),
                            document.getElementById('weight_academic'),
                            document.getElementById('weight_experience')
                        ];
                        
                        const weightValues = [
                            document.getElementById('weight_skills_value'),
                            document.getElementById('weight_achievements_value'),
                            document.getElementById('weight_academic_value'),
                            document.getElementById('weight_experience_value')
                        ];
                        
                        const weightWarning = document.getElementById('weight-warning');
                        
                        function updateWeights() {
                            let total = 0;
                            
                            weightSliders.forEach((slider, index) => {
                                weightValues[index].textContent = slider.value + '%';
                                total += parseInt(slider.value);
                            });
                            
                            if (total !== 100) {
                                weightWarning.style.display = 'flex';
                                document.getElementById('generate-btn').disabled = true;
                            } else {
                                weightWarning.style.display = 'none';
                                document.getElementById('generate-btn').disabled = false;
                            }
                        }
                        
                        weightSliders.forEach(slider => {
                            slider.addEventListener('input', updateWeights);
                        });
                        
                        // Initial check
                        updateWeights();
                    });
                </script>
            @endcomponent
        </div>
        
        <div>
            @component('admin.components.cards.card', ['title' => 'Tentang Rekomendasi Otomatis'])
                <div class="prose prose-sm text-gray-600">
                    <p>Sistem ini menggunakan algoritma Decision Support System (DSS) untuk merekomendasikan mahasiswa yang paling cocok untuk kompetisi berdasarkan faktor-faktor berikut:</p>
                    
                    <ul class="mt-2 space-y-1 text-sm">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><strong>Keterampilan</strong> - Kecocokan antara keterampilan mahasiswa dan keterampilan yang dibutuhkan untuk kompetisi</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><strong>Prestasi Sebelumnya</strong> - Jumlah dan kualitas prestasi yang telah diperoleh dalam kategori yang sama</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><strong>Prestasi Akademik</strong> - Performa akademik yang relevan dengan jenis kompetisi</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><strong>Pengalaman Lomba</strong> - Pengalaman sebelumnya dalam mengikuti kompetisi serupa</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <h4 class="text-blue-800 font-medium text-sm">Tips Penggunaan:</h4>
                        <ul class="mt-2 text-blue-700 text-xs space-y-1">
                            <li>• Sesuaikan bobot faktor dengan jenis kompetisi</li>
                            <li>• Untuk kompetisi teknis, tingkatkan bobot keterampilan</li>
                            <li>• Untuk kompetisi riset, tingkatkan bobot akademik</li>
                            <li>• Gunakan threshold lebih tinggi (70%+) untuk kompetisi tingkat nasional/internasional</li>
                        </ul>
                    </div>
                </div>
            @endcomponent
            
            @component('admin.components.cards.card', ['title' => 'Rekomendasi Terbaru'])
                @if($latest_recommendations->count() > 0)
                    <div class="space-y-3">
                        @foreach($latest_recommendations as $recommendation)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $recommendation->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($recommendation->user->name) . '&background=4338ca&color=fff' }}" alt="">
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $recommendation->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $recommendation->competition->name }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $recommendation->match_score }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500 text-sm">
                        Belum ada rekomendasi yang dihasilkan.
                    </div>
                @endif
            @endcomponent
        </div>
    </div>
    
    @if(session('generated_recommendations'))
        @component('admin.components.cards.card', ['title' => 'Hasil Rekomendasi Terbaru'])
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kompetisi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Kecocokan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Faktor</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(session('generated_recommendations') as $rec)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($rec['student_name']) . '&background=4338ca&color=fff' }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $rec['student_name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $rec['student_nim'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $rec['program_name'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $rec['competition_name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $rec['competition_organizer'] }}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rec['competition_level'] === 'international' ? 'bg-purple-100 text-purple-800' : ($rec['competition_level'] === 'national' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($rec['competition_level']) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @php
                                            $score = $rec['match_score'];
                                            $scoreColor = $score >= 80 ? 'text-green-600' : ($score >= 50 ? 'text-amber-600' : 'text-red-600');
                                            $bgColor = $score >= 80 ? 'bg-green-100' : ($score >= 50 ? 'bg-amber-100' : 'bg-red-100');
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="{{ $bgColor }} h-2.5 rounded-full" style="width: {{ $score }}%"></div>
                                        </div>
                                        <span class="{{ $scoreColor }} text-sm font-medium">{{ $score }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-600 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span>Keterampilan:</span>
                                            <span>{{ $rec['factors']['skills'] }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Prestasi:</span>
                                            <span>{{ $rec['factors']['achievements'] }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Akademik:</span>
                                            <span>{{ $rec['factors']['academic'] }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Pengalaman:</span>
                                            <span>{{ $rec['factors']['experience'] }}%</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form method="POST" action="{{ route('admin.recommendations.save-generated') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $rec['student_id'] }}">
                                        <input type="hidden" name="competition_id" value="{{ $rec['competition_id'] }}">
                                        <input type="hidden" name="match_score" value="{{ $rec['match_score'] }}">
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900" title="Simpan Rekomendasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 flex justify-between">
                <span class="text-sm text-gray-600">{{ count(session('generated_recommendations')) }} rekomendasi dihasilkan</span>
                <form method="POST" action="{{ route('admin.recommendations.save-all-generated') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Semua Rekomendasi
                    </button>
                </form>
            </div>
        @endcomponent
    @endif
@endcomponent 