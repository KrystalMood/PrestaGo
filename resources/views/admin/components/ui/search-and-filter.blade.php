@props([
    'searchRoute' => '#',
    'searchPlaceholder' => 'Cari...',
    'filterOptions' => [],
    'filterName' => 'filter',
    'filterLabel' => 'Filter',
    'currentFilter' => ''
])

<div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4 w-full">
        <!-- Search -->
        <div class="w-full md:w-2/3">
            <form action="{{ $searchRoute }}" method="GET" id="search-form">
                <div class="form-control">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="{{ $searchPlaceholder }}">
                    </div>
                </div>
            </form>
        </div>

        <!-- Filter -->
        @if(count($filterOptions) > 0)
            <div class="w-full md:w-1/3">
                <form action="{{ $searchRoute }}" method="GET" id="filter-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="form-control">
                        <select name="{{ $filterName }}" id="filter-select" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md transition-colors" onchange="document.getElementById('filter-form').submit()">
                            <option value="">{{ $filterLabel }}</option>
                            @foreach($filterOptions as $option)
                                <option value="{{ $option['value'] }}" {{ $currentFilter == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('search-input').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('search-form').submit();
            }
        });
        
        const filterSelect = document.getElementById('filter-select');
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        }
    });
</script> 