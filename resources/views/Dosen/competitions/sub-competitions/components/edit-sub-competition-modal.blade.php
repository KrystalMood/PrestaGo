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
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Sub-Kompetisi <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="edit_name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Masukkan nama sub-kompetisi" required>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-name-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" id="edit_category_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-category-error"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="form-group">
                                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" id="edit_description" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Masukkan deskripsi sub-kompetisi"></textarea>
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-description-error"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Details and Schedule -->
                <div id="edit-step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <label for="edit_start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="edit_start_date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-start-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="edit_end_date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-end-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_registration_start" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Pendaftaran</label>
                            <input type="date" name="registration_start" id="edit_registration_start" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-start-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_registration_end" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Pendaftaran</label>
                            <input type="date" name="registration_end" id="edit_registration_end" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-registration-end-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_competition_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kompetisi</label>
                            <input type="date" name="competition_date" id="edit_competition_date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-competition-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="edit_status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="upcoming">Akan Datang</option>
                                <option value="ongoing">Berlangsung</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-sub-status-error"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Form Buttons -->
                <div class="flex justify-between mt-6 border-t border-gray-200 pt-6">
                    <button type="button" id="cancel-edit-sub-competition" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <div class="flex space-x-3">
                        <button type="button" id="edit-prev-step" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hidden">
                            Sebelumnya
                        </button>
                        <button type="button" id="edit-next-step" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Selanjutnya
                        </button>
                        <button type="button" id="submit-edit-sub-competition" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hidden">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 