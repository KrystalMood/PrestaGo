@props(['achievements' => []])

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Prestasi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Kompetisi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jenis Kompetisi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($achievements as $achievement)
                <tr class="hover:bg-gray-50 transition-colors" data-achievement-id="{{ $achievement->id }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($achievements instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($achievements->currentPage() - 1) * $achievements->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $achievement->title }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $achievement->competition_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $achievement->competition->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $achievement->date ? $achievement->date->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($achievement->status == 'approved') 
                                bg-green-100 text-green-800
                            @elseif($achievement->status == 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            @if($achievement->status == 'approved')
                                Disetujui
                            @elseif($achievement->status == 'rejected')
                                Ditolak
                            @else
                                Menunggu
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <!-- Button to show achievement details -->
                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-achievement" data-id="{{ $achievement->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <!-- Button to edit achievement -->
                            <button type="button" class="btn btn-sm btn-ghost text-indigo-600 hover:bg-indigo-50 transition-colors edit-achievement" data-id="{{ $achievement->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <!-- Button to delete achievement -->
                            <button type="button" class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 transition-colors delete-achievement" data-id="{{ $achievement->id }}" data-title="{{ $achievement->title }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada data prestasi yang tersedia</p>
                            <p class="text-gray-500 mt-1 text-sm">Silakan tambahkan prestasi baru atau ubah filter pencarian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-achievement-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div id="delete-modal-container" class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Hapus Prestasi</h3>
                <button type="button" data-modal-hide="delete-achievement-modal" class="text-gray-400 hover:text-gray-500">
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
                <h3 class="mb-3 text-lg font-medium text-gray-900">Yakin ingin menghapus prestasi ini?</h3>
                <p class="mb-5 text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan dan semua data terkait prestasi <span id="achievement-name-to-delete" class="font-semibold"></span> akan dihapus secara permanen.</p>
                
                <form id="delete-achievement-form" action="" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-center space-x-4">
                        <button data-modal-hide="delete-achievement-modal" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" id="confirm-delete-achievement" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

<script>
    document.querySelectorAll('.delete-achievement').forEach(button => {
        button.addEventListener('click', () => {
            const achievementId = button.getAttribute('data-id');
            const achievementTitle = button.getAttribute('data-title');
            
            const deleteForm = document.getElementById('delete-achievement-form');
            const achievementNameToDelete = document.getElementById('achievement-name-to-delete');
            
            if (deleteForm && achievementNameToDelete) {
                deleteForm.action = window.achievementRoutes.delete.replace('__ID__', achievementId);
                achievementNameToDelete.textContent = achievementTitle;
                
                // Show delete modal
                const deleteModal = document.getElementById('delete-achievement-modal');
                if (deleteModal) {
                    deleteModal.classList.remove('hidden');
                    deleteModal.classList.add('flex');
                    
                    setTimeout(() => {
                        const deleteModalContainer = document.getElementById('delete-modal-container');
                        if (deleteModalContainer) {
                            deleteModalContainer.classList.add('animate-modal-appear');
                        }
                    }, 10);
                }
            }
        });
    });
</script> 