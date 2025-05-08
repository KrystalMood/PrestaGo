<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 {{ $period->is_active ? 'bg-green-50 border-b border-green-100' : 'bg-gray-50 border-b border-gray-100' }}">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $period->is_active ? 'text-green-500' : 'text-gray-400' }} mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-sm font-medium {{ $period->is_active ? 'text-green-800' : 'text-gray-800' }}">{{ $period->name }}</h3>
            </div>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $period->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $period->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
        </div>
    </div>
    <div class="px-4 py-3">
        <div class="flex justify-between text-sm text-gray-600 mb-2">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Mulai: {{ $period->start_date->format('d M Y') }}</span>
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Selesai: {{ $period->end_date->format('d M Y') }}</span>
            </div>
        </div>
        
        @if(isset($showCompetitions) && $showCompetitions)
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                <span>{{ $period->competitions->count() }} Kompetisi</span>
            </div>
        @endif
    </div>
    <div class="px-4 py-3 bg-gray-50 text-right border-t border-gray-100 flex justify-end space-x-2">
        <a href="{{ route('admin.periods.show', $period->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
            Detail
        </a>
        <a href="{{ route('admin.periods.edit', $period->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
            Edit
        </a>
    </div>
</div> 