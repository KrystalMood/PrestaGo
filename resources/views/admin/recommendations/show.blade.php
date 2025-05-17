@component('layouts.admin', ['title' => 'Detail Rekomendasi'])
    @include('admin.components.ui.page-header', [
        'title' => 'Detail Rekomendasi',
        'description' => 'Informasi lengkap tentang rekomendasi kompetisi untuk mahasiswa',
        'backUrl' => route('admin.recommendations.index'),
        'backText' => 'Kembali ke Daftar'
    ])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Recommendation Info -->
            @component('admin.components.cards.card', ['title' => 'Informasi Rekomendasi'])
                <div class="space-y-6">
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

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Skor Kecocokan</h4>
                        <div class="flex items-center">
                            @php
                                $score = $recommendation->match_score;
                                $scoreColor = $score >= 80 ? 'text-green-600' : ($score >= 50 ? 'text-amber-600' : 'text-red-600');
                                $bgColor = $score >= 80 ? 'bg-green-100' : ($score >= 50 ? 'bg-amber-100' : 'bg-red-100');
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                <div class="{{ $bgColor }} h-2.5 rounded-full" style="width: {{ $score }}%"></div>
                            </div>
                            <span class="{{ $scoreColor }} text-lg font-medium">{{ $score }}%</span>
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recommendation->competition->level === 'international' ? 'bg-purple-100 text-purple-800' : ($recommendation->competition->level === 'national' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($recommendation->competition->level) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                {{ ucfirst($recommendation->competition->type) }}
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
                    <p class="text-sm text-gray-500">{{ $recommendation->user->program_studi->name ?? 'Program Studi tidak tersedia' }}</p>
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
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Prestasi</h4>
                    @if(count($achievements) > 0)
                        <div class="space-y-3">
                            @foreach($achievements as $achievement)
                                <div class="bg-gray-50 rounded-md p-3">
                                    <h5 class="text-sm font-medium text-gray-900">{{ $achievement->title }}</h5>
                                    <p class="text-xs text-gray-500">{{ $achievement->competition_name }}</p>
                                    <div class="mt-1 flex items-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $achievement->level === 'international' ? 'bg-purple-100 text-purple-800' : ($achievement->level === 'national' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($achievement->level) }}
                                        </span>
                                        <span class="text-xs text-gray-500 ml-2">{{ \Carbon\Carbon::parse($achievement->date)->format('M Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Belum ada prestasi yang direkam</p>
                    @endif
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Pengalaman Kompetisi</h4>
                    @if(count($participations) > 0)
                        <div class="space-y-3">
                            @foreach($participations as $participation)
                                <div class="bg-gray-50 rounded-md p-3">
                                    <h5 class="text-sm font-medium text-gray-900">{{ $participation->competition->name }}</h5>
                                    <p class="text-xs text-gray-500">{{ $participation->competition->organizer }}</p>
                                    <div class="mt-1 flex items-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $participation->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ ucfirst($participation->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Belum pernah berpartisipasi dalam kompetisi</p>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.users.show', $recommendation->user->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1" />
                        </svg>
                        Lihat Profil Lengkap
                    </a>
                </div>
            @endcomponent
            
            <!-- Match Analysis -->
            @component('admin.components.cards.card', ['title' => 'Analisis Kecocokan'])
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">
                        Analisis berikut menunjukkan tingkat kecocokan antara profil mahasiswa dan kebutuhan kompetisi.
                    </p>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Keterampilan</span>
                            <span class="text-sm font-medium text-gray-700">{{ $match_factors['skills'] ?? '0' }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $match_factors['skills'] ?? '0' }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Kecocokan antara keterampilan mahasiswa dan keterampilan yang dibutuhkan untuk kompetisi
                        </p>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Prestasi Terkait</span>
                            <span class="text-sm font-medium text-gray-700">{{ $match_factors['achievements'] ?? '0' }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $match_factors['achievements'] ?? '0' }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Pengalaman/prestasi mahasiswa dalam kategori kompetisi yang sama
                        </p>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Prestasi Akademik</span>
                            <span class="text-sm font-medium text-gray-700">{{ $match_factors['academic'] ?? '0' }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-amber-600 h-2 rounded-full" style="width: {{ $match_factors['academic'] ?? '0' }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Kesesuaian prestasi akademik dengan jenis kompetisi
                        </p>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Pengalaman Kompetisi</span>
                            <span class="text-sm font-medium text-gray-700">{{ $match_factors['experience'] ?? '0' }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $match_factors['experience'] ?? '0' }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Riwayat partisipasi dalam kompetisi serupa
                        </p>
                    </div>
                    
                    <div class="pt-2 border-t border-gray-200 mt-2">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Skor Keseluruhan</span>
                            <span class="text-sm font-medium text-gray-700">{{ $recommendation->match_score }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $recommendation->match_score }}%"></div>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
@endcomponent 