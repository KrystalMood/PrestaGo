<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>403 - Forbidden | {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
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
                        'custom': '0 4px 20px 0 rgba(0, 0, 0, 0.05)'
                    }
                }
            },
            daisyui: {
                themes: ["light"]
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <script src="{{ asset('build/assets/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased bg-white min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md mx-auto text-center">
        <div class="py-6">
            <h2 class="text-indigo-500 text-2xl font-semibold mb-4">403</h2>

            <img src="{{ asset('images/errors.png') }}" alt="Error Illustration" class="w-auto h-48 mx-auto mb-5 object-contain">

            <h1 class="text-4xl font-bold text-gray-800 mb-3">Akses Ditolak</h1>

            <p class="text-gray-600 mb-6">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. posisi kamu adalah {{ auth()->user()  }}</p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-2">
                <a href="{{ url('/') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-none rounded-md px-6 py-3 text-white font-medium">
                    Kembali ke Beranda
                </a>

                <a href="#" class="btn btn-link text-gray-600 hover:text-indigo-600 flex items-center justify-center">
                    Hubungi Dukungan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
