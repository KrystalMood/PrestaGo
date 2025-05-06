@component('layouts.admin', ['title' => 'Manajemen Pengguna'])
<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('admin.components.ui.page-header', [
            'subtitle' => 'Halaman ini menampilkan daftar semua pengguna sistem dan memungkinkan Anda untuk menambah, mengubah, atau menghapus data pengguna.',
            'actionText' => 'Tambah Pengguna',
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Pengguna',
                'value' => $totalUsers ?? 0,
                'icon' => 'user'
            ],
            [
                'title' => 'Pengguna Baru',
                'value' => $newUsers ?? 0,
                'icon' => 'user-plus'
            ],
            [
                'title' => 'Total Pengguna Aktif',
                'value' => $activeUsers ?? 0,
                'icon' => 'user-check'
            ],
        ];
    @endphp
    @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
    @endcomponent

    <div class="mt-4 mb-6 flex justify-end space-x-3">
        <x-admin.buttons.add-button 
            route="{{ route('admin.users.create') }}" 
            text="Tambah Pengguna" 
            icon="user-plus"
            color="blue"
        />
        
        <x-admin.buttons.action-button
            route="#"
            text="Ekspor Data"
            icon="download"
            color="green"
        />
    </div>

    @component('admin.users.components.search-and-filter')
    @slot('roles', $roles ?? [])
    @endcomponent

    @component('admin.users.components.tables')
    @slot('users', $users ?? collect())
    @endcomponent

    @component('admin.components.tables.pagination', ['data' => $users ?? collect()])
    @endcomponent
</div>
@endcomponent