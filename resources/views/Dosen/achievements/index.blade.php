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
        
        $typeOptions = [
            ['value' => '', 'label' => 'Semua Jenis'],
            ['value' => 'academic', 'label' => 'Akademik'],
            ['value' => 'technology', 'label' => 'Teknologi'],
            ['value' => 'arts', 'label' => 'Seni'],
            ['value' => 'sports', 'label' => 'Olahraga'],
            ['value' => 'entrepreneurship', 'label' => 'Kewirausahaan']
        ];
    @endphp

    <!-- Combined Search, Filter, and Sort Row -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row gap-4 w-full">
            <!-- Search -->
            <div class="w-full md:w-2/4">
                <form action="{{ route('lecturer.achievements.index') }}" method="GET" id="search-form">
                    <div class="form-control">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Cari prestasi berdasarkan judul atau nama mahasiswa..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Type Filter -->
            <div class="w-full md:w-1/4">
                <select
                    id="type-filter"
                    name="type"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    onchange="applyFilters()"
                >
                    @foreach($typeOptions as $option)
                        <option value="{{ $option['value'] }}" {{ request('type') == $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Sorting Dropdown -->
            <div class="w-full md:w-1/4">
                <select
                    id="achievement-sort"
                    name="sort"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    onchange="applyFilters()"
                >
                    <option value="achievements_desc" {{ ($currentSort ?? '') == 'achievements_desc' ? 'selected' : '' }}>Total Prestasi (Tertinggi-Terendah)</option>
                    <option value="achievements_asc" {{ ($currentSort ?? '') == 'achievements_asc' ? 'selected' : '' }}>Total Prestasi (Terendah-Tertinggi)</option>
                    <option value="name_asc" {{ ($currentSort ?? '') == 'name_asc' ? 'selected' : '' }}>Nama Mahasiswa (A-Z)</option>
                    <option value="name_desc" {{ ($currentSort ?? '') == 'name_desc' ? 'selected' : '' }}>Nama Mahasiswa (Z-A)</option>
                </select>
            </div>
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

    // Function to apply filters and sorting
    function applyFilters() {
        const sortValue = document.getElementById('achievement-sort').value;
        const searchValue = document.getElementById('search').value;
        const typeValue = document.getElementById('type-filter').value;
        const url = new URL(window.location.href);
        
        if (sortValue) {
            url.searchParams.set('sort', sortValue);
        } else {
            url.searchParams.delete('sort');
        }
        
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        
        if (typeValue) {
            url.searchParams.set('type', typeValue);
        } else {
            url.searchParams.delete('type');
        }
        
        window.location.href = url.toString();
    }
    
    // Event listener for search form
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
</script>

<!-- Load External JS -->
@vite('resources/js/dosen/achievements.js')

@endcomponent