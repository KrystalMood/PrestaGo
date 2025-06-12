@props(['activities' => []])

<div class="bg-white rounded-lg shadow-custom p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
    
    <div class="space-y-4">
        @foreach($activities as $activity)
        <div class="flex gap-3">
            <div class="avatar">
                <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] ?? 'gray' }}-100 inline-flex items-center justify-center">
                    {!! $activity['icon'] !!}
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-800">{!! $activity['message'] !!}</p>
                <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4 text-center">
        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua Aktivitas</a>
    </div>
</div>