@props(['deadlines' => []])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Batas Waktu Mendatang</h3>
    </div>
    
    <div class="space-y-3">
        @foreach($deadlines as $deadline)
        <div class="flex gap-3 items-center">
            <div class="bg-{{ $deadline['color'] }}-100 p-2 rounded-md flex items-center justify-center">
                <span class="text-sm font-bold text-{{ $deadline['color'] }}-700">{{ $deadline['date_day'] }}<br>{{ $deadline['date_month'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $deadline['title'] }}</p>
                <p class="text-xs text-gray-500">{{ $deadline['description'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center justify-center">
            Lihat Kalender Lengkap
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>