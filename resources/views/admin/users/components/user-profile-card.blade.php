@props(['user'])

<div class="bg-gray-50 p-6 rounded-lg border border-gray-200 flex flex-col items-center">
    @if($user->photo)
        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover shadow-md mb-4" loading="lazy">
    @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4338ca&color=fff&size=128" alt="{{ $user->name }}" class="w-32 h-32 rounded-full shadow-md mb-4" loading="lazy">
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

</div> 