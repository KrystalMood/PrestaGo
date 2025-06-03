@component('layouts.dosen', ['title' => 'Dashboard Dosen'])

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $user = Auth::user();
        
        // Get achievements statistics
        $totalAchievements = App\Models\AchievementModel::where('user_id', $user->id)->count();
        $verifiedAchievements = App\Models\AchievementModel::where('user_id', $user->id)
            ->where('status', 'verified')
            ->count();
        $pendingAchievements = App\Models\AchievementModel::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        // Get competition statistics
        $activeCompetitions = App\Models\CompetitionModel::where('verified', true)
            ->where('status', 'active')
            ->count();
            
        $stats = [
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Total Prestasi',
                'value' => $totalAchievements,
                'trend' => 'Prestasi sepanjang karir'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Terverifikasi',
                'value' => $verifiedAchievements,
                'trend' => 'Prestasi yang sudah divalidasi'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Tertunda',
                'value' => $pendingAchievements,
                'trend' => 'Menunggu verifikasi'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
                'title' => 'Kompetisi Aktif',
                'value' => $activeCompetitions,
                'trend' => 'Tersedia untuk diikuti'
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
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Prestasi Saya</h2>
                @php
                $recentAchievements = App\Models\AchievementModel::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
                @endphp

                @if($recentAchievements->isEmpty())
                    <div class="py-4 text-center text-gray-500">
                        <p>Anda belum memiliki prestasi. Mulailah dengan menambahkan prestasi pertama Anda!</p>
                        <a href="{{ route('student.achievements.create') }}" class="mt-3 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-light">
                            Tambah Prestasi Baru
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentAchievements as $achievement)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between">
                                    <h3 class="text-sm font-medium text-gray-800">{{ $achievement->title }}</h3>
                                    <span class="text-xs text-gray-500">{{ $achievement->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $achievement->competition_name }}</p>
                                <div class="mt-2">
                                    @if($achievement->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($achievement->status === 'verified')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <a href="{{ route('student.achievements.index') }}" class="text-sm text-brand-light hover:underline">
                                Lihat Semua Prestasi
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Lomba Terbaru</h2>
                @php
                $recentCompetitions = App\Models\CompetitionModel::where('verified', true)
                    ->where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
                @endphp

                @if($recentCompetitions->isEmpty())
                    <div class="py-4 text-center text-gray-500">
                        <p>Tidak ada lomba aktif saat ini. Periksa kembali nanti!</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentCompetitions as $competition)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between">
                                    <h3 class="text-sm font-medium text-gray-800">{{ $competition->name }}</h3>
                                    <span class="text-xs text-gray-500">
                                        Deadline: {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $competition->organizer }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($competition->level) }}
                                    </span>
                                </div>
                                <a href="{{ route('student.competitions.show', $competition->id) }}" class="mt-2 inline-block text-sm text-brand-light hover:underline">
                                    Lihat Detail
                                </a>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <a href="{{ route('student.competitions.index') }}" class="text-sm text-brand-light hover:underline">
                                Lihat Semua Lomba
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Profil Saya</h2>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4338ca&color=fff" class="h-16 w-16 rounded-full" alt="{{ $user->name }}">
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        @if($user->nim)
                            <p class="text-sm text-gray-500">NIM: {{ $user->nim }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('student.profile.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-dark">
                        Edit Profil
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('student.achievements.create') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center transition-colors">
                        <div class="flex-shrink-0 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Tambah Prestasi Baru</p>
                    </a>
                    
                    <a href="{{ route('student.competitions.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center transition-colors">
                        <div class="flex-shrink-0 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Cari Lomba</p>
                    </a>
                    
                    <a href="{{ route('student.profile.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center transition-colors">
                        <div class="flex-shrink-0 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Perbarui Profil</p>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Deadline Mendatang</h2>
                @php
                $upcomingDeadlines = App\Models\CompetitionModel::where('verified', true)
                    ->where('status', 'active')
                    ->whereNotNull('registration_end')
                    ->where('registration_end', '>=', now())
                    ->orderBy('registration_end', 'asc')
                    ->take(3)
                    ->get();
                @endphp

                @if($upcomingDeadlines->isEmpty())
                    <div class="py-4 text-center text-gray-500">
                        <p>Tidak ada deadline mendatang saat ini.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingDeadlines as $deadline)
                            <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50">
                                <div class="flex-shrink-0">
                                    <div class="flex flex-col items-center justify-center h-12 w-12 rounded-lg {{ now()->diffInDays($deadline->registration_end) < 3 ? 'bg-red-100 text-red-800' : (now()->diffInDays($deadline->registration_end) < 7 ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        <span class="text-lg font-bold leading-none">{{ $deadline->registration_end->format('d') }}</span>
                                        <span class="text-xs">{{ $deadline->registration_end->format('M') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $deadline->name }}</h3>
                                    <p class="text-xs text-gray-500">Pendaftaran ditutup</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endcomponent 