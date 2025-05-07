@props(['users' => []])

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pengguna
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Peran
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Terdaftar Pada
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $user->users_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($user->photo)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" loading="lazy">
                                @else
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4338ca&color=fff" alt="{{ $user->name }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $user->name }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($user->getRole() == 'admin') 
                                bg-purple-100 text-purple-800
                            @elseif($user->getRole() == 'user')
                                bg-green-100 text-green-800
                            @else
                                bg-blue-100 text-blue-800
                            @endif">
                            {{ $user->getRoleName() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-user" data-user-id="{{ $user->users_id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            
                            <a href="{{ route('admin.users.edit', $user->users_id) }}" class="btn btn-sm btn-ghost text-brand hover:bg-brand-light hover:bg-opacity-10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            
                            <button type="button" class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 transition-colors delete-user" data-user-id="{{ $user->users_id }}" data-user-name="{{ $user->name }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada data pengguna yang ditemukan</p>
                            <p class="text-gray-500 mt-1 text-sm">Silakan tambahkan pengguna baru atau ubah filter pencarian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Use standardized delete modal component -->
@component('admin.components.modals.delete-modal', [
    'modalId' => 'delete-modal',
    'route' => route('admin.users.index'),
    'itemType' => 'pengguna'
])
@endcomponent

<!-- User Detail Modal using standardized component -->
@component('admin.components.modals.detail-modal', [
    'modalId' => 'user-detail-modal',
    'title' => 'Detail Pengguna',
    'titleIcon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>'
])
    @slot('photo')
        <div class="h-24 w-24 rounded-full overflow-hidden bg-white border-4 border-white shadow-md" id="user-photo-container">
            <img id="user-photo" class="h-full w-full object-cover" src="" alt="User Photo">
        </div>
    @endslot

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <!-- User ID card -->
        @component('admin.components.cards.info-card', [
            'title' => 'ID Pengguna',
            'value' => 'Loading...',
        ])
            <div id="user-id"></div>
        @endcomponent
        
        <!-- Role card -->
        @component('admin.components.cards.info-card', [
            'title' => 'Peran',
            'value' => ' ',
        ])
            <div id="user-role" class="px-3 py-1 text-sm font-semibold rounded-full"></div>
        @endcomponent
        
        <!-- Name card -->
        @component('admin.components.cards.info-card', [
            'title' => 'Nama Lengkap',
            'value' => ' ',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
            'iconBg' => 'bg-blue-100',
            'iconColor' => 'text-blue-600'
        ])
            <div id="user-name"></div>
        @endcomponent
        
        <!-- Email card -->
        @component('admin.components.cards.info-card', [
            'title' => 'Email',
            'value' => ' ',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
            'iconBg' => 'bg-purple-100',
            'iconColor' => 'text-purple-600'
        ])
            <div id="user-email"></div>
        @endcomponent
        
        <!-- Created at card - full width -->
        @component('admin.components.cards.info-card', [
            'title' => 'Terdaftar Pada',
            'value' => ' ',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
            'iconBg' => 'bg-green-100',
            'iconColor' => 'text-green-600'
        ])
            <div id="user-created-at"></div>
        @endcomponent
    </div>

    @slot('footer')
        <a href="#" id="edit-user-link" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
        <button id="close-detail-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Tutup
        </button>
    @endslot
@endcomponent

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete user functionality
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form-delete-modal');
        const userNameToDelete = document.getElementById('item-name-to-delete');
        
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                deleteForm.action = `{{ route('admin.users.index') }}/${userId}`;
                userNameToDelete.textContent = userName;
                deleteModal.classList.remove('hidden');
            });
        });
        
        // User detail functionality
        const detailModal = document.getElementById('user-detail-modal');
        const closeDetailBtn = document.getElementById('close-detail-btn');
        
        document.querySelectorAll('.show-user').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-user-id');
                
                try {
                    const response = await fetch(`/admin/users/${userId}/details`);
                    if (!response.ok) throw new Error('Failed to fetch user details');
                    
                    const userData = await response.json();
                    
                    // Update user details in modal
                    document.getElementById('user-id').textContent = userData.id;
                    document.getElementById('user-name').textContent = userData.name;
                    document.getElementById('user-email').textContent = userData.email;
                    document.getElementById('user-created-at').textContent = userData.created_at;
                    
                    // Set role with appropriate styling
                    const roleElement = document.getElementById('user-role');
                    roleElement.textContent = userData.role_name;
                    
                    // Apply role-based styling
                    if (userData.role === 'admin') {
                        roleElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800';
                    } else if (userData.role === 'user') {
                        roleElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800';
                    } else {
                        roleElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800';
                    }
                    
                    // Set user photo
                    const photoUrl = userData.photo 
                        ? `/storage/${userData.photo}` 
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(userData.name)}&background=4338ca&color=fff`;
                    
                    document.getElementById('user-photo').src = photoUrl;
                    
                    // Set edit user link
                    document.getElementById('edit-user-link').href = `/admin/users/${userId}/edit`;
                    
                    // Show modal
                    detailModal.classList.remove('hidden');
                    
                } catch (error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load user details. Please try again.');
                }
            });
        });
        
        if (closeDetailBtn) {
            closeDetailBtn.addEventListener('click', () => {
                detailModal.classList.add('hidden');
            });
        }
    });
</script>
