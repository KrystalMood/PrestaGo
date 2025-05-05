@component('layouts.admin', ['title' => 'Edit Pengguna'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <x-admin.users.components.page-header title="Edit Pengguna: {{ $user->name }}" />
        <x-admin.users.components.form :user="$user" :roles="$roles" :isEdit="true" />
    </div>
@endcomponent 