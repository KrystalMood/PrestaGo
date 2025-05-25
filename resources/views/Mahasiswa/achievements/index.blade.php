@component('layouts.mahasiswa', ['title' => 'Prestasi Saya'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('Mahasiswa.components.ui.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar semua prestasi yang telah Anda peroleh dan statusnya.',
            ])
        </div>
        
        @php
            $stats = [
                [
                    'title' => 'Total Prestasi',
                    'value' => $totalAchievements ?? 0,
                    'icon' => 'award',
                    'key' => 'totalAchievements'
                ],
                [
                    'title' => 'Menunggu Verifikasi',
                    'value' => $pendingAchievements ?? 0,
                    'icon' => 'clock',
                    'key' => 'pendingAchievements'
                ],
                [
                    'title' => 'Prestasi Terverifikasi',
                    'value' => $approvedAchievements ?? 0,
                    'icon' => 'check-circle',
                    'key' => 'approvedAchievements'
                ],
            ];
        @endphp
        @component('Mahasiswa.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
        @endcomponent

        @php
            $filterOptions = [
                ['value' => 'all', 'label' => 'Semua Status'],
                ['value' => 'pending', 'label' => 'Menunggu'],
                ['value' => 'approved', 'label' => 'Disetujui'],
                ['value' => 'rejected', 'label' => 'Ditolak']
            ];
        @endphp

        @component('Mahasiswa.components.ui.search-and-filter', [
            'searchRoute' => route('Mahasiswa.achievements.index'),
            'searchPlaceholder' => 'Cari berdasarkan nama prestasi...',
            'filterOptions' => $filterOptions,
            'filterName' => 'status',
            'filterLabel' => 'Semua Status',
            'currentFilter' => request('status'),
            'addButton' => [
                'text' => 'Tambah Prestasi',
                'route' => '#',
                'action' => 'showAddAchievementModal()'
            ]
        ])
        @endcomponent

        <div id="achievements-table-container">
            @component('Mahasiswa.achievements.components.tables')
                @slot('achievements', $achievements ?? collect())
            @endcomponent
        </div>

        <div id="achievements-pagination">
            @component('Mahasiswa.components.tables.pagination', ['data' => $achievements ?? collect()])
            @endcomponent
        </div>
    </div>

    <!-- Include Achievement Modals -->
    @include('Mahasiswa.achievements.components.add-achievement-modal', ['competitions' => $competitions ?? []])
    @include('Mahasiswa.achievements.components.show-achievement-modal')
    @include('Mahasiswa.achievements.components.edit-achievement-modal', ['competitions' => $competitions ?? []])
    
    <!-- JavaScript Variables and Setup -->
    <script>
        window.achievementRoutes = {
            index: "{{ route('Mahasiswa.achievements.index') }}",
            create: "{{ route('Mahasiswa.achievements.create') }}",
            store: "{{ route('Mahasiswa.achievements.store') }}",
            show: "{{ route('Mahasiswa.achievements.show', ['id' => '__ID__']) }}",
            edit: "{{ route('Mahasiswa.achievements.edit', ['id' => '__ID__']) }}",
            update: "{{ route('Mahasiswa.achievements.update', ['id' => '__ID__']) }}",
            delete: "{{ route('Mahasiswa.achievements.destroy', ['id' => '__ID__']) }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    
    @vite('resources/js/mahasiswa/achievements.js')
@endcomponent

