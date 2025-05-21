<!-- Show User Modal -->
<div id="show-user-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Pengguna</h3>
                <button type="button" id="close-show-modal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-center mb-6">
                <div class="h-24 w-24 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                    <img id="show-photo" class="h-full w-full object-cover" src="{{ asset('images/avatar.png') }}" alt="User Photo">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Pengguna</label>
                    <div id="show-id" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
                    <div class="flex items-center">
                        <span id="show-role" class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div id="show-name" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div id="show-email" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>

                <div class="form-group md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terdaftar Pada</label>
                    <div id="show-created-at" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                <div class="flex items-center text-gray-500 text-sm">
                    <span>Terakhir diperbarui: <span id="show-user-updated-at">-</span></span>
                </div>
                <div class="flex space-x-3">
                    <x-ui.button 
                        variant="secondary" 
                        id="close-show-user"
                        type="button"
                    >
                        Tutup
                    </x-ui.button>
                    
                    <x-ui.button 
                        variant="primary"
                        id="edit-from-show"
                        type="button"
                        data-user-id=""
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div> 