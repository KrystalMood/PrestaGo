@component('layouts.dosen', ['title' => 'Prestasi Mahasiswa'])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('Dosen.components.ui.page-header', [
            'title' => 'Prestasi Mahasiswa',
            'subtitle' => 'Halaman ini menampilkan daftar prestasi mahasiswa.',
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Mahasiswa Terdaftar',
                'value' => $totalAchievements ?? 0,
                'icon' => 'user',
                'key' => 'totalAchievements'
            ],
            [
                'title' => 'Total Prestasi Terdata',
                'value' => $newAchievements ?? 0,
                'icon' => 'star',
                'key' => 'newAchievements'
            ],
            [
                'title' => 'Prestasi Tingkat Nasional / Internasional',
                'value' => $verifiedAchievements ?? 0,
                'icon' => 'check-circle',
                'key' => 'verifiedAchievements'
            ],
        ];
    @endphp
    @component('Dosen.components.cards.stats-cards', ['stats' => $stats, 'columns' => 3])
    @endcomponent

    @php
        $filterOptions = [];
        foreach ($types ?? [] as $type) {
            $filterOptions[] = [
                'value' => $type->type_code,
                'label' => $type->type_name
            ];
        }
    @endphp

    @component('Dosen.components.ui.search-and-filter', [
        'searchRoute' => route('lecturer.achievements.index'),
        'searchPlaceholder' => 'Cari prestasi berdasarkan judul atau nama mahasiswa...',
    ])
    @endcomponent

    <!-- Sorting Dropdown -->
    <div class="mb-6 flex justify-end items-center">
        <span class="mr-2 text-sm text-gray-600">Urutkan berdasarkan:</span>
        <div class="w-full md:w-64">
            <select
                id="achievement-sort"
                name="sort"
                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                onchange="applySorting()"
            >
                <option value="achievements_desc" {{ ($currentSort ?? '') == 'achievements_desc' ? 'selected' : '' }}>Total Prestasi (Tertinggi-Terendah)</option>
                <option value="achievements_asc" {{ ($currentSort ?? '') == 'achievements_asc' ? 'selected' : '' }}>Total Prestasi (Terendah-Tertinggi)</option>
                <option value="name_asc" {{ ($currentSort ?? '') == 'name_asc' ? 'selected' : '' }}>Nama Mahasiswa (A-Z)</option>
                <option value="name_desc" {{ ($currentSort ?? '') == 'name_desc' ? 'selected' : '' }}>Nama Mahasiswa (Z-A)</option>
            </select>
        </div>
    </div>
    
    <div id="achievements-table-container">
        @component('Dosen.achievements.components.tables')
        @slot('achievements', $achievements ?? collect())
        @slot('currentSort', $currentSort ?? 'achievements_desc')
        @endcomponent
    </div>

    <div id="pagination-container">
        @component('Dosen.components.tables.pagination', ['data' => $achievements ?? collect()])
        @endcomponent
    </div>
</div>

<!-- Include modals -->
@include('Dosen.achievements.components.show-achievements-modal')

<!-- JavaScript Variables and Setup -->
<script>
    window.achievementRoutes = {
        index: "{{ route('lecturer.achievements.index') }}",
        show: "{{ route('lecturer.achievements.show', ['id' => '__ID__']) }}"
    };
    window.csrfToken = "{{ csrf_token() }}";
    window.defaultCertificateUrl = "{{ asset('images/certificate.png') }}";

    // Function to apply sorting
    function applySorting() {
        const sortValue = document.getElementById('achievement-sort').value;
        const url = new URL(window.location.href);
        
        if (sortValue) {
            url.searchParams.set('sort', sortValue);
        } else {
            url.searchParams.delete('sort');
        }
        
        window.location.href = url.toString();
    }
</script>

<!-- Load External JS -->
@vite('resources/js/dosen/achievements.js')

@endcomponent