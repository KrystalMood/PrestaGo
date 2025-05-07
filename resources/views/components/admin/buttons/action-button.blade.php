@props([
    'route' => '#',
    'text' => '',
    'icon' => 'view',
    'color' => 'indigo',
    'size' => 'md',
    'iconOnly' => false
])

@php
    $icons = [
        'view' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />',
        'edit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />',
        'delete' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />',
        'download' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />',
        'upload' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
        'x' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
        'refresh' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />',
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

<a href="{{ $route }}" {{ $attributes->merge(['class' => "inline-flex items-center {$sizeClass} border border-transparent font-medium rounded-md shadow-sm text-white {$colorClass} focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconOnly ? 'h-5 w-5' : 'h-4 w-4 mr-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {!! $icons[$icon] !!}
    </svg>
    @if(!$iconOnly)
        {{ $text }}
    @endif
</a> 