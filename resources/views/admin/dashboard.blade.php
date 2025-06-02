@component('layouts.admin', ['title' => 'Dasbor Admin'])
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                
                @if(isset($achievementStats) && $achievementStats && !empty($achievementStats['byType']))
                    <!-- Increased height for chart container -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="h-80">
                            <canvas id="achievementByTypeChart" class="w-full h-full"></canvas>
                        </div>
                        <div class="h-80">
                            <canvas id="achievementByMonthChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Chart for achievement by type
                            const typeCtx = document.getElementById('achievementByTypeChart').getContext('2d');
                            
                            // Prepare data for type chart
                            const typeData = {!! json_encode($achievementStats['byType']) !!};
                            const typeLabels = [];
                            const typeValues = [];
                            const backgroundColors = [
                                'rgba(79, 70, 229, 0.7)',  // indigo
                                'rgba(59, 130, 246, 0.7)', // blue
                                'rgba(16, 185, 129, 0.7)', // emerald
                                'rgba(245, 158, 11, 0.7)', // amber
                                'rgba(239, 68, 68, 0.7)',  // red
                                'rgba(124, 58, 237, 0.7)', // purple
                                'rgba(236, 72, 153, 0.7)'  // pink
                            ];
                            
                            // Extract data from achievement stats
                            typeData.forEach((item, index) => {
                                typeLabels.push(item.type);
                                typeValues.push(item.total);
                            });
                            
                            const typeChart = new Chart(typeCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: typeLabels,
                                    datasets: [{
                                        data: typeValues,
                                        backgroundColor: backgroundColors.slice(0, typeLabels.length),
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Prestasi Berdasarkan Jenis'
                                        },
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                boxWidth: 12,
                                                padding: 10,
                                                font: {
                                                    size: 11
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                            
                            // Chart for achievements by month
                            const monthCtx = document.getElementById('achievementByMonthChart').getContext('2d');
                            
                            // Prepare data for month chart
                            const monthData = {!! json_encode($achievementStats['byMonth'] ?? []) !!};
                            const monthLabels = [];
                            const monthValues = [];
                            
                            // Extract data from achievement stats
                            if (monthData && monthData.length) {
                                monthData.forEach(item => {
                                    monthLabels.push(item.month);
                                    monthValues.push(item.total);
                                });
                            }
                            
                            const monthChart = new Chart(monthCtx, {
                                type: 'bar',
                                data: {
                                    labels: monthLabels,
                                    datasets: [{
                                        label: 'Prestasi per Bulan',
                                        data: monthValues,
                                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Prestasi Berdasarkan Bulan'
                                        },
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                precision: 0
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                font: {
                                                    size: 10
                                                },
                                                maxRotation: 45,
                                                minRotation: 45
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                @else
                    <div class="w-full h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-gray-500 mb-4">Tidak ada data prestasi terverifikasi</p>
                            <a href="{{ route('admin.verification.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Verifikasi Prestasi
                            </a>
                        </div>
                    </div>
                @endif
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
                    // Get pending verifications from actual database
                    $pendingAchievements = App\Models\AchievementModel::with('user')
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                @endphp

                <div class="space-y-4">
                    @forelse($pendingAchievements as $achievement)
                        <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($achievement->user->name ?? 'User') }}&background=4338ca&color=fff" alt="{{ $achievement->user->name ?? 'User' }}" class="w-10 h-10 rounded-full mr-3">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-800">{{ $achievement->user->name ?? 'Unknown User' }}</h3>
                                        <span class="text-xs text-gray-500">{{ $achievement->created_at->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $achievement->user->nim ?? '' }}</p>
                                    <p class="text-sm mt-1">{{ $achievement->title }}</p>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu Verifikasi
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500">
                            <p>Tidak ada pengajuan prestasi yang menunggu verifikasi saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>

                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-50">
                                    {!! $activity->icon_svg ?? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' !!}
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">{!! $activity->message !!}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->formatted_time ?? $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500">
                            <p>Belum ada aktivitas terbaru</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Deadline Mendatang</h2>

                @php
                    // Get upcoming deadlines from actual database
                    $upcomingDeadlines = App\Models\CompetitionModel::where('verified', true)
                        ->whereNotNull('registration_end')
                        ->where('registration_end', '>=', now())
                        ->orderBy('registration_end', 'asc')
                        ->take(3)
                        ->get();
                @endphp

                <div class="space-y-4">
                    @forelse($upcomingDeadlines as $deadline)
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                @php
                                    $daysLeft = now()->diffInDays($deadline->registration_end, false);
                                    $colorClass = $daysLeft < 3 ? 'bg-red-100 text-red-800' : ($daysLeft < 7 ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800');
                                @endphp
                                <div class="flex flex-col items-center justify-center h-12 w-12 rounded-lg {{ $colorClass }}">
                                    <span class="text-lg font-bold leading-none">{{ $deadline->registration_end->format('d') }}</span>
                                    <span class="text-xs">{{ $deadline->registration_end->format('M') }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $deadline->name }}</h3>
                                <p class="text-xs text-gray-500">Pendaftaran ditutup</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500">
                            <p>Tidak ada deadline mendatang saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Sistem</h2>

                @php
                $metrics = [
                    [
                        'name' => 'Penggunaan Penyimpanan',
                        'value' => 65,
                        'color' => 'indigo'
                    ],
                    [
                        'name' => 'Kapasitas Pengguna', 
                        'value' => round((App\Models\UserModel::count() / 1000) * 100), // Assuming 1000 user capacity
                        'color' => 'green'
                    ],
                    [
                        'name' => 'Verifikasi Tertunda', 
                        'value' => round((App\Models\AchievementModel::where('status', 'pending')->count() / max(1, App\Models\AchievementModel::count())) * 100),
                        'color' => 'amber'
                    ]
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
                                    <td class="py-1 text-sm">Pengguna Aktif</td>
                                    <td class="py-1 text-right">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ App\Models\UserModel::count() }}
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
