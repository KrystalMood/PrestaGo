@component('layouts.admin', ['title' => 'Pengaturan'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Pengaturan Sistem</h2>
        
        @include('admin.settings.components.settings-menu')
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(request()->routeIs('admin.settings.index') && !request()->routeIs('admin.settings.*.*'))            
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Pengaturan</h3>
                <p class="text-gray-600">Kelola pengaturan dasar aplikasi PrestaGo</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <a href="{{ route('admin.settings.security') }}" class="block group">
                    <div class="h-full bg-gray-50 border-2 border-gray-300 rounded-lg p-6 hover:border-blue-500 hover:shadow-lg transition-all">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800 mb-1">Keamanan & Password</h3>
                                <p class="text-sm text-gray-600">Ubah password dan kelola pengaturan keamanan akun.</p>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-50 text-blue-600 text-sm font-medium group-hover:bg-blue-100 transition-colors">
                                Kelola
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