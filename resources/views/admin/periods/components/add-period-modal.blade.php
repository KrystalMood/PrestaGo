<!-- Add Period Modal -->
<div id="add-period-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Tambah Periode Baru</h3>
                <button type="button" id="close-add-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="add-period-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="add-period-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="add-period-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="add-period-form" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="form-group md:col-span-2">
                        <x-ui.form-input
                            name="name"
                            id="add-name"
                            label="Nama Periode"
                            required
                            placeholder="Contoh: Semester Ganjil 2025/2026"
                            helperText="Nama periode harus unik dan mudah diidentifikasi"
                        />
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="name-error"></p>
                    </div>

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

                    <div class="form-group md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                name="is_active" 
                                id="add-is-active" 
                                value="1" 
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <label for="add-is-active" class="ml-2 block text-sm text-gray-700">Aktifkan periode ini</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Jika diaktifkan, periode ini akan menjadi periode aktif dan periode lain akan dinonaktifkan</p>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="add-description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                        <textarea id="add-description" 
                            name="description" 
                            rows="3" 
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                            placeholder="Masukkan deskripsi periode"></textarea>
                        <p class="text-sm text-red-600 error-message hidden mt-1" id="description-error"></p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                    <x-ui.button 
                        variant="secondary" 
                        id="cancel-add-period"
                        type="button"
                    >
                        Batal
                    </x-ui.button>
                    
                    <x-ui.button 
                        type="submit" 
                        variant="primary"
                        id="submit-add-period"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Periode
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div> 