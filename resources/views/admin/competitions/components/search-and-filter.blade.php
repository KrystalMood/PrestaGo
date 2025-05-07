@props(['categories' => collect(), 'statuses' => collect()])

<div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4 w-full">
        <!-- Search -->
        <div class="w-full md:w-1/2">
            <form action="{{ route('admin.competitions.index') }}" method="GET" id="search-form">
                <div class="form-control">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Cari nama kompetisi, penyelenggara, dll.">
                    </div>
                </div>
            </form>
        </div>

        <!-- Filters -->
        <div class="w-full md:w-1/2 grid grid-cols-2 gap-3">
            <div>
                <select id="category" name="category" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="applyFilter()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="applyFilter()">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<script>
    function applyFilter() {
        const category = document.getElementById('category').value;
        const status = document.getElementById('status').value;
        const search = document.getElementById('search').value;
        
        const url = new URL(window.location.href);
        
        if (category) url.searchParams.set('category', category);
        else url.searchParams.delete('category');
        
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        window.location.href = url.toString();
    }
    
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilter();
    });
</script> 