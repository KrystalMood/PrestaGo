<div id="edit-sub-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Sub-Kompetisi</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
                        
            <!-- Error Alert -->
            <div id="edit-sub-competition-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="edit-sub-competition-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-sub-competition-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Skeleton Loading -->
            <div class="edit-sub-competition-skeleton">
                <!-- Step Indicator Skeleton -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <div class="step-item flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full bg-gray-200 animate-pulse"></div>
                            <div class="w-16 h-4 bg-gray-200 rounded mt-1 animate-pulse"></div>
                        </div>
                        <div class="h-0.5 flex-1 bg-gray-200 animate-pulse"></div>
                        <div class="step-item flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full bg-gray-200 animate-pulse"></div>
                            <div class="w-16 h-4 bg-gray-200 rounded mt-1 animate-pulse"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 1 Skeleton -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <div class="h-4 bg-gray-200 w-24 rounded mb-2 animate-pulse"></div>
                            <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                        </div>
                        
                        <div class="form-group">
                            <div class="h-4 bg-gray-200 w-24 rounded mb-2 animate-pulse"></div>
                            <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="form-group">
                                <div class="h-4 bg-gray-200 w-24 rounded mb-2 animate-pulse"></div>
                                <div class="h-24 bg-gray-200 rounded animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Buttons Skeleton -->
                <div class="flex justify-between mt-6">
                    <div></div>
                    <div class="flex space-x-3">
                        <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-10 w-24 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
            
            <!-- Step Indicator -->
            <div class="mb-6 edit-sub-competition-content hidden">
                <div class="flex items-center">
                    <div class="step-item active flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                        <p class="text-xs font-medium mt-1">Informasi Dasar</p>
                    </div>
                    <div class="h-0.5 flex-1 bg-gray-300 step-line"></div>
                    <div class="step-item flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">2</div>
                        <p class="text-xs font-medium mt-1">Detail & Jadwal</p>
                    </div>
                </div>
            </div>
                        
            <form id="edit-sub-competition-form" class="space-y-6 edit-sub-competition-content hidden">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                
                <!-- Step 1: Basic Information -->
                <div id="edit-step-1-content" class="transition-opacity duration-300">
                                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                name="name"
                                id="edit_name"
                                label="Nama Sub-Kompetisi"
                                required
                                placeholder="Masukkan nama sub-kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-name-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-select
                                name="category_id"
                                id="edit_category_id"
                                label="Kategori"
                                required
                                placeholder="Pilih Kategori"
                            >
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-ui.form-select>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-category-error"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="form-group">
                                <x-ui.form-textarea
                                    name="description"
                                    id="edit_description"
                                    label="Deskripsi"
                                    placeholder="Masukkan deskripsi sub-kompetisi"
                                    rows="3"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-description-error"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Details and Schedule -->
                <div id="edit-step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="start_date"
                                id="edit_start_date"
                                label="Tanggal Mulai"
                                required
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-start-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="end_date"
                                id="edit_end_date"
                                label="Tanggal Selesai"
                                required
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-end-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="registration_start"
                                id="edit_registration_start"
                                label="Tanggal Mulai Pendaftaran"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-start-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="registration_end"
                                id="edit_registration_end"
                                label="Tanggal Selesai Pendaftaran"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-end-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="competition_date"
                                id="edit_competition_date"
                                label="Tanggal Kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-competition-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="text"
                                name="registration_link"
                                id="edit_registration_link"
                                label="Link Pendaftaran"
                                placeholder="Masukkan link pendaftaran (opsional)"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-link-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-select
                                name="status"
                                id="edit_status"
                                label="Status"
                                :options="[
                                    'upcoming' => 'Akan Datang',
                                    'ongoing' => 'Sedang Berlangsung',
                                    'completed' => 'Selesai'
                                ]"
                                placeholder="Pilih Status"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-status-error"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="form-group">
                                <x-ui.form-textarea
                                    name="requirements"
                                    id="edit_requirements"
                                    label="Persyaratan"
                                    placeholder="Masukkan persyaratan. Pisahkan setiap persyaratan dengan baris baru."
                                    rows="3"
                                    helperText="Pisahkan setiap persyaratan dengan baris baru"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-requirements-error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="flex justify-between mt-6">
                <button type="button" id="edit-prev-step" class="hidden inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali
                </button>
                <div>
                    <button type="button" id="cancel-edit-sub-competition" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Batal
                    </button>
                    <button type="button" id="edit-next-step" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Lanjut
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button type="submit" id="submit-edit-sub-competition" class="hidden ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 