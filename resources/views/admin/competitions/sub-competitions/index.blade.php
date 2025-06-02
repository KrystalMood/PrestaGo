@component('layouts.admin', ['title' => 'Sub-Kompetisi ' . $competition->name])
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <span>{{ $competition->name }}</span>
                    <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">Kompetisi Utama</span>
                </h1>
                <p class="text-sm text-gray-600">{{ $competition->organizer }}</p>
                <div class="mt-3">
                    <a href="{{ route('admin.competitions.index') }}" class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Kompetisi
                    </a>
                </div>
            </div>
            <div>
                <button id="open-add-sub-competition-modal" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Sub-Kompetisi
                </button>
            </div>
        </div>

        <!-- Sub Competitions List -->
        <div class="mt-6">
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sub-Kompetisi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subCompetitions as $subCompetition)
                                <tr class="hover:bg-gray-50 transition-colors" data-sub-competition-id="{{ $subCompetition->id }}">
                                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                                            {{ ($subCompetitions instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($subCompetitions->currentPage() - 1) * $subCompetitions->perPage() + $loop->iteration) : $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $subCompetition->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            {{ $subCompetition->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($subCompetition->description, 50) ?: 'Tidak ada deskripsi' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = [
                                                'upcoming' => 'bg-yellow-100 text-yellow-800',
                                                'ongoing' => 'bg-green-100 text-green-800',
                                                'completed' => 'bg-blue-100 text-blue-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ][$subCompetition->status] ?? 'bg-gray-100 text-gray-800';
                                            
                                            $statusText = [
                                                'upcoming' => 'Akan Datang',
                                                'ongoing' => 'Berlangsung',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                            ][$subCompetition->status] ?? ucfirst($subCompetition->status);
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subCompetition->category->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($subCompetition->start_date && $subCompetition->end_date)
                                            {{ $subCompetition->start_date->format('d M Y') }} - {{ $subCompetition->end_date->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.competitions.sub-competitions.participants', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" class="group flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors" title="Kelola Peserta">
                                            <div class="bg-blue-50 rounded-full p-1.5 mr-2 group-hover:bg-blue-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ $subCompetition->participants->count() }}</span>
                                            @php
                                                $pendingRegistrations = $subCompetition->participants()->where('status', 'pending')->count();
                                            @endphp
                                            @if($pendingRegistrations > 0)
                                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $pendingRegistrations }}</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-1">
                                            <a href="{{ route('admin.competitions.sub-competitions.skills', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" class="btn btn-sm btn-ghost text-green-600 hover:bg-green-50 transition-colors" title="Kelola Skill">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                            </a>
                                            <button onclick="showSubCompetition({{ $subCompetition->id }})" class="btn btn-sm btn-ghost text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button onclick="editSubCompetition({{ $subCompetition->id }})" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteSubCompetition({{ $subCompetition->id }})" class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-600 font-medium">Belum ada sub-kompetisi yang tersedia</p>
                                            <p class="text-gray-500 mt-1 text-sm">Silakan tambahkan sub-kompetisi baru untuk kompetisi ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($subCompetitions->count() > 0 && method_exists($subCompetitions, 'links'))
            <div class="mt-6">
                {{ $subCompetitions->links() }}
            </div>
        @endif
    </div>

    <!-- Include modals -->
    @include('admin.competitions.sub-competitions.components.add-sub-competition-modal')
    @include('admin.competitions.sub-competitions.components.edit-sub-competition-modal')
    @include('admin.competitions.sub-competitions.components.delete-sub-competition-modal')
    @include('admin.competitions.sub-competitions.components.show-sub-competition-modal')

    <!-- JavaScript Variables and Setup -->
    <script>
        window.competitionId = {{ $competition->id }};
        window.subCompetitionRoutes = {
            store: "{{ route('admin.competitions.sub-competitions.store', ['competition' => $competition->id]) }}",
            show: "{{ route('admin.competitions.sub-competitions.show', ['competition' => $competition->id, 'sub_competition' => '__id__']) }}",
            update: "{{ route('admin.competitions.sub-competitions.update', ['competition' => $competition->id, 'sub_competition' => '__id__']) }}",
            destroy: "{{ route('admin.competitions.sub-competitions.destroy', ['competition' => $competition->id, 'sub_competition' => '__id__']) }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Include sub-competitions.js -->
    @vite(['resources/js/admin/sub-competitions.js'])
@endcomponent 