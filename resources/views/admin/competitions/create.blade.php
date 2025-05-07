@component('layouts.admin', ['title' => 'Tambah Kompetisi Baru'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Tambah Kompetisi Baru</h2>
            <p class="text-gray-600">Lengkapi formulir di bawah ini untuk menambahkan kompetisi baru.</p>
        </div>

        <form action="{{ route('admin.competitions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            @include('admin.competitions.components.form', [
                'competition' => null,
                'categories' => $categories ?? collect(),
                'submitButtonText' => 'Simpan Kompetisi'
            ])
        </form>
    </div>
@endcomponent 