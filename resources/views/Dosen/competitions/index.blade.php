@component('layouts.dosen', ['title' => 'Manajemen Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @component('Dosen.components.ui.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar kompetisi yang tersedia dan memungkinkan Anda untuk menambah, mengubah, atau menghapus informasi kompetisi.',
            ])
                Daftar Kompetisi
            @endcomponent
        </div>
        
        @php
            $stats = [
                [
                    'title' => 'Total Kompetisi',
                    'value' => $totalCompetitions ?? 0,
                    'icon' => 'trophy',
                    'key' => 'totalCompetitions'
                ],
                [
                    'title' => 'Kompetisi Aktif',
                    'value' => $activeCompetitions ?? 0,
                    'icon' => 'calendar',
                    'key' => 'activeCompetitions'
                ],
                [
                    'title' => 'Rekomendasi Admin',
                    'value' => $recommendedCompetitions ?? 0,
                    'icon' => 'badge-check',
                    'key' => 'recommendedCompetitions'
                ],
                [
                    'title' => 'Peserta Terdaftar',
                    'value' => $registeredParticipants ?? 0,
                    'icon' => 'users',
                    'key' => 'registeredParticipants'
                ],
            ];
        @endphp
        
        <div class="mb-4">
            @component('Dosen.components.cards.stats-cards', ['stats' => $stats, 'columns' => 4])
            @endcomponent
        </div>

        @if(isset($recommendations) && $recommendations->count() > 0)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Rekomendasi Kompetisi untuk Anda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="recommendations-container">
                @foreach($recommendations as $recommendation)
                    @if($recommendation->competition)
                        <div class="bg-white rounded-lg shadow-custom p-4 hover:shadow-lg transition-shadow cursor-pointer recommendation-card">
                            <h3 class="font-semibold text-indigo-700 mb-2">{{ $recommendation->competition->name }}</h3>
                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $recommendation->competition->start_date ? \Carbon\Carbon::parse($recommendation->competition->start_date)->format('d M Y') : 'TBA' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>{{ $recommendation->competition->organizer }}</span>
                            </div>
                            <div class="flex items-center mb-3">
                                <div class="text-xs bg-green-100 text-green-800 rounded-full px-2 py-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>{{ number_format($recommendation->match_score, 0) }}% Match</span>
                                </div>
                            </div>
                            @if($recommendation->notes)
                                <p class="text-sm text-gray-600 mb-3">{{ $recommendation->notes }}</p>
                            @endif
                            <button type="button" 
                                    onclick="showCompetitionDetails('{{ $recommendation->competition->id }}')" 
                                    class="block w-full text-center border border-indigo-600 hover:bg-indigo-700 text-indigo-600 hover:text-white font-medium py-2 px-4 rounded transition-colors">
                                Lihat Detail
                            </button>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-4 mb-6 flex justify-end space-x-3">
            <button type="button" id="open-add-competition-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Kompetisi
            </button>
        </div>

        @component('Dosen.components.ui.search-and-filter', [
            'searchRoute' => route('lecturer.competitions.index'),
            'searchPlaceholder' => 'Cari kompetisi...',
            'statuses' => $statuses ?? collect(),
            'levels' => $levels ?? collect(),
        ])
        @endcomponent

        <div id="competitions-table-container">
            @component('Dosen.competitions.components.tables')
                @slot('competitions', $competitions ?? collect())
            @endcomponent
        </div>

        <div id="pagination-container">
            @component('Dosen.components.tables.pagination', ['data' => $competitions ?? collect()])
            @endcomponent
        </div>
    </div>

    <!-- Include modals -->
    @include('Dosen.competitions.components.show-competition-modal')
    @include('Dosen.competitions.components.add-competition-modal')

    <!-- JavaScript Variables and Setup -->
    <script>
        window.competitionRoutes = {
            index: "{{ route('lecturer.competitions.index') }}",
            show: "{{ route('lecturer.competitions.show', ['id' => '__id__']) }}",
            store: "{{ route('lecturer.competitions.store') }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
        
        function showCompetitionDetails(id) {
            const url = window.competitionRoutes.show.replace('__id__', id);
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal with competition data
                    const modal = document.getElementById('show-competition-modal');
                    
                    // Set data in modal
                    document.getElementById('competition-title').textContent = data.competition.name;
                    document.getElementById('competition-organizer').textContent = data.competition.organizer;
                    document.getElementById('competition-level').textContent = data.competition.level_formatted;
                    document.getElementById('competition-status').textContent = data.competition.status_formatted;
                    document.getElementById('competition-period').textContent = data.competition.period ? data.competition.period.name : 'N/A';
                    
                    // Show modal
                    modal.classList.remove('hidden');
                }
            })
            .catch(error => console.error('Error fetching competition details:', error));
        }
    </script>

    <!-- Load External JS -->
    @vite(['resources/js/dosen/competitions.js'])
@endcomponent