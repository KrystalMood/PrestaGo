@component('layouts.admin', ['title' => 'Pengaturan Umum'])
    <div class="bg-white rounded-lg shadow-custom p-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Pengaturan Sistem</h2>
        
        @include('admin.settings.components.settings-menu')
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-lg border-l-4 border-blue-500">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Pengaturan Umum</h3>
            <p class="text-gray-600">Ubah pengaturan dasar untuk sistem PrestaGo. Pengaturan ini akan memengaruhi tampilan dan perilaku dasar aplikasi.</p>
        </div>

        <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" class="max-w-full">
            @csrf
            
            <div class="grid grid-cols-1 gap-10">
                <!-- Application Settings -->
                <div class="col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <h4 class="font-medium text-gray-700 mb-5 flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Informasi Aplikasi
                        </h4>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                                <input type="text" name="app_name" id="app_name" 
                                    class="w-full h-12 px-4 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors text-base" 
                                    value="{{ $settings['app_name'] }}"
                                    placeholder="Masukkan nama aplikasi">
                                @error('app_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">Email Admin</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input type="email" name="admin_email" id="admin_email" 
                                        class="w-full h-12 pl-12 px-4 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors text-base" 
                                        value="{{ $settings['admin_email'] }}"
                                        placeholder="admin@example.com">
                                </div>
                                @error('admin_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Logo Settings -->
                <div class="col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <h4 class="font-medium text-gray-700 mb-5 flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Logo Sistem
                        </h4>
                        
                        <div class="mb-2">
                            <p class="text-sm text-gray-500 mb-4">Logo akan ditampilkan di halaman login dan header aplikasi</p>
                            
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-center w-full md:w-1/3">
                                    <img src="{{ $settings['logo_path'] }}" alt="Logo" class="w-32 h-32 object-contain">
                                </div>
                                
                                <div class="flex-1 w-full">
                                    <div class="relative">
                                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*" onchange="updateFileName(this)">
                                        <label for="logo" class="cursor-pointer flex items-center justify-center px-4 py-3 bg-blue-50 border border-blue-300 rounded-lg font-medium text-sm text-blue-700 hover:bg-blue-100 transition-colors w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <span>Pilih Logo Baru</span>
                                        </label>
                                    </div>
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p id="file-name" class="text-sm text-gray-500">Belum ada file yang dipilih</p>
                                        <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, GIF (Maks. 2MB)</p>
                                    </div>
                                </div>
                            </div>
                            @error('logo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-10 pt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    
    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name;
            document.getElementById('file-name').textContent = fileName || 'Belum ada file yang dipilih';
        }
    </script>
@endcomponent 