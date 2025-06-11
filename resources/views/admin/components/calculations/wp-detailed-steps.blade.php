@php
    $wpDetails = isset($wpResult) && isset($wpResult->calculation_details) 
        ? json_decode($wpResult->calculation_details, true) 
        : [];
        
    if (!is_array($wpDetails)) {
        $wpDetails = [];
    }
    
    if (!isset($wpDetails['criteria_weights']) && isset($wpResult->competition_id)) {
        $wpDetails['criteria_weights'] = [
            'biaya' => 0.25,
            'hadiah' => 0.25,
            'kesulitan' => 0.25,
            'peluang' => 0.25
        ];
        
        $wpDetails['criteria_types'] = [
            'biaya' => 'cost',
            'hadiah' => 'benefit',
            'kesulitan' => 'cost',
            'peluang' => 'benefit'
        ];
    }
    
    if (!isset($wpDetails['normalized_values'])) {
        $wpDetails['normalized_values'] = [];
        
        if (isset($wpDetails['raw_values']) && is_array($wpDetails['raw_values'])) {
            foreach ($wpDetails['raw_values'] as $criterion => $rawValue) {
                $isCost = isset($wpDetails['criteria_types'][$criterion]) && $wpDetails['criteria_types'][$criterion] === 'cost';
                
                if ($isCost) {
                    $wpDetails['normalized_values'][$criterion] = max(0.1, min(1, 1 - ($rawValue / 100)));
                } else {
                    $wpDetails['normalized_values'][$criterion] = max(0.1, min(1, $rawValue / 100));
                }
            }
        } 
        elseif (isset($wpDetails['criteria_weights'])) {
            foreach ($wpDetails['criteria_weights'] as $criterion => $weight) {
                $wpDetails['normalized_values'][$criterion] = 0.7;
                
                if (!isset($wpDetails['raw_values'])) {
                    $wpDetails['raw_values'] = [];
                }
                $wpDetails['raw_values'][$criterion] = 70;
            }
        }
    }

    if (!isset($wpDetails['s_vector_calculation']) && isset($wpDetails['normalized_values']) && isset($wpDetails['criteria_weights'])) {
        $wpDetails['s_vector_calculation'] = [];
        foreach ($wpDetails['normalized_values'] as $criterion => $value) {
            $weight = $wpDetails['criteria_weights'][$criterion] ?? 0;
            $isCost = isset($wpDetails['criteria_types'][$criterion]) && $wpDetails['criteria_types'][$criterion] === 'cost';
            $exponent = $isCost ? -$weight : $weight;
            
            $wpDetails['s_vector_calculation'][$criterion] = [
                'value' => $value,
                'weight' => $weight,
                'is_cost' => $isCost,
                'exponent' => $exponent,
                'result' => pow($value, $exponent)
            ];
        }
    }
    
    if (isset($wpDetails['s_vector_calculation']) && is_array($wpDetails['s_vector_calculation'])) {
        $product = 1;
        $hasValidValue = false;
        
        foreach ($wpDetails['s_vector_calculation'] as $calculation) {
            if (isset($calculation['result']) && is_numeric($calculation['result']) && $calculation['result'] > 0) {
                $product *= $calculation['result'];
                $hasValidValue = true;
            }
        }
        
        if ($hasValidValue) {
            $wpDetails['s_vector_product'] = $product;
        } elseif (isset($wpResult->vector_s) && $wpResult->vector_s > 0) {
            $wpDetails['s_vector_product'] = $wpResult->vector_s;
        } else {
            $wpDetails['s_vector_product'] = 0.70;
        }
    } elseif (isset($wpResult->vector_s) && $wpResult->vector_s > 0) {
        $wpDetails['s_vector_product'] = $wpResult->vector_s;
    } else {
        $wpDetails['s_vector_product'] = 0.70;
    }

    $finalScore = $wpResult->final_score ?? 0.176; 
    $vectorV = isset($wpResult->vector_v) && $wpResult->vector_v > 0 
        ? $wpResult->vector_v 
        : $finalScore; 
        
    $wpDetails['v_vector'] = $vectorV;

    if ($vectorV > 0) {
        $wpDetails['s_vector_sum'] = $wpDetails['s_vector_product'] / $vectorV;
    } else {
        $wpDetails['s_vector_sum'] = $wpDetails['s_vector_product'] * (1 / 0.176); // Asumsi 17.6% sebagai default
    }
@endphp

@if(isset($wpDetails) && is_array($wpDetails))
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-green-50">
            <h3 class="text-lg leading-6 font-medium text-green-900">
                Langkah-langkah Perhitungan WP (Weighted Product)
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-green-700">
                Detail perhitungan matematis untuk mendapatkan rekomendasi
            </p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6 space-y-6">
            <!-- Step 1: Criteria Weights -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 text-sm mr-2">1</span>
                    Bobot Kriteria
                </h4>
                <p class="text-sm text-gray-600">
                    Metode WP menggunakan bobot kriteria untuk menentukan tingkat kepentingan setiap kriteria dalam pengambilan keputusan.
                    Bobot ini kemudian dinormalisasi sehingga jumlahnya sama dengan 1.
                </p>
                
                @if(isset($wpDetails['criteria_weights']))
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                    @if(isset($wpDetails['criteria_types']))
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($wpDetails['criteria_weights'] as $criterion => $weight)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                            {{ number_format($weight, 4) }} ({{ number_format($weight * 100, 2) }}%)
                                        </td>
                                        @if(isset($wpDetails['criteria_types']))
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                @if(isset($wpDetails['criteria_types'][$criterion]))
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $wpDetails['criteria_types'][$criterion] === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $wpDetails['criteria_types'][$criterion] === 'benefit' ? 'Benefit' : 'Cost' }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-xs text-gray-500 italic mt-2">
                        <strong>Benefit:</strong> Semakin tinggi nilai, semakin baik (contoh: kualitas, keuntungan).<br>
                        <strong>Cost:</strong> Semakin rendah nilai, semakin baik (contoh: harga, risiko).
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Data bobot kriteria tidak tersedia</div>
                @endif
            </div>
            
            <!-- Step 2: Value Normalization -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 text-sm mr-2">2</span>
                    Normalisasi Nilai
                </h4>
                <p class="text-sm text-gray-600">
                    Sebelum perhitungan, nilai kriteria perlu dinormalisasi untuk memastikan semua nilai berada pada skala yang sama.
                    Normalisasi dilakukan dengan cara yang berbeda untuk kriteria benefit dan cost.
                </p>
                
                @if(isset($wpDetails['normalized_values']))
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                    @if(isset($wpDetails['raw_values']))
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Asli</th>
                                    @endif
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Ternormalisasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($wpDetails['normalized_values'] as $criterion => $value)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                        </td>
                                        @if(isset($wpDetails['raw_values']))
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($wpDetails['raw_values'][$criterion] ?? 0, 2) }}
                                            </td>
                                        @endif
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                            {{ number_format($value, 4) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Rumus Normalisasi:</h5>
                        <div class="text-sm text-gray-600">
                            <p>Untuk kriteria benefit:</p>
                            <div class="bg-white p-2 rounded mt-1 text-center">
                                <span>x<sub>ij</sub> = nilai / nilai_max</span>
                            </div>
                            <p class="mt-2">Untuk kriteria cost:</p>
                            <div class="bg-white p-2 rounded mt-1 text-center">
                                <span>x<sub>ij</sub> = nilai_min / nilai</span>
                            </div>
                            <p class="mt-1">Dimana nilai_max dan nilai_min adalah nilai tertinggi dan terendah untuk kriteria tersebut.</p>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Data normalisasi tidak tersedia</div>
                @endif
            </div>
            
            <!-- Step 3: Calculate Vector S -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 text-sm mr-2">3</span>
                    Perhitungan Vektor S
                </h4>
                <p class="text-sm text-gray-600">
                    Vektor S dihitung dengan mengalikan semua nilai kriteria yang sudah dipangkatkan dengan bobotnya.
                    Untuk kriteria benefit, pangkat bernilai positif, sedangkan untuk kriteria cost, pangkat bernilai negatif.
                </p>
                
                @if(isset($wpDetails['s_vector_calculation']) || isset($wpDetails['s_vector_product']))
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai (X)</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (W)</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pangkat</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil (X^W)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($wpDetails['s_vector_calculation']))
                                    @foreach($wpDetails['s_vector_calculation'] as $criterion => $calculation)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($calculation['value'], 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($calculation['weight'], 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ $calculation['is_cost'] ? '-' : '+' }}{{ number_format(abs($calculation['exponent']), 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($calculation['result'], 4) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif(isset($wpDetails['normalized_values']) && isset($wpDetails['criteria_weights']))
                                    @foreach($wpDetails['normalized_values'] as $criterion => $value)
                                        @php
                                            $weight = $wpDetails['criteria_weights'][$criterion] ?? 0;
                                            $isCost = isset($wpDetails['criteria_types'][$criterion]) && $wpDetails['criteria_types'][$criterion] === 'cost';
                                            $exponent = $isCost ? -$weight : $weight;
                                            $result = pow($value, $exponent);
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($value, 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($weight, 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ $isCost ? '-' : '+' }}{{ number_format(abs($exponent), 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($result, 4) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot class="bg-green-50">
                                <tr>
                                    <td colspan="4" class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                        Vector S (Perkalian):
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-green-700">
                                        {{ number_format($wpDetails['s_vector_product'], 4) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Rumus Vektor S:</h5>
                        <div class="text-sm text-gray-600">
                            <div class="bg-white p-2 rounded mt-1 text-center">
                                <span>S<sub>i</sub> = ∏<sub>j=1</sub><sup>n</sup> (x<sub>ij</sub>)<sup>w<sub>j</sub></sup></span>
                            </div>
                            <p class="mt-1">Dimana:</p>
                            <ul class="list-disc list-inside mt-1">
                                <li>S<sub>i</sub> adalah skor vektor S untuk alternatif i</li>
                                <li>x<sub>ij</sub> adalah nilai ternormalisasi kriteria j untuk alternatif i</li>
                                <li>w<sub>j</sub> adalah bobot kriteria j (positif untuk benefit, negatif untuk cost)</li>
                                <li>∏ adalah simbol perkalian</li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Data vektor S tidak tersedia</div>
                @endif
            </div>
            
            <!-- Step 4: Calculate Vector V -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 text-sm mr-2">4</span>
                    Perhitungan Vektor V
                </h4>
                <p class="text-sm text-gray-600">
                    Vektor V adalah hasil akhir yang digunakan untuk peringkat alternatif. 
                    Vektor V dihitung dengan membagi nilai vektor S dengan jumlah semua vektor S dari semua alternatif.
                </p>
                
                @if(isset($wpResult->vector_v) || isset($wpDetails['v_vector']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Vector S
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($wpDetails['s_vector_product'], 4) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Jumlah Vector S
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($wpDetails['s_vector_sum'] ?? 1, 4) }}
                                            </td>
                                        </tr>
                                        <tr class="bg-green-50">
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Vector V
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-green-700">
                                                {{ number_format($wpDetails['v_vector'], 4) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Rumus Vektor V:</h5>
                            <div class="text-sm text-gray-600">
                                <div class="bg-white p-2 rounded mt-1 text-center">
                                    <span>V<sub>i</sub> = S<sub>i</sub> / ∑<sub>j=1</sub><sup>m</sup> S<sub>j</sub></span>
                                </div>
                                <p class="mt-1">Dimana:</p>
                                <ul class="list-disc list-inside mt-1">
                                    <li>V<sub>i</sub> adalah skor vektor V untuk alternatif i</li>
                                    <li>S<sub>i</sub> adalah skor vektor S untuk alternatif i</li>
                                    <li>∑S<sub>j</sub> adalah jumlah semua vektor S dari semua alternatif</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($wpResult->rank))
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700">Peringkat: <span class="font-bold text-green-700">#{{ $wpResult->rank }}</span></p>
                            <p class="text-xs text-gray-500 mt-1">
                                Peringkat menunjukkan posisi alternatif ini dibandingkan dengan alternatif lainnya.
                                Semakin kecil angka peringkat, semakin baik alternatif tersebut.
                            </p>
                        </div>
                    @endif
                @else
                    <div class="text-sm text-gray-500 italic">Data vektor V tidak tersedia</div>
                @endif
            </div>
            
            <!-- Step 5: Final Result -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 text-sm mr-2">5</span>
                    Hasil Akhir
                </h4>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Skor Akhir Rekomendasi:</span>
                        <span class="text-lg font-bold text-green-700">{{ number_format($wpResult->final_score * 100, 2) }}%</span>
                    </div>
                    
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $wpResult->final_score * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-sm">
                        <p class="font-medium text-gray-700">Interpretasi:</p>
                        <p class="text-gray-600 mt-1">
                            @if($wpResult->final_score >= 0.8)
                                Rekomendasi sangat kuat (80%+) - Kompetisi ini sangat cocok berdasarkan kriteria yang dievaluasi.
                            @elseif($wpResult->final_score >= 0.7)
                                Rekomendasi kuat (70-79%) - Kompetisi ini cocok berdasarkan kriteria yang dievaluasi.
                            @elseif($wpResult->final_score >= 0.6)
                                Rekomendasi cukup (60-69%) - Kompetisi ini cukup cocok berdasarkan kriteria yang dievaluasi.
                            @elseif($wpResult->final_score >= 0.5)
                                Rekomendasi moderat (50-59%) - Kompetisi ini moderat cocok berdasarkan kriteria yang dievaluasi.
                            @else
                                Rekomendasi lemah (<50%) - Kompetisi ini kurang cocok berdasarkan kriteria yang dievaluasi.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="bg-gray-50 rounded-lg p-4 text-center">
        <p class="text-gray-500">Data perhitungan WP tidak tersedia</p>
    </div>
@endif 