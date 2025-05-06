@props(['title' => null, 'linkText' => null, 'linkUrl' => null])

<div class="bg-white rounded-lg shadow-custom overflow-hidden">
    <div class="p-6">
        @if($title)
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                @if($linkText && $linkUrl)
                    <a href="{{ $linkUrl }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ $linkText }}</a>
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</div> 