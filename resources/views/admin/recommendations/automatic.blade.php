@component('layouts.admin', ['title' => 'Rekomendasi Otomatis'])
    @include('admin.components.ui.page-header', [
        'title' => 'Rekomendasi Otomatis',
        'description' => 'Gunakan sistem DSS gabungan AHP-WP untuk menghasilkan rekomendasi kompetisi yang sesuai dengan profil mahasiswa secara otomatis.'
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
                        
                        <div id="sub-competition-container">
                            <label for="sub_competition_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Kompetisi</label>
                            <select id="sub_competition_id" name="sub_competition_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">-- Semua Sub Kompetisi --</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih sub kompetisi spesifik, atau biarkan kosong untuk semua sub kompetisi dari kompetisi yang dipilih
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
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Metode DSS</h3>
                            <div class="flex flex-col space-y-3">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio" name="dss_method" value="ahp" checked>
                                    <span class="ml-2">AHP (Analytical Hierarchy Process)</span>
                                    <span class="ml-2 text-xs px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full">Untuk Mahasiswa</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Metode AHP digunakan untuk perhitungan rekomendasi mahasiswa
                            </p>
                        </div>

                        <!-- AHP Configuration Section -->
                        <div id="ahp-config-section" class="border-t border-gray-200 pt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Konfigurasi AHP (Untuk Rekomendasi Mahasiswa)</h3>
                            
                            <div class="mb-4">
                                <label for="ahp_consistency_threshold" class="block text-sm font-medium text-gray-700 mb-1">Batas Rasio Konsistensi</label>
                                <div class="flex items-center">
                                    <input type="range" id="ahp_consistency_threshold" name="ahp_consistency_threshold" min="0" max="0.2" step="0.01" value="0.1" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    <span id="ahp_consistency_threshold_value" class="ml-2 text-sm text-gray-600 w-10">0.1</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Nilai maksimum rasio konsistensi yang diterima (standar: 0.1)
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas Kriteria AHP</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ahp_priority_skills" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Keterampilan</span>
                                            <span id="ahp_priority_skills_value">5</span>
                                        </label>
                                        <input type="range" id="ahp_priority_skills" name="ahp_priority_skills" min="1" max="9" value="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_achievements" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Prestasi</span>
                                            <span id="ahp_priority_achievements_value">4</span>
                                        </label>
                                        <input type="range" id="ahp_priority_achievements" name="ahp_priority_achievements" min="1" max="9" value="4" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_interests" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Minat</span>
                                            <span id="ahp_priority_interests_value">4</span>
                                        </label>
                                        <input type="range" id="ahp_priority_interests" name="ahp_priority_interests" min="1" max="9" value="4" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_deadline" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Tenggat Waktu</span>
                                            <span id="ahp_priority_deadline_value">3</span>
                                        </label>
                                        <input type="range" id="ahp_priority_deadline" name="ahp_priority_deadline" min="1" max="9" value="3" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_competition_level" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Tingkat Lomba</span>
                                            <span id="ahp_priority_competition_level_value">6</span>
                                        </label>
                                        <input type="range" id="ahp_priority_competition_level" name="ahp_priority_competition_level" min="1" max="9" value="6" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Tingkat kepentingan relatif antar kriteria (skala 1-9)
                                </p>
                            </div>
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
                
                <!-- Menggunakan file JS terpisah -->
                @vite(['resources/js/admin/recommendations.js'])
            @endcomponent
        </div>
        
        <div>
            @component('admin.components.cards.card', ['title' => 'Tentang Rekomendasi Otomatis'])
                <div class="prose prose-sm text-gray-600">
                    <p>Sistem ini menggunakan metode DSS AHP (Analytical Hierarchy Process) untuk merekomendasikan mahasiswa yang paling cocok untuk kompetisi berdasarkan faktor-faktor berikut:</p>
                    
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
                            <span><strong>Minat</strong> - Kesesuaian minat mahasiswa dengan bidang kompetisi</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><strong>Tenggat Waktu</strong> - Urgensi berdasarkan jarak waktu hingga batas pendaftaran</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><strong>Tingkat Lomba</strong> - Tingkat kompetisi (internasional, nasional, provinsi, dll)</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <h4 class="text-blue-800 font-medium text-sm">Tentang Metode AHP:</h4>
                        <ul class="mt-2 text-blue-700 text-xs space-y-1">
                            <li>• <strong>AHP (Analytical Hierarchy Process)</strong> - Metode untuk menentukan prioritas kriteria dan menghitung skor kecocokan mahasiswa dengan kompetisi</li>
                            <li>• AHP memperhitungkan keterampilan, prestasi, dan minat mahasiswa dalam menentukan rekomendasi</li>
                            <li>• Metode ini menerapkan perbandingan berpasangan antar kriteria untuk menghasilkan bobot yang optimal</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <h4 class="text-blue-800 font-medium text-sm">Tips Penggunaan:</h4>
                        <ul class="mt-2 text-blue-700 text-xs space-y-1">
                            <li class="flex items-start">
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">AHP</span> 
                                <span>Sesuaikan prioritas kriteria AHP dengan jenis kompetisi untuk rekomendasi mahasiswa</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">AHP</span> 
                                <span>Untuk kompetisi teknis, tingkatkan prioritas keterampilan</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">AHP</span> 
                                <span>Pastikan rasio konsistensi AHP < 0.1 untuk hasil yang valid</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">AHP</span> 
                                <span>Tingkatkan prioritas tenggat waktu untuk kompetisi yang mendekati deadline</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">AHP</span> 
                                <span>Tingkatkan prioritas tingkat lomba untuk mendorong partisipasi di kompetisi internasional/nasional</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">Umum</span> 
                                <span>Gunakan threshold lebih tinggi (70%+) untuk kompetisi tingkat nasional/internasional</span>
                            </li>
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
                                    @if($recommendation->ahp_result_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            AHP: {{ number_format($recommendation->ahpResult->final_score * 100, 1) }}%
                                        </span>
                                    @endif
                                    @if($recommendation->wp_result_id)
                                        <span class="inline-flex items-center ml-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            WP: {{ number_format($recommendation->wpResult->relative_preference * 100, 1) }}%
                                        </span>
                                    @endif
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
                                            <span>Minat:</span>
                                            <span>{{ $rec['factors']['interests'] }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Tenggat:</span>
                                            <span>{{ $rec['factors']['deadline'] ?? 'N/A' }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Tingkat Lomba:</span>
                                            <span>{{ $rec['factors']['competition_level'] ?? 'N/A' }}%</span>
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