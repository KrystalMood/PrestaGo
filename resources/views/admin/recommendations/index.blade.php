@component('layouts.admin', ['title' => 'Rekomendasi Kompetisi'])
    @include('admin.components.ui.page-header', [
        'title' => 'Rekomendasi Kompetisi',
        'description' => 'Kelola dan pantau rekomendasi kompetisi untuk mahasiswa berdasarkan prestasi, keterampilan, dan profil mereka.'
    ])

    <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-2">
            <a href="{{ route('admin.recommendations.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                Semua Rekomendasi
            </a>
            <a href="{{ route('admin.recommendations.automatic') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                Rekomendasi Otomatis
            </a>
        </div>
        <div>
            <a href="{{ route('admin.recommendations.export') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
            </a>
        </div>
    </div>

    @include('admin.components.ui.search-and-filter', [
        'searchRoute' => route('admin.recommendations.index'),
        'searchPlaceholder' => 'Cari mahasiswa atau kompetisi...',
        'filterOptions' => [
            ['value' => 'pending', 'label' => 'Status: Menunggu'],
            ['value' => 'accepted', 'label' => 'Status: Diterima'],
            ['value' => 'rejected', 'label' => 'Status: Ditolak'],
            ['value' => 'system', 'label' => 'Sumber: Sistem'],
            ['value' => 'lecturer', 'label' => 'Sumber: Dosen'],
            ['value' => 'admin', 'label' => 'Sumber: Admin'],
            ['value' => 'high_match', 'label' => 'Skor: Tinggi (>80%)'],
            ['value' => 'medium_match', 'label' => 'Skor: Sedang (50-80%)'],
            ['value' => 'low_match', 'label' => 'Skor: Rendah (<50%)'],
        ],
        'filterName' => 'filter',
        'filterLabel' => 'Filter Rekomendasi',
        'currentFilter' => request('filter', '')
    ])

    @component('admin.components.cards.card')
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kompetisi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Kecocokan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direkomendasikan Oleh</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recommendations as $recommendation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $recommendation->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($recommendation->user->name) . '&background=4338ca&color=fff' }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $recommendation->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $recommendation->user->nim }}</div>
                                        <div class="text-xs text-gray-500">{{ $recommendation->user->program_studi->name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $recommendation->competition->name }}</div>
                                <div class="text-xs text-gray-500">{{ $recommendation->competition->organizer }}</div>
                                <div class="text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recommendation->competition->level === 'international' ? 'bg-purple-100 text-purple-800' : ($recommendation->competition->level === 'national' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($recommendation->competition->level) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $score = $recommendation->match_score;
                                        $scoreColor = $score >= 80 ? 'text-green-600' : ($score >= 50 ? 'text-amber-600' : 'text-red-600');
                                        $bgColor = $score >= 80 ? 'bg-green-100' : ($score >= 50 ? 'bg-amber-100' : 'bg-red-100');
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                        <div class="{{ $bgColor }} h-2.5 rounded-full" style="width: {{ $score }}%"></div>
                                    </div>
                                    <span class="{{ $scoreColor }} text-sm font-medium">{{ $score }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'accepted' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak',
                                    ];
                                    $statusColor = $statusColors[$recommendation->status] ?? 'bg-gray-100 text-gray-800';
                                    $statusLabel = $statusLabels[$recommendation->status] ?? ucfirst($recommendation->status);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $sourceColors = [
                                        'system' => 'bg-blue-100 text-blue-800',
                                        'lecturer' => 'bg-purple-100 text-purple-800',
                                        'admin' => 'bg-indigo-100 text-indigo-800',
                                    ];
                                    $sourceLabels = [
                                        'system' => 'Sistem',
                                        'lecturer' => 'Dosen',
                                        'admin' => 'Admin',
                                    ];
                                    $sourceColor = $sourceColors[$recommendation->recommended_by] ?? 'bg-gray-100 text-gray-800';
                                    $sourceLabel = $sourceLabels[$recommendation->recommended_by] ?? ucfirst($recommendation->recommended_by);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sourceColor }}">
                                    {{ $sourceLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($recommendation->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @if($recommendation->status === 'pending')
                                        <form method="POST" action="{{ route('admin.recommendations.update-status', $recommendation->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Terima Rekomendasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.recommendations.update-status', $recommendation->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Tolak Rekomendasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.recommendations.show', $recommendation->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data rekomendasi yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $recommendations->links() }}
        </div>
    @endcomponent
@endcomponent 