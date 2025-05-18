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
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                    <p id="period-detail-description" class="text-base text-gray-900 whitespace-pre-line"></p>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
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
            </div>
        </div>
    </div>
</div> 