@component('layouts.admin', ['title' => 'Manajemen Periode'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Manajemen Periode Semester</h2>
        <p class="text-gray-600 mb-6">Halaman ini menampilkan daftar periode semester yang tersedia dan memungkinkan Anda untuk menambah, mengubah, atau menghapus data periode.</p>
        
        <div class="flex p-4 mb-4 text-yellow-800 bg-yellow-50 rounded-lg" role="alert">
            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div class="ml-3 text-sm font-medium">
                Halaman ini masih dalam tahap pengembangan. Fitur manajemen periode akan segera tersedia.
            </div>
        </div>
    </div>
@endcomponent 