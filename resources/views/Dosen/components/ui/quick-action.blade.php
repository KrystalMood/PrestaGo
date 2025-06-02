@props(['title' => '', 'icon' => '', 'href' => '#'])

<a href="{{ $href }}" class="bg-white flex items-center p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-100">
    {!! $icon !!}
    <span class="ml-3 text-sm font-medium text-gray-900">{{ $title }}</span>
</a> 