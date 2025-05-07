@component('layouts.admin', ['title' => 'Verifikasi Prestasi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'title' => 'Verifikasi Prestasi',
                'subtitle' => 'Halaman ini menampilkan daftar prestasi mahasiswa yang perlu diverifikasi. Anda dapat memeriksa, menyetujui, atau menolak prestasi yang diajukan.',
            ])
        </div>
        
        @php
            $stats = [
                [
                    'title' => 'Total Prestasi',
                    'value' => 0,
                    'icon' => 'award'
                ],
                [
                    'title' => 'Menunggu Verifikasi',
                    'value' => 0,
                    'icon' => 'clock'
                ],
                [
                    'title' => 'Disetujui',
                    'value' => 0,
                    'icon' => 'check-circle'
                ],
                [
                    'title' => 'Ditolak',
                    'value' => 0,
                    'icon' => 'x-circle'
                ],
            ];
        @endphp
        @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 4])
        @endcomponent

        <div class="mt-4 mb-6 flex justify-end space-x-3">
            <x-admin.buttons.action-button
                route="#"
                text="Ekspor Data"
                icon="download"
                color="green"
            />
        </div>

        @component('admin.verification.components.search-and-filter')
        @slot('categories', collect())
        @slot('levels', collect())
        @endcomponent

        @component('admin.verification.components.tables')
        @slot('achievements', collect())
        @endcomponent

        @component('admin.components.tables.pagination', ['data' => $achievements ?? collect()])
        @endcomponent
    </div>
@endcomponent 