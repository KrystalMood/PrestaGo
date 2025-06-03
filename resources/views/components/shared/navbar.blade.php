@props(['title' => null, 'user' => null])

@php
    $userRole = $user ? $user->role : (auth()->user() ? auth()->user()->role : 'admin');
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="lg:hidden text-gray-500 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="hidden lg:block ml-2">
                    <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
                </div>
            </div>

            <div class="flex items-center">

                <div class="relative ml-4">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="flex items-center cursor-pointer hover:opacity-80 transition-opacity duration-300">
                            <div class="avatar">
                                <div class="w-8 h-8 rounded-full ring ring-brand ring-offset-base-100 ring-offset-1 bg-brand-light">
                                    <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $user ? urlencode($user->name) : 'User' }}&background=4338ca&color=fff" alt="User avatar" loading="lazy" />
                                </div>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700 hidden sm:block">{{ $user ? $user->name : (auth()->user() ? auth()->user()->name : 'User') }}</span>
                            <svg class="ml-1 h-4 w-4 text-gray-400 hidden sm:block transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <ul tabindex="0" class="dropdown-content menu p-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-custom w-56 z-10 animate-fadeIn">
                            <li class="p-3 text-sm border-b border-gray-100 bg-gray-50 rounded-t-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="avatar">
                                        <div class="w-10 h-10 rounded-full ring ring-brand ring-offset-base-100 ring-offset-1">
                                            <img src="https://ui-avatars.com/api/?name={{ $user ? urlencode($user->name) : 'User' }}&background=4338ca&color=fff" alt="User avatar" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $user ? $user->name : (auth()->user() ? auth()->user()->name : 'Unknown User') }}</span>
                                        <span class="text-xs text-gray-500">{{ $user ? $user->email : (auth()->user() ? auth()->user()->email : 'Unknown Email') }}</span>
                                        <span class="text-xs text-brand capitalize">{{ $userRole }}</span>
                                    </div>
                                </div>
                            </li>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-b-lg transition-all duration-200 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
