@props(['icon', 'title', 'value', 'trend'])

<div class="bg-white rounded-lg shadow-custom p-6 flex items-start">
    <div class="flex-shrink-0 mr-4">
        {!! $icon !!}
    </div>
    <div>
        <h3 class="text-gray-500 text-sm font-medium">{{ $title }}</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $value }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ $trend }}</p>
    </div>
</div> 