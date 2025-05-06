@props(['modalId' => 'delete-modal', 'route' => '', 'itemType' => 'item'])

<!-- Delete Confirmation Modal -->
<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-red-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus {{ $itemType }} <span id="item-name-to-delete" class="font-semibold"></span>? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex justify-center gap-4">
                <button id="cancel-delete-{{ $modalId }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg">
                    Batal
                </button>
                
                <form id="delete-form-{{ $modalId }}" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('{{ $modalId }}');
        const deleteForm = document.getElementById('delete-form-{{ $modalId }}');
        const itemNameToDelete = document.getElementById('item-name-to-delete');
        const cancelDelete = document.getElementById('cancel-delete-{{ $modalId }}');
        
        document.querySelectorAll('.delete-item').forEach(button => {
            button.addEventListener('click', () => {
                const itemId = button.getAttribute('data-item-id');
                const itemName = button.getAttribute('data-item-name');
                
                deleteForm.action = `{{ $route }}/${itemId}`;
                itemNameToDelete.textContent = itemName;
                deleteModal.classList.remove('hidden');
            });
        });
        
        cancelDelete.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    });
</script> 