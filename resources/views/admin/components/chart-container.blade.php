@props(['title', 'height' => '64'])

<div class="bg-white rounded-lg shadow-custom p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h2>
    <div class="h-{{ $height }}">
        
        <div class="w-full h-full flex items-center justify-center bg-gray-50 rounded-lg">
            {{ $slot }}
        </div>
    </div>
</div> 