@props(['stats' => [], 'columns' => 3])

<div class="grid grid-cols-1 md:grid-cols-{{ $columns }} gap-4 mb-6">
    @foreach($stats as $stat)
    @php
        // Define different colors for each stat card based on the icon
        $bgColor = 'bg-indigo-100';
        $textColor = 'text-indigo-600';
        
        if ($stat['icon'] == 'trophy') {
            $bgColor = 'bg-purple-100';
            $textColor = 'text-purple-600';
        } elseif ($stat['icon'] == 'star') {
            $bgColor = 'bg-amber-100';
            $textColor = 'text-amber-600';
        } elseif ($stat['icon'] == 'check-circle') {
            $bgColor = 'bg-emerald-100';
            $textColor = 'text-emerald-600';
        } elseif ($stat['icon'] == 'badge-check') {
            $bgColor = 'bg-blue-100';
            $textColor = 'text-blue-600';
        }
    @endphp
    <div class="bg-white rounded-lg shadow-custom p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-full {{ $bgColor }} mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $textColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($stat['icon'] == 'trophy')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    @elseif($stat['icon'] == 'star')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    @elseif($stat['icon'] == 'check-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @elseif($stat['icon'] == 'badge-check')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    @endif
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">{{ $stat['title'] }}</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $stat['value'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>