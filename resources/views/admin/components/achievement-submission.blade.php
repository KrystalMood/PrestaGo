@props(['name', 'class', 'avatar', 'achievement', 'date', 'status'])

<div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50">
    <div class="flex items-center">
        <img src="{{ $avatar }}" alt="{{ $name }}" class="w-10 h-10 rounded-full mr-3">
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-800">{{ $name }}</h3>
                <span class="text-xs text-gray-500">{{ $date }}</span>
            </div>
            <p class="text-xs text-gray-500">{{ $class }}</p>
            <p class="text-sm mt-1">{{ $achievement }}</p>
            <div class="mt-2">
                @if ($status === 'Menunggu')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $status }}
                    </span>
                @elseif ($status === 'Disetujui')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $status }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $status }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div> 