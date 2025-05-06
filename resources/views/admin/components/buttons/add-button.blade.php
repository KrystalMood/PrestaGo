@props([
    'route' => '#',
    'text' => 'Tambah',
    'icon' => 'plus'
])

@php
    $icons = [
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
        'user-plus' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 0 011-8 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />'
    ];
@endphp

<a href="{{ $route }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {!! $icons[$icon] !!}
    </svg>
    {{ $text }}
</a> 