@props(['metrics' => [], 'services' => []])

<div class="bg-white rounded-lg shadow-custom p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Sistem</h3>
    
    <div class="space-y-3">
        @foreach($metrics as $metric)
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm text-gray-600">{{ $metric['name'] }}</span>
                <span class="text-sm font-medium text-gray-700">{{ $metric['value'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-{{ $metric['color'] }}-500 h-2 rounded-full" style="width: {{ $metric['value'] }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="divider my-4"></div>
    
    <div class="grid grid-cols-2 gap-2">
        @foreach($services as $service)
        <div class="bg-green-50 p-3 rounded-md">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-xs font-medium text-gray-700">{{ $service['name'] }}: {{ $service['status'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        <x-ui.button variant="secondary" class="w-full flex justify-center items-center text-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Muat Ulang Status
        </x-ui.button>
    </div>
</div>