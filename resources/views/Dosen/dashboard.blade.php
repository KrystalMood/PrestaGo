@component('layouts.dosen', ['title' => 'Dashboard Dosen'])

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $user = Auth::user();

        // Get achievements statistics (currently for the logged-in user, which is the lecturer)
        // As a lecturer, these might represent their own achievements or achievements they oversee
        $totalAchievements = App\Models\AchievementModel::where('user_id', $user->id)->count();
        $verifiedAchievements = App\Models\AchievementModel::where('user_id', $user->id)
            ->where('status', 'verified')
            ->count();
        $pendingAchievements = App\Models\AchievementModel::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Get competition statistics (general active competitions)
        $activeCompetitions = App\Models\CompetitionModel::where('verified', true)
            ->where('status', 'active')
            ->count();

        $stats = [
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Total Prestasi Saya', // Adjusted title for clarity on lecturer dashboard
                'value' => $totalAchievements,
                'trend' => 'Prestasi sepanjang karir'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Terverifikasi Saya', // Adjusted title
                'value' => $verifiedAchievements,
                'trend' => 'Prestasi yang sudah divalidasi'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Tertunda Saya', // Adjusted title
                'value' => $pendingAchievements,
                'trend' => 'Menunggu verifikasi'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
                'title' => 'Kompetisi Aktif', // This stat is general, so no change needed
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

    {{-- Main content area: Structure matching student dashboard (left 2-span, right 1-span) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left column for lecturer specific content (spanning 2 columns) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Mahasiswa Bimbingan block --}}
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa Bimbingan</h2>
                @php
                    // Fetch real supervised students data from the database
                    // Get students who are mentored by this lecturer in competitions
                    $supervisedStudents = App\Models\SubCompetitionParticipantModel::with(['user', 'subCompetition.competition'])
                        ->whereHas('subCompetition.competition.mentorships', function($query) use ($user) {
                            $query->where('dosen_id', $user->id);
                        })
                        ->get();
                    
                    // Transform the data to match the expected format
                    $students = $supervisedStudents->map(function($participant) {
                        return [
                            'id' => $participant->id,
                            'name' => $participant->user->name ?? 'Nama tidak tersedia',
                            'nim' => $participant->user->nim ?? 'NIM tidak tersedia',
                            'program_studi' => $participant->user->program_studi ?? 'Program Studi tidak tersedia',
                            'competition' => $participant->subCompetition->competition->name ?? 'Kompetisi tidak tersedia',
                            'status' => $participant->status ?? 'registered'
                        ];
                    });
                @endphp

                <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="students-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Mahasiswa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIM
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Program Studi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kompetisi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition-colors student-row" data-student-id="{{ $student['id'] }}">
                                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                                            {{ ($students instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($students->currentPage() - 1) * $students->perPage() + $loop->iteration) : $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student['name'] ?? 'Nama tidak tersedia' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student['nim'] ?? 'NIM tidak tersedia' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student['program_studi'] ?? 'Program Studi tidak tersedia' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student['competition'] ?? 'Kompetisi tidak tersedia' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if(isset($student['status']))
                                                @if($student['status'] == 'registered')
                                                    bg-green-100 text-green-800
                                                @elseif($student['status'] == 'on going')
                                                    bg-amber-100 text-amber-800
                                                @elseif($student['status'] == 'rejected')
                                                    bg-red-100 text-red-800
                                                @elseif($student['status'] == 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            @if(isset($student['status']))
                                                @if($student['status'] == 'registered')
                                                    Terdaftar
                                                @elseif($student['status'] == 'on going')
                                                    Sedang Berlangsung
                                                @elseif($student['status'] == 'rejected')
                                                    Ditolak
                                                @elseif($student['status'] == 'pending')
                                                    Menunggu Konfirmasi
                                                @else
                                                    {{ ucfirst($student['status']) }}
                                                @endif
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-student" data-student-id="{{ $student['id'] }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-gray-600 font-medium">Belum ada data mahasiswa yang dibimbing</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Moved "Lomba Terbaru" here, below Mahasiswa Bimbingan --}}
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
                                {{-- Changed route to lecturer competitions show --}}
                                <a href="{{ route('lecturer.competitions.show', $competition->id) }}" class="mt-2 inline-block text-sm text-brand-light hover:underline">
                                    Lihat Detail
                                </a>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <a href="{{ route('lecturer.competitions.index') }}" class="text-sm text-brand-light hover:underline">
                                Lihat Semua Lomba
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right column for profile, quick actions, and upcoming deadlines --}}
        <div class="space-y-6">
            {{-- Profil Saya block --}}
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Profil Saya</h2>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        {{-- Using a placeholder image with dynamic background/foreground colors for demo purposes --}}
                        <img src="{{ asset('storage/photos/' . $user->photo) }}?background=4338ca&color=fff" class="h-16 w-16 rounded-full" alt="{{ $user->name }}">
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        @if($user->nim) {{-- Assuming some lecturers might still have NIM or it's a general user model --}}
                            <p class="text-sm text-gray-500">NIM: {{ $user->nim }}</p>
                        @endif
                        @if($user->nidn) {{-- Added NIDN for lecturer specific info --}}
                            <p class="text-sm text-gray-500">NIDN: {{ $user->nidn }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    {{-- Changed route to lecturer profile index --}}
                    <a href="{{ route('lecturer.profile.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        Edit Profil
                    </a>
                </div>
            </div>

            {{-- Aksi Cepat block --}}
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 gap-4">
                    {{-- Adjusted quick actions for lecturer relevance --}}
                    <a href="{{ route('lecturer.achievements.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center transition-colors">
                        <div class="flex-shrink-0 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Prestasi Mahasiswa</p>
                    </a>

                    <a href="{{ route('lecturer.competitions.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center transition-colors">
                        <div class="flex-shrink-0 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Buat Lomba Baru</p>
                    </a>

                    <a href="{{ route('lecturer.profile.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center transition-colors">
                        <div class="flex-shrink-0 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Perbarui Profil</p>
                    </a>
                </div>
            </div>

            {{-- Deadline Mendatang block --}}
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
