<!-- Create Achievement Modal -->
<div id="create-achievement-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Tambah Prestasi Baru</h3>
                <button type="button" id="close-create-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="create-achievement-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="create-achievement-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="create-achievement-error-list" class="list-disc pl-5 space-y-1">
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
                        <p class="text-xs font-medium mt-1">Detail & Lampiran</p>
                    </div>
                </div>
            </div>

            <form id="create-achievement-form" class="space-y-6" method="POST" action="/student/achievements" enctype="multipart/form-data">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div id="step-1-content" class="transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <!-- Judul Prestasi -->
                        <div class="md:col-span-2 form-group">
                            <x-ui.form-input 
                                name="title" 
                                id="create-title"
                                label="Judul Prestasi" 
                                placeholder="Masukkan judul prestasi" 
                                required="true"
                                helperText="Contoh: Juara 1 Hackathon, Best Paper Award"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="title-error"></p>
                        </div>

                        <!-- Nama Kompetisi -->
                        <div class="md:col-span-2 form-group">
                            <x-ui.form-input 
                                name="competition_name" 
                                id="create-competition-name"
                                label="Nama Kompetisi/Event" 
                                placeholder="Nama kompetisi atau event" 
                                required="true"
                                helperText="Contoh: Gemastik 2025, IEEE Conference 2025"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="competition-name-error"></p>
                        </div>

                        <!-- Tipe dan Level Prestasi -->
                        <div class="form-group">
                            <x-ui.form-select 
                                name="type" 
                                id="create-type"
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
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="type-error"></p>
                        </div>

                        <div class="form-group">
                            <x-ui.form-select 
                                name="level" 
                                id="create-level"
                                label="Tingkat Prestasi" 
                                required="true"
                                :options="[
                                    'international' => 'Internasional',
                                    'national' => 'Nasional',
                                    'regional' => 'Regional'
                                ]"
                                placeholder="Pilih tingkat prestasi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="level-error"></p>
                        </div>

                        <!-- Tanggal -->
                        <div class="md:col-span-2 form-group">
                            <x-ui.form-input 
                                type="date" 
                                name="date" 
                                id="create-date"
                                label="Tanggal Prestasi" 
                                required="true"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="date-error"></p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Details and Attachments -->
                <div id="step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <!-- Deskripsi -->
                        <div class="md:col-span-2 form-group">
                            <label for="create-description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Prestasi <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="create-description" 
                                name="description" 
                                rows="5" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Jelaskan detail prestasi yang diraih" 
                                required
                            ></textarea>
                            <p class="mt-1.5 text-sm text-gray-500">Jelaskan bagaimana Anda mendapatkan prestasi ini, konteks, dan pencapaian yang diraih</p>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="description-error"></p>
                        </div>

                        <!-- Pilih Kompetisi Terkait (Opsional) -->
                        <div class="md:col-span-2 form-group">
                            <x-ui.form-select 
                                name="competition_id" 
                                id="create-competition-id"
                                label="Kompetisi Terkait (Opsional)" 
                                :options="[]"
                                placeholder="-- Pilih jika prestasi terkait dengan kompetisi terdaftar --"
                                helperText="Kosongkan jika prestasi ini tidak terkait kompetisi yang terdaftar di sistem"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="competition-id-error"></p>
                        </div>

                        <!-- Bukti/Lampiran -->
                        <div class="md:col-span-2">
                            <div id="attachment-upload-container" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bukti Prestasi <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        <div class="h-24 w-24 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <label class="block flex-1">
                                        <span class="sr-only">Pilih Bukti Prestasi</span>
                                        <input type="file"
                                               name="attachments[]" 
                                               id="create-attachments"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               multiple
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Format yang didukung: PDF, JPG, JPEG, PNG. Maks: 2MB. Anda dapat memilih beberapa file.</p>
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="attachments-error"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between space-x-3 pt-5 border-t border-gray-200 mt-6">
                    <div>
                        <button type="button" id="prev-step" class="hidden inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" id="cancel-create-achievement" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </button>
                        <button type="button" id="next-step" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Lanjut
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button type="submit" id="submit-achievement" class="hidden px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Prestasi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
