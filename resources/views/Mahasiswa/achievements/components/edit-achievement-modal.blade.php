<!-- Edit Achievement Modal -->
<div id="edit-achievement-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Prestasi</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-achievement-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-achievement-id" name="achievement_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="form-group md:col-span-2">
                        <label for="edit-title" class="block text-sm font-medium text-gray-700 mb-1">Judul Prestasi <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-title" name="title" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                        <div id="edit-title-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit-competition-name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kompetisi <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-competition-name" name="competition_name" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                        <div id="edit-competition-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit-type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Prestasi <span class="text-red-500">*</span></label>
                        <select id="edit-type" name="type" class="form-select w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="" disabled selected>Pilih Jenis Prestasi</option>
                            <option value="academic">Akademik</option>
                            <option value="technology">Teknologi</option>
                            <option value="arts">Seni</option>
                            <option value="sports">Olahraga</option>
                            <option value="entrepreneurship">Kewirausahaan</option>
                        </select>
                        <div id="edit-type-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit-level" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Prestasi <span class="text-red-500">*</span></label>
                        <select id="edit-level" name="level" class="form-select w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="" disabled selected>Pilih Tingkat Prestasi</option>
                            <option value="international">Internasional</option>
                            <option value="national">Nasional</option>
                            <option value="regional">Regional</option>
                        </select>
                        <div id="edit-level-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" id="edit-date" name="date" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                        <div id="edit-date-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit-competition-id" class="block text-sm font-medium text-gray-700 mb-1">Kompetisi Terkait (Opsional)</label>
                        <select id="edit-competition-id" name="competition_id" class="form-select w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Tidak Ada</option>
                            <!-- Competition options will be loaded dynamically -->
                        </select>
                        <div id="edit-competition-id-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea id="edit-description" name="description" rows="4" class="form-textarea w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        <div id="edit-description-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="edit-attachments" class="block text-sm font-medium text-gray-700 mb-1">Bukti Prestasi (Opsional)</label>
                        <input type="file" id="edit-attachments" name="attachments[]" class="form-input w-full py-2" multiple>
                        <div class="text-xs text-gray-500 mt-1">Format yang didukung: JPG, JPEG, PNG, PDF. Ukuran maksimal: 2MB per file.</div>
                        <div id="edit-attachments-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="form-group md:col-span-2" id="existing-attachments-container">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Prestasi yang Diunggah</label>
                        <div id="existing-attachments" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <!-- Existing attachments will be loaded here -->
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-5 border-t border-gray-200 mt-6">
                    <div class="flex space-x-3">
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
