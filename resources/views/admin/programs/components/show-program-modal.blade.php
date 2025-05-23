<!-- Show Program Modal -->
<div id="show-program-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Program Studi</h3>
                <button type="button" id="close-show-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900" id="show-program-name">-</h4>
                        <p class="text-sm text-gray-600" id="show-program-code">-</p>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800" id="show-program-status">
                            -
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-500">FAKULTAS</h5>
                    <p class="text-base font-medium text-gray-900" id="show-program-faculty">-</p>
                </div>

                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-500">JENJANG</h5>
                    <p class="text-base font-medium text-gray-900" id="show-program-degree-level">-</p>
                </div>

                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-500">AKREDITASI</h5>
                    <p class="text-base font-medium text-gray-900" id="show-program-accreditation">-</p>
                </div>

                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-500">TAHUN BERDIRI</h5>
                    <p class="text-base font-medium text-gray-900" id="show-program-year-established">-</p>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <h5 class="text-sm font-medium text-gray-500">DESKRIPSI</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-700" id="show-program-description">-</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                <div class="flex items-center text-gray-500 text-sm">
                    <span>Terakhir diperbarui: <span id="show-program-updated-at">-</span></span>
                </div>
                <div class="flex space-x-3">
                    <x-ui.button 
                        variant="secondary" 
                        id="close-show-program"
                        type="button"
                    >
                        Tutup
                    </x-ui.button>
                    
                    <x-ui.button 
                        variant="primary"
                        id="edit-from-show"
                        type="button"
                        data-program-id=""
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