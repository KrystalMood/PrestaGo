<!-- Edit Competition Modal -->
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Edit Kompetisi</h3>
                <form method="dialog">
                    <button class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Error alerts -->
            <div id="edit-competition-error" class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 hidden">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat <span id="edit-competition-error-count">1</span> error saat menyimpan kompetisi</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul id="edit-competition-error-list" class="list-disc pl-5 space-y-1">
                                <!-- Error messages will be inserted here -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="edit-competition-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-competition-id" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Competition Name -->
                    <div class="col-span-2">
                        <label for="edit-name" class="block text-sm font-medium text-gray-700">Nama Kompetisi <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        <p id="edit-name-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Organizer -->
                    <div>
                        <label for="edit-organizer" class="block text-sm font-medium text-gray-700">Penyelenggara <span class="text-red-500">*</span></label>
                        <input type="text" name="organizer" id="edit-organizer" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        <p id="edit-organizer-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="edit-category" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                        <select id="edit-category" name="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="edit-category-id-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="edit-level" class="block text-sm font-medium text-gray-700">Level</label>
                        <input type="text" name="level" id="edit-level" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p id="edit-level-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="edit-status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select id="edit-status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Pilih Status</option>
                            <option value="upcoming">Akan Datang</option>
                            <option value="active">Aktif</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        <p id="edit-status-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Dates -->
                    <div>
                        <label for="edit-start-date" class="block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="edit-start-date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        <p id="edit-start-date-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <div>
                        <label for="edit-end-date" class="block text-sm font-medium text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="edit-end-date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        <p id="edit-end-date-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Registration Dates -->
                    <div>
                        <label for="edit-registration-start" class="block text-sm font-medium text-gray-700">Tanggal Mulai Pendaftaran</label>
                        <input type="date" name="registration_start" id="edit-registration-start" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p id="edit-registration-start-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <div>
                        <label for="edit-registration-end" class="block text-sm font-medium text-gray-700">Tanggal Selesai Pendaftaran</label>
                        <input type="date" name="registration_end" id="edit-registration-end" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p id="edit-registration-end-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="edit-description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="edit-description" name="description" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        <p id="edit-description-error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 text-right">
            <form method="dialog" class="inline-flex">
                <button class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
            </form>
            <button type="button" id="submit-edit-competition" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Perbarui Kompetisi
            </button>
