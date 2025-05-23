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

            <li>
                <a href="{{ route('admin.verification.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.verification.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <span class="font-medium">Verifikasi Prestasi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.competitions.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.competitions.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Lomba</span>
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
                    <span class="font-medium">Rekomendasi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Laporan</span>
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