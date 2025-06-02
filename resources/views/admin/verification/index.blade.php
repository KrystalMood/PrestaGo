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
                [
                    'title' => 'Verifikasi Ditolak',
                    'value' => $rejectedVerifications ?? 0,
                    'icon' => 'x-circle',
                    'key' => 'rejectedVerifications'
                ],
            ];
        @endphp
        @component('admin.components.cards.stats-cards', ['stats' => $stats, 'columns' => 4])
        @endcomponent

        @php
            // Determine the current filter to be selected in the dropdown.
            $currentSelectedFilter = 'all'; // Default for initial load or if no status is specified
            if (isset($activeQueryStatus)) { // $activeQueryStatus is passed from controller
                $currentSelectedFilter = $activeQueryStatus;
            } elseif (request()->has('status')) {
                $currentSelectedFilter = request('status');
            }

            $filterOptions = [
                ['value' => 'all', 'label' => 'Semua Status'],
                ['value' => 'pending', 'label' => 'Menunggu'],
                ['value' => 'verified', 'label' => 'Disetujui'],
                ['value' => 'rejected', 'label' => 'Ditolak']
            ];
        @endphp

        @component('admin.components.ui.search-and-filter', [
            'searchRoute' => route('admin.verification.index'),
            'searchPlaceholder' => 'Cari berdasarkan nama atau email pengguna...',
            'filterOptions' => $filterOptions,
            'filterName' => 'status',
            'filterLabel' => 'Semua Status',
            'currentFilter' => $currentSelectedFilter // Use the determined selected filter
        ])
        @endcomponent

        <div id="verifications-table-container">
            @component('admin.verification.components.tables', [
                'verifications' => $verifications ?? collect(),
                'activeFilterStatus' => $currentSelectedFilter // Pass the active filter status to the table component
            ])
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
            show: "{{ route('admin.verification.show', ['id' => '__ID__']) }}",
            update: "{{ route('admin.verification.update', ['id' => '__ID__']) }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
        
        // Add applyFilter function to ensure it's available immediately
        function applyFilter() {
            const status = document.getElementById('status') ? document.getElementById('status').value : null;
            const search = document.getElementById('search') ? document.getElementById('search').value : null;
            
            const url = new URL(window.location.href);
            
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            
            if (status) url.searchParams.set('status', status);
            else url.searchParams.delete('status');
            
            window.location.href = url.toString();
        }
    </script>

    <!-- Load External JS -->
    @vite('resources/js/admin/verification.js')
@endcomponent 