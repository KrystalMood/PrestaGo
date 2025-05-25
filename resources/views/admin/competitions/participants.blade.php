@component('layouts.admin', ['title' => 'Peserta ' . $competition->name])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @component('admin.competitions.components.page-header')
            @slot('subtitle', 'Halaman ini memungkinkan Anda mengelola peserta yang terdaftar untuk kompetisi ini.')
            @slot('showBackButton', true)
            @slot('backRoute', route('admin.competitions.index'))
        @endcomponent

        <!-- Search and Filter Bar -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.competitions.participants', $competition->id) }}">
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

        <!-- Competition Interest Registration -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Pendaftaran Minat Kompetisi</h2>
                    <p class="text-sm text-gray-600 mt-1">Daftar mahasiswa yang berminat mengikuti kompetisi ini</p>
                </div>
                <button id="showAddParticipantForm" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Pendaftar
                </button>
            </div>

            <!-- Info Alert -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Ini adalah daftar mahasiswa yang telah menyatakan minat untuk kompetisi ini. Untuk partisipasi aktual, silakan kelola di masing-masing sub-kompetisi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Minat</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($competition->participants as $participant)
                            <tr class="hover:bg-gray-50">
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->status === 'registered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $participant->status === 'registered' ? 'Terdaftar' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.competitions.sub-competitions.index', $competition) }}" class="text-blue-600 hover:text-blue-900">Lihat Sub-Kompetisi</a>
                                        <form action="{{ route('admin.competitions.participants.destroy', ['competition' => $competition->id, 'participant' => $participant->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada mahasiswa yang mendaftar untuk kompetisi ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Participant Form (Hidden by Default) -->
        <div id="addParticipantForm" class="mt-6 bg-white p-6 rounded-lg border border-gray-200 shadow-sm hidden">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <span class="text-blue-600 mr-2">●</span>
                Tambah Pendaftar Baru
            </h3>
            <form action="{{ route('admin.competitions.participants.store', $competition) }}" method="POST" class="space-y-4">
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
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Pendaftaran
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="registered">Terdaftar</option>
                        <option value="pending">Menunggu</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelAddParticipant" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showFormButton = document.getElementById('showAddParticipantForm');
            const cancelButton = document.getElementById('cancelAddParticipant');
            const form = document.getElementById('addParticipantForm');

            showFormButton.addEventListener('click', function() {
                form.classList.remove('hidden');
            });

            cancelButton.addEventListener('click', function() {
                form.classList.add('hidden');
            });
        });
    </script>
    @endpush
@endcomponent 