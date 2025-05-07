@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'iconBg' => 'bg-indigo-100',
    'iconColor' => 'text-indigo-600'
])

<div class="bg-white rounded-lg p-4 shadow-custom border border-gray-100">
    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">{{ $title }}</p>
    <div class="flex items-center">
        @if($icon)
            <div class="flex-shrink-0 {{ $iconBg }} {{ $iconColor }} rounded-full p-2 mr-3">
                {!! $icon !!}
            </div>
        @endif
        <p class="text-lg font-semibold text-gray-800">{{ $value }}</p>
    </div>
    @if($slot->isNotEmpty())
        <div class="mt-2 text-sm text-gray-500">
            {{ $slot }}
        </div>
    @endif
</div> 