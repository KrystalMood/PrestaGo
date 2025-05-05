@props(['stats'])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    @foreach($stats as $stat)
    <div class="bg-white p-6 rounded-lg shadow-custom border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ $stat['title'] }}</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</h3>
            </div>
            <div class="bg-brand-light bg-opacity-10 w-12 h-12 rounded-lg flex items-center justify-center">
                @if($stat['icon'] == 'user')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                @elseif($stat['icon'] == 'user-plus')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                @elseif($stat['icon'] == 'user-check')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
