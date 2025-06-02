@props(['name', 'value', 'color'])

<div>
    <div class="flex justify-between items-center mb-1">
        <span class="text-sm font-medium text-gray-700">{{ $name }}</span>
        <span class="text-sm font-medium text-gray-700">{{ $value }}%</span>
    </div>
    <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-100">
        <div style="width: {{ $value }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $color === 'indigo' ? 'bg-indigo-500' : ($color === 'green' ? 'bg-green-500' : 'bg-amber-500') }}">
        </div>
    </div>
</div> 