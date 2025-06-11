@props(['title' => '', 'subtitle' => null, 'showBackButton' => false, 'backRoute' => null, 'actionText' => null, 'actionRoute' => null, 'actionIcon' => null])

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        @if($showBackButton)
            <a href="{{ $backRoute ?? url()->previous() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        @endif
        
        @if($actionText && $actionRoute)
            <a href="{{ $actionRoute }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                @if($actionIcon)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        @if($actionIcon == 'plus')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        @elseif($actionIcon == 'user-plus')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 017.75 3.97M18 21H6a2 2 0 01-2-2v-1a6 6 0 0112 0v1a2 2 0 01-2 2z" />
                        @endif
                    </svg>
                @endif
                {{ $actionText }}
            </a>
        @endif
    </div>
</div> 