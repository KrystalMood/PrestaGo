@component('layouts.dosen', ['title' => 'Manajemen Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @component('Dosen.components.ui.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar kompetisi yang tersedia dan memungkinkan Anda untuk menambah, mengubah, atau menghapus informasi kompetisi.',
            ])
                Daftar Kompetisi
            @endcomponent
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
            @component('Dosen.components.cards.stats-cards', ['stats' => $stats, 'columns' => 4])
            @endcomponent
        </div>

        <div class="mt-4 mb-6 flex justify-end space-x-3">
            <button type="button" id="open-add-competition-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Kompetisi
            </button>
            
            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Ekspor Data
            </a>
        </div>

        @component('Dosen.components.ui.search-and-filter', [
            'searchRoute' => route('lecturer.competitions.index'),
            'searchPlaceholder' => 'Cari kompetisi...',
            'statuses' => $statuses ?? collect(),
            'levels' => $levels ?? collect(),
        ])
        @endcomponent

        <div id="competitions-table-container">
            @component('Dosen.competitions.components.tables')
                @slot('competitions', $competitions ?? collect())
            @endcomponent
        </div>

        <div id="pagination-container">
            @component('Dosen.components.tables.pagination', ['data' => $competitions ?? collect()])
            @endcomponent
        </div>
    </div>

    <!-- Include modals -->
    @include('Dosen.competitions.components.show-competition-modal')
    @include('Dosen.competitions.components.add-competition-modal')

    <!-- JavaScript Variables and Setup -->
    <script>
        window.competitionRoutes = {
            index: "{{ route('lecturer.competitions.index') }}",
            show: "{{ route('lecturer.competitions.show', ['id' => '__id__']) }}",
            store: "{{ route('lecturer.competitions.store') }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Load External JS -->
    @vite(['resources/js/dosen/competitions.js'])
@endcomponent