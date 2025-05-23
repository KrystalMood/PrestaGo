<!-- Show Competition Modal -->
<div id="show-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 my-6 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Kompetisi</h3>
                <button type="button" id="close-show-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center justify-center mb-6">
                <div id="level-icon-container" class="h-24 w-24 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center shadow-md">
                    <svg id="level-icon" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
            </div>
            
            <!-- Competition Name -->
            <div class="text-center mb-6">
                <h2 id="competition-name" class="text-2xl font-bold text-gray-900"></h2>
                <p id="competition-level" class="text-gray-500"></p>
            </div>
            
            <!-- Competition Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Kompetisi</label>
                    <div id="competition-id" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex items-center">
                        <span id="competition-status" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penyelenggara</label>
                    <div id="competition-organizer" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <div id="competition-period" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai - Selesai</label>
                    <div id="competition-dates" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendaftaran</label>
                    <div id="competition-registration" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
                
                <div class="form-group md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                    <div id="competition-date" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <div id="competition-description" class="bg-gray-50 px-3 py-3 rounded-md border border-gray-300 text-gray-700 min-h-[100px]"></div>
            </div>

            <!-- Requirements -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Persyaratan</label>
                <div id="competition-requirements" class="bg-gray-50 px-3 py-3 rounded-md border border-gray-300 text-gray-700 min-h-[100px]"></div>
            </div>
            
            <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                <div class="flex items-center text-gray-500 text-sm">
                    <span>Terakhir diperbarui: <span id="show-competition-updated-at">-</span></span>
                </div>
                <div class="flex space-x-3">
                    <x-ui.button 
                        variant="secondary" 
                        id="close-show-competition"
                        type="button"
                    >
                        Tutup
                    </x-ui.button>
                    
                    <x-ui.button 
                        variant="primary"
                        id="edit-from-show"
                        type="button"
                        data-competition-id=""
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