@component('layouts.admin', ['title' => 'Manajemen Program Studi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar program studi yang tersedia dan memungkinkan Anda untuk menambah, mengubah, atau menghapus data program studi.',
            ])
        </div>
        
        @php
            $stats = [
                [
                    'title' => 'Total Program Studi',
                    'value' => $totalPrograms ?? 0,
                    'icon' => 'academic-cap',
                    'key' => 'totalPrograms'
                ],
                [
                    'title' => 'Fakultas/Jurusan',
                    'value' => $totalFaculties ?? 0,
                    'icon' => 'building-library',
                    'key' => 'totalFaculties'
                ],
                [
                    'title' => 'Program Studi Aktif',
                    'value' => $activePrograms ?? 0,
                    'icon' => 'check-badge',
                    'key' => 'activePrograms'
                ],
            ];
        @endphp
        
        <div class="mb-4">
            @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
            @endcomponent
        </div>

        <div class="mt-4 mb-6 flex justify-end space-x-3">
            <button type="button" id="open-add-program-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                </svg>
                Tambah Program Studi
            </button>
            
            <x-admin.buttons.action-button
                route="#"
                text="Ekspor Data"
                icon="download"
                color="green"
            />
        </div>

        @component('admin.components.ui.search-and-filter', [
            'searchRoute' => route('admin.programs.index'),
            'searchPlaceholder' => 'Cari program studi...',
            'filterOptions' => [
                ['value' => 'active', 'label' => 'Aktif'],
                ['value' => 'inactive', 'label' => 'Tidak Aktif']
            ],
            'filterName' => 'status',
            'filterLabel' => 'Semua Status',
            'currentFilter' => request('status')
        ])
        @endcomponent

        <div id="programs-table-container">
            @component('admin.programs.components.tables', ['programs' => $programs ?? collect()])
            @endcomponent
        </div>

        <div id="pagination-container">
            @component('admin.components.tables.pagination', ['data' => $programs ?? collect()])
            @endcomponent
        </div>
    </div>

    @include('admin.programs.components.add-program-modal')
    @include('admin.programs.components.edit-program-modal')
    @include('admin.programs.components.show-program-modal')
    @include('admin.programs.components.delete-program-modal')

    <script>
        window.programRoutes = {
            index: "{{ route('admin.programs.index') }}",
            store: "{{ route('admin.programs.store') }}",
            show: "{{ route('admin.programs.show', ['id' => '__id__']) }}",
            edit: "{{ route('admin.programs.edit', ['id' => '__id__']) }}",
            update: "{{ route('admin.programs.update', ['id' => '__id__']) }}",
            destroy: "{{ route('admin.programs.destroy', ['id' => '__id__']) }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    @vite('resources/js/admin/programs.js')
@endcomponent 