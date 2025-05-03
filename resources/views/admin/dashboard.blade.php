@component('layouts.dashboard', ['title' => 'Dasbor'])
    @php
    $stats = [
        [
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
            'title' => 'Total Pengguna',
            'value' => '254',
            'trend' => '↗︎ 12% (30 hari)',
            'color' => 'indigo'
        ],
        [
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'title' => 'Prestasi Terverifikasi',
            'value' => '85%',
            'trend' => '↗︎ 14% (30 hari)',
            'color' => 'green'
        ],
        [
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'title' => 'Verifikasi Tertunda',
            'value' => '24',
            'trend' => 'Perlu perhatian',
            'color' => 'amber'
        ],
        [
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
            'title' => 'Kompetisi Aktif',
            'value' => '12',
            'trend' => '↗︎ 5 ditambahkan bulan ini',
            'color' => 'blue'
        ]
    ];
    @endphp
    <x-dashboard.stats-overview :stats="$stats" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-dashboard.achievement-chart />
            @php
            $actions = [
                [
                    'title' => 'Verifikasi Prestasi',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'color' => 'indigo',
                    'href' => '#'
                ],
                [
                    'title' => 'Tambah Kompetisi',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>',
                    'color' => 'blue',
                    'href' => '#'
                ],
                [
                    'title' => 'Kelola Pengguna',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                    'color' => 'amber',
                    'href' => '#'
                ],
                [
                    'title' => 'Buat Laporan',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                    'color' => 'green',
                    'href' => '#'
                ],
                [
                    'title' => 'Pengaturan Sistem',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
                    'color' => 'purple',
                    'href' => '#'
                ],
                [
                    'title' => 'Aksi Lainnya',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>',
                    'color' => 'gray',
                    'href' => '#'
                ]
            ];
            @endphp
            <x-dashboard.quick-actions :actions="$actions" />
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
            <x-dashboard.recent-submissions :submissions="$submissions" />
        </div>
        <div class="space-y-6">
            @php
            $activities = [
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'message' => 'Prestasi Ahmad <span class="font-medium">"UI/UX Design Competition"</span> telah diverifikasi',
                    'time' => '5 menit lalu',
                    'color' => 'green'
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>',
                    'message' => 'Kompetisi baru <span class="font-medium">"National Robotics Championship"</span> ditambahkan',
                    'time' => '1 jam lalu',
                    'color' => 'blue'
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
                    'message' => 'Pengguna baru <span class="font-medium">Siska Meliana</span> terdaftar',
                    'time' => '3 jam lalu',
                    'color' => 'indigo'
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>',
                    'message' => 'Batas waktu kompetisi <span class="font-medium">"AI Challenge 2025"</span> dalam 2 hari',
                    'time' => '6 jam lalu',
                    'color' => 'amber'
                ]
            ];
            @endphp
            <x-dashboard.activity-feed :activities="$activities" />
            @php
            $metrics = [
                ['name' => 'Penggunaan Penyimpanan', 'value' => 65, 'color' => 'indigo'],
                ['name' => 'Kapasitas Pengguna', 'value' => 40, 'color' => 'green'],
                ['name' => 'Penggunaan API', 'value' => 82, 'color' => 'amber']
            ];
            $services = [
                ['name' => 'Basis Data', 'status' => 'Online'],
                ['name' => 'API', 'status' => 'Operasional'],
                ['name' => 'CDN', 'status' => 'Aktif'],
                ['name' => 'Server', 'status' => 'Sehat']
            ];
            @endphp
            <x-dashboard.system-status :metrics="$metrics" :services="$services" />
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
            <x-dashboard.upcoming-deadlines :deadlines="$deadlines" />
        </div>
    </div>
@endcomponent
