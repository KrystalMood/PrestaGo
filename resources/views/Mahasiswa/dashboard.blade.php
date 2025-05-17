@component('layouts.mahasiswa', ['title' => 'Dasbor Mahasiswa'])
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $stats = [
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                'title' => 'Total Pengguna',
                'value' => '254',
                'trend' => '↗︎ 12% (30 hari)'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Terverifikasi',
                'value' => '85%',
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistik Prestasi</h2>
                <div class="h-64">
                    <!-- Chart placeholder -->
                    <div class="w-full h-full flex items-center justify-center bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Grafik statistik prestasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php
                    $actions = [
                        [
                            'title' => 'Verifikasi Prestasi',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                            'href' => route('admin.verification.index')
                        ],
                        [
                            'title' => 'Tambah Kompetisi',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>',
                            'href' => route('admin.competitions.create')
                        ],
                        [
                            'title' => 'Kelola Pengguna',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                            'href' => route('admin.users.index')
                        ],
                        [
                            'title' => 'Buat Laporan',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                            'href' => route('admin.reports.index')
                        ],
                        [
                            'title' => 'Pengaturan Sistem',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
                            'href' => route('admin.settings.index')
                        ],
                        [
                            'title' => 'Rekomendasi Lomba',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>',
                            'href' => route('admin.recommendations.automatic')
                        ]
                    ];
                    @endphp

                    @foreach ($actions as $action)
                        <a href="{{ $action['href'] }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
                            <div class="mb-2">
                                {!! $action['icon'] !!}
                            </div>
                            <p class="text-sm font-medium text-gray-700">{{ $action['title'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Pengajuan Prestasi Terbaru</h2>
                    <a href="{{ route('admin.verification.index') }}" class="text-sm text-brand-light hover:underline">Lihat Semua</a>
                </div>

                @php
                $submissions = [
                    [
                        'name' => 'Budi Santoso',
                        'class' => 'TI-3A',
                        'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=4338ca&color=fff',
                        'achievement' => 'Juara 1 Hackathon Nasional',
                        'date' => '1 Mei 2025',
                        'status' => 'Menunggu',
                        'status_color' => 'warning'
                    ],
                    [
                        'name' => 'Dewi Lestari',
                        'class' => 'MI-2B',
                        'avatar' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=4338ca&color=fff',
                        'achievement' => 'Best Paper Award IEEE Conference',
                        'date' => '30 Apr 2025',
                        'status' => 'Menunggu',
                        'status_color' => 'warning'
                    ],
                    [
                        'name' => 'Ahmad Fauzi',
                        'class' => 'TI-4B',
                        'avatar' => 'https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=4338ca&color=fff',
                        'achievement' => 'Juara 2 UI/UX Design Competition',
                        'date' => '28 Apr 2025',
                        'status' => 'Disetujui',
                        'status_color' => 'success'
                    ]
                ];
                @endphp

                <div class="space-y-4">
                    @foreach ($submissions as $submission)
                        <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center">
                                <img src="{{ $submission['avatar'] }}" alt="{{ $submission['name'] }}" class="w-10 h-10 rounded-full mr-3">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-800">{{ $submission['name'] }}</h3>
                                        <span class="text-xs text-gray-500">{{ $submission['date'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $submission['class'] }}</p>
                                    <p class="text-sm mt-1">{{ $submission['achievement'] }}</p>
                                    <div class="mt-2">
                                        @if ($submission['status'] === 'Menunggu')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $submission['status'] }}
                                            </span>
                                        @elseif ($submission['status'] === 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $submission['status'] }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $submission['status'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>

                @php
                $activities = [
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'message' => 'Prestasi Ahmad <span class="font-medium">"UI/UX Design Competition"</span> telah diverifikasi',
                        'time' => '5 menit lalu'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>',
                        'message' => 'Kompetisi baru <span class="font-medium">"National Robotics Championship"</span> ditambahkan',
                        'time' => '1 jam lalu'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
                        'message' => 'Pengguna baru <span class="font-medium">Siska Meliana</span> terdaftar',
                        'time' => '3 jam lalu'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>',
                        'message' => 'Batas waktu kompetisi <span class="font-medium">"AI Challenge 2025"</span> dalam 2 hari',
                        'time' => '6 jam lalu'
                    ]
                ];
                @endphp

                <div class="space-y-4">
                    @foreach ($activities as $activity)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-50">
                                    {!! $activity['icon'] !!}
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">{!! $activity['message'] !!}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
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

                <div class="space-y-4">
                    @foreach ($deadlines as $deadline)
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50">
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
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Sistem</h2>

                @php
                $metrics = [
                    ['name' => 'Penggunaan Penyimpanan', 'value' => 65, 'color' => 'indigo'],
                    ['name' => 'Kapasitas Pengguna', 'value' => 40, 'color' => 'green'],
                    ['name' => 'Penggunaan API', 'value' => 82, 'color' => 'amber']
                ];
                @endphp

                <div class="space-y-4">
                    @foreach ($metrics as $metric)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $metric['name'] }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $metric['value'] }}%</span>
                            </div>
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-100">
                                <div style="width: {{ $metric['value'] }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $metric['color'] === 'indigo' ? 'bg-indigo-500' : ($metric['color'] === 'green' ? 'bg-green-500' : 'bg-amber-500') }}">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <table class="min-w-full">
                            <tbody>
                                <tr>
                                    <td class="py-1 text-sm">Basis Data</td>
                                    <td class="py-1 text-right">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Online
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm">API</td>
                                    <td class="py-1 text-right">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Operasional
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm">CDN</td>
                                    <td class="py-1 text-right">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm">Server</td>
                                    <td class="py-1 text-right">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Sehat
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcomponent
