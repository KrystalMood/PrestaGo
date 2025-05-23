@component('layouts.admin', ['title' => 'Peserta ' . $subCompetition->name])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $subCompetition->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $competition->name }} - {{ $competition->organizer }}</p>
                <div class="mt-2">
                    <a href="{{ route('admin.competitions.sub-competitions.index', $competition->id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Sub-Kompetisi
                    </a>
                </div>
            </div>
            <div>
                <button id="toggleAddParticipantForm" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Peserta
                </button>
            </div>
        </div>

        <!-- Add Participant Form (Hidden by Default) -->
        <div id="addParticipantForm" class="mb-6 bg-white p-6 rounded-lg border border-gray-200 shadow-sm hidden">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <span class="text-blue-600 mr-2">●</span>
                Tambah Peserta
            </h3>
            <form action="{{ route('admin.competitions.sub-competitions.participants.store', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" method="POST" class="space-y-4">
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
                        <button type="button" id="cancelAddParticipant" class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                            <input type="text" name="search" id="search" class="block w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari peserta..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="flex-none">
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <span class="text-blue-600 mr-2">●</span>
                    Daftar Peserta
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->status === 'registered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $participant->status === 'registered' ? 'Terdaftar' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">Detail</a>
                                        <form action="{{ route('admin.competitions.sub-competitions.participants.destroy', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => $participant->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada peserta untuk sub-kompetisi ini.
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
            const toggleButton = document.getElementById('toggleAddParticipantForm');
            const cancelButton = document.getElementById('cancelAddParticipant');
            const form = document.getElementById('addParticipantForm');
            
            toggleButton.addEventListener('click', function() {
                form.classList.toggle('hidden');
            });
            
            cancelButton.addEventListener('click', function() {
                form.classList.add('hidden');
            });
        });
    </script>
@endcomponent 