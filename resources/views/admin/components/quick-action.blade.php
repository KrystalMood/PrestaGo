@props(['title', 'icon', 'href'])

<a href="{{ $href }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
    <div class="mb-2">
        {!! $icon !!}
    </div>
    <p class="text-sm font-medium text-gray-700">{{ $title }}</p>
</a> 