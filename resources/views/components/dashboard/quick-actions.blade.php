@props(['actions' => []])

<div class="bg-white rounded-lg shadow-custom p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        @foreach($actions as $action)
        <!-- {{ $action['title'] }} -->
        <a href="{{ $action['href'] ?? '#' }}" class="flex flex-col items-center p-4 bg-{{ $action['color'] ?? 'gray' }}-50 hover:bg-{{ $action['color'] ?? 'gray' }}-100 rounded-lg transition duration-200">
            <div class="rounded-full bg-{{ $action['color'] ?? 'gray' }}-100 p-3 mb-2">
                {!! $action['icon'] !!}
            </div>
            <span class="text-sm font-medium text-gray-700">{{ $action['title'] }}</span>
        </a>
        @endforeach
    </div>
</div>