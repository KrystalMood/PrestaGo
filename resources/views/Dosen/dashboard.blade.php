@component('layouts.dosen', ['title' => 'Dashboard Dosen'])

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $user = Auth::user();

        $supervisedStudentsCount = App\Models\SubCompetitionParticipantModel::where(function($query) use ($user) {
            $query->where('mentor_id', $user->id)
                  ->orWhereHas('subCompetition.competition.mentorships', function($q) use ($user) {
                      $q->where('dosen_id', $user->id);
                  });
        })->count();

        $pendingMentorshipsCount = App\Models\SubCompetitionParticipantModel::where('mentor_id', $user->id)
            ->where('status_mentor', 'pending')
            ->count();

        $activeCompetitions = App\Models\CompetitionModel::where('verified', true)
            ->where('status', 'active')
            ->count();

        $myCompetitionsCount = App\Models\CompetitionModel::where('added_by', $user->id)->count();

        $stats = [
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                'title' => 'Mahasiswa Bimbingan',
                'value' => $supervisedStudentsCount,
                'trend' => 'Total mahasiswa yang dibimbing'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                'title' => 'Permintaan Bimbingan',
                'value' => $pendingMentorshipsCount,
                'trend' => 'Menunggu persetujuan'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
                'title' => 'Kompetisi Aktif',
                'value' => $activeCompetitions,
                'trend' => 'Tersedia untuk diikuti'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Kompetisi Saya',
                'value' => $myCompetitionsCount,
                'trend' => 'Ditambahkan oleh Anda'
            ],
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

    <div class="grid grid-cols-1 gap-6">
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('lecturer.achievements.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center text-center transition-colors">
                        <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Prestasi Mahasiswa</p>
                    </a>

                    <a href="{{ route('lecturer.competitions.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center text-center transition-colors">
                        <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Buat Lomba Baru</p>
                    </a>

                    <a href="{{ route('lecturer.profile.index') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center text-center transition-colors">
                        <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Perbarui Profil</p>
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-custom p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa Bimbingan</h2>
                @php
                    $mentorshipStudents = App\Models\SubCompetitionParticipantModel::with(['user', 'subCompetition.competition'])
                        ->whereHas('subCompetition.competition.mentorships', function($query) use ($user) {
                            $query->where('dosen_id', $user->id);
                        })
                        ->get();
                    
                    $directMentorStudents = App\Models\SubCompetitionParticipantModel::with(['user', 'subCompetition.competition'])
                        ->where('mentor_id', $user->id)
                        ->get();
                    
                    $supervisedStudents = $mentorshipStudents->concat($directMentorStudents)->unique('id');
                    
                    $students = $supervisedStudents->map(function($participant) {
                        return [
                            'id' => $participant->id,
                            'name' => $participant->user->name ?? 'Nama tidak tersedia',
                            'nim' => $participant->user->nim ?? 'NIM tidak tersedia',
                            'program_studi' => $participant->user->program_studi ?? 'Program Studi tidak tersedia',
                            'competition' => $participant->subCompetition->competition->name ?? 'Kompetisi tidak tersedia',
                            'status' => $participant->status ?? 'registered',
                            'status_mentor' => $participant->status_mentor ?? 'pending'
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
                                            @if(isset($student['status_mentor']))
                                                @if($student['status_mentor'] == 'accept')
                                                    bg-green-100 text-green-800
                                                @elseif($student['status_mentor'] == 'pending')
                                                    bg-amber-100 text-amber-800
                                                @elseif($student['status_mentor'] == 'reject')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            @if(isset($student['status_mentor']))
                                                @if($student['status_mentor'] == 'accept')
                                                    Terima
                                                @elseif($student['status_mentor'] == 'pending')
                                                    Menunggu Persetujuan
                                                @elseif($student['status_mentor'] == 'reject')
                                                    Ditolak
                                                @else
                                                    {{ ucfirst($student['status_mentor']) }}
                                                @endif
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
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
    </div>
@endcomponent
