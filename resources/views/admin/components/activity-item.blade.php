@props(['icon', 'message', 'time'])

<div class="flex space-x-3">
    <div class="flex-shrink-0">
        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-50">
            {!! $icon !!}
        </div>
    </div>
    <div>
        <p class="text-sm text-gray-700">{!! $message !!}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $time }}</p>
    </div>
</div> 