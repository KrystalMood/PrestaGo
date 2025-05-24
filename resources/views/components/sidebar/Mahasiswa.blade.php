<aside class="w-64 shadow-custom bg-white border-r border-gray-200 transition-all duration-300 ease-in-out overflow-hidden hidden lg:block h-screen sticky top-0">
    <div class="p-4 flex items-center border-b border-gray-200">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="w-10 h-10 mr-2">
        <span class="text-lg font-bold text-gray-800">{{ config('app.name') }}</span>
    </div>
    <div class="p-4 overflow-y-auto max-h-[calc(100vh-4rem)]">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('Mahasiswa.dashboard') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('Mahasiswa.dashboard') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            <div class="py-2 mt-2 border-t border-gray-100">
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Manajemen</span>
            </div>
            <li>
                <a href="{{ route('Mahasiswa.achievements.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('Mahasiswa.achievements.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="font-medium">Prestasi Saya</span>
                </a>
            </li>
            <li>
                <a href="{{ route('Mahasiswa.competitions.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('Mahasiswa.competitions.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="font-medium">Daftar Lomba</span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('Mahasiswa.recommendations.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span class="font-medium">Rekomendasi Lomba</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
