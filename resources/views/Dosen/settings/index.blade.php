@component('layouts.dosen', ['title' => 'Pengaturan Akun & Profil'])
<div class="bg-white rounded-lg shadow-custom p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Pengaturan Akun & Profil</h2>
    <p class="text-gray-600 mb-6">Halaman ini memungkinkan Anda mengelola informasi akun dan profil akademik sebagai dosen.</p>

    <div class="flex p-4 mb-6 text-yellow-800 bg-yellow-50 rounded-lg" role="alert">
        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div class="ml-3 text-sm font-medium">
            Halaman ini masih dalam tahap pengembangan. Fitur pengaturan sistem akan segera tersedia.
        </div>
    </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Informasi Akun -->
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Informasi Akun</h3>
            <p class="text-sm text-gray-600 mb-4">Atur nama, email, dan foto profil Anda.</p>
            <a href="{{ route('lecturer.profile.profile') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Kelola Akun
            </a>
        </div>

        <!-- Profil Akademik -->
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Profil Akademik</h3>
            <p class="text-sm text-gray-600 mb-4">Isi riwayat pendidikan, gelar akademik, dan jabatan fungsional.</p>
            <a href="{{ route('lecturer.akademik.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Kelola Profil Akademik
            </a>
        </div>

        <!-- Bidang Minat Penelitian -->
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Bidang Minat Penelitian</h3>
            <p class="text-sm text-gray-600 mb-4">Tambahkan atau perbarui bidang keahlian dan minat riset Anda.</p>
            <a href="{{ route('lecturer.penelitian.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Kelola Minat Riset
            </a>
        </div>

        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Keamanan</h3>
            <p class="text-sm text-gray-600 mb-4">Pengaturan keamanan, seperti kebijakan kata sandi.</p>
            <button disabled class="px-4 py-2 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                Pengaturan Keamanan
            </button>
        </div>
            
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Tampilan Aplikasi</h3>
            <p class="text-sm text-gray-600 mb-4">Kustomisasi tampilan portal dan pengaturan tema aplikasi.</p>
            <button disabled class="px-4 py-2 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                Sesuaikan Tampilan
            </button>
        </div>
            
{{-- 
        <!-- Riwayat Pendidikan -->
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Riwayat Pendidikan</h3>
            <p class="text-sm text-gray-600 mb-4">Masukkan data pendidikan dari jenjang S1 hingga S3.</p>
            <a href="{{ route('lecturer.pendidikan.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Atur Riwayat Pendidikan
            </a>
        </div>

        <!-- Publikasi Ilmiah -->
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Publikasi Ilmiah</h3>
            <p class="text-sm text-gray-600 mb-4">Kelola daftar publikasi Anda, seperti jurnal, konferensi, dan buku.</p>
            <a href="{{ route('lecturer.publikasi.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Kelola Publikasi
            </a>
        </div>

        <!-- Kegiatan Penelitian -->
        <div class="border border-gray-100 rounded-lg p-5 hover:bg-gray-50 transition-colors">
            <h3 class="font-medium text-gray-800 mb-3">Kegiatan Penelitian</h3>
            <p class="text-sm text-gray-600 mb-4">Lacak dan kelola proyek riset serta hibah yang sedang dijalankan.</p>
            <a href="{{ route('lecturer.kegiatan.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Lihat Kegiatan Penelitian
            </a>
        </div>
    </div> --}}
</div>
@endcomponent
