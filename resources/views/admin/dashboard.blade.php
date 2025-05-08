@component('layouts.admin', ['title' => 'Dasbor Admin'])
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @foreach ($stats as $stat)
            @include('admin.components.cards.stats-card', [
                'icon' => $stat['icon'],
                'title' => $stat['title'],
                'value' => $stat['value'],
                'trend' => $stat['trend']
            ])
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @component('admin.components.cards.chart-container', ['title' => 'Statistik Prestasi'])
                <p class="text-gray-500">Grafik statistik prestasi</p>
            @endcomponent
            
            @component('admin.components.cards.card', ['title' => 'Aksi Cepat'])
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
                            'href' => route('admin.recommendations.index')
                        ]
                    ];
                    @endphp
                    
                    @foreach ($actions as $action)
                        @include('admin.components.ui.quick-action', [
                            'title' => $action['title'],
                            'icon' => $action['icon'],
                            'href' => $action['href']
                        ])
                    @endforeach
                </div>
            @endcomponent
            
            @component('admin.components.cards.card', [
                'title' => 'Pengajuan Prestasi Terbaru',
                'linkText' => 'Lihat Semua',
                'linkUrl' => route('admin.verification.index')
            ])
                @php
                $submissions = [
                    [
                        'name' => 'Budi Santoso',
                        'class' => 'TI-3A',
                        'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=4338ca&color=fff',
                        'achievement' => 'Juara 1 Hackathon Nasional',
                        'date' => '1 Mei 2025',
                        'status' => 'Menunggu'
                    ],
                    [
                        'name' => 'Dewi Lestari',
                        'class' => 'MI-2B',
                        'avatar' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=4338ca&color=fff',
                        'achievement' => 'Best Paper Award IEEE Conference',
                        'date' => '30 Apr 2025',
                        'status' => 'Menunggu'
                    ],
                    [
                        'name' => 'Ahmad Fauzi',
                        'class' => 'TI-4B',
                        'avatar' => 'https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=4338ca&color=fff',
                        'achievement' => 'Juara 2 UI/UX Design Competition',
                        'date' => '28 Apr 2025',
                        'status' => 'Disetujui'
                    ]
                ];
                @endphp
                
                <div class="space-y-4">
                    @foreach ($submissions as $submission)
                        @include('admin.components.ui.achievement-submission', [
                            'name' => $submission['name'],
                            'class' => $submission['class'],
                            'avatar' => $submission['avatar'],
                            'achievement' => $submission['achievement'],
                            'date' => $submission['date'],
                            'status' => $submission['status']
                        ])
                    @endforeach
                </div>
            @endcomponent
        </div>
        
        <div class="space-y-6">
            @component('admin.components.cards.card', ['title' => 'Aktivitas Terbaru'])
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
                        @include('admin.components.ui.activity-item', [
                            'icon' => $activity['icon'],
                            'message' => $activity['message'],
                            'time' => $activity['time']
                        ])
                    @endforeach
                </div>
            @endcomponent
            
            @component('admin.components.cards.card', ['title' => 'Deadline Mendatang'])
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
                        @include('admin.components.ui.deadline-item', [
                            'date_day' => $deadline['date_day'],
                            'date_month' => $deadline['date_month'],
                            'title' => $deadline['title'],
                            'description' => $deadline['description'],
                            'color' => $deadline['color']
                        ])
                    @endforeach
                </div>
            @endcomponent
            
            @include('admin.periods.components.periods-summary')
            
            @component('admin.components.cards.card', ['title' => 'Status Sistem'])
                @php
                $metrics = [
                    ['name' => 'Penggunaan Penyimpanan', 'value' => 65, 'color' => 'indigo'],
                    ['name' => 'Kapasitas Pengguna', 'value' => 40, 'color' => 'green'],
                    ['name' => 'Penggunaan API', 'value' => 82, 'color' => 'amber']
                ];
                @endphp
                
                <div class="space-y-4">
                    @foreach ($metrics as $metric)
                        @include('admin.components.metrics.system-metric', [
                            'name' => $metric['name'],
                            'value' => $metric['value'],
                            'color' => $metric['color']
                        ])
                    @endforeach
                    
                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <table class="min-w-full">
                            <tbody>
                                @include('admin.components.metrics.system-service', ['name' => 'Basis Data', 'status' => 'Online'])
                                @include('admin.components.metrics.system-service', ['name' => 'API', 'status' => 'Operasional'])
                                @include('admin.components.metrics.system-service', ['name' => 'CDN', 'status' => 'Aktif'])
                                @include('admin.components.metrics.system-service', ['name' => 'Server', 'status' => 'Sehat'])
                            </tbody>
                        </table>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
@endcomponent
