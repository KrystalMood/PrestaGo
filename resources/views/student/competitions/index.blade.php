@extends('components.shared.content')

@section('content')

@component('layouts.app', ['title' => 'Kompetisi'])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('student.competitions.components.page-header', [
            'subtitle' => 'Halaman ini menampilkan daftar kompetisi yang tersedia untuk mahasiswa dan memungkinkan Anda untuk melihat detail atau mendaftar pada kompetisi yang diminati.',
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Kompetisi',
                'value' => $totalCompetitions ?? 0,
                'icon' => 'trophy',
                'key' => 'totalCompetitions'
            ],
            [
                'title' => 'Kompetisi Terbaru',
                'value' => $newCompetitions ?? 0,
                'icon' => 'star',
                'key' => 'newCompetitions'
            ],
            [
                'title' => 'Kompetisi Aktif',
                'value' => $activeCompetitions ?? 0,
                'icon' => 'check-circle',
                'key' => 'activeCompetitions'
            ],
        ];
    @endphp
    @component('student.competitions.components.stats-cards', ['stats' => $stats, 'columns' => 3])
    @endcomponent

    @component('student.competitions.components.search-and-filter', [
        'searchRoute' => route('student.competitions.index'),
        'searchPlaceholder' => 'Cari kompetisi berdasarkan nama atau kategori...',
        'categories' => $subCompetitionCategories ?? [],
        'currentFilter' => request('category')
    ])
    @endcomponent

    <div id="competitions-table-container">
        @component('student.competitions.components.tables')
        @slot('competitions', $competitions ?? collect())
        @endcomponent
    </div>

    <div id="pagination-container">
        @component('student.competitions.components.pagination', ['data' => $competitions ?? collect()])
        @endcomponent
    </div>
</div>

<!-- JavaScript Variables and Setup -->
<script>
    window.competitionRoutes = {
        index: "{{ route('student.competitions.index') }}",
    };
    window.csrfToken = "{{ csrf_token() }}";
</script>

@endcomponent
@endsection
