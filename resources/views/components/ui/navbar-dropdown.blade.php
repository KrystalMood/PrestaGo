@props(['user' => Auth::user()])

<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
            <img alt="Avatar" src="https://ui-avatars.com/api/?name={{ $user->name }}&background=4f46e5&color=fff" loading="lazy" />
        </div>
    </div>
    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-white rounded-box w-52">
        <li class="font-medium px-4 py-2 text-gray-700">{{ $user->name }}</li>
        <li class="divider"></li>
        <li>
            <a class="justify-between">
                Profile
                <span class="badge badge-sm badge-primary">New</span>
            </a>
        </li>
        <form method="POST" action="{{ route('logout') }}" class="mt-1 px-2">
            @csrf
            <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-2 px-3 rounded-lg flex items-center justify-center transition-all duration-200 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </ul>
</div>