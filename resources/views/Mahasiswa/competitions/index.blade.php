@component('layouts.mahasiswa', ['title' => 'Daftar Lomba'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('Mahasiswa.components.ui.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar semua prestasi yang telah Anda peroleh dan statusnya.',
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
                    'title' => 'Kompetisi Aktif',
                    'value' => $activeCompetitions ?? 0,
                    'icon' => 'calendar',
                    'key' => 'activeCompetitions'
                ],
                [
                    'title' => 'Kompetisi Selesai',
                    'value' => $completedCompetitions ?? 0,
                    'icon' => 'check-circle',
                    'key' => 'completedCompetitions'
                ],
                [
                    'title' => 'Peserta Terdaftar',
                    'value' => $registeredParticipants ?? 0,
                    'icon' => 'users',
                    'key' => 'registeredParticipants'
                ],
            ];
        @endphp
        
        <div class="mb-4">
            @component('Mahasiswa.components.cards.stats-cards', ['stats' => $stats, 'columns' => 4])
            @endcomponent
        </div>
        @php
            $filterOptions = [
                ['value' => 'all', 'label' => 'Semua Status'],
                ['value' => 'pending', 'label' => 'Menunggu'],
                ['value' => 'approved', 'label' => 'Disetujui'],
                ['value' => 'rejected', 'label' => 'Ditolak']
            ];
        @endphp

        @component('Mahasiswa.components.ui.search-and-filter', [
            'searchRoute' => route('Mahasiswa.competitions.index'),
            'searchPlaceholder' => 'Cari berdasarkan nama prestasi...',
            'currentFilter' => request('status'),
            // 'addButton' => [
            //     'text' => 'Tambah Prestasi',
            //     'route' => '#',
            //     'action' => 'showAddAchievementModal()'
            // ]
        ])
        @endcomponent

        <div id="competitions-table-container">
            @component('Mahasiswa.competitions.components.tables')
                @slot('competitions', $competitions ?? collect())
            @endcomponent
        </div>

        <div id="competitions-pagination">
            @component('Mahasiswa.components.tables.pagination', ['data' => $competitions ?? collect()])
            @endcomponent
        </div>
    </div>

    <!-- Include Achievement Modals -->
    @include('Mahasiswa.competitions.components.show-competitions-modal')

    
    <!-- JavaScript Variables and Setup -->
    {{-- <script>
        window.achievementRoutes = {
            index: "{{ route('Mahasiswa.competitions.index') }}",
            create: "{{ route('Mahasiswa.competitions.create') }}",
            store: "{{ route('Mahasiswa.competitions.store') }}",
            show: "{{ route('Mahasiswa.competitions.show', ['id' => '__ID__']) }}",
            edit: "{{ route('Mahasiswa.competitions.edit', ['id' => '__ID__']) }}",
            update: "{{ route('Mahasiswa.competitions.update', ['id' => '__ID__']) }}",
            delete: "{{ route('Mahasiswa.competitions.destroy', ['id' => '__ID__']) }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script> --}}
    
    @vite('resources/js/mahasiswa/competitions.js')
@endcomponent

