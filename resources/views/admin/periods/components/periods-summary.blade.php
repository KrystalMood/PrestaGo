@php
    // Get the current period (whose date range includes today)
    $currentPeriod = \App\Models\PeriodModel::where('start_date', '<=', now())
                      ->where('end_date', '>=', now())
                      ->first();
    
    // Get upcoming periods (start date in the future)
    $upcomingPeriods = \App\Models\PeriodModel::where('start_date', '>', now())
                        ->orderBy('start_date', 'asc')
                        ->take(2)
                        ->get();
@endphp

@component('admin.components.cards.card', ['title' => 'Periode Akademik'])
    <div class="space-y-4">
        <div>
            <h3 class="text-sm font-medium text-gray-500">PERIODE SAAT INI</h3>
            @if($currentPeriod)
                <div class="mt-2 p-3 bg-green-50 border border-green-100 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-base font-medium text-gray-900">{{ $currentPeriod->name }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($currentPeriod->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($currentPeriod->end_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Saat Ini
                        </span>
                    </div>
                </div>
            @else
                <div class="mt-2 p-3 bg-gray-50 border border-gray-100 rounded-lg">
                    <p class="text-sm text-gray-500">Tidak ada periode saat ini</p>
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500">PERIODE MENDATANG</h3>
            <div class="mt-2 space-y-2">
                @forelse($upcomingPeriods as $period)
                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-base font-medium text-gray-900">{{ $period->name }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ \Carbon\Carbon::parse($period->start_date)->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-3 bg-gray-50 border border-gray-100 rounded-lg">
                        <p class="text-sm text-gray-500">Tidak ada periode mendatang yang terjadwal</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <div class="pt-2 mt-2 border-t border-gray-100">
            <a href="{{ route('admin.periods.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                Lihat semua periode
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
@endcomponent 