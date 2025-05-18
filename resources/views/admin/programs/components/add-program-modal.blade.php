<!-- Add Program Modal -->
<div id="add-program-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Tambah Program Studi Baru</h3>
                <button type="button" id="close-add-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="add-program-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="add-program-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="add-program-error-list" class="list-disc pl-5 space-y-1">
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
                        <p class="text-xs font-medium mt-1">Detail Program</p>
                    </div>
                </div>
            </div>

            <form id="add-program-form" class="space-y-6">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div id="step-1-content" class="transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                name="code"
                                id="add-code"
                                label="Kode Program Studi"
                                required
                                placeholder="Masukkan kode program studi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="code-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                name="name"
                                id="add-name"
                                label="Nama Program Studi"
                                required
                                placeholder="Masukkan nama program studi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="name-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                name="faculty"
                                id="add-faculty"
                                label="Fakultas"
                                required
                                placeholder="Masukkan nama fakultas"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="faculty-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select
                                name="degree_level"
                                id="add-degree-level"
                                label="Jenjang"
                                :options="[
                                    'D-III' => 'D-III (Diploma)',
                                    'D-IV' => 'D-IV (Sarjana Terapan)',
                                    'S1' => 'S1 (Sarjana)',
                                    'S2' => 'S2 (Magister)',
                                    'S3' => 'S3 (Doktor)',
                                    'S2 Terapan' => 'S2 Terapan (Magister Terapan)'
                                ]"
                                required
                                placeholder="Pilih jenjang"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="degree-level-error"></p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Additional Information -->
                <div id="step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                name="accreditation"
                                id="add-accreditation"
                                label="Akreditasi"
                                placeholder="Masukkan akreditasi program studi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="accreditation-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                name="year_established"
                                id="add-year-established"
                                label="Tahun Berdiri"
                                type="number"
                                placeholder="Masukkan tahun berdiri"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="year-established-error"></p>
                        </div>

                        <div class="md:col-span-2">
                            <div class="form-group">
                                <x-ui.form-textarea
                                    name="description"
                                    id="add-description"
                                    label="Deskripsi"
                                    placeholder="Masukkan deskripsi program studi"
                                    rows="4"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="description-error"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <x-ui.form-toggle
                                name="is_active"
                                id="add-is-active"
                                label="Status Aktif"
                                :checked="true"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="is-active-error"></p>
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
                            id="cancel-add-program"
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
                            id="submit-add-program"
                            class="hidden"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Program Studi
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 