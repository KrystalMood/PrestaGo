@props(['title' => null, 'user' => null])
 


 

<!DOCTYPE html>
 

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
 


 

<head>
 

    <meta charset="utf-8">
 

    <meta name="viewport" content="width=device-width, initial-scale=1">
 

    <meta name="csrf-token" content="{{ csrf_token() }}">
 

    <title>{{ $title ? "$title | Admin" . config('app.name', 'SIM Prestasi') : "Admin | " . config('app.name', 'SIM Prestasi') }}</title>
 


 

    <!-- Favicon -->
 

    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
 

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
 


 

    <!-- Fonts -->
 

    <link rel="preconnect" href="https://fonts.bunny.net">
 

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
 


 

    <!-- DaisyUI and Tailwind -->
 

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
 

    <script src="https://cdn.tailwindcss.com"></script>
 

    <script>
 

        tailwind.config = {
 

            theme: {
 

                extend: {
 

                    colors: {
 

                        brand: {
 

                            light: '#6366f1',
 

                            DEFAULT: '#4f46e5',
 

                            dark: '#4338ca',
 

                        }
 

                    },
 

                    boxShadow: {
 

                        'custom': '0 10px 25px -5px rgba(99, 102, 241, 0.1), 0 8px 10px -6px rgba(99, 102, 241, 0.1)',
 

                        'custom-top': '0 -4px 20px 0 rgba(0, 0, 0, 0.05)'
 

                    }
 

                }
 

            },
 

            daisyui: {
 

                themes: ["light"]
 

            }
 

        }
 

    </script>
 


 

    <!-- Chart.js -->
 

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 


 

    @vite(['resources/css/app.css', 'resources/js/app.js'])
 

    @stack('styles')
 

</head>
 


 

<body class="font-sans antialiased min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
 

    <x-ui.toast />
 


 

    <div class="flex min-h-screen">
 

        <aside class="w-64 shadow-custom bg-white border-r border-gray-200 transition-all duration-300 ease-in-out overflow-hidden hidden lg:block">
 

            <div class="p-4 flex items-center border-b border-gray-200">
 

                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="w-10 h-10 mr-2">
 

                <span class="text-lg font-bold text-gray-800">{{ config('app.name') }}</span>
 

            </div>
 


 

            <div class="p-4">
 

                <ul class="space-y-1">
 

                    <li>
 

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
 

                            </svg>
 

                            <span class="font-medium">Dashboard</span>
 

                        </a>
 

                    </li>
 


 

                    <div class="py-2 mt-2 border-t border-gray-100">
 

                        <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Manajemen</span>
 

                    </div>
 


 

                    <li>
 

                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
 

                            </svg>
 

                            <span class="font-medium">Pengguna</span>
 

                        </a>
 

                    </li>
 


 

                    <li>
 

                        <a href="{{ route('admin.achievements.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.achievements.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
 

                            </svg>
 

                            <span class="font-medium">Verifikasi Prestasi</span>
 

                        </a>
 

                    </li>
 


 

                    <li>
 

                        <a href="{{ route('admin.competitions.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.competitions.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
 

                            </svg>
 

                            <span class="font-medium">Manajemen Lomba</span>
 

                        </a>
 

                    </li>
 


 

                    <li>
 

                        <a href="{{ route('admin.periods.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.periods.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
 

                            </svg>
 

                            <span class="font-medium">Periode Semester</span>
 

                        </a>
 

                    </li>
 


 

                    <li>
 

                        <a href="{{ route('admin.programs.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.programs.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
 

                            </svg>
 

                            <span class="font-medium">Program Studi</span>
 

                        </a>
 

                    </li>
 


 

                    <div class="py-2 mt-2 border-t border-gray-100">
 

                        <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Fitur Utama</span>
 

                    </div>
 


 

                    <li>
 

                        <a href="{{ route('admin.recommendations.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.recommendations.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
 

                            </svg>
 

                            <span class="font-medium">Sistem Rekomendasi</span>
 

                        </a>
 

                    </li>
 


 

                    <li>
 

                        <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
 

                            </svg>
 

                            <span class="font-medium">Laporan & Analisis</span>
 

                        </a>
 

                    </li>
 


 

                    <div class="py-2 mt-2 border-t border-gray-100">
 

                        <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Sistem</span>
 

                    </div>
 


 

                    <li>
 

                        <a href="{{ route('admin.settings.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
 

                            </svg>
 

                            <span class="font-medium">Pengaturan</span>
 

                        </a>
 

                    </li>
 

                </ul>
 

            </div>
 

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
 


 

            <div class="p-4">
 

                <ul class="space-y-1">
 

                    <li>
 

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
 

                            </svg>
 

                            <span class="font-medium">Dashboard</span>
 

                        </a>
 

                    </li>
 


 

                    <div class="py-2 mt-2 border-t border-gray-100">
 

                        <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Manajemen</span>
 

                    </div>
 


 

                    <li>
 

                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
 

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
 

                            </svg>
 

                            <span class="font-medium">Pengguna</span>
 

                        </a>
 

                    </li>
 


 

                </ul>
 

            </div>
 

        </aside>
 


 

        <!-- Main content -->
 

        <div class="flex-1 flex flex-col overflow-hidden">
 

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
 

                                    <div tabindex="0" role="button" class="flex items-center cursor-pointer">
 

                                        <img class="h-8 w-8 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Admin&background=4338ca&color=fff" alt="User avatar" />
 

                                        <span class="ml-2 text-sm font-medium text-gray-700 hidden sm:block">{{ auth() ? auth()->user()->name : 'Admin User' }}</span>
 

                                        <svg class="ml-1 h-4 w-4 text-gray-400 hidden sm:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
 

                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
 

                                        </svg>
 

                                    </div>
 

                                    <ul tabindex="0" class="dropdown-content menu p-2 mt-1 bg-white border border-gray-200 rounded-lg shadow-custom w-48 z-10">
 

                                        <li class="p-2 text-sm font-medium border-b border-gray-100">
 

                                            <span class="block">{{ auth() ? auth()->user()->name : 'Unknown User' }}</span>
 

                                            <span class="block text-xs text-gray-500">{{ auth() ? auth()->user()->email : 'Unknown Email' }}</span>
 

                                        </li>
 

                                        <li><a class="text-sm p-2 hover:bg-gray-50 rounded-md">Profil</a></li>
 

                                        <li><a class="text-sm p-2 hover:bg-gray-50 rounded-md">Pengaturan</a></li>
 

                                        <li>
 

                                            <form method="POST" action="{{ route('logout') }}">
 

                                                @csrf
 

                                                <button type="submit" class="w-full text-left text-sm p-2 text-red-500 hover:bg-gray-50 rounded-md">Logout</button>
 

                                            </form>
 

                                        </li>
 

                                    </ul>
 

                                </div>
 

                            </div>
 

                        </div>
 

                    </div>
 

                </div>
 

            </nav>
 


 

            <main class="flex-1 overflow-y-auto bg-gray-50">
 

                <div class="py-6 px-4 sm:px-6 lg:px-8">
 

                    <div class="lg:hidden mb-6">
 

                        <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
 

                    </div>
 


 

                    {{ $slot }}
 

                </div>
 

            </main>
 

        </div>
 

    </div>
 


 

    <script>
 

        document.addEventListener('DOMContentLoaded', function() {
 

            const toggleSidebar = document.getElementById('toggle-sidebar');
 

            const closeSidebar = document.getElementById('close-sidebar');
 

            const sidebar = document.getElementById('mobile-sidebar');
 

            const overlay = document.getElementById('sidebar-overlay');
 


 

            if (toggleSidebar && closeSidebar && sidebar && overlay) {
 

                toggleSidebar.addEventListener('click', function() {
 

                    sidebar.classList.remove('-translate-x-full');
 

                    overlay.classList.remove('hidden');
 

                });
 


 

                function closeMobileSidebar() {
 

                    sidebar.classList.add('-translate-x-full');
 

                    overlay.classList.add('hidden');
 

                }
 


 

                closeSidebar.addEventListener('click', closeMobileSidebar);
 

                overlay.addEventListener('click', closeMobileSidebar);
 

            }
 

        });
 

    </script>
 


 

    @stack('scripts')
 

</body>
 


 

</html>
