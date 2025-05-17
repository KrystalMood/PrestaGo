@component('layouts.admin', ['title' => 'Tren Partisipasi'])
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tren Partisipasi</h1>
        <p class="text-gray-600 mt-1">Analisis perkembangan partisipasi dan capaian mahasiswa dari waktu ke waktu</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            @include('admin.reports.components.sidebar')

            @include('admin.reports.components.trends-filter')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Trend Highlights -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-custom p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-medium text-gray-900">Pertumbuhan Partisipasi</h3>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900">+27.4%</p>
                                <p class="ml-2 text-sm text-gray-600">dari tahun lalu</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-custom p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-medium text-gray-900">Kompetisi Internasional</h3>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900">+53%</p>
                                <p class="ml-2 text-sm text-gray-600">pertumbuhan 5 tahun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Annual Trend Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800">Tren Tahunan Partisipasi</h2>
                    <div class="flex space-x-2">
                        <button type="button" class="px-3 py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-800">
                            5 Tahun
                        </button>
                        <button type="button" class="px-3 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800">
                            10 Tahun
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            <p class="mt-2">Chart: Grafik tren partisipasi mahasiswa dalam 5 tahun terakhir</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quarterly Trend Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Tren Partisipasi per Semester</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            <p class="mt-2">Chart: Grafik tren partisipasi per semester dalam 3 tahun terakhir</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Antar Tahun</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
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
                            @php
                            $yearlyData = [
                                [
                                    'year' => '2025',
                                    'participation' => 315,
                                    'achievements' => 168,
                                    'success_rate' => '53.3%',
                                    'change' => '+14.2%',
                                    'positive' => true
                                ],
                                [
                                    'year' => '2024',
                                    'participation' => 276,
                                    'achievements' => 147,
                                    'success_rate' => '53.3%',
                                    'change' => '+8.9%',
                                    'positive' => true
                                ],
                                [
                                    'year' => '2023',
                                    'participation' => 245,
                                    'achievements' => 135,
                                    'success_rate' => '55.1%',
                                    'change' => '+12.5%',
                                    'positive' => true
                                ],
                                [
                                    'year' => '2022',
                                    'participation' => 218,
                                    'achievements' => 120,
                                    'success_rate' => '55.0%',
                                    'change' => '+4.3%',
                                    'positive' => true
                                ],
                                [
                                    'year' => '2021',
                                    'participation' => 192,
                                    'achievements' => 115,
                                    'success_rate' => '59.9%',
                                    'change' => '-2.5%',
                                    'positive' => false
                                ],
                            ];
                            @endphp

                            @foreach ($yearlyData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['year'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data['participation'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data['achievements'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data['success_rate'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $data['positive'] ? 'text-green-600' : 'text-red-600' }}">
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