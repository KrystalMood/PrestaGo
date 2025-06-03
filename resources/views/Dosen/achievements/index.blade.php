@component('layouts.dosen', ['title' => 'Prestasi Mahasiswa'])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('Dosen.components.ui.page-header', [
            'subtitle' => 'Halaman ini menampilkan daftar prestasi mahasiswa.',
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Mahasiswa Terdaftar',
                'value' => $totalAchievements ?? 0,
                'icon' => 'user',
                'key' => 'totalAchievements'
            ],
            [
                'title' => 'Total Prestasi Terdata',
                'value' => $newAchievements ?? 0,
                'icon' => 'star',
                'key' => 'newAchievements'
            ],
            [
                'title' => 'Prestasi Tingkat Nasional / Internasional',
                'value' => $verifiedAchievements ?? 0,
                'icon' => 'check-circle',
                'key' => 'verifiedAchievements'
            ],
        ];
    @endphp
    @component('Dosen.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
    @endcomponent

    @php
        $filterOptions = [];
        foreach ($types ?? [] as $type) {
            $filterOptions[] = [
                'value' => $type->type_code,
                'label' => $type->type_name
            ];
        }
    @endphp

    @component('Dosen.components.ui.search-and-filter', [
        'searchRoute' => route('lecturer.profile.index'),
        'searchPlaceholder' => 'Cari prestasi berdasarkan judul atau nama mahasiswa...',
    ])
    @endcomponent

    
    <div id="achievements-table-container">
        @component('Dosen.achievements.components.tables')
        @slot('achievements', $achievements ?? collect())
        @endcomponent
    </div>

    <div id="pagination-container">
        @component('Dosen.components.tables.pagination', ['data' => $achievements ?? collect()])
        @endcomponent
    </div>
</div>


<!-- JavaScript Variables and Setup -->
<script>
    window.achievementRoutes = {
        index: "{{ route('lecturer.profile.index') }}",
    };
    window.csrfToken = "{{ csrf_token() }}";
    window.defaultCertificateUrl = "{{ asset('images/certificate.png') }}";
</script>

<!-- Load External JS -->
@vite('resources/js/dosen/achievements.js')

@endcomponent