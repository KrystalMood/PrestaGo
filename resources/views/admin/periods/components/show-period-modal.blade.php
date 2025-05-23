<!-- Show Period Modal -->
<div id="show-period-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Periode</h3>
                <button type="button" id="close-show-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="flex items-center">
                    <span id="period-detail-status" class="px-2 py-1 text-xs rounded-full"></span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama Periode</p>
                    <p id="period-detail-name" class="text-base font-medium text-gray-900"></p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Mulai</p>
                        <p id="period-detail-start-date" class="text-base text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Selesai</p>
                        <p id="period-detail-end-date" class="text-base text-gray-900"></p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-2">
                <h5 class="text-sm font-medium text-gray-500">KETERANGAN</h5>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700" id="show-period-description">-</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                <div class="flex items-center text-gray-500 text-sm">
                    <span>Terakhir diperbarui: <span id="show-period-updated-at">-</span></span>
                </div>
                <div class="flex space-x-3">
                    <x-ui.button 
                        variant="secondary" 
                        id="cancel-show-period" 
                        type="button"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tutup
                    </x-ui.button>
                    
                    <x-ui.button 
                        variant="primary"
                        id="edit-from-show"
                        type="button"
                        data-period-id=""
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