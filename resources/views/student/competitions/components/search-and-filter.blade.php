@props([
    'searchRoute' => '#',
    'searchPlaceholder' => 'Cari kompetisi...',
    'categories' => [],
    'currentFilter' => null,
    'currentSort' => null,
])

<div class="mb-6">
    <div class="flex flex-col md:flex-row gap-4 w-full">
        <!-- Search -->
        <div class="w-full md:w-3/4">
            <form action="{{ $searchRoute }}" method="GET" id="search-form">
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
                            placeholder="{{ $searchPlaceholder }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                </div>
            </form>
        </div>

        <!-- Category Filter -->
        <div class="w-full md:w-1/4">
            <select
                id="category"
                name="category"
                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                onchange="applyFilter()"
            >
                <option value="">Pilih Kategori Sub Lomba</option>
                @foreach($categories as $category)
                    @if($category && $category->id)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        
        <!-- Sort Filter -->
        <div class="w-full md:w-1/4">
            <select
                id="sort"
                name="sort"
                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                onchange="applyFilter()"
            >
                <option value="">Urut Berdasarkan</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Tanggal (Terlama)</option>
                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Tanggal (Terbaru)</option>
                <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>Batas Akhir (Terdekat)</option>
                <option value="deadline_desc" {{ request('sort') == 'deadline_desc' ? 'selected' : '' }}>Batas Akhir (Terjauh)</option>
            </select>
        </div>
    </div>
</div>

<script>
    function applyFilter() {
        const category = document.getElementById('category') ? document.getElementById('category').value : null;
        const search = document.getElementById('search') ? document.getElementById('search').value : null;
        const sort = document.getElementById('sort') ? document.getElementById('sort').value : null;
        
        const url = new URL(window.location.href);
        
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (category) url.searchParams.set('category', category);
        else url.searchParams.delete('category');
        
        if (sort) url.searchParams.set('sort', sort);
        else url.searchParams.delete('sort');
        
        window.location.href = url.toString();
    }
    
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilter();
    });
</script>
</script> 