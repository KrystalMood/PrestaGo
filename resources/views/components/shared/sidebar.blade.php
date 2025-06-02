@props(['user' => null])

@php
    $userRoleFromFileNames = auth()->user()->level->level_nama;
    $routeRoleName = '';

    switch ($userRoleFromFileNames) {
        case 'Mahasiswa':
            $routeRoleName = 'student';
            break;
        case 'Admin':
            $routeRoleName = 'admin';
            break;
        case 'Dosen':
            $routeRoleName = 'lecturer';
            break;
        default:
            $routeRoleName = strtolower($userRoleFromFileNames);
            break;
    }
@endphp

<aside class="w-64 shadow-custom bg-white border-r border-gray-200 transition-all duration-300 ease-in-out overflow-hidden hidden lg:block h-screen sticky top-0">
    <div class="p-4 flex items-center border-b border-gray-200">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="w-10 h-10 mr-2">
        <span class="text-lg font-bold text-gray-800">{{ config('app.name') }}</span>
    </div>

    <div class="p-4 overflow-y-auto max-h-[calc(100vh-4rem)]">
        <ul class="space-y-1">
            <li>
                <a href="{{ route($routeRoleName . '.dashboard') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs($routeRoleName . '.dashboard') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            @include('components.sidebar.' . $userRoleFromFileNames)

            <div class="py-2 mt-2 border-t border-gray-100">
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Akun</span>
            </div>

            <li>
                <a href="{{ route($routeRoleName . '.profile.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs($routeRoleName . '.profile.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Profil Saya</span>
                </a>
            </li>

            @if($routeRoleName == 'admin')
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Pengaturan Sistem</span>
                    </a>
                </li>
            @endif
        </ul>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-custom z-30 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <div class="p-4 flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="w-8 h-8 mr-2">
            <span class="text-lg font-bold text-gray-800">{{ config('app.name') }}</span>
        </div>
        <button id="close-sidebar" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="p-4 overflow-y-auto">
        <ul class="space-y-1">
            <li>
                <a href="{{ route($routeRoleName . '.dashboard') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs($routeRoleName . '.dashboard') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            @include('components.sidebar.mobile.' . $userRoleFromFileNames)

            <div class="py-2 mt-2 border-t border-gray-100">
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Akun</span>
            </div>

            <li>
                <a href="#" class="flex items-center p-3 rounded-lg {{ request()->routeIs($routeRoleName . '.profile.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Profil Saya</span>
                </a>
            </li>
        </ul>
    </div>
</aside>


