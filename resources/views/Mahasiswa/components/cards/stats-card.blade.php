@props(['icon' => '', 'title' => '', 'value' => '', 'trend' => ''])

<div class="bg-white rounded-lg shadow-custom p-5 flex items-center">
    @php
        $iconColor = match(true) {
            str_contains($icon, 'users') => 'text-blue-500 bg-blue-100',
            str_contains($icon, 'document') => 'text-green-500 bg-green-100',
            str_contains($icon, 'academic-cap') => 'text-indigo-600 bg-indigo-100',
            str_contains($icon, 'check') => 'text-green-500 bg-green-100',
            str_contains($icon, 'x-circle') => 'text-red-500 bg-red-100',
            str_contains($icon, 'ticket') => 'text-purple-500 bg-purple-100',
            default => 'text-gray-500 bg-gray-100'
        };
    @endphp
    
    <div class="flex-shrink-0 {{ $iconColor }} rounded-full p-3 mr-4">
        {!! $icon !!}
    </div>
    
    <div>
        <div class="text-sm text-gray-500 font-medium">{{ $title }}</div>
        <div class="text-2xl font-bold text-gray-800">{{ $value }}</div>
        @if($trend)
            <div class="text-xs text-gray-500 mt-1">{{ $trend }}</div>
        @endif
    </div>
</div> 