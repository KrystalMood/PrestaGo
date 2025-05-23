<div id="edit-sub-competition-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Edit Sub-Kompetisi
                        </h3>
                        
                        <!-- Error Alert -->
                        <div id="edit-sub-competition-error" class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 hidden">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-700">
                                        Ada <span id="edit-sub-competition-error-count">0</span> error yang perlu diperbaiki.
                                    </h3>
                                    <ul id="edit-sub-competition-error-list" class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <form id="edit-sub-competition-form" class="space-y-4">
                                @csrf
                                <input type="hidden" name="id" id="edit_id">
                                
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
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <x-ui.form-input
                                            type="date"
                                            name="start_date"
                                            id="edit_start_date"
                                            label="Tanggal Mulai"
                                        />
                                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-start-date-error"></p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <x-ui.form-input
                                            type="date"
                                            name="end_date"
                                            id="edit_end_date"
                                            label="Tanggal Selesai"
                                        />
                                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-end-date-error"></p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <x-ui.form-input
                                            type="date"
                                            name="registration_start"
                                            id="edit_registration_start"
                                            label="Pendaftaran Dibuka"
                                        />
                                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-start-error"></p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <x-ui.form-input
                                            type="date"
                                            name="registration_end"
                                            id="edit_registration_end"
                                            label="Pendaftaran Ditutup"
                                        />
                                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-end-error"></p>
                                    </div>
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
                                        type="url"
                                        name="registration_link"
                                        id="edit_registration_link"
                                        label="Link Pendaftaran"
                                        placeholder="https://example.com/daftar"
                                    />
                                    <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-link-error"></p>
                                </div>
                                
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="submit-edit-sub-competition" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan Perubahan
                </button>
                <button type="button" id="close-edit-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div> 