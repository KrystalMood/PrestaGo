@component('layouts.admin', ['title' => 'Tambah Kompetisi Baru'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @component('admin.users.components.page-header')
            @slot('subtitle', 'Lengkapi formulir di bawah ini untuk menambahkan kompetisi baru.')
            @slot('showBackButton', true)
            @slot('backRoute', route('admin.competitions.index'))
        @endcomponent

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.competitions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            @include('admin.competitions.components.form', [
                'competition' => null,
                'periods' => $periods ?? collect(),
                'skills' => $skills ?? collect(),
                'submitButtonText' => 'Simpan Kompetisi'
            ])
        </form>
        
        <!-- Participants Section Placeholder -->
        <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <span class="text-blue-600 mr-2">●</span>
                Peserta Kompetisi
                </h3>
            
            <div class="p-6 text-center bg-gray-50 rounded-lg border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-700 font-medium">Anda dapat mengelola peserta setelah kompetisi dibuat.</p>
                <p class="text-sm text-gray-500 mt-1">Peserta dapat ditambahkan setelah kompetisi berhasil disimpan.</p>
            </div>
            </div>
    </div>
@endcomponent 