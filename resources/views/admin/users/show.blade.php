@component('layouts.admin', ['title' => 'Detail Pengguna'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @include('admin.components.ui.page-header', [
            'title' => 'Detail Pengguna',
            'subtitle' => 'Melihat informasi lengkap pengguna',
            'showBackButton' => true,
            'backRoute' => route('admin.users.index')
        ])

        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/3">
                @include('admin.users.components.user-profile-card', ['user' => $user])
            </div>
            
            <div class="md:w-2/3">
                @include('admin.users.components.user-info-card', ['user' => $user])
                @include('admin.users.components.activity-card', ['user' => $user])
            </div>
        </div>
    </div>
@endcomponent 