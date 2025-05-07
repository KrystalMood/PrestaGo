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
                <div class="relative ml-3">
                    <button class="flex text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="bg-red-500 text-white rounded-full w-4 h-4 absolute -top-1 -right-1 text-xs flex items-center justify-center">2</span>
                    </button>
                </div>

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
                            <li>
                                <a href="" class="flex items-center px-4 py-3 hover:bg-gray-50 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profil</span>
                                </a>
                            </li>
                            @if($userRole == 'admin')
                                <li>
                                    <a href="" class="flex items-center px-4 py-3 hover:bg-gray-50 text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Pengaturan</span>
                                    </a>
                                </li>
                            @endif
                            <li class="border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 hover:bg-red-50 text-red-600 rounded-b-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
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
