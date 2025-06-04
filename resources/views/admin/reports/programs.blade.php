@component('layouts.admin', ['title' => 'Analisis Program Studi'])
    @push('scripts')
    @vite('resources/js/admin/reports/programs.js')
    @endpush

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Analisis Program Studi</h1>
        <p class="text-gray-600 mt-1">Analisis perbandingan dan performa antar program studi</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            @include('admin.reports.components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Program Studi',
                    'value' => count($programStats),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                    'trend' => '0%',
                    'trend_positive' => true
                ])
                
                @php
                    $totalStudents = array_sum(array_column($programStats, 'students'));
                    $totalAchievements = array_sum(array_column($programStats, 'achievements'));
                @endphp
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Total Mahasiswa',
                    'value' => $totalStudents,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                    'trend' => '+5.2%',
                    'trend_positive' => true
                ])
                
                @php
                    $avgAchievements = $totalStudents > 0 ? round(($totalAchievements / $totalStudents) * 100, 1) : 0;
                @endphp
                
                @include('admin.reports.components.summary-card', [
                    'title' => 'Rata-rata Prestasi',
                    'value' => $avgAchievements,
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>',
                    'trend' => '+8.3%',
                    'trend_positive' => true
                ])
            </div>

            <!-- Program Study Comparison -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Perbandingan Program Studi</h2>
                </div>
                <div class="p-4">
                    <div class="h-80">
                        <canvas id="program-comparison-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Performing Program Details -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Program Studi Terbaik</h2>
                </div>
                <div class="p-4">
                    @php
                    // Ambil program studi dengan prestasi tertinggi
                    $topProgram = !empty($programStats) ? $programStats[0] : null;
                    
                    if ($topProgram) {
                        // Kategori terbaik bisa ditambahkan nanti dari data sebenarnya
                        $topCategories = ['Pemrograman', 'Data Science', 'UI/UX Design'];
                        $growth = 12.5; // Nilai default, bisa diubah nanti dengan data sebenarnya
                    }
                    @endphp

                    @if($topProgram)
                    <div class="bg-indigo-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-indigo-900">{{ $topProgram['name'] }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <p class="text-sm text-gray-500">Total Prestasi</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $topProgram['achievements'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Mahasiswa</p>
                                <p class="text-2xl font-bold text-indigo-600">{{ $topProgram['students'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tingkat Partisipasi</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $topProgram['participation_rate'] }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tingkat Keberhasilan</p>
                                <p class="text-2xl font-bold text-green-600">{{ $topProgram['success_rate'] }}%</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Kategori Terbaik</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($topCategories as $category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $category }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="p-4 text-center text-gray-500">
                        Tidak ada data program studi yang tersedia.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Program Study Comparison Table -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Detail per Program Studi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table id="program-details-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Mahasiswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Prestasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partisipasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Keberhasilan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($programStats as $program)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $program['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $program['students'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $program['achievements'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $program['participation_rate'] }}%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $program['success_rate'] }}%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data program studi yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcomponent 