@props(['modalId' => 'delete-period-modal'])

<!-- Delete Period Confirmation Modal -->
<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4" id="delete-modal-container">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Hapus Periode</h3>
                <button type="button" data-modal-hide="{{ $modalId }}" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="text-center py-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-5">
                    <svg class="h-10 w-10 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <h3 class="mb-3 text-lg font-medium text-gray-900">Yakin ingin menghapus periode ini?</h3>
                <p class="mb-5 text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan dan semua data terkait periode ini akan dihapus secara permanen.</p>
                
                <form id="delete-period-form" action="" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete-period-id" name="id">
                    <div class="flex items-center justify-center space-x-4">
                        <button data-modal-hide="{{ $modalId }}" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Ya, hapus periode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('{{ $modalId }}');
        const modalContainer = document.getElementById('delete-modal-container');
        
        if (!modal || !modalContainer) return;
        
        const closeButtons = document.querySelectorAll('[data-modal-hide="{{ $modalId }}"]');
        
        function showModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modalContainer.classList.add('animate-modal-appear');
            }, 10);
        }
        
        function hideModal() {
            modalContainer.classList.remove('animate-modal-appear');
            modalContainer.classList.add('animate-modal-disappear');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modalContainer.classList.remove('animate-modal-disappear');
            }, 300);
        }
        
        document.addEventListener('delete-modal:show', function() {
            showModal();
        });
        
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

<style>
    @keyframes modalAppear {
        from { opacity: 0; transform: translateY(-1rem); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes modalDisappear {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-1rem); }
    }
    
    .animate-modal-appear {
        animation: modalAppear 0.3s ease-out forwards;
    }
    
    .animate-modal-disappear {
        animation: modalDisappear 0.3s ease-in forwards;
    }
</style> 