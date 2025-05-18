<!-- Edit Competition Modal -->
<div id="edit-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full mx-4 my-6 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Kompetisi</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        
            <!-- Error alerts -->
            <div id="edit-competition-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="edit-competition-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-competition-error-list" class="list-disc pl-5 space-y-1">
                                <!-- Error messages will be inserted here -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="edit-competition-form" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-competition-id" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <!-- Competition Name -->
                    <div class="form-group md:col-span-2">
                        <x-ui.form-input
                            name="name"
                            id="edit-name"
                            label="Nama Kompetisi"
                            required
                            placeholder="Masukkan nama kompetisi"
                        />
                        <p id="edit-name-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Organizer -->
                    <div class="form-group">
                        <x-ui.form-input
                            name="organizer"
                            id="edit-organizer"
                            label="Penyelenggara"
                            required
                            placeholder="Masukkan nama penyelenggara"
                        />
                        <p id="edit-organizer-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <x-ui.form-select
                            name="category_id"
                            id="edit-category"
                            label="Kategori"
                            :options="collect($categories ?? [])->pluck('name', 'id')->toArray()"
                            :selected="''"
                            required
                            placeholder="Pilih Kategori"
                        />
                        <p id="edit-category-id-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Period -->
                    <div class="form-group">
                        <x-ui.form-select
                            name="period_id"
                            id="edit-period"
                            label="Periode"
                            :options="collect($periods ?? [])->pluck('name', 'id')->toArray()"
                            :selected="''"
                            required
                            placeholder="Pilih Periode"
                        />
                        <p id="edit-period-id-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Level -->
                    <div class="form-group">
                        <x-ui.form-select
                            name="level"
                            id="edit-level"
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
                        <p id="edit-level-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <x-ui.form-select
                            name="type"
                            id="edit-type"
                            label="Tipe Kompetisi"
                            :options="[
                                'individual' => 'Individual',
                                'team' => 'Tim',
                                'both' => 'Keduanya'
                            ]"
                            :selected="''"
                            required
                            placeholder="Pilih Tipe Kompetisi"
                        />
                        <p id="edit-type-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <x-ui.form-select
                            name="status"
                            id="edit-status"
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
                        <p id="edit-status-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Dates -->
                    <div class="form-group">
                        <x-ui.form-input
                            type="date"
                            name="start_date"
                            id="edit-start-date"
                            label="Tanggal Mulai"
                            required
                        />
                        <p id="edit-start-date-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <div class="form-group">
                        <x-ui.form-input
                            type="date"
                            name="end_date"
                            id="edit-end-date"
                            label="Tanggal Selesai"
                            required
                        />
                        <p id="edit-end-date-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Registration Dates -->
                    <div class="form-group">
                        <x-ui.form-input
                            type="date"
                            name="registration_start"
                            id="edit-registration-start"
                            label="Tanggal Mulai Pendaftaran"
                        />
                        <p id="edit-registration-start-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <div class="form-group">
                        <x-ui.form-input
                            type="date"
                            name="registration_end"
                            id="edit-registration-end"
                            label="Tanggal Selesai Pendaftaran"
                        />
                        <p id="edit-registration-end-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Competition Date -->
                    <div class="form-group md:col-span-2">
                        <x-ui.form-input
                            type="date"
                            name="competition_date"
                            id="edit-competition-date"
                            label="Tanggal Kompetisi"
                            required
                        />
                        <p id="edit-competition-date-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Description -->
                    <div class="form-group md:col-span-2">
                        <label for="edit-description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="edit-description" name="description" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan deskripsi kompetisi"></textarea>
                        <p id="edit-description-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Requirements -->
                    <div class="form-group md:col-span-2">
                        <label for="edit-requirements" class="block text-sm font-medium text-gray-700">Persyaratan</label>
                        <textarea id="edit-requirements" name="requirements" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan persyaratan kompetisi"></textarea>
                        <p id="edit-requirements-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                    <x-ui.button 
                        variant="secondary" 
                        id="cancel-edit-competition"
                        type="button"
                    >
                        Batal
                    </x-ui.button>
                    
                    <x-ui.button 
                        type="button" 
                        variant="primary"
                        id="submit-edit-competition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Perbarui Kompetisi
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div> 