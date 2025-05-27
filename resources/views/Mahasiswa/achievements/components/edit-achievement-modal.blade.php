<!-- Edit Achievement Modal -->
<div id="edit-achievement-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto" id="edit-modal-container">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Prestasi</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="edit-achievement-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="edit-achievement-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-achievement-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="edit-achievement-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-achievement-id" name="achievement_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <!-- Achievement Title -->
                    <div class="md:col-span-2">
                        <x-ui.form-input 
                            name="title" 
                            id="edit-title"
                            label="Judul Prestasi" 
                            placeholder="Masukkan judul prestasi" 
                            required="true"
                            helperText="Contoh: Juara 1 Hackathon, Best Paper Award"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-title-error"></p>
                    </div>

                    <!-- Competition Name -->
                    <div class="md:col-span-2">
                        <x-ui.form-input 
                            name="competition_name" 
                            id="edit-competition-name"
                            label="Nama Kompetisi/Event" 
                            placeholder="Nama kompetisi atau event" 
                            required="true"
                            helperText="Contoh: Gemastik 2025, IEEE Conference 2025"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-competition-name-error"></p>
                    </div>

                    <!-- Achievement Type and Level -->
                    <div class="form-group">
                        <x-ui.form-select 
                            name="type" 
                            id="edit-type"
                            label="Jenis Prestasi" 
                            required="true"
                            :options="[
                                'academic' => 'Akademik',
                                'technology' => 'Teknologi',
                                'arts' => 'Seni',
                                'sports' => 'Olahraga',
                                'entrepreneurship' => 'Kewirausahaan'
                            ]"
                            placeholder="Pilih jenis prestasi"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-type-error"></p>
                    </div>

                    <div class="form-group">
                        <x-ui.form-select 
                            name="level" 
                            id="edit-level"
                            label="Tingkat Prestasi" 
                            required="true"
                            :options="[
                                'international' => 'Internasional',
                                'national' => 'Nasional',
                                'regional' => 'Regional'
                            ]"
                            placeholder="Pilih tingkat prestasi"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-level-error"></p>
                    </div>

                    <!-- Date -->
                    <div class="md:col-span-2">
                        <x-ui.form-input 
                            type="date" 
                            name="date" 
                            id="edit-date"
                            label="Tanggal Prestasi" 
                            required="true"
                            value=""
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-date-error"></p>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <div class="form-group">
                            <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Prestasi <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="edit-description" 
                                name="description" 
                                rows="5" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Jelaskan detail prestasi yang diraih" 
                                required
                            ></textarea>
                            <p class="mt-1.5 text-sm text-gray-500">Jelaskan bagaimana Anda mendapatkan prestasi ini, konteks, dan pencapaian yang diraih</p>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-description-error"></p>
                        </div>
                    </div>

                    <!-- Select Related Competition (Optional) -->
                    <div class="md:col-span-2">
                        <x-ui.form-select 
                            name="competition_id" 
                            id="edit-competition-id"
                            label="Kompetisi Terkait (Opsional)" 
                            placeholder="-- Pilih jika prestasi terkait dengan kompetisi terdaftar --"
                            helperText="Kosongkan jika prestasi ini tidak terkait kompetisi yang terdaftar di sistem"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-competition-id-error"></p>
                    </div>

                    <!-- Evidence/Attachments -->
                    <div class="md:col-span-2">
                        <x-ui.form-attachment 
                            name="attachments[]" 
                            id="edit-attachments"
                            label="Bukti Prestasi Baru (Opsional)" 
                            accept=".pdf,.jpg,.jpeg,.png" 
                            multiple
                            helperText="Unggah sertifikat atau bukti prestasi tambahan (Format: PDF, JPG, JPEG, PNG. Maks 2MB)"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-attachments-error"></p>
                    </div>

                    <!-- Existing Attachments -->
                    <div class="md:col-span-2" id="existing-attachments-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Prestasi yang Diunggah</label>
                        <div id="existing-attachments" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <!-- Existing attachments will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-5 border-t border-gray-200 mt-6">
                    <div class="flex items-center space-x-3">
                        <x-ui.button 
                            variant="secondary" 
                            id="cancel-edit-achievement"
                            type="button"
                        >
                            Batal
                        </x-ui.button>
                        
                        <x-ui.button 
                            type="submit" 
                            variant="primary"
                            id="save-achievement-changes"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modalAppear {
        from { opacity: 0; transform: translateY(-1rem); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes modalDisappear {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-1rem); }
    }
    
    .animate-modal-appear {
        animation: modalAppear 0.3s ease-out forwards;
    }
    
    .animate-modal-disappear {
        animation: modalDisappear 0.3s ease-in forwards;
    }
</style>
