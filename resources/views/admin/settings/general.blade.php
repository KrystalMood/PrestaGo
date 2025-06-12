@component('layouts.admin', ['title' => 'Pengaturan Aplikasi'])
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
        
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Pengaturan Aplikasi</h3>
            <p class="text-gray-600">Ubah pengaturan dasar untuk sistem PrestaGo.</p>
        </div>

        <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="bg-gray-50 p-5 rounded-lg mb-6">
                <h4 class="font-medium text-gray-700 mb-4">Informasi Dasar</h4>
                
                <div class="mb-4">
                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi</label>
                    <input type="text" name="app_name" id="app_name" 
                        class="w-full py-2.5 px-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                        value="{{ $settings['app_name'] ?? '' }}"
                        placeholder="Masukkan nama aplikasi">
                    @error('app_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">Email Admin</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input type="email" name="admin_email" id="admin_email" 
                            class="w-full py-2.5 px-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10 transition-colors" 
                            value="{{ $settings['admin_email'] ?? '' }}"
                            placeholder="admin@example.com">
                    </div>
                    @error('admin_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="bg-gray-50 p-5 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-4">Logo Aplikasi</h4>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-white border-2 border-gray-300 rounded-lg p-3 w-24 h-24 flex items-center justify-center logo-preview">
                        <img src="{{ $settings['logo_path'] ?? asset('images/default-logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                    
                    <div class="flex-1">
                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*" onchange="updateFileName(this)">
                        <label for="logo" class="cursor-pointer inline-flex items-center px-4 py-2.5 bg-blue-50 border-2 border-blue-300 rounded-md font-medium text-sm text-blue-700 hover:bg-blue-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Pilih Logo
                        </label>
                        <p id="file-name" class="text-sm text-gray-500 mt-2">Belum ada file yang dipilih</p>
                        <p class="text-xs text-gray-500">Format: PNG, JPG, GIF (Maks. 2MB)</p>
                    </div>
                </div>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2.5 border-2 border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    
    @vite('resources/js/admin/settings.js')
@endcomponent 