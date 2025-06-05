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
    @component('Dosen.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
    @endcomponent

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