<!-- Add Sub-Competition Modal -->
<div id="add-sub-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Tambah Sub-Kompetisi</h3>
                <button type="button" id="close-add-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Error Alert -->
            <div id="add-sub-competition-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="add-sub-competition-error-count">0</span> kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="add-sub-competition-error-list" class="list-disc pl-5 space-y-1">
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
                        <p class="text-xs font-medium mt-1">Detail & Jadwal</p>
                    </div>
                </div>
            </div>

            <form id="add-sub-competition-form" class="space-y-6">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div id="step-1-content" class="transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                name="name"
                                id="add-sub-name"
                                label="Nama Sub-Kompetisi"
                                required
                                placeholder="Masukkan nama sub-kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-name-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-select
                                name="category_id"
                                id="add-sub-category"
                                label="Kategori"
                                required
                                placeholder="Pilih Kategori"
                            >
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-ui.form-select>
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-category-error"></p>
                        </div>

                        <div class="md:col-span-2">
                            <div class="form-group">
                                <x-ui.form-textarea
                                    name="description"
                                    id="add-sub-description"
                                    label="Deskripsi"
                                    placeholder="Masukkan deskripsi sub-kompetisi"
                                    rows="3"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-description-error"></p>
                            </div>
                        </div>
                        

                    </div>
                </div>
                
                <!-- Step 2: Details and Schedule -->
                <div id="step-2-content" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="start_date"
                                id="add-sub-start-date"
                                label="Tanggal Mulai"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-start-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="end_date"
                                id="add-sub-end-date"
                                label="Tanggal Selesai"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-end-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="registration_start"
                                id="add-sub-registration-start"
                                label="Pendaftaran Dibuka"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-registration-start-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="registration_end"
                                id="add-sub-registration-end"
                                label="Pendaftaran Ditutup"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-registration-end-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="date"
                                name="competition_date"
                                id="add-sub-competition-date"
                                label="Tanggal Kompetisi"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-competition-date-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-input
                                type="text"
                                name="registration_link"
                                id="add-sub-registration-link"
                                label="Link Pendaftaran"
                                placeholder="Masukkan link pendaftaran (opsional)"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-registration-link-error"></p>
                        </div>
                        
                        <div class="form-group">
                            <x-ui.form-select
                                name="status"
                                id="add-sub-status"
                                label="Status"
                                :options="[
                                    'upcoming' => 'Akan Datang',
                                    'ongoing' => 'Sedang Berlangsung',
                                    'completed' => 'Selesai'
                                ]"
                                :selected="'upcoming'"
                                placeholder="Pilih Status"
                            />
                            <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-status-error"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="form-group">
                                <x-ui.form-textarea
                                    name="requirements"
                                    id="add-sub-requirements"
                                    label="Persyaratan"
                                    placeholder="Masukkan persyaratan. Pisahkan setiap persyaratan dengan baris baru."
                                    rows="4"
                                    helperText="Pisahkan setiap persyaratan dengan baris baru"
                                />
                                <p class="text-sm text-red-600 error-message hidden mt-1" id="sub-requirements-error"></p>
                            </div>
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
                        <button type="button" id="cancel-add-sub-competition" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Batal
                        </button>
                        
                        <button type="button" id="next-step" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Langkah Berikutnya
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        
                        <button type="submit" id="submit-add-sub-competition" class="hidden inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Simpan Sub-Kompetisi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('add-sub-competition-modal');
    const form = document.getElementById('add-sub-competition-form');
    const closeButton = document.getElementById('close-add-modal');
    const cancelButton = document.getElementById('cancel-add-sub-competition');
    const nextButton = document.getElementById('next-step');
    const prevButton = document.getElementById('prev-step');
    const submitButton = document.getElementById('submit-add-sub-competition');
    const step1Content = document.getElementById('step-1-content');
    const step2Content = document.getElementById('step-2-content');
    const stepItems = document.querySelectorAll('.step-item');
    const errorAlert = document.getElementById('add-sub-competition-error');
    const errorList = document.getElementById('add-sub-competition-error-list');
    const errorCount = document.getElementById('add-sub-competition-error-count');
    
    let currentStep = 1;
    
    function showStep(step) {
        if (step === 1) {
            step1Content.classList.remove('hidden');
            step2Content.classList.add('hidden');
            nextButton.classList.remove('hidden');
            submitButton.classList.add('hidden');
            prevButton.classList.add('hidden');
            stepItems[0].classList.add('active');
            stepItems[0].querySelector('div').classList.add('bg-blue-600', 'text-white');
            stepItems[0].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
            stepItems[1].classList.remove('active');
            stepItems[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
            stepItems[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
        } else {
            step1Content.classList.add('hidden');
            step2Content.classList.remove('hidden');
            nextButton.classList.add('hidden');
            submitButton.classList.remove('hidden');
            prevButton.classList.remove('hidden');
            stepItems[1].classList.add('active');
            stepItems[1].querySelector('div').classList.add('bg-blue-600', 'text-white');
            stepItems[1].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
        }
        currentStep = step;
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        form.reset();
        showStep(1);
        clearErrors();
    }
    
    function clearErrors() {
        errorAlert.classList.add('hidden');
        errorList.innerHTML = '';
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }
    
    function displayErrors(errors) {
        clearErrors();
        
        if (Object.keys(errors).length > 0) {
            errorCount.textContent = Object.keys(errors).length;
            errorAlert.classList.remove('hidden');
            
            for (const [field, message] of Object.entries(errors)) {
                const item = document.createElement('li');
                item.textContent = message;
                errorList.appendChild(item);
                
                const errorElement = document.getElementById(`sub-${field.replace('_', '-')}-error`);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            }
        }
    }
    
    if (typeof window.subCompetitionSetup === 'undefined') {
        window.subCompetitionSetup = true;
        
        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }
        
        if (cancelButton) {
            cancelButton.addEventListener('click', closeModal);
        }
        
        if (nextButton) {
            nextButton.addEventListener('click', function() {
                showStep(2);
            });
        }
        
        if (prevButton) {
            prevButton.addEventListener('click', function() {
                showStep(1);
            });
        }
        
        // Form submission is handled by the JavaScript file (sub-competitions.js)
        // to prevent duplicate submissions
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
            });
        }
        
        const openButtons = [
            document.getElementById('open-add-sub-competition-modal'),
            document.getElementById('open-add-sub-competition-modal-empty')
        ];
        
        openButtons.forEach(button => {
            if (button && !button.hasEventListener) {
                button.hasEventListener = true;
                button.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                });
            }
        });
    }
});
</script> 