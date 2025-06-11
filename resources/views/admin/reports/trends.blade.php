@component('layouts.admin', ['title' => 'Tren Partisipasi'])
    @push('scripts')
    @vite('resources/js/admin/reports/trends.js')
    @endpush

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tren Partisipasi</h1>
        <p class="text-gray-600 mt-1">Analisis perkembangan partisipasi dan capaian mahasiswa dari waktu ke waktu</p>
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
                    'title' => 'Pertumbuhan Tahunan',
                    'value' => $yearlyGrowth . '%',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>',
                    'trend' => 'dari tahun lalu',
                    'trend_positive' => $yearlyGrowthPositive
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Prestasi',
                    'value' => $totalAchievements,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>',
                    'trend' => 'prestasi terverifikasi',
                    'trend_positive' => true
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Partisipan',
                    'value' => $totalParticipants,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => 'mahasiswa unik',
                    'trend_positive' => true
                ])
            </div>

            <!-- Annual Trend Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Tren Tahunan Partisipasi</h2>
                </div>
                <div class="p-4">
                    <div class="h-80">
                        <canvas id="annual-trend-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quarterly Trend Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Tren Partisipasi per Semester</h2>
                </div>
                <div class="p-4">
                    <div class="h-80">
                        <canvas id="quarterly-trend-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Antar Tahun</h2>
                </div>
                <div class="overflow-x-auto">
                    <table id="yearly-comparison-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Partisipasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Prestasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Keberhasilan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($yearlyData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['year'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data['participants'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data['achievements'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data['success_rate'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $data['is_positive'] ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $data['change'] }}
                                        </span>
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