@props([
    'text' => 'Simpan',
    'icon' => 'save',
    'color' => 'indigo',
    'size' => 'md',
    'type' => 'submit'
])

@php
    $icons = [
        'save' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
        'refresh' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />',
        'send' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />',
    ];

    $colorClasses = [
        'indigo' => 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
        'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'yellow' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        'purple' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500',
        'pink' => 'bg-pink-600 hover:bg-pink-700 focus:ring-pink-500',
        'gray' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
    ];

    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base',
    ];

    $colorClass = $colorClasses[$color] ?? $colorClasses['indigo'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center {$sizeClass} border border-transparent font-medium rounded-md shadow-sm text-white {$colorClass} focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {!! $icons[$icon] !!}
    </svg>
    {{ $text }}
</button> 