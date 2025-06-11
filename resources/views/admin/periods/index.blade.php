@component('layouts.admin', ['title' => 'Manajemen Periode'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="mb-6">
            @include('admin.periods.components.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar periode semester yang tersedia dan memungkinkan Anda untuk menambah, mengubah, atau menghapus data periode.',
            ])
        </div>

        @php
            $stats = [
                [
                    'title' => 'Total Periode',
                    'value' => $totalPeriods,
                    'icon' => 'calendar',
                    'key' => 'totalPeriods'
                ],
                [
                    'title' => 'Tahun Akademik',
                    'value' => date('Y') . '/' . (date('Y')+1),
                    'icon' => 'clock',
                    'key' => 'academicYear'
                ],
            ];
        @endphp
        
        <div class="mb-4">
            @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 2])
            @endcomponent
        </div>

        <div class="mt-4 mb-6 flex justify-end space-x-3">
            <button type="button" id="open-add-period-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Periode
            </button>
        </div>

        @component('admin.components.ui.search-and-filter', [
            'searchRoute' => route('admin.periods.index'),
            'searchPlaceholder' => 'Cari nama periode...',
            'filterName' => '',
            'filterLabel' => '',
            'currentFilter' => ''
        ])
        @endcomponent

        <div id="periods-table-container">
            @component('admin.periods.components.tables')
            @slot('periods', $periods ?? collect())
            @endcomponent
        </div>

        <div id="pagination-container">
            @component('admin.components.tables.pagination', ['data' => $periods ?? collect()])
            @endcomponent
        </div>
    </div>

    <!-- Include modals -->
    @include('admin.periods.components.add-period-modal')
    @include('admin.periods.components.edit-period-modal')
    @include('admin.periods.components.show-period-modal')
    @include('admin.periods.components.delete-period-modal')

    <!-- JavaScript Variables and Setup -->
    <script>
        window.periodRoutes = {
            index: "{{ route('admin.periods.index') }}",
            store: "{{ route('admin.periods.store') }}",
            show: (id) => `{{ url('admin/periods') }}/${id}`,
            update: (id) => `{{ url('admin/periods') }}/${id}`,
            destroy: (id) => `{{ url('admin/periods') }}/${id}`,
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Load External JS -->
    @vite('resources/js/admin/periods.js')
@endcomponent 