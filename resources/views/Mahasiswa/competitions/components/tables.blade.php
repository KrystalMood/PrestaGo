@props(['competitions' => []])

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Kompetisi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Penyelenggara
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Peserta
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($competitions as $competition)
                <tr class="hover:bg-gray-50 transition-colors" data-competition-id="{{ $competition->id }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($competitions instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($competitions->currentPage() - 1) * $competitions->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center">
                            @php
                                $level = strtolower($competition->level ?? '');
                                
                                $iconBg = 'bg-indigo-100';
                                $iconColor = 'text-indigo-500';
                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />';
                                
                                if ($level === 'international') {
                                    $iconBg = 'bg-blue-100';
                                    $iconColor = 'text-blue-600';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                } elseif ($level === 'national') {
                                    $iconBg = 'bg-red-100';
                                    $iconColor = 'text-red-600';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />';
                                } elseif ($level === 'regional') {
                                    $iconBg = 'bg-green-100';
                                    $iconColor = 'text-green-600';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />';
                                } elseif ($level === 'provincial') {
                                    $iconBg = 'bg-yellow-100';
                                    $iconColor = 'text-yellow-600';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
                                } elseif ($level === 'university') {
                                    $iconBg = 'bg-purple-100';
                                    $iconColor = 'text-purple-600';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />';
                                }
                            @endphp
                            <div class="flex-shrink-0 h-10 w-10 rounded-md {{ $iconBg }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $icon !!}
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $competition->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ ucfirst($competition->level ?? 'Umum') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $competition->organizer }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($competition->status == 'completed') 
                                bg-green-100 text-green-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            @if($competition->status == 'completed')
                                Selesai
                            @else
                                Menunggu
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $competition->competition_date ? $competition->competition_date->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.competitions.participants', $competition) }}" class="group flex items-center text-sm text-gray-500 hover:text-brand transition-colors" title="Kelola Peserta">
                            <div class="bg-blue-50 rounded-full p-1.5 mr-2 group-hover:bg-blue-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="font-medium">{{ $competition->participants_count ?? 0 }}</span>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <!-- Button to show competition details -->
                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-competition" data-competition-id="{{ $competition->id }}" data-competition-name="{{ $competition->name }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            {{-- <!-- Button to edit competition -->
                            <button type="button" class="btn btn-sm btn-ghost text-indigo-600 hover:bg-indigo-50 transition-colors edit-competition" data-id="{{ $competition->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <!-- Button to delete competition -->
                            <button type="button" class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 transition-colors delete-competition" data-id="{{ $competition->id }}" data-title="{{ $competition->title }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button> --}}
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
<div id="delete-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div id="delete-modal-container" class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Hapus Prestasi</h3>
                <button type="button" data-modal-hide="delete-competition-modal" class="text-gray-400 hover:text-gray-500">
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
                <p class="mb-5 text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan dan semua data terkait prestasi <span id="competition-name-to-delete" class="font-semibold"></span> akan dihapus secara permanen.</p>
                
                <form id="delete-competition-form" action="" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-center space-x-4">
                        <button data-modal-hide="delete-competition-modal" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" id="confirm-delete-competition" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
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
    document.querySelectorAll('.delete-competition').forEach(button => {
        button.addEventListener('click', () => {
            const competitionId = button.getAttribute('data-id');
            const competitionTitle = button.getAttribute('data-title');
            
            const deleteForm = document.getElementById('delete-competition-form');
            const competitionNameToDelete = document.getElementById('competition-name-to-delete');
            
            if (deleteForm && competitionNameToDelete) {
                deleteForm.action = window.competitionRoutes.delete.replace('__ID__', competitionId);
                competitionNameToDelete.textContent = competitionTitle;
                
                // Show delete modal
                const deleteModal = document.getElementById('delete-competition-modal');
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