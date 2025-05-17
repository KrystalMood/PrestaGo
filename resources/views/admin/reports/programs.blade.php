@component('layouts.admin', ['title' => 'Analisis Program Studi'])
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Analisis Program Studi</h1>
        <p class="text-gray-600 mt-1">Analisis perbandingan dan performa antar program studi</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 bg-indigo-50">
                    <h2 class="font-semibold text-indigo-800">Navigasi Laporan</h2>
                </div>
                <div class="p-2">
                    <nav class="space-y-1">
                        @php
                        $navItems = [
                            [
                                'title' => 'Dashboard Laporan', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
                                'url' => route('admin.reports.index'),
                                'active' => false
                            ],
                            [
                                'title' => 'Statistik Prestasi', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                                'url' => route('admin.reports.achievements'),
                                'active' => false
                            ],
                            [
                                'title' => 'Analisis Program Studi', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                                'url' => route('admin.reports.programs'),
                                'active' => true
                            ],
                            [
                                'title' => 'Tren Partisipasi', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>',
                                'url' => route('admin.reports.trends'),
                                'active' => false
                            ],
                            [
                                'title' => 'Perbandingan Periode', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                                'url' => route('admin.reports.periods'),
                                'active' => false
                            ],
                            [
                                'title' => 'Ekspor Laporan', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                                'url' => route('admin.reports.export'),
                                'active' => false
                            ]
                        ];
                        @endphp

                        @foreach ($navItems as $item)
                            <a href="{{ $item['url'] }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $item['active'] ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="mr-3">{!! $item['icon'] !!}</span>
                                <span>{{ $item['title'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom overflow-hidden mt-6">
                <div class="p-4 bg-emerald-50">
                    <h2 class="font-semibold text-emerald-800">Filter Data</h2>
                </div>
                <div class="p-4">
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700">Periode</label>
                                <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="all">Semua Periode</option>
                                    <option value="2024-2">2024/2025 - Semester 2</option>
                                    <option value="2024-1">2024/2025 - Semester 1</option>
                                    <option value="2023-2">2023/2024 - Semester 2</option>
                                </select>
                            </div>
                            <div>
                                <label for="competition_type" class="block text-sm font-medium text-gray-700">Jenis Kompetisi</label>
                                <select id="competition_type" name="competition_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="all">Semua Jenis</option>
                                    <option value="academic">Akademik</option>
                                    <option value="non-academic">Non-Akademik</option>
                                </select>
                            </div>
                            <div>
                                <label for="metric" class="block text-sm font-medium text-gray-700">Metrik</label>
                                <select id="metric" name="metric" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="achievements">Jumlah Prestasi</option>
                                    <option value="participation">Tingkat Partisipasi</option>
                                    <option value="success_rate">Tingkat Keberhasilan</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Program Study Comparison -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Program Studi</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2">Chart: Perbandingan performa antar program studi berdasarkan jumlah prestasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performing Program Details -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Program Studi Terbaik</h2>
                </div>
                <div class="p-4">
                    @php
                    $topProgram = [
                        'name' => 'Teknik Informatika',
                        'total_achievements' => 124,
                        'international' => 26,
                        'national' => 58,
                        'top_categories' => ['Pemrograman', 'Data Science', 'UI/UX Design'],
                        'growth' => 12.5
                    ];
                    @endphp

                    <div class="bg-indigo-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-indigo-900">{{ $topProgram['name'] }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <p class="text-sm text-gray-500">Total Prestasi</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $topProgram['total_achievements'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Internasional</p>
                                <p class="text-2xl font-bold text-indigo-600">{{ $topProgram['international'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nasional</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $topProgram['national'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Pertumbuhan</p>
                                <p class="text-2xl font-bold text-green-600">+{{ $topProgram['growth'] }}%</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Kategori Terbaik</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($topProgram['top_categories'] as $category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $category }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Program Study Comparison Table -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Detail per Program Studi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Mahasiswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Prestasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partisipasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Keberhasilan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            $programDetails = [
                                [
                                    'name' => 'Teknik Informatika',
                                    'students' => 450,
                                    'achievements' => 124,
                                    'participation' => '32%',
                                    'success_rate' => '56%'
                                ],
                                [
                                    'name' => 'Sistem Informasi',
                                    'students' => 380,
                                    'achievements' => 87,
                                    'participation' => '28%',
                                    'success_rate' => '52%'
                                ],
                                [
                                    'name' => 'Manajemen Informatika',
                                    'students' => 220,
                                    'achievements' => 37,
                                    'participation' => '22%',
                                    'success_rate' => '48%'
                                ]
                            ];
                            @endphp

                            @foreach ($programDetails as $program)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $program['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $program['students'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $program['achievements'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $program['participation'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $program['success_rate'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
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