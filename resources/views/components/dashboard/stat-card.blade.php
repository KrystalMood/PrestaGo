@props([
    'icon' => '',
    'title' => '',
    'value' => '',
    'trend' => '',
    'color' => 'indigo'
])

<div class="stats shadow-custom bg-white">
    <div class="stat">
        <div class="stat-figure text-{{ $color }}-600">
            {!! $icon !!}
        </div>
        <div class="stat-title text-gray-600">{{ $title }}</div>
        <div class="stat-value text-{{ $color }}-600">{{ $value }}</div>
        @if ($trend)
            <div class="stat-desc">{{ $trend }}</div>
        @endif
    </div>
</div>