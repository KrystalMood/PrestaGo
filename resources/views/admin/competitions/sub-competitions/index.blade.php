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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @forelse($subCompetitions as $subCompetition)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group h-[360px] flex flex-col">
                    <div class="p-5 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-900 mr-2 group-hover:text-blue-700 transition-colors duration-200">{{ $subCompetition->name }}</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $subCompetition->status === 'upcoming' ? 'bg-blue-100 text-blue-800' : ($subCompetition->status === 'ongoing' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($subCompetition->status) }}
                            </span>
                        </div>
                        
                        <div class="overflow-auto mb-4 flex-1">
                            <p class="text-sm text-gray-600">{{ $subCompetition->description ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                        
                        <div class="space-y-3 mb-5">
                            @if($subCompetition->category)
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="bg-gray-100 p-1.5 rounded-md mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <span>{{ $subCompetition->category->name }}</span>
                            </div>
                            @endif
                            
                            @if($subCompetition->start_date && $subCompetition->end_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="bg-gray-100 p-1.5 rounded-md mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span>{{ $subCompetition->start_date->format('d M Y') }} - {{ $subCompetition->end_date->format('d M Y') }}</span>
                            </div>
                            @endif
                            
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="bg-gray-100 p-1.5 rounded-md mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <span>{{ $subCompetition->participants->count() }} Peserta</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center border-t border-gray-200 pt-4 mt-auto">
                            <a href="{{ route('admin.competitions.sub-competitions.participants', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 group-hover:translate-x-1 transition-transform duration-200">
                                <span>Lihat Peserta</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <div class="flex space-x-1">
                                <button onclick="editSubCompetition({{ $subCompetition->id }})" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="deleteSubCompetition({{ $subCompetition->id }})" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-gray-50 rounded-lg border border-gray-200 p-8 text-center">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada sub-kompetisi</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">Tambahkan sub-kompetisi baru untuk kompetisi ini untuk mulai mengelola cabang-cabang kompetisi.</p>
                    <div class="mt-6">
                        <button type="button" id="open-add-sub-competition-modal-empty" class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Sub-Kompetisi
                        </button>
                    </div>
                </div>
            @endforelse
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