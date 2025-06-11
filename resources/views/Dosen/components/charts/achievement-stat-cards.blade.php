@props(['achievementStats'])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Total Achievements Card -->
    <div class="bg-white rounded-lg shadow-custom p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Prestasi</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $achievementStats['summary']['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    @if($achievementStats['summary']['growth']['trend'] == 'up')
                        <span class="text-green-600">↗︎ {{ $achievementStats['summary']['growth']['percentage'] }}%</span>
                    @else
                        <span class="text-red-600">↘︎ {{ abs($achievementStats['summary']['growth']['percentage']) }}%</span>
                    @endif
                    dibanding 3 bulan lalu
                </p>
            </div>
        </div>
    </div>

    <!-- International Achievements Card -->
    <div class="bg-white rounded-lg shadow-custom p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Prestasi Internasional</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $achievementStats['summary']['international'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    @php
                        $internationalPercentage = $achievementStats['summary']['total'] > 0 
                            ? round(($achievementStats['summary']['international'] / $achievementStats['summary']['total']) * 100) 
                            : 0;
                    @endphp
                    {{ $internationalPercentage }}% dari total prestasi
                </p>
            </div>
        </div>
    </div>

    <!-- National Achievements Card -->
    <div class="bg-white rounded-lg shadow-custom p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Prestasi Nasional</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $achievementStats['summary']['national'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    @php
                        $nationalPercentage = $achievementStats['summary']['total'] > 0 
                            ? round(($achievementStats['summary']['national'] / $achievementStats['summary']['total']) * 100) 
                            : 0;
                    @endphp
                    {{ $nationalPercentage }}% dari total prestasi
                </p>
            </div>
        </div>
    </div>
</div>
