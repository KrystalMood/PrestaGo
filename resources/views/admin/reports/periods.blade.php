@component('layouts.admin', ['title' => 'Perbandingan Periode'])
    @push('scripts')
    @vite('resources/js/admin/reports/periods.js')
    @endpush

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Perbandingan Periode</h1>
        <p class="text-gray-600 mt-1">Analisis dan perbandingan prestasi antar periode akademik</p>
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
                                'active' => false
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
                                'active' => true
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
            @if(isset($error))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ $error }}
                            </p>
                            <p class="mt-2 text-sm text-red-700">
                                Silakan tambahkan minimal dua periode akademik di sistem untuk menggunakan fitur perbandingan periode.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    @include('admin.reports.components.summary-card', [
                        'title' => 'Total Prestasi',
                        'value' => $currentPeriodAchievements,
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'trend' => $achievementGrowth . '%',
                        'trend_positive' => $achievementGrowthPositive
                    ])
                    
                    @include('admin.reports.components.summary-card', [
                        'title' => 'Partisipasi',
                        'value' => $currentPeriodParticipation,
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                        'trend' => $participationGrowth . '%',
                        'trend_positive' => $participationGrowthPositive
                    ])
                    
                    @include('admin.reports.components.summary-card', [
                        'title' => 'Internasional',
                        'value' => $currentPeriodInternational,
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'trend' => $internationalGrowth . '%',
                        'trend_positive' => $internationalGrowthPositive
                    ])
                </div>

                <!-- Periods Summary -->
                <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Ringkasan Perbandingan Periode</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Period 1 -->
                            <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                                <h3 class="text-lg font-medium text-indigo-900 mb-3 period-1-name">{{ $currentPeriod }}</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Total Prestasi:</span>
                                        <span class="font-medium text-indigo-900 period-1-achievements">{{ $currentPeriodAchievements }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Partisipasi:</span>
                                        <span class="font-medium text-indigo-900 period-1-participation">{{ $currentPeriodParticipation }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Internasional:</span>
                                        <span class="font-medium text-indigo-900 period-1-international">{{ $currentPeriodInternational }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Nasional:</span>
                                        <span class="font-medium text-indigo-900 period-1-national">{{ $currentPeriodNational }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Period 2 -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-3 period-2-name">{{ $previousPeriod }}</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Total Prestasi:</span>
                                        <span class="font-medium text-gray-900 period-2-achievements">{{ $previousPeriodAchievements }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Partisipasi:</span>
                                        <span class="font-medium text-gray-900 period-2-participation">{{ $previousPeriodParticipation }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Internasional:</span>
                                        <span class="font-medium text-gray-900 period-2-international">{{ $previousPeriodInternational }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">Nasional:</span>
                                        <span class="font-medium text-gray-900 period-2-national">{{ $previousPeriodNational }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visual Comparison -->
                <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Perbandingan Visual</h2>
                    </div>
                    <div class="p-4">
                        <div class="h-80">
                            <canvas id="periods-comparison-chart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Category Comparison -->
                <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Perbandingan Kategori</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $currentPeriod }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $previousPeriod }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perubahan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($categoriesData as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $category['name'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $category['current_count'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $category['previous_count'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm {{ $category['is_positive'] ? 'text-green-600' : 'text-red-600' }}">
                                                {{ round($category['change_percentage'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endcomponent