@component('layouts.admin', ['title' => 'Manajemen Pengguna'])
<div class="bg-white rounded-lg shadow-custom p-6">
    <p class="text-start text-gray-500 mb-6 text-sm">Halaman ini menampilkan daftar semua pengguna sistem dan memungkinkan Anda untuk
        menambah, mengubah, atau menghapus data pengguna.</p>
    @component('admin.users.components.stats-cards')
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
    @slot('stats', $stats)
    @endcomponent

    @component('admin.users.components.search-and-filter')
    @slot('roles', $roles ?? [])
    @endcomponent

    @component('admin.users.components.tables')
    @slot('users', $users ?? collect())
    @endcomponent

    @component('admin.users.components.pagination')
    @slot('users', $users ?? collect())
    @endcomponent
</div>
@endcomponent