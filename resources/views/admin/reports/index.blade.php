@component('layouts.admin', ['title' => 'Laporan dan Analisis'])
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan dan Analisis</h1>
        <p class="text-gray-600 mt-1">Kelola dan analisis laporan prestasi mahasiswa</p>
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
                                'url' => '/admin/reports',
                                'active' => true
                            ],
                            [
                                'title' => 'Statistik Prestasi', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                                'url' => '/admin/reports/achievements',
                                'active' => false
                            ],
                            [
                                'title' => 'Analisis Program Studi', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                                'url' => '/admin/reports/programs',
                                'active' => false
                            ],
                            [
                                'title' => 'Tren Partisipasi', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>',
                                'url' => '/admin/reports/trends',
                                'active' => false
                            ],
                            [
                                'title' => 'Perbandingan Periode', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                                'url' => '/admin/reports/periods',
                                'active' => false
                            ],
                            [
                                'title' => 'Ekspor Laporan', 
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                                'url' => '/admin/reports/export',
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
                                <label for="program" class="block text-sm font-medium text-gray-700">Program Studi</label>
                                <select id="program" name="program" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="all">Semua Program Studi</option>
                                    <option value="ti">Teknik Informatika</option>
                                    <option value="si">Sistem Informasi</option>
                                    <option value="mi">Manajemen Informatika</option>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Summary Cards -->
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Prestasi',
                    'value' => '248',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => '12.5%',
                    'trend_positive' => true
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Tingkat Nasional',
                    'value' => '142',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" /></svg>',
                    'trend' => '8.3%',
                    'trend_positive' => true
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Tingkat Internasional',
                    'value' => '45',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => '15.8%',
                    'trend_positive' => true
                ])
            </div>

            <!-- Achievement Distribution Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Distribusi Prestasi</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="mt-2">Chart: Distribusi prestasi berdasarkan tingkat kompetisi dan program studi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement Trends Chart -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Tren Prestasi Tahunan</h2>
                </div>
                <div class="p-4">
                    <div class="h-80 flex items-center justify-center">
                        <!-- Placeholder for chart -->
                        <div class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
            </svg>
                            <p class="mt-2">Chart: Tren prestasi mahasiswa dalam 5 tahun terakhir</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Achievements Table -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Prestasi Tertinggi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            $achievements = [
                                [
                                    'student' => 'Ahmad Rizki',
                                    'avatar' => 'https://ui-avatars.com/api/?name=Ahmad+Rizki&background=4338ca&color=fff',
                                    'program' => 'Teknik Informatika',
                                    'achievement' => 'Juara 1 International Hackathon 2025',
                                    'level' => 'Internasional',
                                    'date' => '15 Mar 2025'
                                ],
                                [
                                    'student' => 'Anisa Putri',
                                    'avatar' => 'https://ui-avatars.com/api/?name=Anisa+Putri&background=4338ca&color=fff',
                                    'program' => 'Sistem Informasi',
                                    'achievement' => 'Best Paper Award ICSET Conference',
                                    'level' => 'Internasional',
                                    'date' => '22 Feb 2025'
                                ],
                                [
                                    'student' => 'Budi Santoso',
                                    'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=4338ca&color=fff',
                                    'program' => 'Teknik Informatika',
                                    'achievement' => 'Juara 1 Gemastik XVI',
                                    'level' => 'Nasional',
                                    'date' => '10 Jan 2025'
                                ],
                                [
                                    'student' => 'Dewi Lestari',
                                    'avatar' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=4338ca&color=fff',
                                    'program' => 'Manajemen Informatika',
                                    'achievement' => 'Juara 2 ASEAN Data Science Competition',
                                    'level' => 'Internasional',
                                    'date' => '5 Dec 2024'
                                ],
                                [
                                    'student' => 'Eko Prasetyo',
                                    'avatar' => 'https://ui-avatars.com/api/?name=Eko+Prasetyo&background=4338ca&color=fff',
                                    'program' => 'Sistem Informasi',
                                    'achievement' => 'Juara 1 National UI/UX Challenge',
                                    'level' => 'Nasional',
                                    'date' => '18 Nov 2024'
                                ],
                            ];
                            @endphp

                            @foreach ($achievements as $achievement)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $achievement['avatar'] }}" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $achievement['student'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $achievement['program'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $achievement['achievement'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $achievement['level'] == 'Internasional' ? 'green' : 'blue' }}-100 text-{{ $achievement['level'] == 'Internasional' ? 'green' : 'blue' }}-800">
                                            {{ $achievement['level'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $achievement['date'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-center">
                    <button type="button" class="text-indigo-600 hover:text-indigo-900">
                        Lihat Semua Prestasi <span aria-hidden="true">→</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endcomponent 