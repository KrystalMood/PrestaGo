@props(['title' => null, 'user' => null])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getlocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? "$title | " . config('app.name', 'PrestaGo') : config('app.name', 'PrestaGo') }}</title>

    <!-- favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- daisyui and tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#6366f1',
                            default: '#4f46e5',
                            dark: '#4338ca',
                        }
                    },
                    boxshadow: {
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

    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>



@props(['title' => null, 'user' => null])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getlocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? "$title | " . config('app.name', 'PrestaGo') : config('app.name', 'PrestaGo') }}</title>

    <!-- favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- daisyui and tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#6366f1',
                            default: '#4f46e5',
                            dark: '#4338ca',
                        }
                    },
                    boxshadow: {
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

    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>


<body class="font-sans antialiased min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
    <x-ui.toast />
    <div class="flex min-h-screen">
    <x-shared.sidebar :user="$user" />

        <!-- main content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <x-shared.navbar :title="$title" :user="$user" />
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <div class="lg:hidden mb-6">
                        <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'dashboard' }}</h1>
                    </div>

                    @yield('content')

                    <div class="mt-6">
                        @include('partials.footer')
                    </div>
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
</body></html>
</html>
