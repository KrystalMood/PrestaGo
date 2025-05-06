@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
    'disabled' => false,
    'tag' => 'button',
    'href' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center border font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';
    
    $variantClasses = [
        'primary' => 'border-transparent bg-brand hover:bg-brand-dark text-white focus:ring-brand',
        'secondary' => 'border-gray-300 bg-white hover:bg-gray-50 text-gray-700 focus:ring-brand',
        'danger' => 'border-transparent bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success' => 'border-transparent bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'warning' => 'border-transparent bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
        'info' => 'border-transparent bg-blue-500 hover:bg-blue-600 text-white focus:ring-blue-500',
        'ghost' => 'border-transparent bg-transparent hover:bg-gray-100 text-gray-700 focus:ring-brand',
    ][$variant] ?? 'border-transparent bg-brand hover:bg-brand-dark text-white focus:ring-brand';
    
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-base',
    ][$size] ?? 'px-4 py-2 text-sm';
    
    $widthClasses = $fullWidth ? 'w-full' : '';
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    
    $classes = "$baseClasses $variantClasses $sizeClasses $widthClasses $disabledClasses";
@endphp

@if($tag === 'a')
    <a 
        href="{{ $href ?? '#' }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon && $iconPosition === 'left')
            <span class="mr-2">{!! $icon !!}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="ml-2">{!! $icon !!}</span>
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) disabled @endif
    >
        @if($icon && $iconPosition === 'left')
            <span class="mr-2">{!! $icon !!}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="ml-2">{!! $icon !!}</span>
        @endif
    </button>
@endif