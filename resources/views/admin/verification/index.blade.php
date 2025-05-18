@component('layouts.admin', ['title' => 'Verifikasi Pengguna'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'subtitle' => 'Halaman ini menampilkan daftar semua permintaan verifikasi dan memungkinkan Anda untuk menyetujui atau menolak verifikasi.',
            ])
        </div>
        
        @php
            $stats = [
                [
                    'title' => 'Total Verifikasi',
                    'value' => $totalVerifications ?? 0,
                    'icon' => 'clipboard-check',
                    'key' => 'totalVerifications'
                ],
                [
                    'title' => 'Menunggu Verifikasi',
                    'value' => $pendingVerifications ?? 0,
                    'icon' => 'clock',
                    'key' => 'pendingVerifications'
                ],
                [
                    'title' => 'Verifikasi Disetujui',
                    'value' => $approvedVerifications ?? 0,
                    'icon' => 'check-circle',
                    'key' => 'approvedVerifications'
                ],
            ];
        @endphp
        @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
        @endcomponent

        @php
            $filterOptions = [
                ['value' => 'all', 'label' => 'Semua Status'],
                ['value' => 'pending', 'label' => 'Menunggu'],
                ['value' => 'approved', 'label' => 'Disetujui'],
                ['value' => 'rejected', 'label' => 'Ditolak']
            ];
        @endphp

        @component('admin.components.ui.search-and-filter', [
            'searchRoute' => route('admin.verification.index'),
            'searchPlaceholder' => 'Cari berdasarkan nama atau email pengguna...',
            'filterOptions' => $filterOptions,
            'filterName' => 'status',
            'filterLabel' => 'Semua Status',
            'currentFilter' => request('status')
        ])
        @endcomponent

        <div id="verifications-table-container">
            @component('admin.verification.components.tables')
            @slot('verifications', $verifications ?? collect())
            @endcomponent
        </div>

        <div id="pagination-container">
            @component('admin.components.tables.pagination', ['data' => $verifications ?? collect()])
            @endcomponent
        </div>
    </div>

    <!-- Include modals -->
    @include('admin.verification.components.show-verification-modal')

    <!-- JavaScript Variables and Setup -->
    <script>
        window.verificationRoutes = {
            index: "{{ route('admin.verification.index') }}",
            update: "{{ route('admin.verification.update', ['id' => '__ID__']) }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Load External JS -->
    @vite('resources/js/admin/verification.js')
@endcomponent 