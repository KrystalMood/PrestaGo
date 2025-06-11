<!-- Edit User Modal -->
<div id="edit-user-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Pengguna</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="edit-user-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="edit-user-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-user-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="edit-user-form" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-user-id" name="user_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <x-ui.form-input
                        name="name"
                        id="edit-name"
                        label="Nama Lengkap"
                        value=""
                        required
                        placeholder="Masukkan nama lengkap"
                    />

                    <x-ui.form-input
                        type="email"
                        name="email"
                        id="edit-email"
                        label="Email"
                        value=""
                        required
                        placeholder="Masukkan alamat email"
                    />

                    @php
                        $roleOptions = [];
                        foreach($roles ?? [] as $role) {
                            $roleOptions[$role->id] = $role->level_nama;
                        }
                    @endphp

                    <x-ui.form-select
                        name="level_id"
                        id="edit-level-id"
                        label="Peran"
                        :options="$roleOptions"
                        :selected="''"
                        required
                        placeholder="Pilih Peran"
                    />
                    
                    <!-- Study Program field - visible for all users -->
                    @php
                        $studyProgramOptions = [];
                        foreach(App\Models\StudyProgramModel::all() as $program) {
                            $studyProgramOptions[$program->id] = $program->name . ' (' . $program->code . ')';
                        }
                    @endphp

                    <x-ui.form-select
                        name="program_studi_id"
                        id="edit-program-studi-id"
                        label="Program Studi"
                        :options="$studyProgramOptions"
                        :selected="''"
                        placeholder="Pilih Program Studi"
                    />
                    
                    <!-- NIM field - only visible for students -->
                    <div class="form-group student-field" style="display: none;">
                        <x-ui.form-input
                            name="nim"
                            id="edit-nim"
                            label="NIM (Nomor Induk Mahasiswa)"
                            placeholder="Masukkan NIM"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-nim-error"></p>
                    </div>

                    <!-- NIP field - only visible for lecturers -->
                    <div class="form-group lecturer-field" style="display: none;">
                        <x-ui.form-input
                            name="nip"
                            id="edit-nip"
                            label="NIP (Nomor Induk Pegawai)"
                            placeholder="Masukkan NIP"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-nip-error"></p>
                    </div>

                    <div class="form-group">
                        <label for="edit-photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                <img id="current-photo-preview" class="h-full w-full object-cover" 
                                    src="{{ asset('images/avatar.png') }}" alt="Current Photo">
                            </div>
                            <span class="text-xs text-gray-500">Foto saat ini</span>
                        </div>
                        <x-ui.form-file
                            name="photo"
                            id="edit-photo"
                            helperText="Biarkan kosong jika tidak ingin mengubah foto profil."
                            accept="image/*"
                        />
                    </div>

                    <div class="md:col-span-2 mt-2 border-t pt-4">
                        <h3 class="text-md font-medium text-gray-900 mb-2">Ganti Kata Sandi</h3>
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="update-password-toggle" class="h-4 w-4 text-indigo-600 border-gray-300 rounded mr-2">
                            <label for="update-password-toggle" class="text-sm text-gray-700">Saya ingin mengubah kata sandi</label>
                        </div>
                    </div>

                    <div id="password-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 hidden">
                        <x-ui.form-input
                            type="password"
                            name="password"
                            id="edit-password"
                            label="Kata Sandi Baru"
                            placeholder="Masukkan kata sandi baru"
                            helperText="Minimal 6 karakter"
                        />

                        <x-ui.form-input
                            type="password"
                            name="password_confirmation"
                            id="edit-password-confirmation"
                            label="Konfirmasi Kata Sandi Baru"
                            placeholder="Masukkan ulang kata sandi baru"
                        />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                    <x-ui.button 
                        variant="secondary" 
                        id="cancel-edit-user"
                        type="button"
                    >
                        Batal
                    </x-ui.button>
                    
                    <x-ui.button 
                        type="submit" 
                        variant="primary"
                        id="submit-edit-user"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Simpan Perubahan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div> 