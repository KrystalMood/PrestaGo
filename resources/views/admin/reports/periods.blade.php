@component('layouts.admin', ['title' => 'Perbandingan Periode'])
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Perbandingan Periode</h1>
        <p class="text-gray-600 mt-1">Analisis dan perbandingan prestasi antar periode akademik</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            @include('admin.reports.components.sidebar')

            @include('admin.reports.components.periods-filter')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Periods Summary -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Ringkasan Perbandingan</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <h3 class="text-lg font-semibold text-blue-800">2024/2025 - Semester 1</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Total Prestasi:</span>
                                    <span class="text-sm font-medium text-gray-900">156</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Partisipasi:</span>
                                    <span class="text-sm font-medium text-gray-900">348 Mahasiswa</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tingkat Internasional:</span>
                                    <span class="text-sm font-medium text-gray-900">28 Prestasi</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tingkat Nasional:</span>
                                    <span class="text-sm font-medium text-gray-900">75 Prestasi</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                            <h3 class="text-lg font-semibold text-indigo-800">2023/2024 - Semester 2</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Total Prestasi:</span>
                                    <span class="text-sm font-medium text-gray-900">134</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Partisipasi:</span>
                                    <span class="text-sm font-medium text-gray-900">302 Mahasiswa</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tingkat Internasional:</span>
                                    <span class="text-sm font-medium text-gray-900">22 Prestasi</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tingkat Nasional:</span>
                                    <span class="text-sm font-medium text-gray-900">65 Prestasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 border border-green-100 bg-green-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800">Perubahan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
                            <div>
                                <p class="text-xs text-gray-500">Total Prestasi</p>
                                <p class="text-xl font-bold text-green-600">+16.4%</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Partisipasi</p>
                                <p class="text-xl font-bold text-green-600">+15.2%</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Internasional</p>
                                <p class="text-xl font-bold text-green-600">+27.3%</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Nasional</p>
                                <p class="text-xl font-bold text-green-600">+15.4%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison Charts -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Visual</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="mt-2">Chart: Grafik perbandingan statistik prestasi antar periode</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Comparison -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Kategori Prestasi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">2024/2025-1</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">2023/2024-2</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            $categoryComparisons = [
                                [
                                    'name' => 'Pemrograman',
                                    'period1' => 42,
                                    'period2' => 35,
                                    'change' => '+20.0%',
                                    'positive' => true
                                ],
                                [
                                    'name' => 'Data Science & AI',
                                    'period1' => 34,
                                    'period2' => 25,
                                    'change' => '+36.0%',
                                    'positive' => true
                                ],
                                [
                                    'name' => 'UI/UX Design',
                                    'period1' => 28,
                                    'period2' => 22,
                                    'change' => '+27.3%',
                                    'positive' => true
                                ],
                                [
                                    'name' => 'IoT & Embedded Systems',
                                    'period1' => 15,
                                    'period2' => 18,
                                    'change' => '-16.7%',
                                    'positive' => false
                                ],
                                [
                                    'name' => 'Mobile Development',
                                    'period1' => 25,
                                    'period2' => 20,
                                    'change' => '+25.0%',
                                    'positive' => true
                                ],
                                [
                                    'name' => 'Cyber Security',
                                    'period1' => 12,
                                    'period2' => 14,
                                    'change' => '-14.3%',
                                    'positive' => false
                                ],
                            ];
                            @endphp

                            @foreach ($categoryComparisons as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $category['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $category['period1'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $category['period2'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $category['positive'] ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $category['change'] }}
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