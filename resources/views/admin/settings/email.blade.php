@component('layouts.admin', ['title' => 'Pengaturan Email'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Pengaturan Sistem</h2>
        
        @include('admin.settings.components.settings-menu')
        
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Pengaturan Email</h3>
            <p class="text-gray-600 mb-4">Konfigurasi pengaturan email dan notifikasi sistem.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-4">Konfigurasi SMTP</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-4">
                        <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                        <input type="text" id="mail_driver" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $settings['mail_driver'] }}" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <input type="text" id="mail_host" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $settings['mail_host'] }}" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                        <input type="text" id="mail_port" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $settings['mail_port'] }}" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengirim</label>
                        <input type="email" id="mail_from_address" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $settings['mail_from_address'] }}" readonly>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Pengaturan email tersimpan dalam file .env aplikasi. Untuk mengubah konfigurasi ini, silakan hubungi administrator sistem.</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-700 mb-4">Template Notifikasi</h4>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-md p-4 hover:bg-gray-50">
                        <h5 class="font-medium text-sm mb-1">Notifikasi Prestasi Baru</h5>
                        <p class="text-xs text-gray-500 mb-2">Dikirim saat ada prestasi baru yang ditambahkan.</p>
                        <a href="#" class="text-blue-600 text-sm hover:underline">Lihat Template</a>
                    </div>
                    
                    <div class="border border-gray-200 rounded-md p-4 hover:bg-gray-50">
                        <h5 class="font-medium text-sm mb-1">Notifikasi Verifikasi</h5>
                        <p class="text-xs text-gray-500 mb-2">Dikirim saat prestasi diverifikasi oleh admin.</p>
                        <a href="#" class="text-blue-600 text-sm hover:underline">Lihat Template</a>
                    </div>
                    
                    <div class="border border-gray-200 rounded-md p-4 hover:bg-gray-50">
                        <h5 class="font-medium text-sm mb-1">Notifikasi Kompetisi</h5>
                        <p class="text-xs text-gray-500 mb-2">Dikirim saat ada kompetisi baru yang relevan.</p>
                        <a href="#" class="text-blue-600 text-sm hover:underline">Lihat Template</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex p-4 mt-6 text-yellow-800 bg-yellow-50 rounded-lg" role="alert">
            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div class="ml-3 text-sm font-medium">
                Fitur kustomisasi template email sedang dalam pengembangan dan akan segera tersedia.
            </div>
        </div>
    </div>
@endcomponent 