@php
    $ahpDetails = isset($ahpResult) && isset($ahpResult->calculation_details) 
        ? json_decode($ahpResult->calculation_details, true) 
        : null;
        
    if ($ahpDetails && isset($ahpDetails['pairwise_matrix']) && isset($ahpDetails['criteria_weights'])) {
        if (!isset($ahpDetails['column_sums'])) {
            $ahpDetails['column_sums'] = [];
            foreach (array_keys($ahpDetails['pairwise_matrix']) as $col) {
                $sum = 0;
                foreach ($ahpDetails['pairwise_matrix'] as $row) {
                    $sum += $row[$col] ?? 0;
                }
                $ahpDetails['column_sums'][$col] = $sum;
            }
        }
        
        if (!isset($ahpDetails['normalized_matrix'])) {
            $ahpDetails['normalized_matrix'] = [];
            foreach ($ahpDetails['pairwise_matrix'] as $rowKey => $row) {
                $ahpDetails['normalized_matrix'][$rowKey] = [];
                foreach ($row as $colKey => $value) {
                    if (isset($ahpDetails['column_sums'][$colKey]) && $ahpDetails['column_sums'][$colKey] != 0) {
                        $ahpDetails['normalized_matrix'][$rowKey][$colKey] = $value / $ahpDetails['column_sums'][$colKey];
                    } else {
                        $ahpDetails['normalized_matrix'][$rowKey][$colKey] = 0;
                    }
                }
            }
        }
        
        if (!isset($ahpDetails['consistency_vector'])) {
            $ahpDetails['consistency_vector'] = [];
            $weights_values = array_values($ahpDetails['criteria_weights']);

            foreach ($ahpDetails['pairwise_matrix'] as $rowKey => $row) {
                $row_values = array_values($row);
                $sum = 0;
                for ($i = 0; $i < count($row_values); $i++) {
                    $sum += $row_values[$i] * ($weights_values[$i] ?? 0);
                }
                $ahpDetails['consistency_vector'][$rowKey] = $sum;
            }
            
            if (!isset($ahpDetails['lambda_max'])) {
                $sum = 0;
                $n = count($ahpDetails['criteria_weights']);
                if ($n > 0) {
                    $consistency_vector_values = array_values($ahpDetails['consistency_vector']);
                    $weights_values_for_lambda = array_values($ahpDetails['criteria_weights']);

                    for ($i = 0; $i < $n; $i++) {
                        if (isset($weights_values_for_lambda[$i]) && $weights_values_for_lambda[$i] != 0) {
                            $sum += $consistency_vector_values[$i] / $weights_values_for_lambda[$i];
                        }
                    }
                    $ahpDetails['lambda_max'] = $sum / $n;
                    
                    $ahpDetails['consistency_index'] = ($n > 1) ? ($ahpDetails['lambda_max'] - $n) / ($n - 1) : 0;
                    
                    $randomIndexTable = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
                    $index = max(0, min(9, $n - 1));
                    $ahpDetails['random_index'] = $randomIndexTable[$index];
                    
                    if ($ahpDetails['random_index'] != 0) {
                        $cr = $ahpDetails['consistency_index'] / $ahpDetails['random_index'];
                        $ahpResult->consistency_ratio = $cr;
                        $ahpResult->is_consistent = abs($cr) < 0.1; // Standard consistency check
                    } else {
                        $ahpResult->consistency_ratio = 0;
                        $ahpResult->is_consistent = true;
                    }
                } else {
                    $ahpDetails['lambda_max'] = 0;
                    $ahpDetails['consistency_index'] = 0;
                    $ahpDetails['random_index'] = 0;
                    if (!isset($ahpResult->consistency_ratio)) {
                        $ahpResult->consistency_ratio = 0;
                        $ahpResult->is_consistent = true;
                    }
                }
            }
        }
    }
@endphp

@if($ahpDetails)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-indigo-50">
            <h3 class="text-lg leading-6 font-medium text-indigo-900">
                Langkah-langkah Perhitungan AHP (Analytical Hierarchy Process)
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-indigo-700">
                Detail perhitungan matematis untuk mendapatkan rekomendasi
            </p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6 space-y-6">
            <!-- Step 1: Pairwise Comparison Matrix -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-sm mr-2">1</span>
                    Matriks Perbandingan Berpasangan
                </h4>
                <p class="text-sm text-gray-600">
                    Matriks perbandingan berpasangan adalah dasar dari metode AHP yang menunjukkan perbandingan kepentingan antar kriteria.
                    Nilai dalam matriks menunjukkan seberapa penting kriteria baris dibandingkan dengan kriteria kolom.
                </p>
                
                @if(isset($ahpDetails['pairwise_matrix']))
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                    @foreach(array_keys($ahpDetails['pairwise_matrix']) as $criterion)
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($ahpDetails['pairwise_matrix'] as $criterion => $values)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                        </td>
                                        @foreach($values as $value)
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($value, 4) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-xs text-gray-500 italic mt-2">
                        Nilai 1 pada diagonal utama menunjukkan perbandingan kriteria dengan dirinya sendiri.
                        Nilai > 1 menunjukkan kriteria baris lebih penting dari kriteria kolom.
                        Nilai < 1 menunjukkan kriteria baris kurang penting dari kriteria kolom.
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Data matriks perbandingan tidak tersedia</div>
                @endif
            </div>
            
            <!-- Step 2: Column Sum and Normalization -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-sm mr-2">2</span>
                    Penjumlahan Kolom dan Normalisasi
                </h4>
                <p class="text-sm text-gray-600">
                    Untuk mendapatkan matriks ternormalisasi, setiap elemen matriks dibagi dengan jumlah kolom masing-masing.
                    Langkah ini menghasilkan nilai relatif antar kriteria dalam skala yang sama.
                </p>
                
                @if(isset($ahpDetails['column_sums']) && isset($ahpDetails['normalized_matrix']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Column Sums -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Jumlah Kolom:</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            @foreach(array_keys($ahpDetails['column_sums']) as $criterion)
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr>
                                            @foreach($ahpDetails['column_sums'] as $sum)
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700 font-medium">
                                                    {{ number_format($sum, 4) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Formula Explanation -->
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Rumus Normalisasi:</h5>
                            <div class="text-sm text-gray-600">
                                <p>Untuk setiap elemen matriks (a<sub>ij</sub>), nilai normalisasi (n<sub>ij</sub>) dihitung dengan:</p>
                                <div class="bg-white p-2 rounded mt-1 text-center">
                                    <span>n<sub>ij</sub> = a<sub>ij</sub> / Σa<sub>kj</sub></span>
                                </div>
                                <p class="mt-1">Dimana Σa<sub>kj</sub> adalah jumlah kolom j</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Normalized Matrix -->
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Matriks Ternormalisasi:</h5>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                        @foreach(array_keys($ahpDetails['normalized_matrix']) as $criterion)
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($ahpDetails['normalized_matrix'] as $criterion => $values)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                            </td>
                                            @foreach($values as $value)
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                    {{ number_format($value, 4) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">
                        Data normalisasi dihitung ulang berdasarkan matrik perbandingan berpasangan. 
                        Sebaiknya simpan nilai ini di database untuk perhitungan yang lebih akurat.
                    </div>
                @endif
            </div>
            
            <!-- Step 3: Calculate Criteria Weights -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-sm mr-2">3</span>
                    Perhitungan Bobot Kriteria
                </h4>
                <p class="text-sm text-gray-600">
                    Bobot kriteria dihitung dengan merata-ratakan nilai setiap baris pada matriks ternormalisasi.
                    Bobot ini menunjukkan tingkat kepentingan relatif dari setiap kriteria.
                </p>
                
                @if(isset($ahpDetails['criteria_weights']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Weights Table -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Bobot Kriteria:</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($ahpDetails['criteria_weights'] as $criterion => $weight)
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                    {{ number_format($weight, 4) }} ({{ number_format($weight * 100, 2) }}%)
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Formula Explanation -->
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Rumus Perhitungan Bobot:</h5>
                            <div class="text-sm text-gray-600">
                                <p>Untuk setiap kriteria i, bobot (w<sub>i</sub>) dihitung dengan:</p>
                                <div class="bg-white p-2 rounded mt-1 text-center">
                                    <span>w<sub>i</sub> = (Σn<sub>ij</sub>) / n</span>
                                </div>
                                <p class="mt-1">Dimana:</p>
                                <ul class="list-disc list-inside mt-1">
                                    <li>Σn<sub>ij</sub> adalah jumlah nilai ternormalisasi pada baris i</li>
                                    <li>n adalah jumlah kriteria</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Data bobot kriteria tidak tersedia</div>
                @endif
            </div>
            
            <!-- Step 4: Consistency Check -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-sm mr-2">4</span>
                    Uji Konsistensi
                </h4>
                <p class="text-sm text-gray-600">
                    Uji konsistensi dilakukan untuk memastikan bahwa penilaian perbandingan berpasangan bersifat konsisten.
                    Nilai CR (Consistency Ratio) yang dapat diterima adalah < 0.1 (10%).
                </p>
                
                @if(isset($ahpDetails['consistency_vector']) && isset($ahpResult->consistency_ratio))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Consistency Vector -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Vektor Konsistensi:</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($ahpDetails['consistency_vector'] as $criterion => $value)
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                    {{ number_format($value, 4) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Consistency Calculation -->
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Hasil Uji Konsistensi:</h5>
                            <div class="space-y-2">
                                @if(isset($ahpDetails['lambda_max']))
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">λ<sub>max</sub> (Lambda Max):</span>
                                        <span class="text-sm font-medium">{{ number_format($ahpDetails['lambda_max'], 4) }}</span>
                                    </div>
                                @endif
                                
                                @if(isset($ahpDetails['consistency_index']))
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">CI (Consistency Index):</span>
                                        <span class="text-sm font-medium">{{ number_format($ahpDetails['consistency_index'], 4) }}</span>
                                    </div>
                                @endif
                                
                                @if(isset($ahpDetails['random_index']))
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">RI (Random Index):</span>
                                        <span class="text-sm font-medium">{{ number_format($ahpDetails['random_index'], 4) }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between font-medium">
                                    <span class="text-sm text-gray-600">CR (Consistency Ratio):</span>
                                    <span class="text-sm {{ $ahpResult->is_consistent ? 'text-green-600' : 'text-amber-600' }}">
                                        {{ number_format($ahpResult->consistency_ratio, 4) }}
                                        @if($ahpResult->is_consistent)
                                            <span class="text-xs text-green-600 ml-1">(Konsisten)</span>
                                        @else
                                            <span class="text-xs text-amber-600 ml-1">(Tidak Konsisten)</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-3 text-xs text-gray-600">
                                <p><strong>Rumus Perhitungan:</strong></p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>CI = (λ<sub>max</sub> - n) / (n - 1)</li>
                                    <li>CR = CI / RI</li>
                                    <li>Dimana n adalah jumlah kriteria</li>
                                    <li>RI adalah Random Index yang nilainya bergantung pada jumlah kriteria</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">
                        Data uji konsistensi dihitung ulang berdasarkan bobot kriteria dan matriks perbandingan.
                        Sebaiknya simpan nilai ini di database untuk perhitungan yang lebih akurat.
                    </div>
                @endif
            </div>
            
            <!-- Step 5: Alternative Evaluation -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-sm mr-2">5</span>
                    Evaluasi Alternatif
                </h4>
                <p class="text-sm text-gray-600">
                    Setelah mendapatkan bobot kriteria, langkah selanjutnya adalah mengevaluasi alternatif (kompetisi) 
                    terhadap kriteria yang ada untuk mendapatkan skor akhir.
                </p>
                
                @if(isset($ahpDetails['factor_scores']))
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Skor Faktor:</h5>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faktor</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor (%)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($ahpDetails['factor_scores'] as $factor => $score)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $factor)) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($score, 2) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                
                @if(isset($ahpDetails['weighted_scores']))
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Skor Tertimbang:</h5>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faktor</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor (%)</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Tertimbang (%)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($ahpDetails['weighted_scores'] as $factor => $weightedScore)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $factor)) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($ahpDetails['factor_scores'][$factor] ?? 0, 2) }}%
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                @php
                                                    $criterionKey = '';
                                                    foreach(array_keys($ahpDetails['criteria_weights']) as $key) {
                                                        if(strpos(strtolower($factor), strtolower(str_replace('_', ' ', $key))) !== false) {
                                                            $criterionKey = $key;
                                                            break;
                                                        }
                                                    }
                                                    $weight = $criterionKey ? ($ahpDetails['criteria_weights'][$criterionKey] ?? 0) : 0;
                                                @endphp
                                                {{ number_format($weight, 4) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-indigo-700">
                                                {{ number_format($weightedScore, 2) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-indigo-50">
                                    <tr>
                                        <td colspan="3" class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            Total (Skor Akhir):
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-indigo-700">
                                            {{ number_format($ahpResult->final_score * 100, 2) }}%
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="text-xs text-gray-500 italic mt-2">
                            Skor tertimbang dihitung dengan mengalikan skor faktor dengan bobot kriteria terkait.
                            Skor akhir adalah jumlah dari semua skor tertimbang.
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Step 6: Final Result -->
            <div class="space-y-3">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-sm mr-2">6</span>
                    Hasil Akhir
                </h4>
                
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Skor Akhir Rekomendasi:</span>
                        <span class="text-lg font-bold text-indigo-700">{{ number_format($ahpResult->final_score * 100, 2) }}%</span>
                    </div>
                    
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $ahpResult->final_score * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-sm">
                        <p class="font-medium text-gray-700">Interpretasi:</p>
                        <p class="text-gray-600 mt-1">
                            @if($ahpResult->final_score >= 0.8)
                                Rekomendasi sangat kuat (80%+) - Kompetisi ini sangat cocok berdasarkan kriteria yang dievaluasi.
                            @elseif($ahpResult->final_score >= 0.7)
                                Rekomendasi kuat (70-79%) - Kompetisi ini cocok berdasarkan kriteria yang dievaluasi.
                            @elseif($ahpResult->final_score >= 0.6)
                                Rekomendasi cukup (60-69%) - Kompetisi ini cukup cocok berdasarkan kriteria yang dievaluasi.
                            @elseif($ahpResult->final_score >= 0.5)
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
        <p class="text-gray-500">Data perhitungan AHP tidak tersedia</p>
    </div>
@endif 