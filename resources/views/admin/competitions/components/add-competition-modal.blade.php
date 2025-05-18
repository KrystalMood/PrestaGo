<!-- Add Competition Modal -->
<div id="add-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Tambah Kompetisi Baru</h3>
                <button type="button" id="close-add-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="add-competition-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="add-competition-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="add-competition-error-list" class="list-disc pl-5 space-y-1">
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
                        <p class="text-xs font-medium mt-1">Detail Tambahan</p>
                    </div>
                </div>
            </div>

            <form id="add-competition-form" class="space-y-6" enctype="multipart/form-data">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div id="step-1-content" class="transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group md:col-span-2">
                            <x-ui.form-input
                                name="name"
                                id="add-name"
                                label="Nama Kompetisi"
                                required
                                placeholder="Masukkan nama kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="name-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                name="organizer"
                                id="add-organizer"
                                label="Penyelenggara"
                                required
                                placeholder="Masukkan nama penyelenggara"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="organizer-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select
                                name="category_id"
                                id="add-category"
                                label="Kategori"
                                :options="collect($categories ?? [])->pluck('name', 'id')->toArray()"
                                :selected="''"
                                required
                                placeholder="Pilih Kategori"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="category-id-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select
                                name="period_id"
                                id="add-period"
                                label="Periode"
                                :options="collect($periods ?? [])->pluck('name', 'id')->toArray()"
                                :selected="''"
                                required
                                placeholder="Pilih Periode"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="period-id-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select
                                name="level"
                                id="add-level"
                                label="Level"
                                :options="[
                                    'international' => 'Internasional',
                                    'national' => 'Nasional',
                                    'regional' => 'Regional',
                                    'provincial' => 'Provinsi',
                                    'university' => 'Universitas'
                                ]"
                                :selected="''"
                                required
                                placeholder="Pilih Level Kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="level-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select
                                name="type"
                                id="add-type"
                                label="Tipe Kompetisi"
                                :options="[
                                    'individual' => 'Individual',
                                    'team' => 'Tim',
                                    'both' => 'Keduanya'
                                ]"
                                :selected="'individual'"
                                required
                                placeholder="Pilih Tipe Kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="type-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select
                                name="status"
                                id="add-status"
                                label="Status"
                                :options="[
                                    'upcoming' => 'Akan Datang',
                                    'active' => 'Aktif',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ]"
                                :selected="''"
                                required
                                placeholder="Pilih Status"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="status-error"></p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Additional Details -->
                <div id="step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="start_date"
                                id="add-start-date"
                                label="Tanggal Mulai"
                                required
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="start-date-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="end_date"
                                id="add-end-date"
                                label="Tanggal Selesai"
                                required
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="end-date-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="registration_start"
                                id="add-registration-start"
                                label="Tanggal Mulai Pendaftaran"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="registration-start-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="registration_end"
                                id="add-registration-end"
                                label="Tanggal Selesai Pendaftaran"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="registration-end-error"></p>
                        </div>

                        <div class="form-group md:col-span-2">
                            <x-ui.form-input
                                type="date"
                                name="competition_date"
                                id="add-competition-date"
                                label="Tanggal Kompetisi"
                                required
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="competition-date-error"></p>
                        </div>

                        <div class="form-group md:col-span-2">
                            <label for="add-description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="add-description" name="description" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan deskripsi kompetisi"></textarea>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="description-error"></p>
                        </div>

                        <div class="form-group md:col-span-2">
                            <label for="add-requirements" class="block text-sm font-medium text-gray-700">Persyaratan</label>
                            <textarea id="add-requirements" name="requirements" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan persyaratan kompetisi"></textarea>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="requirements-error"></p>
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
                            id="cancel-add-competition"
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
                            id="submit-add-competition"
                            class="hidden"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Kompetisi
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 