@props(['title', 'linkText' => null, 'linkUrl' => null])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">{{ $title }}</h2>
        @if($linkText && $linkUrl)
            <a href="{{ $linkUrl }}" class="text-sm text-brand-light hover:underline">{{ $linkText }}</a>
        @endif
    </div>
    
    {{ $slot }}
</div> 