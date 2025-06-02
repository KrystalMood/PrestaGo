@component('layouts.admin', ['title' => 'Detail Rekomendasi'])
    @php
        // Determine if this is for student or lecturer
        $isForStudent = $recommendation->for_lecturer ? false : true;
        $targetBadgeColor = $isForStudent ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800';
        $targetLabel = $isForStudent ? 'Rekomendasi untuk Mahasiswa' : 'Rekomendasi untuk Dosen';
        $methodLabel = $isForStudent ? '(Metode AHP)' : '(Metode WP)';
    @endphp
    
    @include('admin.components.ui.page-header', [
        'title' => $targetLabel . ' ' . $methodLabel,
        'description' => 'Informasi lengkap tentang rekomendasi kompetisi',
        'backUrl' => route('admin.recommendations.index'),
        'backText' => 'Kembali ke Daftar'
    ])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Recommendation Info -->
            @component('admin.components.cards.card', ['title' => 'Informasi Rekomendasi'])
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Jenis Rekomendasi:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $targetBadgeColor }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isForStudent ? 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' : 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' }}" />
                            </svg>
                            {{ $targetLabel }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Status:</span>
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
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Direkomendasikan oleh:</span>
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sourceColor }} ml-2">
                            {{ $sourceLabel }}
                        </span>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Tanggal Rekomendasi:</span>
                        <span class="text-sm text-gray-700 ml-2">{{ \Carbon\Carbon::parse($recommendation->created_at)->format('d M Y, H:i') }}</span>
                    </div>

                    @if($recommendation->status !== 'pending')
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tanggal Update Status:</span>
                            <span class="text-sm text-gray-700 ml-2">{{ \Carbon\Carbon::parse($recommendation->updated_at)->format('d M Y, H:i') }}</span>
                        </div>
                    @endif

                    @if($recommendation->recommendation_reason)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Alasan Rekomendasi:</span>
                            <p class="text-sm text-gray-700 mt-1">{{ $recommendation->recommendation_reason }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Hasil Perhitungan DSS</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- AHP Result -->
                            <div class="bg-gray-50 p-3 rounded-lg {{ $isForStudent ? 'ring-2 ring-indigo-200' : 'opacity-70' }}">
                                <div class="flex justify-between items-center mb-2">
                                    <h5 class="text-sm font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Analytical Hierarchy Process (AHP)
                                    </h5>
                                    
                                    @if($isForStudent)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                            Untuk Mahasiswa
                                        </span>
                                    @endif
                                </div>
                                
                                @if($recommendation->ahp_result_id)
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-xs text-gray-500">Skor Final:</span>
                                            <span class="text-sm font-medium text-indigo-700 ml-1">{{ number_format($recommendation->ahpResult->final_score * 100, 2) }}%</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-xs text-gray-500">Rasio Konsistensi:</span>
                                            <span class="text-sm font-medium {{ $recommendation->ahpResult->is_consistent ? 'text-green-600' : 'text-amber-600' }} ml-1">
                                                {{ number_format($recommendation->ahpResult->consistency_ratio, 3) }}
                                                @if($recommendation->ahpResult->is_consistent)
                                                    <span class="text-xs text-green-600">(Konsisten)</span>
                                                @else
                                                    <span class="text-xs text-amber-600">(Tidak Konsisten)</span>
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-xs text-gray-500">Dihitung pada:</span>
                                            <span class="text-xs text-gray-600 ml-1">{{ \Carbon\Carbon::parse($recommendation->ahpResult->calculated_at)->format('d M Y, H:i') }}</span>
                                        </div>
                                        
                                        @if(isset($recommendation->ahpResult->calculation_details) && is_array(json_decode($recommendation->ahpResult->calculation_details, true)))
                                            <div class="mt-2">
                                                <button class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center" 
                                                        onclick="document.getElementById('ahp-details').classList.toggle('hidden')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Lihat Detail Perhitungan
                                                </button>
                                                
                                                <div id="ahp-details" class="hidden mt-2 bg-white p-2 rounded border border-gray-200 text-xs">
                                                    <pre class="whitespace-pre-wrap text-xs text-gray-600">{{ json_encode(json_decode($recommendation->ahpResult->calculation_details), JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500 italic">Tidak ada data perhitungan AHP</div>
                                @endif
                            </div>
                            
                            <!-- WP Result -->
                            <div class="bg-gray-50 p-3 rounded-lg {{ !$isForStudent ? 'ring-2 ring-green-200' : 'opacity-70' }}">
                                <div class="flex justify-between items-center mb-2">
                                    <h5 class="text-sm font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                        Weighted Product (WP)
                                    </h5>
                                    
                                    @if(!$isForStudent)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Untuk Dosen
                                        </span>
                                    @endif
                                </div>
                                
                                @if($recommendation->wp_result_id)
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-xs text-gray-500">Vector S:</span>
                                            <span class="text-sm font-medium text-green-700 ml-1">{{ number_format($recommendation->wpResult->vector_s, 4) }}</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-xs text-gray-500">Vector V:</span>
                                            <span class="text-sm font-medium text-green-700 ml-1">{{ number_format($recommendation->wpResult->vector_v, 4) }}</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-xs text-gray-500">Preferensi Relatif:</span>
                                            <span class="text-sm font-medium text-green-700 ml-1">{{ number_format($recommendation->wpResult->relative_preference * 100, 2) }}%</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-xs text-gray-500">Peringkat:</span>
                                            <span class="text-sm font-medium text-green-700 ml-1">#{{ $recommendation->wpResult->rank }}</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-xs text-gray-500">Dihitung pada:</span>
                                            <span class="text-xs text-gray-600 ml-1">{{ \Carbon\Carbon::parse($recommendation->wpResult->calculated_at)->format('d M Y, H:i') }}</span>
                                        </div>
                                        
                                        @if(isset($recommendation->wpResult->calculation_details) && is_array(json_decode($recommendation->wpResult->calculation_details, true)))
                                            <div class="mt-2">
                                                <button class="text-xs text-green-600 hover:text-green-800 flex items-center" 
                                                        onclick="document.getElementById('wp-details').classList.toggle('hidden')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Lihat Detail Perhitungan
                                                </button>
                                                
                                                <div id="wp-details" class="hidden mt-2 bg-white p-2 rounded border border-gray-200 text-xs">
                                                    <pre class="whitespace-pre-wrap text-xs text-gray-600">{{ json_encode(json_decode($recommendation->wpResult->calculation_details), JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500 italic">Tidak ada data perhitungan WP</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($recommendation->status === 'pending')
                        <div class="flex space-x-4 border-t border-gray-200 pt-4">
                            <form method="POST" action="{{ route('admin.recommendations.update-status', $recommendation->id) }}" class="w-1/2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Terima Rekomendasi
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.recommendations.update-status', $recommendation->id) }}" class="w-1/2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Tolak Rekomendasi
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endcomponent

            <!-- Competition Info -->
            @component('admin.components.cards.card', ['title' => 'Informasi Kompetisi'])
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $recommendation->competition->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $recommendation->competition->organizer }}</p>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($recommendation->competition->level) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Pendaftaran:</span>
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($recommendation->competition->registration_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($recommendation->competition->registration_end)->format('d M Y') }}</span>
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tanggal Kompetisi:</span>
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($recommendation->competition->competition_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h4>
                        <p class="text-sm text-gray-700">{{ $recommendation->competition->description }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Persyaratan</h4>
                        <p class="text-sm text-gray-700">{{ $recommendation->competition->requirements }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Keterampilan yang Dibutuhkan</h4>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($recommendation->competition->skills as $skill)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $skill->name }} 
                                    <span class="ml-1 text-indigo-600">({{ $skill->pivot->importance_level }}/5)</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Bidang Minat</h4>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($recommendation->competition->interests as $interest)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $interest->name }} 
                                    <span class="ml-1 text-blue-600">({{ $interest->pivot->relevance_score }})</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <a href="{{ $recommendation->competition->registration_link }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Link Pendaftaran
                        </a>
                    </div>
                </div>
            @endcomponent
        </div>
        
        <div class="space-y-6">
            <!-- Student Info -->
            @component('admin.components.cards.card', ['title' => 'Informasi Mahasiswa'])
                <div class="text-center mb-4">
                    <img class="h-24 w-24 rounded-full mx-auto" src="{{ $recommendation->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($recommendation->user->name) . '&background=4338ca&color=fff&size=200' }}" alt="">
                    <h3 class="mt-2 text-lg font-medium text-gray-900">{{ $recommendation->user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $recommendation->user->nim }}</p>
                    <p class="text-sm text-gray-500">{{ $recommendation->user->programStudi->name ?? 'Program Studi tidak tersedia' }}</p>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Keterampilan</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($recommendation->user->skills as $skill)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $skill->name }} 
                                <span class="ml-1 text-blue-600">({{ $skill->pivot->proficiency_level }}/5)</span>
                            </span>
                        @empty
                            <span class="text-sm text-gray-500">Belum ada keterampilan yang ditambahkan</span>
                        @endforelse
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Minat</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($recommendation->user->interests as $interest)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $interest->name }} 
                                <span class="ml-1 text-green-600">({{ $interest->pivot->interest_level }}/5)</span>
                            </span>
                        @empty
                            <span class="text-sm text-gray-500">Belum ada minat yang ditambahkan</span>
                        @endforelse
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Prestasi</h4>
                    @if($recommendation->user->achievements && $recommendation->user->achievements->count() > 0)
                        <div class="space-y-3">
                            @foreach($recommendation->user->achievements->take(3) as $achievement)
                                <div class="bg-gray-50 p-2 rounded-md">
                                    <div class="text-sm font-medium text-gray-800">{{ $achievement->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $achievement->organizer }} • {{ \Carbon\Carbon::parse($achievement->date)->format('M Y') }}</div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $achievement->level === 'international' ? 'bg-purple-100 text-purple-800' : ($achievement->level === 'national' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($achievement->level) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($recommendation->user->achievements->count() > 3)
                                <div class="text-xs text-center text-indigo-600">
                                    + {{ $recommendation->user->achievements->count() - 3 }} prestasi lainnya
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-sm text-gray-500">Belum ada prestasi yang ditambahkan</div>
                    @endif
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <a href="{{ route('admin.users.show', $recommendation->user->id) }}" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Lihat Profil Lengkap
                    </a>
                </div>
            @endcomponent
        </div>
    </div>
@endcomponent 