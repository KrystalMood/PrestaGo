@component('layouts.admin', ['title' => 'Tambah Pengguna Baru'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @component('admin.users.components.page-header')
            @slot('subtitle', 'Formulir Pendaftaran Pengguna Baru')
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

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <x-ui.form-input
                    name="name"
                    label="Nama Lengkap"
                    :value="old('name')"
                    required
                    placeholder="Masukkan nama lengkap"
                    :hasError="$errors->has('name')"
                    :errorMessage="$errors->first('name')"
                />

                <x-ui.form-input
                    type="email"
                    name="email"
                    label="Email"
                    :value="old('email')"
                    required
                    placeholder="Masukkan alamat email"
                    :hasError="$errors->has('email')"
                    :errorMessage="$errors->first('email')"
                />

                @php
                    $roleOptions = [];
                    foreach($roles as $role) {
                        $roleOptions[$role->id] = $role->level_nama;
                    }
                @endphp

                <x-ui.form-select
                    name="level_id"
                    label="Peran"
                    :options="$roleOptions"
                    :selected="old('level_id')"
                    required
                    placeholder="Pilih Peran"
                    :hasError="$errors->has('level_id')"
                    :errorMessage="$errors->first('level_id')"
                />

                <x-ui.form-file
                    name="photo"
                    label="Foto Profil"
                    accept="image/*"
                    :helperText="'Format yang didukung: JPG, PNG, GIF. Maks: 2MB.'"
                    :hasError="$errors->has('photo')"
                    :errorMessage="$errors->first('photo')"
                />

                <x-ui.form-input
                    type="password"
                    name="password"
                    label="Kata Sandi"
                    required
                    placeholder="Minimal 6 karakter"
                    helperText="Minimal 6 karakter"
                    :hasError="$errors->has('password')"
                    :errorMessage="$errors->first('password')"
                />

                <x-ui.form-input
                    type="password"
                    name="password_confirmation"
                    label="Konfirmasi Kata Sandi"
                    required
                    placeholder="Masukkan ulang kata sandi"
                />
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                <x-ui.button 
                    variant="secondary" 
                    tag="a" 
                    href="{{ route('admin.users.index') }}"
                >
                    Batal
                </x-ui.button>
                
                <x-ui.button 
                    type="submit" 
                    variant="primary"
                >
                    Tambah Pengguna
                </x-ui.button>
            </div>
        </form>
    </div>
@endcomponent 