@component('layouts.admin', ['title' => 'Manajemen Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'title' => 'Manajemen Kompetisi',
                'subtitle' => 'Halaman ini menampilkan daftar kompetisi yang tersedia dan memungkinkan Anda untuk menambah, mengubah, atau menghapus informasi kompetisi.',
            ])
        </div>
        
        @php
            $stats = [
                [
                    'title' => 'Total Kompetisi',
                    'value' => $totalCompetitions ?? 0,
                    'icon' => 'trophy'
                ],
                [
                    'title' => 'Kompetisi Aktif',
                    'value' => $activeCompetitions ?? 0,
                    'icon' => 'calendar'
                ],
                [
                    'title' => 'Kompetisi Selesai',
                    'value' => $completedCompetitions ?? 0,
                    'icon' => 'check-circle'
                ],
                [
                    'title' => 'Peserta Terdaftar',
                    'value' => $registeredParticipants ?? 0,
                    'icon' => 'users'
                ],
            ];
        @endphp
        
        <div class="mb-4">
            @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 4])
            @endcomponent
        </div>

        <div class="mt-4 mb-6 flex justify-end space-x-3">
            <x-admin.buttons.add-button 
                route="{{ route('admin.competitions.create') }}" 
                text="Tambah Kompetisi" 
                icon="plus"
                color="blue"
            />
            
            <x-admin.buttons.action-button
                route="#"
                text="Ekspor Data"
                icon="download"
                color="green"
            />
        </div>

        @component('admin.competitions.components.search-and-filter')
            @slot('categories', $categories ?? collect())
            @slot('statuses', $statuses ?? collect())
        @endcomponent

        @component('admin.competitions.components.tables')
            @slot('competitions', $competitions ?? collect())
        @endcomponent

        @component('admin.components.tables.pagination', ['data' => $competitions ?? collect()])
        @endcomponent
    </div>
@endcomponent 