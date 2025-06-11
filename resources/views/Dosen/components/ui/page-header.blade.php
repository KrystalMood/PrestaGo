@props(['subtitle' => null, 'actionText' => null, 'actionUrl' => null, 'title' => null])

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <div>
        @if(isset($slot) && !empty($slot))
            <h1 class="text-2xl font-bold text-gray-800">{{ $slot }}</h1>
        @elseif($title)
            <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($actionText && $actionUrl)
        <div class="mt-4 sm:mt-0">
            <x-ui.button 
                href="{{ $actionUrl }}" 
                variant="primary"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ $actionText }}
            </x-ui.button>
        </div>
    @endif
</div> 