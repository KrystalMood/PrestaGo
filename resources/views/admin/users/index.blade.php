@component('layouts.admin', ['title' => 'Manajemen Pengguna'])
<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('admin.components.ui.page-header', [
            'subtitle' => 'Halaman ini menampilkan daftar semua pengguna sistem dan memungkinkan Anda untuk menambah, mengubah, atau menghapus data pengguna.',
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Pengguna',
                'value' => $totalUsers ?? 0,
                'icon' => 'user',
                'key' => 'totalUsers'
            ],
            [
                'title' => 'Pengguna Baru',
                'value' => $newUsers ?? 0,
                'icon' => 'user-plus',
                'key' => 'newUsers'
            ],
        ];
    @endphp
    @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 2])
    @endcomponent

    <div class="mt-4 mb-6 flex justify-end space-x-3">
        <button type="button" id="open-add-user-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
            </svg>
            Tambah Pengguna
        </button>
        <button type="button" id="open-import-user-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M12 4v12m8-6H4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Import Pengguna
        </button>
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

    @component('admin.components.ui.search-and-filter', [
        'searchRoute' => route('admin.users.index'),
        'searchPlaceholder' => 'Cari pengguna berdasarkan nama atau email...',
        'filterOptions' => $filterOptions,
        'filterName' => 'role',
        'filterLabel' => 'Semua Peran',
        'currentFilter' => request('role')
    ])
    @endcomponent

    <div id="users-table-container">
        @component('admin.users.components.tables')
        @slot('users', $users ?? collect())
        @endcomponent
    </div>

    <div id="pagination-container">
        @component('admin.components.tables.pagination', ['data' => $users ?? collect()])
        @endcomponent
    </div>
</div>

<!-- Include modals -->
@include('admin.users.components.add-user-modal')
@include('admin.users.components.edit-user-modal')
@include('admin.users.components.show-user-modal')
@include('admin.users.components.delete-user-modal')
@include('admin.users.components.import-user-modal')

<!-- JavaScript Variables and Setup -->
<script>
    window.userRoutes = {
        index: "{{ route('admin.users.index') }}",
        store: "{{ route('admin.users.store') }}",
        import: "{{ route('admin.users.import') }}",
        importTemplate: "{{ route('admin.users.import.template') }}"
    };
    window.csrfToken = "{{ csrf_token() }}";
    window.defaultAvatarUrl = "{{ asset('images/avatar.png') }}";
</script>

<!-- Load External JS -->
@vite('resources/js/admin/users.js')
@endcomponent