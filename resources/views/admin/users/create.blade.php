@component('layouts.admin', ['title' => 'Tambah Pengguna Baru'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <x-admin.users.components.page-header title="Tambah Pengguna Baru" />
        <x-admin.users.components.form :roles="$roles" />
    </div>
@endcomponent 