@component('layouts.admin', ['title' => 'Peserta ' . $subCompetition->name])
    <style>
        /* Ensure skeleton loader renders correctly */
        .skeleton-loader {
            display: block;
            width: 100%;
            min-height: 200px;
        }
        
        /* Background animation for the skeleton */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Ensure the modal has proper z-index and opacity */
        #participantDetailModal {
            z-index: 50;
        }
        
        #participantDetailModal .bg-opacity-75 {
            opacity: 0.75;
        }
    </style>

    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $subCompetition->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $competition->name }} - {{ $competition->organizer }}</p>
                <div class="mt-2">
                    <a href="{{ route('admin.competitions.sub-competitions.index', $competition->id) }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Sub-Kompetisi
                    </a>
                </div>
            </div>
        </div>

        <!-- Add Participant Form (Hidden by Default) -->
        <div id="addParticipantForm" class="mb-6 bg-white p-6 rounded-lg border border-gray-200 shadow-sm hidden">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <span class="text-indigo-600 mr-2">●</span>
                Tambah Peserta
            </h3>
            <form action="{{ route('admin.competitions.sub-competitions.participants.store', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-group mb-4">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mahasiswa
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="student_id" name="student_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->nim ?? 'NIM tidak tersedia' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="team_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Tim (Opsional)
                    </label>
                    <input type="text" name="team_name" id="team_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="form-group mb-4">
                    <label for="advisor_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Dosen Pendamping (Opsional)
                    </label>
                    <input type="text" name="advisor_name" id="advisor_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="form-group mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="registered">Terdaftar</option>
                        <option value="pending">Menunggu</option>
                    </select>
                </div>
                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelAddParticipant" class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.competitions.sub-competitions.participants', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" class="block w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari peserta..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="flex-none">
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">
                    <span class="text-indigo-600 mr-2">●</span>
                    Daftar Peserta
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Program Studi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama Tim</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Anggota Tim</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Dosen Pendamping</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tanggal Bergabung</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($participants as $participant)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($participant->user->profile_picture)
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $participant->user->profile_picture) }}" alt="{{ $participant->user->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500 font-medium">{{ strtoupper(substr($participant->user->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $participant->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $participant->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->user->nim ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->user->study_program ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->team_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if(!empty($participant->team_members))
                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">
                                            {{ count(json_decode($participant->team_members)) }} Anggota
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->advisor_name ?: 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->status === 'registered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $participant->status === 'registered' ? 'Terdaftar' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <button 
                                            class="show-detail-btn inline-flex items-center px-2.5 py-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded transition-colors"
                                            data-participant-id="{{ $participant->id }}"
                                            data-detail-url="{{ route('admin.competitions.sub-competitions.participants.show.ajax', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => $participant->id]) }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </button>
                                        @if($participant->status === 'pending')
                                            <form action="{{ route('admin.competitions.sub-competitions.participants.update', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => $participant->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="registered">
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.competitions.sub-competitions.participants.destroy', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => $participant->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada peserta untuk sub-kompetisi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Participant Detail Modal -->
    <div id="participantDetailModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Loading spinner -->
                    <div id="modalLoadingSpinner" class="py-8">
                        <!-- Skeleton Loading UI -->
                        <div class="animate-pulse skeleton-loader">
                            <!-- Profile Section Skeleton -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-6">
                                <div class="flex items-start space-x-6">
                                    <div class="flex-shrink-0">
                                        <div class="h-24 w-24 rounded-full bg-gray-200"></div>
                                    </div>
                                    <div class="w-full">
                                        <div class="h-6 bg-gray-200 rounded w-1/2 mb-4"></div>
                                        <div class="h-4 bg-gray-200 rounded w-1/3 mb-6"></div>
                                        <div class="flex items-center">
                                            <div class="h-5 w-20 bg-gray-200 rounded-full"></div>
                                            <div class="ml-3 h-5 w-24 bg-gray-200 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Team Information Skeleton -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                                    <div class="h-6 bg-gray-200 rounded w-1/4"></div>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center">
                                        <div class="h-5 w-5 bg-gray-200 rounded-full mr-2"></div>
                                        <div>
                                            <div class="h-5 bg-gray-200 rounded w-40 mb-2"></div>
                                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Student Information Skeleton -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                                    <div class="h-6 bg-gray-200 rounded w-1/3"></div>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <div class="h-4 bg-gray-200 rounded w-16 mb-2"></div>
                                            <div class="h-5 bg-gray-200 rounded w-28"></div>
                                        </div>
                                        <div>
                                            <div class="h-4 bg-gray-200 rounded w-24 mb-2"></div>
                                            <div class="h-5 bg-gray-200 rounded w-36"></div>
                                        </div>
                                        <div>
                                            <div class="h-4 bg-gray-200 rounded w-20 mb-2"></div>
                                            <div class="h-5 bg-gray-200 rounded w-32"></div>
                                        </div>
                                        <div>
                                            <div class="h-4 bg-gray-200 rounded w-28 mb-2"></div>
                                            <div class="h-5 bg-gray-200 rounded w-16"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons Skeleton -->
                            <div class="flex justify-end space-x-3">
                                <div class="h-10 w-24 bg-gray-200 rounded-md"></div>
                                <div class="h-10 w-24 bg-gray-200 rounded-md"></div>
                                <div class="h-10 w-24 bg-gray-200 rounded-md"></div>
                            </div>
                        </div>
                        
                        <!-- Fallback spinner in case skeleton doesn't render -->
                        <div class="spinner-fallback flex justify-center items-center py-12 hidden">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
                        </div>
                    </div>
                    
                    <!-- Modal content will be loaded here -->
                    <div id="modalContent" class="hidden">
                        <!-- Content will be injected here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButton = document.getElementById('cancelAddParticipant');
            const form = document.getElementById('addParticipantForm');
            
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    form.classList.add('hidden');
                });
            }
            
            // Modal functionality
            const modal = document.getElementById('participantDetailModal');
            const modalContent = document.getElementById('modalContent');
            const loadingSpinner = document.getElementById('modalLoadingSpinner');
            const skeletonLoader = loadingSpinner.querySelector('.skeleton-loader');
            const spinnerFallback = loadingSpinner.querySelector('.spinner-fallback');
            const detailButtons = document.querySelectorAll('.show-detail-btn');
            
            console.log('Modal elements:', { 
                modal: modal, 
                modalContent: modalContent, 
                loadingSpinner: loadingSpinner,
                skeletonLoader: skeletonLoader,
                spinnerFallback: spinnerFallback
            });
            
            // Function to open modal
            function openModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
                
                // Show skeleton loader
                console.log('Opening modal, showing skeleton loader');
                loadingSpinner.classList.remove('hidden');
                modalContent.classList.add('hidden');
                
                // Check if the skeleton is rendering properly
                // If it has no height, show the fallback spinner
                setTimeout(() => {
                    if (skeletonLoader.offsetHeight < 10) {
                        console.log('Skeleton not rendering properly, showing fallback spinner');
                        skeletonLoader.classList.add('hidden');
                        spinnerFallback.classList.remove('hidden');
                    }
                }, 100);
            }
            
            // Function to close modal
            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Allow scrolling
                modalContent.innerHTML = ''; // Clear content
                modalContent.classList.add('hidden');
                
                // Reset loading spinners
                loadingSpinner.classList.remove('hidden');
                skeletonLoader.classList.remove('hidden');
                spinnerFallback.classList.add('hidden');
            }
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(event) {
                if (event.target === modal || event.target.classList.contains('bg-opacity-75')) {
                    closeModal();
                }
            });
            
            // Handle ESC key to close modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
            
            // Load participant details
            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const participantId = this.getAttribute('data-participant-id');
                    const detailUrl = this.getAttribute('data-detail-url');
                    
                    console.log('Showing participant details for ID:', participantId);
                    // Show modal with skeleton loading
                    openModal();
                    
                    // Add a small delay to simulate loading and ensure skeleton is visible
                    setTimeout(() => {
                        // Fetch participant details
                        fetch(detailUrl)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Content loaded, hiding skeleton');
                                    // Hide loading spinner and show content
                                    loadingSpinner.classList.add('hidden');
                                    modalContent.innerHTML = data.html;
                                    modalContent.classList.remove('hidden');
                                    
                                    // Handle approve button
                                    const approveButton = modalContent.querySelector('#approve-btn');
                                    if (approveButton) {
                                        approveButton.addEventListener('click', function() {
                                            updateParticipantStatus(data.participant.id, 'registered', 'Peserta berhasil disetujui');
                                        });
                                    }
                                    
                                    // Handle reject button
                                    const rejectButton = modalContent.querySelector('#reject-btn');
                                    if (rejectButton) {
                                        rejectButton.addEventListener('click', function() {
                                            updateParticipantStatus(data.participant.id, 'pending', 'Peserta berhasil ditolak');
                                        });
                                    }
                                    
                                    // Handle close button in modal content
                                    const closeButton = modalContent.querySelector('#close-modal');
                                    if (closeButton) {
                                        closeButton.addEventListener('click', closeModal);
                                    }
                                } else {
                                    alert('Terjadi kesalahan saat memuat data peserta.');
                                    closeModal();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memuat data peserta.');
                                closeModal();
                            });
                    }, 500); // 500ms delay to ensure skeleton is visible
                });
            });
            
            // Function to update participant status
            function updateParticipantStatus(participantId, status, customMessage) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');
                formData.append('status', status);
                
                fetch(`{{ route('admin.competitions.sub-competitions.participants.update', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => 0]) }}`.replace('/0', `/${participantId}`), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success toast notification
                        window.toast.success(customMessage || data.message || 'Status peserta berhasil diperbarui');
                        
                        // Close the modal
                        closeModal();
                        
                        // Reload the page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        window.toast.error('Terjadi kesalahan saat memperbarui status peserta.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.toast.error('Terjadi kesalahan saat memperbarui status peserta.');
                });
            }
        });
    </script>
@endcomponent 