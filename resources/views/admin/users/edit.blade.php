@component('layouts.admin', ['title' => 'Edit Pengguna'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @component('admin.users.components.page-header')
            @slot('title', 'Edit Pengguna: ' . $user->name)
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-2">
                <form action="{{ route('admin.users.update', $user->users_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <x-ui.form-input
                            name="name"
                            label="Nama Lengkap"
                            :value="old('name', $user->name)"
                            required
                            placeholder="Masukkan nama lengkap"
                            :hasError="$errors->has('name')"
                            :errorMessage="$errors->first('name')"
                        />

                        <x-ui.form-input
                            type="email"
                            name="email"
                            label="Email"
                            :value="old('email', $user->email)"
                            required
                            placeholder="Masukkan alamat email"
                            :hasError="$errors->has('email')"
                            :errorMessage="$errors->first('email')"
                        />

                        @php
                            $roleOptions = [];
                            foreach($roles as $role) {
                                $roleOptions[$role->level_id] = $role->level_nama;
                            }
                        @endphp

                        <x-ui.form-select
                            name="level_id"
                            label="Peran"
                            :options="$roleOptions"
                            :selected="old('level_id', $user->level_id)"
                            required
                            placeholder="Pilih Peran"
                            :hasError="$errors->has('level_id')"
                            :errorMessage="$errors->first('level_id')"
                        />

                        <x-ui.form-file
                            name="photo"
                            label="Foto Profil"
                            accept="image/*"
                            :helperText="'Biarkan kosong jika tidak ingin mengubah foto profil.'"
                            :hasError="$errors->has('photo')"
                            :errorMessage="$errors->first('photo')"
                            :preview="$user->photo ? asset('storage/' . $user->photo) : null"
                            :previewAlt="$user->name"
                        />

                        <div class="md:col-span-2 mt-2">
                            <h3 class="text-lg font-medium text-gray-900">Ganti Kata Sandi</h3>
                            <p class="text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah kata sandi.</p>
                        </div>

                        <x-ui.form-input
                            type="password"
                            name="password"
                            label="Kata Sandi Baru"
                            placeholder="Kosongkan jika tidak ingin mengubah"
                            helperText="Minimal 6 karakter"
                            :hasError="$errors->has('password')"
                            :errorMessage="$errors->first('password')"
                        />

                        <x-ui.form-input
                            type="password"
                            name="password_confirmation"
                            label="Konfirmasi Kata Sandi Baru"
                            placeholder="Masukkan ulang kata sandi baru"
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
                            Simpan Perubahan
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <!-- User Profile Card -->
            <div>
                @component('admin.users.components.user-profile-card', ['user' => $user])
                @endcomponent
                
                <!-- Danger Zone -->
                <div class="mt-6 bg-red-50 rounded-lg border border-red-200 p-4">
                    <h3 class="text-red-800 font-medium mb-2">Zona Berbahaya</h3>
                    <p class="text-red-700 text-sm mb-3">Tindakan berikut tidak dapat dibatalkan. Harap hati-hati.</p>
                    
                    <form method="POST" action="{{ route('admin.users.destroy', $user->users_id) }}" id="delete-form" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <x-ui.button
                            type="button"
                            variant="danger"
                            size="sm"
                            onclick="confirmDelete()"
                            icon="<svg class='h-4 w-4' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' /></svg>"
                        >
                            Hapus Pengguna
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-red-100 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus pengguna <span class="font-semibold">{{ $user->name }}</span>? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="flex justify-center gap-4">
                    <x-ui.button
                        id="cancel-delete"
                        variant="secondary"
                    >
                        Batal
                    </x-ui.button>
                    
                    <x-ui.button
                        id="confirm-delete"
                        variant="danger"
                    >
                        Ya, Hapus
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        
        document.getElementById('cancel-delete').addEventListener('click', function() {
            document.getElementById('delete-modal').classList.add('hidden');
        });
        
        document.getElementById('confirm-delete').addEventListener('click', function() {
            document.getElementById('delete-form').submit();
        });
        
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
@endcomponent 