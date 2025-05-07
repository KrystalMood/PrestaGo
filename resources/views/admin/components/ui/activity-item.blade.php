@props(['icon' => '', 'message' => '', 'time' => ''])

<div class="flex items-start space-x-3 py-3 border-b border-gray-100 last:border-0">
    <div class="flex-shrink-0">
        {!! $icon !!}
    </div>
    <div class="min-w-0 flex-1">
        <div class="text-sm text-gray-700">{!! $message !!}</div>
        <div class="mt-1 text-xs text-gray-500">{{ $time }}</div>
    </div>
</div> 