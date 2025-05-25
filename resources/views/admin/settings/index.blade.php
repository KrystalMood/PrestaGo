@component('layouts.admin', ['title' => 'Pengaturan'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengaturan Sistem</h2>
        <p class="text-gray-600 mb-6">Kelola semua pengaturan aplikasi PrestaGo dalam satu tempat</p>
        
        @include('admin.settings.components.settings-menu')
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        @if(request()->routeIs('admin.settings.index') && !request()->routeIs('admin.settings.*.*'))            
            <!-- Settings Cards -->
            <h3 class="text-lg font-medium text-gray-800 mb-5">Konfigurasi Utama</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('admin.settings.general') }}" class="block group">
                    <div class="h-full border border-gray-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                        <div class="flex justify-center mb-4">
                            <div class="bg-blue-100 group-hover:bg-blue-200 rounded-full p-4 transition-all">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-medium text-gray-800 text-center mb-2">Pengaturan Umum</h3>
                        <p class="text-sm text-gray-600 text-center">Konfigurasi nama sistem, logo, dan informasi kontak pengelola portal.</p>
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center text-blue-500 text-sm font-medium group-hover:underline">
                                Kelola
                                <svg class="w-4 h-4 ml-1 group-hover:ml-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.settings.email') }}" class="block group">
                    <div class="h-full border border-gray-200 rounded-lg p-6 hover:border-amber-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                        <div class="flex justify-center mb-4">
                            <div class="bg-amber-100 group-hover:bg-amber-200 rounded-full p-4 transition-all">
                                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-medium text-gray-800 text-center mb-2">Email</h3>
                        <p class="text-sm text-gray-600 text-center">Atur templat email dan konfigurasi notifikasi untuk pengguna sistem.</p>
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center text-amber-500 text-sm font-medium group-hover:underline">
                                Konfigurasi
                                <svg class="w-4 h-4 ml-1 group-hover:ml-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.settings.security') }}" class="block group">
                    <div class="h-full border border-gray-200 rounded-lg p-6 hover:border-red-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                        <div class="flex justify-center mb-4">
                            <div class="bg-red-100 group-hover:bg-red-200 rounded-full p-4 transition-all">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-medium text-gray-800 text-center mb-2">Keamanan</h3>
                        <p class="text-sm text-gray-600 text-center">Pengaturan keamanan, seperti kebijakan kata sandi dan durasi sesi.</p>
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center text-red-500 text-sm font-medium group-hover:underline">
                                Atur Keamanan
                                <svg class="w-4 h-4 ml-1 group-hover:ml-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.settings.display') }}" class="block group">
                    <div class="h-full border border-gray-200 rounded-lg p-6 hover:border-green-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                        <div class="flex justify-center mb-4">
                            <div class="bg-green-100 group-hover:bg-green-200 rounded-full p-4 transition-all">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-medium text-gray-800 text-center mb-2">Tampilan Aplikasi</h3>
                        <p class="text-sm text-gray-600 text-center">Kustomisasi tampilan portal dan pengaturan tema aplikasi.</p>
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center text-green-500 text-sm font-medium group-hover:underline">
                                Sesuaikan
                                <svg class="w-4 h-4 ml-1 group-hover:ml-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
@endcomponent 