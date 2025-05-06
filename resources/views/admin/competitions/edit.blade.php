@component('layouts.admin', ['title' => 'Edit Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Edit Kompetisi</h2>
            <p class="text-gray-600">Perbarui detail kompetisi dengan menggunakan formulir di bawah ini.</p>
        </div>

        <form action="{{ route('admin.competitions.update', $competition->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            @include('admin.competitions.components.form', [
                'competition' => $competition,
                'categories' => $categories ?? collect(),
                'submitButtonText' => 'Perbarui Kompetisi'
            ])
        </form>
    </div>
@endcomponent 