<!-- Toggle Status Modal -->
<div id="toggle-status-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4" id="toggle-status-container">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Konfirmasi Perubahan Status</h3>
                <button type="button" id="close-toggle-status" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="text-center py-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-lg font-medium text-gray-900">Apakah Anda yakin?</h3>
                <p class="mb-5 text-sm text-gray-500">
                    Anda akan <span id="period-status-action" class="font-semibold text-amber-600"></span> periode <span id="period-status-name" class="font-semibold"></span>.
                </p>
                
                <div class="flex items-center justify-center space-x-4 mt-6">
                    <button id="cancel-toggle-status" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button id="confirm-toggle-status" type="button" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 border border-transparent rounded-md shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                        Ya, lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('toggle-status-modal');
        const modalContainer = document.getElementById('toggle-status-container');
        
        if (!modal || !modalContainer) return;
        
        const closeButtons = document.querySelectorAll('#close-toggle-status, #cancel-toggle-status');
        
        function hideModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                hideModal();
            });
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                hideModal();
            }
        });
    });
</script> 