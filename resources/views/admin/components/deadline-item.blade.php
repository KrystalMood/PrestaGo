@props(['date_day', 'date_month', 'title', 'description', 'color'])

<div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50">
    <div class="flex-shrink-0">
        <div class="flex flex-col items-center justify-center h-12 w-12 rounded-lg {{ $color === 'red' ? 'bg-red-100 text-red-800' : ($color === 'amber' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
            <span class="text-lg font-bold leading-none">{{ $date_day }}</span>
            <span class="text-xs">{{ $date_month }}</span>
        </div>
    </div>
    <div>
        <h3 class="font-medium text-gray-800">{{ $title }}</h3>
        <p class="text-xs text-gray-500">{{ $description }}</p>
    </div>
</div> 