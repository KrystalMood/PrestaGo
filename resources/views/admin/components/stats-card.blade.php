@props(['icon', 'title', 'value', 'trend'])

<div class="bg-white rounded-lg shadow-custom p-6 flex items-start h-32">
    <div class="flex-shrink-0 mr-4">
        {!! $icon !!}
    </div>
    <div class="flex flex-col justify-between w-full">
        <div>
            <h3 class="text-gray-500 text-sm font-medium truncate max-w-full" title="{{ $title }}">{{ $title }}</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1 truncate max-w-full" title="{{ $value }}">{{ $value }}</p>
        </div>
        <p class="text-sm text-gray-500 mt-auto line-clamp-2" title="{{ $trend }}">{{ $trend }}</p>
    </div>
</div> 