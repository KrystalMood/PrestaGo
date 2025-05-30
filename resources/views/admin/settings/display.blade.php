@component('layouts.admin', ['title' => 'Pengaturan Tampilan'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Pengaturan Sistem</h2>
        
        @include('admin.settings.components.settings-menu')
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Pengaturan Tampilan</h3>
            <p class="text-gray-600 mb-4">Kustomisasi tampilan dan pengalaman pengguna pada sistem PrestaGo.</p>
        </div>

        <form action="{{ route('admin.settings.index') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Tema Aplikasi</h4>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <div class="mb-5">
                            <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tema</label>
                            <select id="theme" name="theme" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="default" {{ $settings['theme'] === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="dark" disabled>Dark Mode (Segera Hadir)</option>
                                <option value="custom" disabled>Kustom (Segera Hadir)</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div>
                                <div class="aspect-w-16 aspect-h-9 mb-2">
                                    <div class="w-full h-full rounded-md bg-white border-2 border-blue-500 flex items-center justify-center text-xs p-1">
                                        <div class="bg-blue-100 w-full h-4 rounded"></div>
                                    </div>
                                </div>
                                <p class="text-xs text-center">Default</p>
                            </div>
                            <div>
                                <div class="aspect-w-16 aspect-h-9 mb-2">
                                    <div class="w-full h-full rounded-md bg-gray-800 border border-gray-700 flex items-center justify-center text-xs p-1">
                                        <div class="bg-gray-700 w-full h-4 rounded"></div>
                                    </div>
                                </div>
                                <p class="text-xs text-center text-gray-400">Dark Mode</p>
                            </div>
                            <div>
                                <div class="aspect-w-16 aspect-h-9 mb-2">
                                    <div class="w-full h-full rounded-md bg-gradient-to-r from-indigo-100 to-purple-100 border border-gray-300 flex items-center justify-center text-xs p-1">
                                        <div class="bg-gradient-to-r from-indigo-400 to-purple-400 w-full h-4 rounded"></div>
                                    </div>
                                </div>
                                <p class="text-xs text-center text-gray-400">Kustom</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Pengaturan Pagination & Widget</h4>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <div class="mb-5">
                            <label for="items_per_page" class="block text-sm font-medium text-gray-700 mb-1">Item Per Halaman</label>
                            <select id="items_per_page" name="items_per_page" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="10" {{ $settings['items_per_page'] == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $settings['items_per_page'] == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $settings['items_per_page'] == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $settings['items_per_page'] == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Jumlah item yang ditampilkan per halaman pada tabel</p>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Widget Dashboard</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="widget_achievements" name="dashboard_widgets[]" value="achievements" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ in_array('achievements', $settings['dashboard_widgets']) ? 'checked' : '' }}>
                                    <label for="widget_achievements" class="ml-2 block text-sm text-gray-700">Prestasi Terbaru</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="widget_competitions" name="dashboard_widgets[]" value="competitions" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ in_array('competitions', $settings['dashboard_widgets']) ? 'checked' : '' }}>
                                    <label for="widget_competitions" class="ml-2 block text-sm text-gray-700">Kompetisi Mendatang</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="widget_users" name="dashboard_widgets[]" value="users" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ in_array('users', $settings['dashboard_widgets']) ? 'checked' : '' }}>
                                    <label for="widget_users" class="ml-2 block text-sm text-gray-700">Statistik Pengguna</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="widget_reports" name="dashboard_widgets[]" value="reports" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
                                    <label for="widget_reports" class="ml-2 block text-sm text-gray-400">Laporan Mingguan (Segera Hadir)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex p-4 mt-6 text-yellow-800 bg-yellow-50 rounded-lg" role="alert">
                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3 text-sm font-medium">
                    Pengaturan tampilan masih dalam tahap pengembangan. Perubahan belum dapat disimpan saat ini.
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