<!-- Edit Achievement Modal -->
<div id="edit-achievement-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Edit Prestasi</h3>
                <button type="button" id="close-edit-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Skeleton Loading -->
            <div id="edit-achievement-skeleton" class="hidden space-y-6">
                <!-- Achievement Details Skeleton -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Judul Prestasi Skeleton -->
                    <div class="md:col-span-2">
                        <div class="block text-sm font-medium text-gray-700 mb-1">Judul Prestasi</div>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <!-- Nama Kompetisi Skeleton -->
                    <div class="md:col-span-2">
                        <div class="block text-sm font-medium text-gray-700 mb-1">Nama Kompetisi/Event</div>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <!-- Tipe dan Level Prestasi Skeleton -->
                    <div>
                        <div class="block text-sm font-medium text-gray-700 mb-1">Jenis Prestasi</div>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <div>
                        <div class="block text-sm font-medium text-gray-700 mb-1">Tingkat Prestasi</div>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <!-- Tanggal Skeleton -->
                    <div class="md:col-span-2">
                        <div class="block text-sm font-medium text-gray-700 mb-1">Tanggal Prestasi</div>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <!-- Deskripsi Skeleton -->
                    <div class="md:col-span-2">
                        <div class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Prestasi</div>
                        <div class="h-32 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <!-- Attachments Skeleton -->
                    <div class="md:col-span-2">
                        <div class="block text-sm font-medium text-gray-700 mb-1">Lampiran Saat Ini</div>
                        <div class="h-20 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Error Alert -->
            <div id="edit-achievement-error" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terdapat <span id="edit-achievement-error-count">0</span> kesalahan dalam form
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-achievement-error-list" class="list-disc pl-5 space-y-1"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Indicator -->
            <div id="edit-step-indicator" class="mb-6 hidden">
                <div class="flex items-center">
                    <div class="edit-step-item active flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                        <p class="text-xs font-medium mt-1">Informasi Dasar</p>
                    </div>
                    <div class="h-0.5 flex-1 bg-gray-300 step-line"></div>
                    <div class="edit-step-item flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">2</div>
                        <p class="text-xs font-medium mt-1">Detail & Lampiran</p>
                    </div>
                </div>
            </div>

            <!-- Actual Form Content -->
            <div id="edit-achievement-content" class="hidden">
                <form id="edit-achievement-form" class="space-y-6" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-achievement-id" name="achievement_id" value="">
                    
                    <!-- Step 1: Basic Information -->
                    <div id="edit-step-1-content" class="transition-opacity duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul Prestasi -->
                            <div class="md:col-span-2 form-group">
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

                            <!-- Nama Kompetisi -->
                            <div class="md:col-span-2 form-group">
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

                            <!-- Tipe dan Level Prestasi -->
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

                            <!-- Tanggal -->
                            <div class="md:col-span-2 form-group">
                                <x-ui.form-input 
                                    type="date" 
                                    name="date" 
                                    id="edit-date"
                                    label="Tanggal Prestasi" 
                                    required="true"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-date-error"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Details and Attachments -->
                    <div id="edit-step-2-content" class="hidden transition-opacity duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Deskripsi -->
                            <div class="md:col-span-2 form-group">
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

                            <!-- Pilih Kompetisi Terkait (Opsional) -->
                            <div class="md:col-span-2 form-group">
                                <x-ui.form-select 
                                    name="competition_id" 
                                    id="edit-competition-id"
                                    label="Kompetisi Terkait (Opsional)" 
                                    :options="[]"
                                    placeholder="-- Pilih jika prestasi terkait dengan kompetisi terdaftar --"
                                    helperText="Kosongkan jika prestasi ini tidak terkait kompetisi yang terdaftar di sistem"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-competition-id-error"></p>
                            </div>

                            <!-- Bukti/Lampiran -->
                            <div class="md:col-span-2 form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Lampiran Saat Ini
                                </label>
                                <div id="edit-current-attachments" class="p-4 border border-gray-200 rounded-lg bg-gray-50 space-y-2">
                                    <p class="text-sm text-gray-500">Memuat lampiran...</p>
                                </div>
                            </div>
                            
                            <!-- Upload New Attachments -->
                            <div class="md:col-span-2 form-group">
                                <label for="edit-attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tambah Lampiran Baru (Opsional)
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="edit-attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Unggah berkas</span>
                                                <input id="edit-attachments" name="attachments[]" type="file" class="sr-only" multiple>
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, PDF, DOC, DOCX hingga 10MB
                                        </p>
                                    </div>
                                </div>
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="edit-attachments-error"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between space-x-3 pt-5 border-t border-gray-200 mt-6">
                        <div>
                            <button type="button" id="edit-prev-step" class="hidden inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" id="close-edit-achievement-btn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </button>
                            <button type="button" id="edit-next-step" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Langkah Berikutnya
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <button type="submit" id="edit-submit-achievement" class="hidden inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Perbarui Prestasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
