@component('layouts.dosen', ['title' => 'Peserta ' . $subCompetition->name])
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
                    <a href="{{ route('lecturer.competitions.sub-competitions.index', $competition->id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
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
                <span class="text-blue-600 mr-2">●</span>
                Tambah Peserta
            </h3>
            <form action="{{ route('lecturer.competitions.sub-competitions.participants.store', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-group mb-4">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mahasiswa
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="student_id" name="student_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
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
                    <input type="text" name="team_name" id="team_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="form-group mb-4">
                    <label for="advisor_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Dosen Pendamping (Opsional)
                    </label>
                    <input type="text" name="advisor_name" id="advisor_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="form-group mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="registered">Terdaftar</option>
                        <option value="pending">Menunggu</option>
                    </select>
                </div>
                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelAddParticipant" class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
            <form method="GET" action="{{ route('lecturer.competitions.sub-competitions.participants', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" class="block w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari peserta..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="flex-none">
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
                    <span class="text-blue-600 mr-2">●</span>
                    Daftar Peserta
                </h3>
                <button id="showAddParticipantForm" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Peserta
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Program Studi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama Tim</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->advisor_name ?: 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->status === 'registered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $participant->status === 'registered' ? 'Terdaftar' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" class="view-participant text-blue-600 hover:text-blue-900" data-participant-id="{{ $participant->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button type="button" class="update-status text-green-600 hover:text-green-900" data-participant-id="{{ $participant->id }}" data-status="{{ $participant->status }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-1">Belum ada peserta</p>
                                        <p class="text-gray-500">Belum ada mahasiswa yang terdaftar untuk sub-kompetisi ini.</p>
                                        <button id="emptyAddParticipantBtn" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah Peserta
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle add participant form
            const showAddParticipantFormBtn = document.getElementById('showAddParticipantForm');
            const emptyAddParticipantBtn = document.getElementById('emptyAddParticipantBtn');
            const addParticipantForm = document.getElementById('addParticipantForm');
            const cancelAddParticipantBtn = document.getElementById('cancelAddParticipant');
            
            if (showAddParticipantFormBtn) {
                showAddParticipantFormBtn.addEventListener('click', function() {
                    addParticipantForm.classList.remove('hidden');
                    window.scrollTo({top: addParticipantForm.offsetTop - 100, behavior: 'smooth'});
                });
            }
            
            if (emptyAddParticipantBtn) {
                emptyAddParticipantBtn.addEventListener('click', function() {
                    addParticipantForm.classList.remove('hidden');
                    window.scrollTo({top: addParticipantForm.offsetTop - 100, behavior: 'smooth'});
                });
            }
            
            if (cancelAddParticipantBtn) {
                cancelAddParticipantBtn.addEventListener('click', function() {
                    addParticipantForm.classList.add('hidden');
                });
            }
            
            // Update status buttons
            const updateStatusBtns = document.querySelectorAll('.update-status');
            updateStatusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const participantId = this.dataset.participantId;
                    const currentStatus = this.dataset.status;
                    const newStatus = currentStatus === 'registered' ? 'pending' : 'registered';
                    
                    if (confirm('Apakah Anda yakin ingin mengubah status peserta ini?')) {
                        // Create a form and submit it
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ route('lecturer.competitions.sub-competitions.participants', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}/${participantId}/update-status`;
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        
                        const statusInput = document.createElement('input');
                        statusInput.type = 'hidden';
                        statusInput.name = 'status';
                        statusInput.value = newStatus;
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        
                        form.appendChild(csrfToken);
                        form.appendChild(statusInput);
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endcomponent 