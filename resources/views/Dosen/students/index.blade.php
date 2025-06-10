@component('layouts.dosen', ['title' => 'Mahasiswa yang dibimbing'])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('Dosen.components.ui.page-header', [
            'title' => 'Mahasiswa yang dibimbing',
            'subtitle' => 'Halaman ini menampilkan daftar Mahasiswa yang anda bimbing.',
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Mahasiswa dibimbing',
                'value' => $totalStudents ?? 0,
                'icon' => 'users',
                'key' => 'totalStudents'
            ],
            [
                'title' => 'Mahasiswa yang dibimbingan saat ini',
                'value' => $onGoingStudents ?? 0,
                'icon' => 'user-plus',
                'key' => 'onGoingStudents'
            ],
            [
                'title' => 'Mahasiswa ditolak',
                'value' => $rejectedStudents ?? 0,
                'icon' => 'x-circle',
                'key' => 'rejectedStudents'
            ],
        ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach($stats as $stat)
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100" data-key="{{ $stat['key'] }}">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-50 p-3 rounded-lg">
                    @if($stat['icon'] == 'users')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    @elseif($stat['icon'] == 'user-plus')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    @elseif($stat['icon'] == 'x-circle')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    @endif
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</h3>
                    <p class="text-2xl font-semibold text-gray-900 counter-value">{{ $stat['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @php
        $filterOptions = [];
        foreach ($roles ?? [] as $role) {
            $filterOptions[] = [
                'value' => $role->level_kode,
                'label' => $role->level_nama
            ];
        }
    @endphp

    @component('Dosen.components.ui.search-and-filter', [
        'searchRoute' => route('lecturer.profile.update'),
        'searchPlaceholder' => 'Cari pengguna berdasarkan nama atau email...',
        'filterOptions' => $filterOptions,
        'filterName' => 'role',
        'filterLabel' => 'Semua Peran',
        'currentFilter' => request('role')
    ])
    @endcomponent

    <div id="students-table-container">
        @component('Dosen.students.components.tables')
        @slot('students', $students ?? collect())
        @endcomponent
    </div>

    <div id="pagination-container">
        @component('Dosen.components.tables.pagination', ['data' => $students ?? collect()])
        @endcomponent
    </div>
</div>

<!-- Include modals -->
@include('Dosen.students.components.show-student-modal')

<!-- JavaScript Variables and Setup -->
<script>
    window.studentRoutes = {
        index: "{{ route('lecturer.students.index') }}"
    };
    window.csrfToken = "{{ csrf_token() }}";
    window.defaultAvatarUrl = "{{ asset('images/avatar.png') }}";
</script>

<!-- Load External JS -->
@vite('resources/js/dosen/students.js')

@endcomponent