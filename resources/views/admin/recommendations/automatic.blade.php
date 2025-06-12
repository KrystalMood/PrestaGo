<style>
    /* Custom Select Styling */
    .custom-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        transition: all 0.2s ease;
    }
    
    .custom-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        outline: none;
    }
    
    .custom-select:hover:not(:focus) {
        border-color: #a5b4fc;
    }
    
    /* Dropdown container styling */
    .select-container {
        position: relative;
    }
    
    /* Disabled state styling */
    .custom-select:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    /* Custom Range Input Styling */
    input[type="range"] {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 5px;
        background: #e5e7eb;
        outline: none;
    }
    
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #6366f1;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: all 0.2s ease;
    }
    
    input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #6366f1;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: all 0.2s ease;
    }
    
    input[type="range"]::-webkit-slider-thumb:hover {
        background: #4f46e5;
        transform: scale(1.1);
    }
    
    input[type="range"]::-moz-range-thumb:hover {
        background: #4f46e5;
        transform: scale(1.1);
    }
    
    input[type="range"]:focus {
        outline: none;
    }
    
    input[type="range"]:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    input[type="range"]:focus::-moz-range-thumb {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    /* Range value display */
    .range-value {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 24px;
        padding: 0 6px;
        border-radius: 12px;
        background-color: #f3f4f6;
        color: #4b5563;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    /* Form section styling */
    .form-section {
        border-top: 1px solid #e5e7eb;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .form-section:focus-within {
        border-color: #a5b4fc;
        background-color: rgba(237, 233, 254, 0.1);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin: 1.5rem -1.5rem 1.5rem -1.5rem;
    }
    
    .form-section-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
    }
    
    /* Radio button styling */
    .form-radio {
        width: 1rem;
        height: 1rem;
    }
    
    /* Form field styling */
    .form-field {
        margin-bottom: 1.5rem;
        transition: all 0.2s ease;
    }
    
    .form-field:last-child {
        margin-bottom: 0;
    }
    
    .form-field:focus-within {
        transform: translateX(4px);
    }
    
    .form-field-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 0.5rem;
    }
    
    /* Submit button styling */
    .submit-btn {
        transition: all 0.2s ease;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .submit-btn:active {
        transform: translateY(0);
    }
</style>

@component('layouts.admin', ['title' => 'Rekomendasi Otomatis'])
    @include('admin.components.ui.page-header', [
        'title' => 'Rekomendasi Otomatis',
        'description' => 'Gunakan sistem DSS gabungan AHP-WP untuk menghasilkan rekomendasi kompetisi yang sesuai dengan profil mahasiswa secara otomatis.'
    ])

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

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
                        <div class="form-field">
                            <label for="competition" class="form-field-label">Kompetisi</label>
                            <div class="select-container">
                                <select id="competition" name="competition_id" class="custom-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-2 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">-- Pilih Kompetisi --</option>
                                    @foreach($competitions as $competition)
                                        <option value="{{ $competition->id }}">{{ $competition->name }} ({{ ucfirst($competition->level) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih kompetisi spesifik, atau biarkan kosong untuk menghasilkan rekomendasi untuk semua kompetisi aktif
                            </p>
                        </div>
                        
                        <div id="sub-competition-container">
                            <label for="sub_competition_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Kompetisi</label>
                            <div class="select-container">
                                <select id="sub_competition_id" name="sub_competition_id" class="custom-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-2 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">-- Pilih Sub Kompetisi --</option>
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih sub kompetisi spesifik, atau biarkan kosong untuk semua sub kompetisi dari kompetisi yang dipilih
                            </p>
                        </div>
                        
                        <div>
                            <label for="threshold" class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Skor Kecocokan (%)</label>
                            <div class="flex items-center">
                                <input type="range" id="threshold" name="threshold" min="0" max="100" value="60" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                <span id="threshold-value" class="ml-2 range-value">60%</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Hanya rekomendasikan mahasiswa dengan skor kecocokan di atas nilai ini
                            </p>
                        </div>
                        
                        <div>
                            <label for="max_recommendations" class="block text-sm font-medium text-gray-700 mb-1">Maksimum Rekomendasi per Mahasiswa</label>
                            <div class="select-container">
                                <select id="max_recommendations" name="max_recommendations" class="custom-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-2 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="1">1 rekomendasi</option>
                                    <option value="2">2 rekomendasi</option>
                                    <option value="3" selected>3 rekomendasi</option>
                                    <option value="5">5 rekomendasi</option>
                                    <option value="10">10 rekomendasi</option>
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Berapa banyak rekomendasi kompetisi yang dapat diterima oleh satu mahasiswa
                            </p>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3 form-section-title">Metode DSS</h3>
                            <div class="flex flex-col space-y-3">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600 focus:ring-indigo-500" name="dss_method" value="ahp" checked>
                                    <span class="ml-2">AHP (Analytical Hierarchy Process)</span>
                                    <span class="ml-2 text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800">Untuk Mahasiswa</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600 focus:ring-indigo-500" name="dss_method" value="wp">
                                    <span class="ml-2">WP (Weighted Product)</span>
                                    <span class="ml-2 text-xs px-2.5 py-0.5 rounded-full bg-green-100 text-green-800">Untuk Dosen</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600 focus:ring-indigo-500" name="dss_method" value="hybrid">
                                    <span class="ml-2">Hybrid (AHP + WP)</span>
                                    <span class="ml-2 text-xs px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800">Untuk Keduanya</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Metode AHP untuk mahasiswa, WP untuk dosen, dan Hybrid untuk keduanya
                            </p>
                        </div>

                        <!-- AHP Configuration Section -->
                        <div id="ahp-config" class="form-section">
                            <h3 class="form-section-title">Konfigurasi AHP (Untuk Rekomendasi Mahasiswa)</h3>
                            
                            <div class="mb-4">
                                <label for="ahp_consistency_threshold" class="block text-sm font-medium text-gray-700 mb-1">Batas Rasio Konsistensi</label>
                                <div class="flex items-center">
                                    <input type="range" id="ahp_consistency_threshold" name="ahp_consistency_threshold" min="0" max="0.2" step="0.01" value="0.1" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    <span id="ahp_consistency_threshold_value" class="ml-2 range-value">0.1</span>
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
                                            <span id="ahp_priority_skills_value" class="range-value">5</span>
                                        </label>
                                        <input type="range" id="ahp_priority_skills" name="ahp_priority_skills" min="1" max="9" value="5" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_achievements" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Prestasi</span>
                                            <span id="ahp_priority_achievements_value" class="range-value">4</span>
                                        </label>
                                        <input type="range" id="ahp_priority_achievements" name="ahp_priority_achievements" min="1" max="9" value="4" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_interests" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Minat</span>
                                            <span id="ahp_priority_interests_value" class="range-value">4</span>
                                        </label>
                                        <input type="range" id="ahp_priority_interests" name="ahp_priority_interests" min="1" max="9" value="4" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_deadline" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Tenggat Waktu</span>
                                            <span id="ahp_priority_deadline_value" class="range-value">3</span>
                                        </label>
                                        <input type="range" id="ahp_priority_deadline" name="ahp_priority_deadline" min="1" max="9" value="3" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="ahp_priority_competition_level" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Tingkat Lomba</span>
                                            <span id="ahp_priority_competition_level_value" class="range-value">6</span>
                                        </label>
                                        <input type="range" id="ahp_priority_competition_level" name="ahp_priority_competition_level" min="1" max="9" value="6" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Tingkat kepentingan relatif antar kriteria (skala 1-9)
                                </p>
                            </div>
                        </div>
                        
                        <!-- WP Configuration Section -->
                        <div id="wp-config" class="form-section hidden">
                            <h3 class="form-section-title">Konfigurasi Weighted Product</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bobot Kriteria WP</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                                    <div>
                                        <label for="wp_weight_skills" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Keterampilan</span>
                                            <span id="wp_weight_skills_value" class="range-value">0.3</span>
                                        </label>
                                        <input type="range" id="wp_weight_skills" name="wp_weight_skills" min="0.1" max="1" step="0.1" value="0.3" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="wp_weight_interests" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Minat</span>
                                            <span id="wp_weight_interests_value" class="range-value">0.2</span>
                                        </label>
                                        <input type="range" id="wp_weight_interests" name="wp_weight_interests" min="0.1" max="1" step="0.1" value="0.2" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="wp_weight_competition_level" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Tingkat Lomba</span>
                                            <span id="wp_weight_competition_level_value" class="range-value">0.2</span>
                                        </label>
                                        <input type="range" id="wp_weight_competition_level" name="wp_weight_competition_level" min="0.1" max="1" step="0.1" value="0.2" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="wp_weight_deadline" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Tenggat Waktu</span>
                                            <span id="wp_weight_deadline_value" class="range-value">0.2</span>
                                        </label>
                                        <input type="range" id="wp_weight_deadline" name="wp_weight_deadline" min="0.1" max="1" step="0.1" value="0.2" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                    
                                    <div>
                                        <label for="wp_weight_activity_rating" class="flex justify-between items-center text-sm text-gray-700">
                                            <span>Rating Dosen</span>
                                            <span id="wp_weight_activity_rating_value" class="range-value">0.1</span>
                                        </label>
                                        <input type="range" id="wp_weight_activity_rating" name="wp_weight_activity_rating" min="0.1" max="1" step="0.1" value="0.1" class="w-full h-2 rounded-lg appearance-none cursor-pointer">
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Bobot untuk masing-masing kriteria (total harus 1.0)
                                </p>
                                <div class="mt-2">
                                    <div class="flex items-center">
                                        <span class="text-xs text-gray-600 mr-2">Total Bobot:</span>
                                        <span id="wp_total_weight" class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-800">1.0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" id="generate-btn" class="submit-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
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
                    
                    <div class="mt-4 bg-green-50 p-3 rounded-lg border border-green-100">
                        <h4 class="text-green-800 font-medium text-sm">Tentang Metode WP:</h4>
                        <ul class="mt-2 text-green-700 text-xs space-y-1">
                            <li>• <strong>WP (Weighted Product)</strong> - Metode untuk menghitung rekomendasi dosen berdasarkan pembobotan kriteria</li>
                            <li>• WP memperhitungkan keterampilan, minat, tingkat lomba, dan rating dosen dalam menentukan rekomendasi dosen</li>
                            <li>• Rating dosen diambil dari feedback mahasiswa tentang aktivitas dosen dalam kompetisi sebelumnya</li>
                            <li>• Metode ini menggunakan perkalian untuk menghubungkan rating atribut, dimana setiap rating atribut dipangkatkan terlebih dahulu dengan bobot atribut yang bersangkutan</li>
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
                            <li class="flex items-start">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">WP</span> 
                                <span>Tingkatkan bobot keterampilan untuk kompetisi yang membutuhkan keahlian teknis spesifik</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-xs mr-1 flex-shrink-0">WP</span> 
                                <span>Tingkatkan bobot tingkat lomba untuk mendorong dosen membimbing di kompetisi internasional/nasional</span>
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
                @php
                    // Use refreshed recommendations from session if available
                    $latestRecs = session('latest_recommendations') ?? $latest_recommendations;
                @endphp
                @if($latestRecs->count() > 0)
                    <div class="space-y-3">
                        <div class="text-xs text-gray-600 mb-2">
                            Rekomendasi yang telah disimpan dalam database:
                        </div>
                        @foreach($latestRecs as $recommendation)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $recommendation->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($recommendation->user->name) . '&background=4338ca&color=fff' }}" alt="">
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $recommendation->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $recommendation->competition->name }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recommendation->for_lecturer ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $recommendation->for_lecturer ? 'Dosen' : 'Mahasiswa' }}
                                    </span>
                                    @if($recommendation->ahp_result_id)
                                        <span class="inline-flex items-center ml-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            AHP: {{ number_format($recommendation->ahpResult->final_score * 100, 1) }}%
                                        </span>
                                    @endif
                                    @if($recommendation->wp_result_id)
                                        <span class="inline-flex items-center ml-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            WP: {{ number_format($recommendation->wpResult->final_score * 100, 1) }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500 text-sm">
                        Belum ada rekomendasi yang disimpan dalam database.
                    </div>
                @endif
            @endcomponent
        </div>
    </div>
    
    @if(session('generated_recommendations'))
        @component('admin.components.cards.card', ['title' => 'Hasil Rekomendasi Terbaru', 'id' => 'hasil-rekomendasi'])
            <div class="mb-3 bg-blue-50 p-3 rounded-lg border border-blue-100">
                <p class="text-sm text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Rekomendasi ini baru dihasilkan dan belum disimpan dalam database. Gunakan tombol "Simpan" untuk menyimpan rekomendasi yang dipilih atau "Simpan Semua" untuk menyimpan semua rekomendasi.
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa/Dosen</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kompetisi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Kecocokan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Faktor</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="recommendation-table-body-session">
                        @foreach(session('generated_recommendations') as $rec)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($rec['user_name']) . '&background=4338ca&color=fff' }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $rec['user_name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $rec['user_identifier'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $rec['program_studi'] }}</div>
                                            <div class="text-xs mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $rec['user_type'] === 'Dosen' ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                    {{ $rec['user_type'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $rec['competition_name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $rec['competition_level'] }}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ strtolower($rec['competition_level']) === 'international' ? 'bg-purple-100 text-purple-800' : 
                                              (strtolower($rec['competition_level']) === 'national' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $rec['competition_level'] }}
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
                                        @if(isset($rec['factors']['skills']))
                                        <div class="flex items-center justify-between">
                                            <span>Keterampilan:</span>
                                            <span>{{ $rec['factors']['skills'] }}%</span>
                                        </div>
                                        @endif
                                        
                                        @if(isset($rec['factors']['achievements']))
                                        <div class="flex items-center justify-between">
                                            <span>Prestasi:</span>
                                            <span>{{ $rec['factors']['achievements'] }}%</span>
                                        </div>
                                        @endif
                                        
                                        @if(isset($rec['factors']['interests']))
                                        <div class="flex items-center justify-between">
                                            <span>Minat:</span>
                                            <span>{{ $rec['factors']['interests'] }}%</span>
                                        </div>
                                        @endif
                                        
                                        @if(isset($rec['factors']['deadline']))
                                        <div class="flex items-center justify-between">
                                            <span>Tenggat:</span>
                                            <span>{{ $rec['factors']['deadline'] }}%</span>
                                        </div>
                                        @endif
                                        
                                        @if(isset($rec['factors']['competition_level']))
                                        <div class="flex items-center justify-between">
                                            <span>Tingkat Lomba:</span>
                                            <span>{{ $rec['factors']['competition_level'] }}%</span>
                                        </div>
                                        @endif
                                        
                                        @if(isset($rec['factors']['activity_rating']))
                                        <div class="flex items-center justify-between">
                                            <span>Rating Dosen:</span>
                                            <span>{{ $rec['factors']['activity_rating'] }}%</span>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <form method="POST" action="{{ route('admin.recommendations.save-generated') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $rec['user_id'] }}">
                                            <input type="hidden" name="competition_id" value="{{ $rec['competition_id'] }}">
                                            <input type="hidden" name="match_score" value="{{ $rec['match_score'] }}">
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900" title="Simpan Rekomendasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </button>
                                        </form>
                                        <button class="delete-recommendation text-red-600 hover:text-red-900" 
                                                data-user-id="{{ $rec['user_id'] }}" 
                                                data-competition-id="{{ $rec['competition_id'] }}"
                                                data-from-session="true"
                                                title="Hapus Rekomendasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination container for session recommendations -->
            <div id="recommendation-pagination-session" class="mt-4 flex justify-center"></div>
            
            <div class="mt-4 flex justify-between">
                <span class="text-sm text-gray-600" id="session-recommendations-count">{{ count(session('generated_recommendations')) }} rekomendasi dihasilkan</span>
                <div class="flex space-x-2">
                    <form method="POST" action="{{ route('admin.recommendations.save-all-generated') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Semua Rekomendasi
                        </button>
                    </form>
                    <button id="delete-all-recommendations-session" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Hapus Semua Rekomendasi
                    </button>
                </div>
            </div>
        @endcomponent
    @endif

    <div id="recommendation-results" class="hidden">
        <!-- This section will be permanently hidden -->
    </div>
@endcomponent 

<!-- Modal untuk konfirmasi hapus -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md mx-auto p-6 w-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Konfirmasi</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500 close-modal">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="modal-message">Apakah Anda yakin ingin menghapus semua rekomendasi yang dihasilkan?</p>
        </div>
        <div class="flex justify-end space-x-3">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 close-modal">
                Batal
            </button>
            <button type="button" id="confirm-action" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to recommendations section if present
    @if(session('generated_recommendations'))
        const recommendationsSection = document.getElementById('hasil-rekomendasi');
        if (recommendationsSection) {
            recommendationsSection.scrollIntoView({ behavior: 'smooth' });
        }
    @endif

    // Method selection logic
    const ahpRadio = document.querySelector('input[name="dss_method"][value="ahp"]');
    const wpRadio = document.querySelector('input[name="dss_method"][value="wp"]');
    const hybridRadio = document.querySelector('input[name="dss_method"][value="hybrid"]');
    const ahpConfig = document.getElementById('ahp-config');
    const wpConfig = document.getElementById('wp-config');
    
    function updateMethodConfig() {
        if (ahpRadio.checked) {
            ahpConfig.classList.remove('hidden');
            wpConfig.classList.add('hidden');
        } else if (wpRadio.checked) {
            ahpConfig.classList.add('hidden');
            wpConfig.classList.remove('hidden');
        } else if (hybridRadio.checked) {
            // For hybrid, show both configurations
            ahpConfig.classList.remove('hidden');
            wpConfig.classList.remove('hidden');
        }
    }
    
    ahpRadio.addEventListener('change', updateMethodConfig);
    wpRadio.addEventListener('change', updateMethodConfig);
    hybridRadio.addEventListener('change', updateMethodConfig);
    
    // Threshold slider
    const thresholdSlider = document.getElementById('threshold');
    const thresholdValue = document.getElementById('threshold-value');
    
    thresholdSlider.addEventListener('input', function() {
        thresholdValue.textContent = this.value + '%';
    });
    
    // AHP consistency threshold slider
    const ahpConsistencySlider = document.getElementById('ahp_consistency_threshold');
    const ahpConsistencyValue = document.getElementById('ahp_consistency_threshold_value');
    
    ahpConsistencySlider.addEventListener('input', function() {
        ahpConsistencyValue.textContent = this.value;
    });
    
    // AHP priority sliders
    const ahpPrioritySliders = [
        { slider: document.getElementById('ahp_priority_skills'), value: document.getElementById('ahp_priority_skills_value') },
        { slider: document.getElementById('ahp_priority_achievements'), value: document.getElementById('ahp_priority_achievements_value') },
        { slider: document.getElementById('ahp_priority_interests'), value: document.getElementById('ahp_priority_interests_value') },
        { slider: document.getElementById('ahp_priority_deadline'), value: document.getElementById('ahp_priority_deadline_value') },
        { slider: document.getElementById('ahp_priority_competition_level'), value: document.getElementById('ahp_priority_competition_level_value') }
    ];
    
    ahpPrioritySliders.forEach(item => {
        item.slider.addEventListener('input', function() {
            item.value.textContent = this.value;
        });
    });
    
    // WP weight sliders
    const wpWeightSkills = document.getElementById('wp_weight_skills');
    const wpWeightInterests = document.getElementById('wp_weight_interests');
    const wpWeightCompetitionLevel = document.getElementById('wp_weight_competition_level');
    const wpWeightDeadline = document.getElementById('wp_weight_deadline');
    const wpWeightActivityRating = document.getElementById('wp_weight_activity_rating');
    const wpWeightSkillsValue = document.getElementById('wp_weight_skills_value');
    const wpWeightInterestsValue = document.getElementById('wp_weight_interests_value');
    const wpWeightCompetitionLevelValue = document.getElementById('wp_weight_competition_level_value');
    const wpWeightDeadlineValue = document.getElementById('wp_weight_deadline_value');
    const wpWeightActivityRatingValue = document.getElementById('wp_weight_activity_rating_value');
    const wpTotalWeight = document.getElementById('wp_total_weight');
    
    function updateWPWeights() {
        const skillsWeight = parseFloat(wpWeightSkills.value);
        const interestsWeight = parseFloat(wpWeightInterests.value);
        const competitionLevelWeight = parseFloat(wpWeightCompetitionLevel.value);
        const deadlineWeight = parseFloat(wpWeightDeadline.value);
        const activityRatingWeight = parseFloat(wpWeightActivityRating.value);
        
        wpWeightSkillsValue.textContent = skillsWeight.toFixed(1);
        wpWeightInterestsValue.textContent = interestsWeight.toFixed(1);
        wpWeightCompetitionLevelValue.textContent = competitionLevelWeight.toFixed(1);
        wpWeightDeadlineValue.textContent = deadlineWeight.toFixed(1);
        wpWeightActivityRatingValue.textContent = activityRatingWeight.toFixed(1);
        
        // Ensure total is 1.0
        const total = skillsWeight + interestsWeight + competitionLevelWeight + deadlineWeight + activityRatingWeight;
        wpTotalWeight.textContent = total.toFixed(1);
        
        // Update visual feedback on total
        if (Math.abs(total - 1.0) < 0.01) {
            wpTotalWeight.classList.remove('bg-red-100', 'text-red-800');
            wpTotalWeight.classList.add('bg-green-100', 'text-green-800');
        } else {
            wpTotalWeight.classList.remove('bg-green-100', 'text-green-800');
            wpTotalWeight.classList.add('bg-red-100', 'text-red-800');
        }
    }
    
    wpWeightSkills.addEventListener('input', updateWPWeights);
    wpWeightInterests.addEventListener('input', updateWPWeights);
    wpWeightCompetitionLevel.addEventListener('input', updateWPWeights);
    wpWeightDeadline.addEventListener('input', updateWPWeights);
    wpWeightActivityRating.addEventListener('input', updateWPWeights);
    
    // Initialize
    updateMethodConfig();
    updateWPWeights();
    
    // Competition dropdown change handler
    const competitionDropdown = document.getElementById('competition');
    const subCompetitionContainer = document.getElementById('sub-competition-container');
    const subCompetitionDropdown = document.getElementById('sub_competition_id');
    
    competitionDropdown.addEventListener('change', function() {
        const competitionId = this.value;
        
        if (competitionId) {
            // Show sub-competition dropdown and fetch options via AJAX
            subCompetitionContainer.classList.remove('hidden');
            // Clear existing options
            subCompetitionDropdown.innerHTML = '<option value="">-- Pilih Sub Kompetisi --</option>';
            
            // Here you would typically fetch sub-competitions via AJAX
            // For example:
            /*
            fetch(`/api/competitions/${competitionId}/sub-competitions`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subComp => {
                        const option = document.createElement('option');
                        option.value = subComp.id;
                        option.textContent = subComp.name;
                        subCompetitionDropdown.appendChild(option);
                    });
                });
            */
        } else {
            // Hide sub-competition dropdown if no competition selected
            subCompetitionContainer.classList.add('hidden');
        }
    });
    
    // Form submission
    const form = document.getElementById('recommendation-form');
    const generateBtn = document.getElementById('generate-btn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        generateBtn.disabled = true;
        generateBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menghasilkan...';
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            generateBtn.disabled = false;
            generateBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg> Hasilkan Rekomendasi';
            
            if (data.success) {
                showToast('Rekomendasi berhasil disimpan!', 'success');
                // Reload page to show new recommendations
                window.location.reload();
            } else {
                showToast('Gagal menyimpan rekomendasi: ' + data.message, 'error');
            }
        })
        .catch(error => {
            generateBtn.disabled = false;
            generateBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg> Hasilkan Rekomendasi';
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghasilkan rekomendasi.', 'error');
        });
    });
    
    // Modal functions
    function showModal(title, message, confirmCallback, buttonText = 'Ya, Hapus') {
        const modal = document.getElementById('confirmation-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmButton = document.getElementById('confirm-action');
        
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        confirmButton.textContent = buttonText;
        
        // Show the modal
        modal.classList.remove('hidden');
        
        // Set up the confirm button action
        confirmButton.onclick = function() {
            // Hide the modal
            modal.classList.add('hidden');
            // Execute the callback
            if (typeof confirmCallback === 'function') {
                confirmCallback();
            }
        };
        
        // Set up close buttons
        const closeButtons = modal.querySelectorAll('.close-modal');
        closeButtons.forEach(button => {
            button.onclick = function() {
                modal.classList.add('hidden');
            };
        });
    }
    
    // Toast notification functions
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        // Set toast class based on type
        let bgColor, textColor, borderColor;
        switch (type) {
            case 'error':
                bgColor = 'bg-red-100';
                textColor = 'text-red-700';
                borderColor = 'border-red-400';
                break;
            case 'warning':
                bgColor = 'bg-yellow-100';
                textColor = 'text-yellow-700';
                borderColor = 'border-yellow-400';
                break;
            case 'info':
                bgColor = 'bg-blue-100';
                textColor = 'text-blue-700';
                borderColor = 'border-blue-400';
                break;
            case 'success':
            default:
                bgColor = 'bg-green-100';
                textColor = 'text-green-700';
                borderColor = 'border-green-400';
                break;
        }
        
        toast.className = `${bgColor} ${textColor} px-4 py-3 rounded-lg border ${borderColor} shadow-md mb-3 flex items-center justify-between transform transition-all duration-500 ease-in-out opacity-0 translate-x-full`;
        
        toast.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    ${type === 'error' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>' :
                      type === 'warning' ? '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>' :
                      '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'}
                </svg>
                <div>${message}</div>
            </div>
            <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-x-full');
        }, 10);
        
        // Auto remove after 5 seconds
        const autoRemoveTimeout = setTimeout(() => {
            removeToast(toast);
        }, 5000);
        
        // Close button event
        toast.querySelector('button').addEventListener('click', () => {
            clearTimeout(autoRemoveTimeout);
            removeToast(toast);
        });
    }
    
    function removeToast(toast) {
        toast.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 500);
    }
});
</script> 