@props(['stats' => []])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @foreach ($stats as $stat)
        <div class="bg-white rounded-lg shadow-custom p-5 flex items-center">
            @php
                $iconColor = match($stat['icon']) {
                    'user' => 'text-blue-500 bg-blue-100',
                    'user-plus' => 'text-green-500 bg-green-100',
                    'user-check' => 'text-purple-500 bg-purple-100',
                    default => 'text-gray-500 bg-gray-100'
                };
            @endphp
            
            <div class="flex-shrink-0 {{ $iconColor }} rounded-full p-3 mr-4">
                @if($stat['icon'] === 'user')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @elseif($stat['icon'] === 'user-plus')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                @elseif($stat['icon'] === 'user-check')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                @endif
            </div>
            
            <div>
                <div class="text-sm text-gray-500 font-medium">{{ $stat['title'] }}</div>
                <div class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</div>
            </div>
        </div>
    @endforeach
</div>
