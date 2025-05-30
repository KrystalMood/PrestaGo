<aside class="w-64 shadow-custom bg-white border-r border-gray-200 transition-all duration-300 ease-in-out overflow-hidden hidden lg:block h-screen sticky top-0">
    <div class="p-4 flex items-center border-b border-gray-200">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="w-10 h-10 mr-2">
        <span class="text-lg font-bold text-gray-800">{{ config('app.name') }}</span>
    </div>

    <div class="p-4 overflow-y-auto max-h-[calc(100vh-4rem)]">
        <ul class="space-y-1">
            <div class="py-2 mt-2 ">
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Pembimbingan</span>
            </div>

            <li>
                <a href="{{ route('lecturer.students.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('lecturer.students.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Mahasiswa Bimbingan</span>
                </a>
            </li>

            <li>
                <a class="flex items-center p-3 rounded-lg {{ request()->routeIs('lecturer.achievements.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <span class="font-medium">Verifikasi Prestasi</span>
                </a>
            </li>

            <li>
                <a class="flex items-center p-3 rounded-lg {{ request()->routeIs('lecturer.competitions.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Informasi Lomba</span>
                </a>
            </li>

            <li>
                <a class="flex items-center p-3 rounded-lg {{ request()->routeIs('lecturer.reports.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Laporan Prestasi</span>
                </a>
            </li>
        </ul>
    </div>
</aside> 