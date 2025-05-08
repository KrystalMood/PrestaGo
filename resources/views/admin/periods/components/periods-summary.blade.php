@php
$activePeriod = \App\Models\PeriodModel::where('is_active', true)->first();
$upcomingPeriods = \App\Models\PeriodModel::where('start_date', '>', now())
    ->where('is_active', false)
    ->orderBy('start_date', 'asc')
    ->limit(2)
    ->get();
@endphp

<div class="bg-white rounded-lg shadow-custom p-5">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Periode Semester</h3>
        <a href="{{ route('admin.periods.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
    </div>
    
    @if($activePeriod)
        <div class="mb-4 p-4 border border-green-200 rounded-lg bg-green-50">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Aktif</span>
                    <h4 class="text-base font-medium text-gray-800 mt-1">{{ $activePeriod->name }}</h4>
                    <div class="mt-1 flex items-center text-sm text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $activePeriod->start_date->format('d M Y') }} - {{ $activePeriod->end_date->format('d M Y') }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.periods.show', $activePeriod->id) }}" class="text-indigo-600 hover:text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
            </div>
            
            @php
            $daysPassed = now()->diffInDays($activePeriod->start_date);
            $totalDays = $activePeriod->end_date->diffInDays($activePeriod->start_date);
            $progress = $totalDays > 0 ? min(100, max(0, (($daysPassed / $totalDays) * 100))) : 0;
            @endphp
            
            <div class="mt-3">
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>Progres</span>
                    <span>{{ round($progress) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>
    @else
        <div class="mb-4 p-4 border border-yellow-200 rounded-lg bg-yellow-50">
            <p class="text-sm text-yellow-800">Tidak ada periode aktif saat ini.</p>
            <a href="{{ route('admin.periods.create') }}" class="mt-2 inline-flex items-center text-sm font-medium text-yellow-800 hover:text-yellow-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Periode Baru
            </a>
        </div>
    @endif
    
    @if($upcomingPeriods->count() > 0)
        <h4 class="text-sm font-medium text-gray-700 mb-2">Periode Mendatang</h4>
        <div class="space-y-3">
            @foreach($upcomingPeriods as $period)
                <div class="p-3 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="text-sm font-medium text-gray-800">{{ $period->name }}</h5>
                            <div class="mt-1 flex items-center text-xs text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Mulai: {{ $period->start_date->format('d M Y') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.periods.show', $period->id) }}" class="text-indigo-600 hover:text-indigo-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-3">
            <p class="text-sm text-gray-500">Tidak ada periode mendatang.</p>
        </div>
    @endif
    
    <div class="mt-4 pt-4 border-t border-gray-100">
        <a href="{{ route('admin.periods.create') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Periode Baru
        </a>
    </div>
</div> 