@component('layouts.app', ['title' => $title ?? 'Authentication', 'bodyClass' => 'h-[100dvh] flex flex-col justify-center items-center bg-gradient-to-b from-gray-50 to-gray-100 overflow-hidden'])
    <div class="w-full sm:max-w-4xl bg-white shadow-custom overflow-hidden sm:rounded-lg grid grid-cols-1 md:grid-cols-2 max-h-[90dvh]">
        <div class="hidden md:flex p-4 md:p-6 bg-gray-100 flex-col justify-start border-r border-gray-200 overflow-y-auto">
            <div class="flex items-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Polinema" class="w-10 h-10 mr-3">
                <span class="text-xl font-bold text-gray-800">{{ config('app.name') }}</span>
            </div>
            <div class="mb-4 text-center">
                <img src="{{ asset('images/auth-illustration.png') }}" alt="Ilustrasi PrestaGo" class="inline-block max-w-xs mx-auto rounded-lg shadow-sm">
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Portal Prestasi Mahasiswa Polinema</h2>
            <p class="text-gray-600 mb-4 text-sm">Masuk untuk mendapatkan akses ke berbagai layanan PrestaGo:</p>
            <ul class="text-xs text-gray-600 space-y-2">
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-green-100 mr-2">
                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Kelola data prestasi pribadi Anda dengan mudah
                </li>
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-green-100 mr-2">
                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Ajukan verifikasi prestasi secara online
                </li>
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-green-100 mr-2">
                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Akses grafik & laporan prestasi Anda
                </li>
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-green-100 mr-2">
                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Dapatkan notifikasi status pengajuan prestasi
                </li>
            </ul>
            
            <div class="mt-4 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-500">Anda memerlukan akun untuk mengakses sistem PrestaGo. Jika mengalami kendala, hubungi administrator Polinema.</p>
            </div>
        </div>

        <div class="p-4 md:p-6 bg-white flex flex-col items-center justify-center overflow-y-auto md:col-span-1 col-span-1">
            <div class="md:hidden flex flex-col items-center justify-center mb-6 w-full">
                <div class="flex items-center justify-center mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Polinema" class="w-12 h-12">
                </div>
                <h1 class="text-xl font-bold text-gray-800">{{ config('app.name') }}</h1>
                <p class="text-sm text-gray-500 mt-1">Portal Prestasi Mahasiswa Polinema</p>
            </div>
            <div class="w-full">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="mt-2 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Politeknik Negeri Malang. Hak Cipta Dilindungi.
    </div>
@endcomponent