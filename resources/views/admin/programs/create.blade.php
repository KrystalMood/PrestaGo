@component('layouts.admin', ['title' => 'Tambah Program Studi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'title' => 'Tambah Program Studi',
                'subtitle' => 'Tambahkan program studi baru ke dalam sistem.',
                'back_url' => route('admin.programs.index'),
            ])
        </div>

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

        <form action="{{ route('admin.programs.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <x-ui.form-input
                    name="code"
                    label="Kode Program Studi"
                    :value="old('code')"
                    required
                    placeholder="Contoh: TMES_D3"
                    helperText="Kode program studi harus unik dan tidak boleh melebihi 20 karakter."
                    :hasError="$errors->has('code')"
                    :errorMessage="$errors->first('code')"
                />

                <x-ui.form-input
                    name="name"
                    label="Nama Program Studi"
                    :value="old('name')"
                    required
                    placeholder="Contoh: D-III Teknik Mesin"
                    :hasError="$errors->has('name')"
                    :errorMessage="$errors->first('name')"
                />

                <x-ui.form-input
                    name="faculty"
                    label="Fakultas/Jurusan"
                    :value="old('faculty')"
                    required
                    placeholder="Contoh: Jurusan Teknik Mesin"
                    :hasError="$errors->has('faculty')"
                    :errorMessage="$errors->first('faculty')"
                />

                @php
                    $degreeOptions = [
                        'D-III' => 'D-III',
                        'D-IV' => 'D-IV',
                        'S1' => 'S1',
                        'S2' => 'S2',
                        'S2 Terapan' => 'S2 Terapan',
                        'S3' => 'S3'
                    ];
                @endphp

                <x-ui.form-select
                    name="degree_level"
                    label="Jenjang"
                    :options="$degreeOptions"
                    :selected="old('degree_level')"
                    required
                    placeholder="-- Pilih Jenjang --"
                    :hasError="$errors->has('degree_level')"
                    :errorMessage="$errors->first('degree_level')"
                />
            </div>

            <div class="form-group mb-4 mt-4">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_active') ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">Program Studi Aktif</label>
                </div>
                <p class="mt-1.5 text-sm text-gray-500">Program studi aktif akan ditampilkan dalam sistem dan dapat dipilih oleh pengguna.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                <x-ui.button 
                    variant="secondary" 
                    tag="a" 
                    href="{{ route('admin.programs.index') }}"
                >
                    Batal
                </x-ui.button>
                
                <x-ui.button 
                    type="submit" 
                    variant="primary"
                >
                    Simpan Program Studi
                </x-ui.button>
            </div>
        </form>
    </div>
@endcomponent 