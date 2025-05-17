@component('layouts.admin', ['title' => 'Statistik Prestasi'])
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Statistik Prestasi</h1>
        <p class="text-gray-600 mt-1">Analisis detail statistik capaian prestasi mahasiswa</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            @include('admin.reports.components.sidebar')

            @include('admin.reports.components.achievements-filter')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Achievements by Level Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Jumlah Prestasi per Tingkat</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            <p class="mt-2">Grafik: Diagram lingkaran distribusi prestasi berdasarkan tingkat (Lokal, Regional, Nasional, Internasional)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Study Program Performance -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Performa Program Studi</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2">Grafik: Diagram batang perbandingan prestasi antar program studi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements by Category -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800">Prestasi Berdasarkan Kategori</h2>
                    <div class="flex space-x-2">
                        <button type="button" class="px-3 py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-800">
                            Akademik
                        </button>
                        <button type="button" class="px-3 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800">
                            Non-Akademik
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    @php
                    $categories = [
                        ['name' => 'Pemrograman', 'count' => 47, 'percentage' => 28, 'color' => 'indigo'],
                        ['name' => 'Data Science', 'count' => 38, 'percentage' => 23, 'color' => 'blue'],
                        ['name' => 'UI/UX Design', 'count' => 32, 'percentage' => 19, 'color' => 'purple'],
                        ['name' => 'IoT & Robotika', 'count' => 21, 'percentage' => 13, 'color' => 'emerald'],
                        ['name' => 'Riset & Inovasi', 'count' => 17, 'percentage' => 10, 'color' => 'amber'],
                        ['name' => 'Lainnya', 'count' => 12, 'percentage' => 7, 'color' => 'gray']
                    ];
                    @endphp

                    <div class="space-y-4">
                        @foreach($categories as $category)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $category['name'] }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $category['count'] }} prestasi ({{ $category['percentage'] }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-{{ $category['color'] }}-500 h-2.5 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
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
                    <table class="min-w-full divide-y divide-gray-200">
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
                            @php
                            $programStats = [
                                [
                                    'name' => 'Teknik Informatika',
                                    'total' => 124,
                                    'international' => 26,
                                    'national' => 58,
                                    'regional' => 22,
                                    'local' => 18
                                ],
                                [
                                    'name' => 'Sistem Informasi',
                                    'total' => 87,
                                    'international' => 14,
                                    'national' => 47,
                                    'regional' => 16,
                                    'local' => 10
                                ],
                                [
                                    'name' => 'Manajemen Informatika',
                                    'total' => 37,
                                    'international' => 5,
                                    'national' => 17,
                                    'regional' => 8,
                                    'local' => 7
                                ]
                            ];
                            @endphp

                            @foreach ($programStats as $stats)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $stats['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $stats['total'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats['international'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats['national'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats['regional'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats['local'] }}</div>
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