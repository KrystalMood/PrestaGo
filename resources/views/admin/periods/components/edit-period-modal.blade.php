<!-- Edit Period Modal -->
<div id="edit-period-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Periode</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="edit-period-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="edit-period-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-period-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="edit-period-form" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-period-id" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="form-group md:col-span-2">
                        <x-ui.form-input
                            name="name"
                            id="edit-name"
                            label="Nama Periode"
                            required
                            placeholder="Contoh: Semester Ganjil 2025/2026"
                            helperText="Nama periode harus unik dan mudah diidentifikasi"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-name-error"></p>
                    </div>

                    <div class="form-group">
                        <x-ui.form-input
                            type="date"
                            name="start_date"
                            id="edit-start-date"
                            label="Tanggal Mulai"
                            required
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-start-date-error"></p>
                    </div>

                    <div class="form-group">
                        <x-ui.form-input
                            type="date"
                            name="end_date"
                            id="edit-end-date"
                            label="Tanggal Selesai"
                            required
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-end-date-error"></p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                    <x-ui.button 
                        variant="secondary" 
                        id="cancel-edit-period"
                        type="button"
                    >
                        Batal
                    </x-ui.button>
                    
                    <x-ui.button 
                        type="submit" 
                        variant="primary"
                        id="submit-edit-period"
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