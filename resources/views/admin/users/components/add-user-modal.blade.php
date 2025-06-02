<!-- Add User Modal -->
<div id="add-user-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Tambah Pengguna Baru</h3>
                <button type="button" id="close-add-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="add-user-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="add-user-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="add-user-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Indicator -->
            <div class="mb-6">
                <div class="flex items-center">
                    <div class="step-item active flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                        <p class="text-xs font-medium mt-1">Informasi Dasar</p>
                    </div>
                    <div class="h-0.5 flex-1 bg-gray-300 step-line"></div>
                    <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">2</div>
                        <p class="text-xs font-medium mt-1">Profil & Keamanan</p>
                    </div>
                </div>
            </div>

            <form id="add-user-form" class="space-y-6">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div id="step-1-content" class="transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                name="name"
                                id="add-name"
                                label="Nama Lengkap"
                                required
                                placeholder="Masukkan nama lengkap"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="name-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                type="email"
                                name="email"
                                id="add-email"
                                label="Email"
                                required
                                placeholder="Masukkan alamat email"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="email-error"></p>
                        </div>

                        @php
                            $roleOptions = [];
                            foreach($roles ?? [] as $role) {
                                $roleOptions[$role->id] = $role->level_nama;
                            }
                        @endphp

                        <div class="form-group">
                            <x-ui.form-select
                                name="level_id"
                                id="add-level-id"
                                label="Peran"
                                :options="$roleOptions"
                                :selected="''"
                                required
                                placeholder="Pilih Peran"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="level-id-error"></p>
                        </div>
                        
                        <!-- Study Program field - visible for all users -->
                        @php
                            $studyProgramOptions = [];
                            foreach(App\Models\StudyProgramModel::all() as $program) {
                                $studyProgramOptions[$program->id] = $program->name . ' (' . $program->code . ')';
                            }
                        @endphp

                        <div class="form-group">
                            <x-ui.form-select
                                name="program_studi_id"
                                id="add-program-studi-id"
                                label="Program Studi"
                                :options="$studyProgramOptions"
                                :selected="''"
                                placeholder="Pilih Program Studi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="program-studi-id-error"></p>
                        </div>
                        
                        <!-- NIM field - only visible for students -->
                        <div class="form-group student-field" style="display: none;">
                            <x-ui.form-input
                                name="nim"
                                id="add-nim"
                                label="NIM (Nomor Induk Mahasiswa)"
                                placeholder="Masukkan NIM"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="nim-error"></p>
                        </div>

                        <!-- NIP field - only visible for lecturers -->
                        <div class="form-group lecturer-field" style="display: none;">
                            <x-ui.form-input
                                name="nip"
                                id="add-nip"
                                label="NIP (Nomor Induk Pegawai)"
                                placeholder="Masukkan NIP"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="nip-error"></p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Profile and Security -->
                <div id="step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="md:col-span-2">
                            <div id="photo-upload-container" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        <img id="photo-preview" class="h-24 w-24 object-cover rounded-full border border-gray-200" 
                                             src="{{ asset('images/avatar.png') }}" alt="">
                                    </div>
                                    <label class="block">
                                        <span class="sr-only">Pilih Foto Profil</span>
                                        <input type="file"
                                               name="photo"
                                               id="add-photo"
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Format yang didukung: JPG, PNG, GIF. Maks: 2MB.</p>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <label for="add-password" class="block text-sm font-medium text-gray-700">Kata Sandi <span class="text-red-500">*</span></label>
                                <div class="mt-1 relative">
                                    <div class="relative rounded-md shadow-sm">
                                        <input
                                            type="password"
                                            name="password"
                                            id="add-password"
                                            required
                                            class="block w-full pl-4 pr-10 py-2 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Minimal 6 karakter"
                                        />
                                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-sm text-red-600 error-message hidden mt-1" id="password-error"></p>
                                </div>
                                
                                <!-- Password strength meter -->
                                <div class="mt-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600" id="password-strength-text">Kekuatan Password</span>
                                        <span class="text-xs font-medium" id="password-strength-label">Belum Diisi</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-200 rounded-full mt-1">
                                        <div id="password-strength-meter" class="h-1.5 bg-gray-400 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <ul class="mt-2 grid grid-cols-2 gap-x-2 gap-y-1">
                                        <li class="text-xs text-gray-500 flex items-center" id="req-length">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Minimal 6 karakter
                                        </li>
                                        <li class="text-xs text-gray-500 flex items-center" id="req-number">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Mengandung angka
                                        </li>
                                        <li class="text-xs text-gray-500 flex items-center" id="req-uppercase">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Mengandung huruf besar
                                        </li>
                                        <li class="text-xs text-gray-500 flex items-center" id="req-special">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Karakter khusus (opsional)
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <x-ui.form-input
                                    type="password"
                                    name="password_confirmation"
                                    id="add-password-confirmation"
                                    label="Konfirmasi Kata Sandi"
                                    required
                                    placeholder="Masukkan ulang kata sandi"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="password-confirmation-error"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between space-x-3 pt-5 border-t border-gray-200 mt-6">
                    <div>
                        <button type="button" id="prev-step" class="hidden inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <x-ui.button 
                            variant="secondary" 
                            id="cancel-add-user"
                            type="button"
                        >
                            Batal
                        </x-ui.button>
                        
                        <button type="button" id="next-step" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Langkah Berikutnya
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        
                        <x-ui.button 
                            type="submit" 
                            variant="primary"
                            id="submit-add-user"
                            class="hidden"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Pengguna
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 