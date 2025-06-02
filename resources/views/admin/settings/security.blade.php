@component('layouts.admin', ['title' => 'Pengaturan Keamanan'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Pengaturan Sistem</h2>
        
        @include('admin.settings.components.settings-menu')
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Pengaturan Keamanan</h3>
            <p class="text-gray-600 mb-4">Konfigurasi pengaturan keamanan sistem PrestaGo.</p>
        </div>

        <form action="{{ route('admin.settings.index') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Kebijakan Password</h4>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <div class="mb-4">
                            <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-1">Panjang Minimum Password</label>
                            <input type="number" id="password_min_length" name="password_min_length" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $settings['password_min_length'] }}" min="6" max="20">
                            <p class="text-xs text-gray-500 mt-1">Jumlah karakter minimum untuk password (disarankan minimal 8)</p>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="require_uppercase" name="require_uppercase" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" checked disabled>
                                <label for="require_uppercase" class="ml-2 block text-sm text-gray-700">Memerlukan huruf kapital</label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="require_number" name="require_number" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" checked disabled>
                                <label for="require_number" class="ml-2 block text-sm text-gray-700">Memerlukan angka</label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="require_symbol" name="require_symbol" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
                                <label for="require_symbol" class="ml-2 block text-sm text-gray-700">Memerlukan simbol khusus</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Manajemen Sesi</h4>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <div class="mb-4">
                            <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-1">Waktu Sesi (menit)</label>
                            <input type="number" id="session_lifetime" name="session_lifetime" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $settings['session_lifetime'] }}" min="5">
                            <p class="text-xs text-gray-500 mt-1">Durasi maksimal sesi pengguna sebelum harus login kembali</p>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="require_approval" name="require_approval" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $settings['require_approval'] ? 'checked' : '' }}>
                                <label for="require_approval" class="ml-2 block text-sm text-gray-700">Persetujuan admin untuk akun baru</label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Akun baru memerlukan persetujuan admin sebelum aktif</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex p-4 mt-6 text-yellow-800 bg-yellow-50 rounded-lg" role="alert">
                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3 text-sm font-medium">
                    Pengaturan keamanan masih dalam tahap pengembangan. Perubahan belum dapat disimpan saat ini.
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-6 pt-6">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" disabled>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endcomponent 