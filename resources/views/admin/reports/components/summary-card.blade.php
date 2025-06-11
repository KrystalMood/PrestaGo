<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            {!! $icon !!}
        </div>
        <div class="ml-5 w-0 flex-1">
            <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                <dd>
                    <div class="text-lg font-semibold text-gray-900">{{ $value }}</div>
                </dd>
            </dl>
        </div>
    </div>
    <div class="mt-4">
        <div class="flex items-center">
            <span class="flex items-center text-sm font-medium {{ $trend_positive ? 'text-green-600' : 'text-red-600' }}">
                @if($trend_positive)
                <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                @else
                <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                @endif
                <span class="ml-1">{{ $trend }}</span>
            </span>
            <span class="text-sm text-gray-500 ml-2">dari periode sebelumnya</span>
        </div>
    </div>
</div> 