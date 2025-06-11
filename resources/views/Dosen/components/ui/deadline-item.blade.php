@props(['date_day' => '', 'date_month' => '', 'title' => '', 'description' => '', 'color' => 'indigo'])

<div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-0">
    <div class="flex-shrink-0">
        <div class="w-12 h-12 flex flex-col items-center justify-center rounded-lg 
            {{ $color === 'red' ? 'bg-red-100 text-red-600' : 
               ($color === 'amber' ? 'bg-amber-100 text-amber-600' : 
               'bg-blue-100 text-blue-600') }}">
            <span class="text-lg font-bold leading-none">{{ $date_day }}</span>
            <span class="text-xs font-medium">{{ $date_month }}</span>
        </div>
    </div>
    <div class="min-w-0 flex-1">
        <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
        <p class="text-xs text-gray-500">{{ $description }}</p>
    </div>
</div> 