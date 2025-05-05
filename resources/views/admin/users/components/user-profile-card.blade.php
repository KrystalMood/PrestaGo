@props(['user'])

<div class="bg-gray-50 p-6 rounded-lg border border-gray-200 flex flex-col items-center">
    @if($user->photo)
        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover shadow-md mb-4">
    @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4338ca&color=fff&size=128" alt="{{ $user->name }}" class="w-32 h-32 rounded-full shadow-md mb-4">
    @endif
    
    <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
    <p class="text-gray-600 mb-2">{{ $user->email }}</p>
    
    <span class="px-3 py-1 mt-1 inline-flex text-xs leading-5 font-semibold rounded-full 
        @if($user->getRole() == 'admin') 
            bg-purple-100 text-purple-800
        @elseif($user->getRole() == 'user')
            bg-green-100 text-green-800
        @else
            bg-blue-100 text-blue-800
        @endif">
        {{ $user->getRoleName() }}
    </span>

    <div class="mt-6 w-full flex flex-col gap-2">
        <a href="{{ route('admin.users.edit', $user->users_id) }}" class="w-full text-center px-4 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profil
        </a>
        
        <button type="button" class="w-full text-center px-4 py-2 border border-red-600 text-red-600 hover:bg-red-50 font-medium rounded-lg delete-user" data-user-id="{{ $user->users_id }}" data-user-name="{{ $user->name }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Hapus Pengguna
        </button>
    </div>
</div> 