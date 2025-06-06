<!-- Sidebar Navigation -->
<div class="lg:col-span-1 sticky top-0 self-start h-screen">
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
                        'active' => request()->routeIs('admin.reports.index')
                    ],
                    [
                        'title' => 'Statistik Prestasi', 
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                        'url' => route('admin.reports.achievements'),
                        'active' => request()->routeIs('admin.reports.achievements')
                    ],
                    [
                        'title' => 'Analisis Program Studi', 
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                        'url' => route('admin.reports.programs'),
                        'active' => request()->routeIs('admin.reports.programs')
                    ],
                    [
                        'title' => 'Tren Partisipasi', 
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>',
                        'url' => route('admin.reports.trends'),
                        'active' => request()->routeIs('admin.reports.trends')
                    ],
                    [
                        'title' => 'Perbandingan Periode', 
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                        'url' => route('admin.reports.periods'),
                        'active' => request()->routeIs('admin.reports.periods')
                    ],
                    [
                        'title' => 'Ekspor Laporan', 
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                        'url' => route('admin.reports.export'),
                        'active' => request()->routeIs('admin.reports.export')
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