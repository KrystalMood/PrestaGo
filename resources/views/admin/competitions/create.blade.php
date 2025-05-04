@component('layouts.admin', ['title' => 'Tambah Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Tambah Kompetisi Baru</h2>
        <p class="text-gray-600 mb-6">Gunakan formulir di bawah ini untuk menambahkan informasi tentang kompetisi baru ke dalam sistem.</p>
        
        <div class="flex p-4 mb-6 text-yellow-800 bg-yellow-50 rounded-lg" role="alert">
            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div class="ml-3 text-sm font-medium">
                Halaman ini masih dalam tahap pengembangan. Fitur penambahan kompetisi akan segera tersedia.
            </div>
        </div>
        
        <form action="#" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kompetisi</label>
                    <input type="text" id="name" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Masukkan nama kompetisi">
                </div>
                
                <div>
                    <label for="organizer" class="block text-sm font-medium text-gray-700 mb-1">Penyelenggara</label>
                    <input type="text" id="organizer" name="organizer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Masukkan nama penyelenggara">
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Masukkan deskripsi kompetisi"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="category" name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Pilih Kategori</option>
                        <option value="teknologi">Teknologi</option>
                        <option value="bisnis">Bisnis</option>
                        <option value="sains">Sains</option>
                        <option value="sosial">Sosial</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL Kompetisi</label>
                <input type="url" id="url" name="url" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="https://example.com">
            </div>
            
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.competitions.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md transition-colors">
                    Simpan Kompetisi
                </button>
            </div>
        </form>
    </div>
@endcomponent 