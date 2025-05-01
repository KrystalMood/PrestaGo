@props(['type' => 'button', 'variant' => 'primary', 'icon' => null])

@php
    $variantClasses = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'accent' => 'btn-accent',
        'ghost' => 'btn-ghost',
        'link' => 'btn-link',
    ][$variant] ?? 'btn-primary';
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "btn $variantClasses"]) }}
>
    @if($icon)
        <span class="mr-2">{!! $icon !!}</span>
    @endif
    {{ $slot }}
</button>