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
        <!-- Desktop Sidebar -->
        <x-sidebar.lecturer />

        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

        <!-- Mobile Sidebar -->
        <x-sidebar.mobile.lecturer />

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <x-shared.navbar :title="$title" :user="$user ?? auth()->user()" />

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
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (toggleSidebar && sidebar && overlay) {
                toggleSidebar.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });

                function closeMobileSidebar() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }

                overlay.addEventListener('click', closeMobileSidebar);
                
                const closeButton = document.querySelector('#close-sidebar, .close-sidebar-btn');
                if (closeButton) {
                    closeButton.addEventListener('click', closeMobileSidebar);
                }
            }
        });
    </script>

    @stack('scripts')
</body>