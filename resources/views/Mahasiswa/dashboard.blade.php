@component('layouts.mahasiswa', ['title' => 'Dasbor Mahasiswa'])
    <div class="mb-6 bg-white rounded-lg shadow-custom p-5">
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Berikut adalah ringkasan aktivitas dan statistik prestasi Anda.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $stats = [
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Terverifikasi',
                'value' => '85%',
                'trend' => '↗︎ 12% (30 hari)'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Tertolak',
                'value' => '15%',
                'trend' => '↗︎ 14% (30 hari)'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Verifikasi Tertunda',
                'value' => '24',
                'trend' => 'Perlu perhatian'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
                'title' => 'Kompetisi Aktif',
                'value' => '12',
                'trend' => '↗︎ 5 ditambahkan bulan ini'
            ]
        ];
        @endphp

        @foreach ($stats as $stat)
            <div class="bg-white rounded-lg shadow-custom p-6 flex items-start">
                <div class="flex-shrink-0 mr-4">
                    {!! $stat['icon'] !!}
                </div>
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">{{ $stat['title'] }}</h3>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stat['trend'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Improved Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (spans 2 columns on large screens) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @php
                    $actions = [
                        [
                            'title' => 'Dashboard',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>',
                            'href' => route('Mahasiswa.dashboard')
                        ],
                        [
                            'title' => 'Prestasi Saya',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
                            'href' => route('Mahasiswa.achievements.index')
                        ],
                        [
                            'title' => 'Daftar Lomba',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
                            'href' => route('Mahasiswa.competitions.index')
                        ],
                        [
                            'title' => 'Daftar Magang',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
                            'href' => '#'
                        ],
                        [
                            'title' => 'Magang Aktif',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>',
                            'href' => '#'
                        ]
                    ];
                    @endphp

                    @foreach ($actions as $action)
                        <a href="{{ $action['href'] }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
                            <div class="mb-2">
                                {!! $action['icon'] !!}
                            </div>
                            <p class="text-sm font-medium text-gray-700 text-center">{{ $action['title'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistik Prestasi</h2>
                <div class="h-64">
                    <!-- Chart placeholder -->
                    <div class="w-full h-full flex items-center justify-center bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Grafik statistik prestasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (spans 1 column) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-custom p-6 h-full flex flex-col">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Deadline Mendatang</h2>

                @php
                $deadlines = [
                    [
                        'date_day' => '05',
                        'date_month' => 'Mei',
                        'title' => 'Kompetisi Mobile App Dev',
                        'description' => 'Pendaftaran ditutup',
                        'color' => 'red'
                    ],
                    [
                        'date_day' => '12',
                        'date_month' => 'Mei',
                        'title' => 'Data Science Hackathon',
                        'description' => 'Batas akhir pengumpulan',
                        'color' => 'amber'
                    ],
                    [
                        'date_day' => '20',
                        'date_month' => 'Mei',
                        'title' => 'AI Challenge 2025',
                        'description' => 'Pendaftaran dibuka',
                        'color' => 'blue'
                    ]
                ];
                @endphp

                <div class="space-y-4 flex-grow">
                    @foreach ($deadlines as $deadline)
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="flex flex-col items-center justify-center h-12 w-12 rounded-lg {{ $deadline['color'] === 'red' ? 'bg-red-100 text-red-800' : ($deadline['color'] === 'amber' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    <span class="text-lg font-bold leading-none">{{ $deadline['date_day'] }}</span>
                                    <span class="text-xs">{{ $deadline['date_month'] }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $deadline['title'] }}</h3>
                                <p class="text-xs text-gray-500">{{ $deadline['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Add this conditional empty state to ensure minimum height -->
                @if(count($deadlines) < 3)
                <div class="mt-auto"></div>
                @endif
            </div>
        </div>
    </div>
@endcomponent
