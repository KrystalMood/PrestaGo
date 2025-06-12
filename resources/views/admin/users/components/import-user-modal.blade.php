<!-- Import Users Modal -->
<div id="import-user-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Import Data Pengguna</h3>
                <button type="button" id="close-import-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Error Message Container -->
            <div id="import-user-error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="import-user-error-count">0</span> kesalahan:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="import-user-error-list" class="list-disc pl-5 space-y-1">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message Container -->
            <div id="import-user-success" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800" id="import-user-success-message"></p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-600 mb-4">Unggah berkas CSV sesuai dengan format template. Pastikan kolom tidak diubah agar proses import berhasil.</p>

            <div class="mb-6">
                <a href="{{ route('admin.users.import.template') }}" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Download Template
                </a>
            </div>

            <form id="import-user-form" action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="file-upload-container">
                    <label for="import-file" class="block text-sm font-medium text-gray-700 mb-2">Berkas CSV <span class="text-red-500">*</span></label>
                    
                    <!-- Drop Zone -->
                    <div id="import-dropzone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 transition-all hover:border-blue-500 bg-gray-50 hover:bg-blue-50 cursor-pointer">
                        <input type="file" name="file" id="import-file" accept=".csv" required class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" />
                        
                        <!-- Default State -->
                        <div id="import-default-state" class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-indigo-600">Klik untuk memilih file</span> atau tarik dan letakkan
                            </p>
                            <p class="mt-1 text-xs text-gray-500">CSV hingga 2MB</p>
                        </div>
                        
                        <!-- File Selected State -->
                        <div id="import-selected-state" class="hidden text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-900" id="import-file-name">Nama file.csv</p>
                            <p class="mt-1 text-xs text-gray-500" id="import-file-size">Ukuran file</p>
                            <button type="button" id="import-change-file" class="mt-2 text-xs text-indigo-600 hover:text-indigo-800">
                                Ganti file
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-red-600 error-message hidden mt-1" id="file-error"></p>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancel-import-user" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit" id="submit-import-user" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 