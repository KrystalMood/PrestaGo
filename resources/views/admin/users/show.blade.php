@component('layouts.admin', ['title' => 'Detail Pengguna'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <x-admin.users.components.page-header title="Detail Pengguna" />

        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/3">
                <x-admin.users.components.user-profile-card :user="$user" />
            </div>
            
            <div class="md:w-2/3">
                <x-admin.users.components.user-info-card :user="$user" />
                <x-admin.users.components.activity-card :user="$user" />
            </div>
        </div>
    </div>
    
    <x-admin.users.components.delete-modal />
@endcomponent 