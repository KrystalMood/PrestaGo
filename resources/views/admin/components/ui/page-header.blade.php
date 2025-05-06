@props(['subtitle' => null, 'actionText' => null, 'actionUrl' => null])

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <div>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($actionText && $actionUrl)
        <div class="mt-4 sm:mt-0">
            <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ $actionText }}
            </a>
        </div>
    @endif
</div> 