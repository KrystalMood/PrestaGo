@component('layouts.admin', ['title' => 'Statistik Prestasi'])
    @push('scripts')
    @vite('resources/js/admin/reports/achievements.js')
    @endpush

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Statistik Prestasi</h1>
        <p class="text-gray-600 mt-1">Analisis detail statistik capaian prestasi mahasiswa</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            @include('admin.reports.components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Prestasi',
                    'value' => $totalAchievements,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => $yearlyGrowth['percentage'] . '%',
                    'trend_positive' => $yearlyGrowth['is_positive']
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Tingkat Nasional',
                    'value' => $nationalAchievements,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" /></svg>',
                    'trend' => number_format(($nationalAchievements / max(1, $totalAchievements)) * 100, 1) . '%',
                    'trend_positive' => true
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Tingkat Internasional',
                    'value' => $internationalAchievements,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => number_format(($internationalAchievements / max(1, $totalAchievements)) * 100, 1) . '%',
                    'trend_positive' => true
                ])
            </div>
            
            <!-- Achievements by Level Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Jumlah Prestasi per Tingkat</h2>
                </div>
                <div class="p-4">
                    <div class="h-80">
                        <canvas id="achievements-by-level-chart" 
                            data-local="{{ $localAchievements }}" 
                            data-regional="{{ $regionalAchievements }}" 
                            data-national="{{ $nationalAchievements }}" 
                            data-international="{{ $internationalAchievements }}">
                        </canvas>
                    </div>
                </div>
            </div>

            <!-- Study Program Performance -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Performa Program Studi</h2>
                </div>
                <div class="p-4">
                    <div class="h-80">
                        <canvas id="study-program-performance-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Achievements by Category -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Prestasi Berdasarkan Kategori</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @php
                        // Calculate total achievements for percentage calculation
                        $totalCategoryAchievements = array_sum(array_column($categoryAchievements, 'count'));
                        
                        // Define colors for categories
                        $colors = ['indigo', 'blue', 'purple', 'emerald', 'amber', 'green', 'red', 'gray'];
                        @endphp
                        
                        @foreach($categoryAchievements as $index => $category)
                        @php
                        // Calculate percentage
                        $percentage = $totalCategoryAchievements > 0 
                            ? round(($category['count'] / $totalCategoryAchievements) * 100) 
                            : 0;
                        
                        // Assign color (cycle through colors array)
                        $color = $colors[$index % count($colors)];
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $category['name'] }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $category['count'] }} prestasi ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-{{ $color }}-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Achievement Details Table -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Rincian Prestasi per Program Studi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table id="program-stats-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Prestasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Internasional</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nasional</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regional</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($programStats as $stats)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $stats->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $stats->total }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats->international }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats->national }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats->regional }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats->local }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcomponent 