@props([
    'searchRoute' => '#',
    'searchPlaceholder' => 'Search...',
    'filterOptions' => [],
    'filterName' => null,
    'filterLabel' => null,
    'currentFilter' => null,
    'statuses' => [],
    'levels' => [],
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

        <!-- Status Filter -->
        @if(count($statuses))
        <div class="w-full md:w-1/4">
            <select
                id="status"
                name="status"
                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                onchange="applyFilter()"
            >
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                    @if($s['value'] !== '')
                        <option value="{{ $s['value'] }}" {{ request('status') == $s['value'] ? 'selected' : '' }}>
                            {{ $s['label'] }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        @endif

        <!-- Level Filter -->
        @if(count($levels))
        <div class="w-full md:w-1/4">
            <select
                id="level"
                name="level"
                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                onchange="applyFilter()"
            >
                <option value="">Semua Level</option>
                @foreach($levels as $l)
                    @if($l['value'] !== '' && $l['label'] !== 'Semua Level')
                        <option value="{{ $l['value'] }}" {{ request('level') == $l['value'] ? 'selected' : '' }}>
                            {{ $l['label'] }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        @endif

        <!-- Generic Filter -->
        @if($filterOptions && $filterName)
        <div class="w-full md:w-1/4">
            <select
                id="{{ $filterName }}"
                name="{{ $filterName }}"
                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                onchange="applyFilter()"
            >
                <option value="">{{ $filterLabel }}</option>
                @foreach($filterOptions as $opt)
                    @if($opt['value'] !== '' && $opt['label'] !== $filterLabel)
                        <option value="{{ $opt['value'] }}" {{ $currentFilter == $opt['value'] ? 'selected' : '' }}>
                            {{ $opt['label'] }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        @endif
    </div>
</div>

<script>
    function applyFilter() {
        const status = document.getElementById('status') ? document.getElementById('status').value : null;
        const level = document.getElementById('level') ? document.getElementById('level').value : null;
        const search = document.getElementById('search') ? document.getElementById('search').value : null;
        const filterName = "{{ $filterName }}";
        const filterElement = filterName ? document.getElementById(filterName) : null;
        const genericFilter = filterElement ? filterElement.value : null;
        
        const url = new URL(window.location.href);
        
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        
        if (status !== null) {
            if (status !== '') {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
        }
        
        if (level !== null) {
            if (level !== '') {
                url.searchParams.set('level', level);
            } else {
                url.searchParams.delete('level');
            }
        }
        
        if (filterName && genericFilter !== null) {
            if (genericFilter !== '') {
                url.searchParams.set(filterName, genericFilter);
            } else {
                url.searchParams.delete(filterName);
            }
        }
        
        window.location.href = url.toString();
    }
    
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilter();
    });
</script> 