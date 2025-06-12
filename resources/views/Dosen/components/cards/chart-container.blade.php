@props(['title' => null])

<div class="bg-white shadow-custom rounded-lg p-6">
    @if($title)
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
    @endif
    
    <div class="w-full">
        {{ $slot }}
    </div>
</div> 