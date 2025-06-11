@component('layouts.admin', ['title' => 'Laporan & Statistik'])
    @push('scripts')
    @vite('resources/js/admin/reports/index.js')
    @endpush

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan & Statistik</h1>
        <p class="text-gray-600 mt-1">Analisis dan laporan prestasi mahasiswa</p>
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
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Prestasi',
                    'value' => $totalAchievements ?? 248,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => ($growthPercentage ?? 12.5) . '%',
                    'trend_positive' => true
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Tingkat Nasional',
                    'value' => $nationalAchievements ?? 142,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" /></svg>',
                    'trend' => '57.3%',
                    'trend_positive' => true
                ])
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Tingkat Internasional',
                    'value' => $internationalAchievements ?? 45,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'trend' => '18.1%',
                    'trend_positive' => true
                ])
            </div>

            <!-- Recent Achievement Trends -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Tren Prestasi Terkini</h2>
                </div>
                <div class="p-4">
                    <div class="h-80">
                        <canvas id="achievement-trend-chart"></canvas>
                    </div>
                    <div id="achievement-trends-container" class="hidden">
                        @if(isset($achievementTrends))
                            @foreach($achievementTrends as $trend)
                                <div class="trend-item" data-month="{{ $trend['month'] }}" data-count="{{ $trend['count'] }}"></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Achievement Distribution by Level -->
                <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Distribusi Prestasi</h2>
                    </div>
                    <div class="p-4">
                        <div class="h-64">
                            <canvas id="achievements-by-level-chart" 
                                data-local="{{ $localAchievements ?? 61 }}" 
                                data-regional="{{ $regionalAchievements ?? 0 }}" 
                                data-national="{{ $nationalAchievements ?? 142 }}" 
                                data-international="{{ $internationalAchievements ?? 45 }}">
                            </canvas>
                        </div>
                    </div>
                </div>

                <!-- Program Study Comparison -->
                <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Perbandingan Program Studi</h2>
                    </div>
                    <div class="p-4">
                        <div class="h-64">
                            <canvas id="program-comparison-chart"></canvas>
                        </div>
                        <div id="program-data-container" class="hidden">
                            @if(isset($programAchievements))
                                @foreach($programAchievements as $program)
                                    <div class="program-data" data-name="{{ $program->name }}" data-achievements="{{ $program->achievement_count }}"></div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Reports -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Akses Cepat Laporan</h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('admin.reports.achievements') }}" class="block p-4 bg-indigo-50 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <h3 class="ml-2 text-lg font-medium text-indigo-900">Statistik Prestasi</h3>
                            </div>
                            <p class="text-sm text-indigo-700">Analisis detail statistik capaian prestasi mahasiswa</p>
                        </a>
                        
                        <a href="{{ route('admin.reports.programs') }}" class="block p-4 bg-blue-50 rounded-lg border border-blue-100 hover:bg-blue-100 transition">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <h3 class="ml-2 text-lg font-medium text-blue-900">Analisis Program Studi</h3>
                            </div>
                            <p class="text-sm text-blue-700">Analisis perbandingan dan performa antar program studi</p>
                        </a>
                        
                        <a href="{{ route('admin.reports.trends') }}" class="block p-4 bg-emerald-50 rounded-lg border border-emerald-100 hover:bg-emerald-100 transition">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                                <h3 class="ml-2 text-lg font-medium text-emerald-900">Tren Partisipasi</h3>
                            </div>
                            <p class="text-sm text-emerald-700">Analisis perkembangan partisipasi dan capaian mahasiswa dari waktu ke waktu</p>
                        </a>
                        
                        <a href="{{ route('admin.reports.periods') }}" class="block p-4 bg-amber-50 rounded-lg border border-amber-100 hover:bg-amber-100 transition">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="ml-2 text-lg font-medium text-amber-900">Perbandingan Periode</h3>
                            </div>
                            <p class="text-sm text-amber-700">Analisis dan perbandingan prestasi antar periode akademik</p>
                        </a>
                        
                        <a href="{{ route('admin.reports.export') }}" class="block p-4 bg-purple-50 rounded-lg border border-purple-100 hover:bg-purple-100 transition">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <h3 class="ml-2 text-lg font-medium text-purple-900">Ekspor Laporan</h3>
                            </div>
                            <p class="text-sm text-purple-700">Buat laporan dalam berbagai format untuk keperluan presentasi dan dokumentasi</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Period Comparison -->
            @if(isset($periodComparison) && !empty($periodComparison))
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Periode Terakhir</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <h3 class="text-lg font-semibold text-blue-800">{{ $periodComparison['current']['name'] }}</h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Total Prestasi</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $periodComparison['current']['achievements'] }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                            <h3 class="text-lg font-semibold text-indigo-800">{{ $periodComparison['previous']['name'] }}</h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Total Prestasi</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $periodComparison['previous']['achievements'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 border border-green-100 bg-green-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800">Perubahan</h3>
                        <div class="mt-3">
                            <p class="text-xs text-gray-500">Total Prestasi</p>
                            <p class="text-xl font-bold {{ $periodComparison['growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $periodComparison['growth'] >= 0 ? '+' : '' }}{{ $periodComparison['growth'] }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endcomponent 