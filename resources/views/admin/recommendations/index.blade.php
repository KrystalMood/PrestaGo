@component('layouts.admin', ['title' => 'Rekomendasi Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @include('admin.components.ui.page-header', [
            'title' => 'Rekomendasi Kompetisi',
            'subtitle' => 'Kelola dan pantau rekomendasi kompetisi untuk mahasiswa (AHP) dan dosen (WP) menggunakan metode DSS.'
        ])

        <div class="mb-6 flex justify-between items-center">
            <div>
                <a href="{{ route('admin.recommendations.automatic') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0L7.02 9.52c-.27.98-.9 1.79-1.8 2.09L.47 13.19c-1.48.55-1.48 2.62 0 3.17l4.75 1.58c.9.3 1.53 1.1 1.8 2.09l1.49 6.35c.38 1.56 2.6 1.56 2.98 0l1.49-6.35c.27-.98.9-1.79 1.8-2.09l4.75-1.58c1.48-.55 1.48-2.62 0-3.17l-4.75-1.58c-.9-.3-1.53-1.1-1.8-2.09L11.49 3.17zM20 16.75a.75.75 0 00-1.45.32l.01.02a1.49 1.49 0 01-2.24 1.7l-.02-.01a.75.75 0 00-.6 1.36l.01.01a3 3 0 004.49-3.41l-.01-.02a.75.75 0 00-.2-.36zM14.75 4a.75.75 0 00-1.45-.32l.01-.02a1.49 1.49 0 01-2.24-1.7l-.02.01a.75.75 0 00-.6-1.36l.01-.01a3 3 0 004.49 3.41l-.01.02a.75.75 0 00-.2.36z" clip-rule="evenodd" />
                    </svg>
                    Rekomendasi Otomatis
                </a>
            </div>
            <div class="flex space-x-2">
                <x-admin.buttons.action-button
                    text="Export"
                    icon="download"
                    color="gray"
                    href="{{ route('admin.recommendations.export') }}"
                />
            </div>
        </div>

        <div class="mb-4 flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-700">Filter Target:</span>
            <button id="toggle-all-btn" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 active-filter-btn">
                Semua
            </button>
            <button id="toggle-student-btn" class="inline-flex items-center px-3 py-1.5 border border-indigo-300 rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mahasiswa (AHP)
            </button>
            <button id="toggle-lecturer-btn" class="inline-flex items-center px-3 py-1.5 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Dosen (WP)
            </button>
        </div>

        @include('admin.components.ui.search-and-filter', [
            'searchRoute' => route('admin.recommendations.index'),
            'searchPlaceholder' => 'Cari mahasiswa, dosen, atau kompetisi...',
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
                ['value' => 'ahp_consistent', 'label' => 'AHP: Konsisten'],
                ['value' => 'ahp_inconsistent', 'label' => 'AHP: Tidak Konsisten'],
                ['value' => 'wp_high', 'label' => 'WP: Preferensi Tinggi'],
                ['value' => 'for_students', 'label' => 'Untuk: Mahasiswa'],
                ['value' => 'for_lecturers', 'label' => 'Untuk: Dosen'],
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kompetisi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor AHP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor WP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direkomendasikan Oleh</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="recommendation-table-body">
                        @forelse($recommendations as $recommendation)
                            @php
                                // Determine if this is for student or lecturer
                                $isForStudent = $recommendation->for_lecturer ? false : true;
                                $targetBadgeColor = $isForStudent ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800';
                                $targetLabel = $isForStudent ? 'Mahasiswa' : 'Dosen';
                                $targetIcon = $isForStudent ? 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' : 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z';
                            @endphp
                            <tr class="hover:bg-gray-50 recommendation-row {{ $isForStudent ? 'student-recommendation' : 'lecturer-recommendation' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $targetBadgeColor }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $targetIcon }}" />
                                        </svg>
                                        {{ $targetLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $recommendation->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($recommendation->user->name) . '&background=4338ca&color=fff' }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $recommendation->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $recommendation->user->nim ?? $recommendation->user->nip }}</div>
                                            <div class="text-xs text-gray-500">{{ $recommendation->user->programStudi->name ?? $recommendation->user->department ?? '' }}</div>
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
                                    @if($recommendation->ahp_result_id)
                                        <div class="flex items-center">
                                            @php
                                                $ahpScore = $recommendation->ahpResult->final_score * 100;
                                                $ahpScoreColor = $ahpScore >= 80 ? 'text-green-600' : ($ahpScore >= 50 ? 'text-amber-600' : 'text-red-600');
                                                $ahpBgColor = $ahpScore >= 80 ? 'bg-green-100' : ($ahpScore >= 50 ? 'bg-amber-100' : 'bg-red-100');
                                            @endphp
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="{{ $ahpBgColor }} h-2.5 rounded-full" style="width: {{ $ahpScore }}%"></div>
                                            </div>
                                            <span class="{{ $ahpScoreColor }} text-sm font-medium">{{ number_format($ahpScore, 1) }}%</span>
                                        </div>
                                        @if($recommendation->ahpResult->is_consistent)
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Konsisten
                                            </span>
                                        @else
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                CR: {{ number_format($recommendation->ahpResult->consistency_ratio, 3) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($recommendation->wp_result_id)
                                        <div class="flex items-center">
                                            @php
                                                $wpScore = $recommendation->wpResult->relative_preference * 100;
                                                $wpScoreColor = $wpScore >= 80 ? 'text-green-600' : ($wpScore >= 50 ? 'text-amber-600' : 'text-red-600');
                                                $wpBgColor = $wpScore >= 80 ? 'bg-green-100' : ($wpScore >= 50 ? 'bg-amber-100' : 'bg-red-100');
                                            @endphp
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="{{ $wpBgColor }} h-2.5 rounded-full" style="width: {{ $wpScore }}%"></div>
                                            </div>
                                            <span class="{{ $wpScoreColor }} text-sm font-medium">{{ number_format($wpScore, 1) }}%</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rank: {{ $recommendation->wpResult->rank }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
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
                                        
                                        <form method="POST" action="{{ route('admin.recommendations.destroy', $recommendation->id) }}" class="inline delete-recommendation-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Rekomendasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleStudentBtn = document.getElementById('toggle-student-btn');
            const toggleLecturerBtn = document.getElementById('toggle-lecturer-btn');
            const studentRows = document.querySelectorAll('.student-recommendation');
            const lecturerRows = document.querySelectorAll('.lecturer-recommendation');
            
            let showingStudents = true;
            let showingLecturers = true;
            
            // Function to update button appearance
            function updateButtonState(button, isActive) {
                if (isActive) {
                    button.classList.add('bg-opacity-100');
                    button.classList.remove('bg-opacity-50', 'opacity-50');
                } else {
                    button.classList.remove('bg-opacity-100');
                    button.classList.add('bg-opacity-50', 'opacity-50');
                }
            }
            
            // Function to toggle student recommendations
            toggleStudentBtn.addEventListener('click', function() {
                showingStudents = !showingStudents;
                studentRows.forEach(row => {
                    row.style.display = showingStudents ? 'table-row' : 'none';
                });
                updateButtonState(toggleStudentBtn, showingStudents);
            });
            
            // Function to toggle lecturer recommendations
            toggleLecturerBtn.addEventListener('click', function() {
                showingLecturers = !showingLecturers;
                lecturerRows.forEach(row => {
                    row.style.display = showingLecturers ? 'table-row' : 'none';
                });
                updateButtonState(toggleLecturerBtn, showingLecturers);
            });
            
            // Initialize button states
            updateButtonState(toggleStudentBtn, showingStudents);
            updateButtonState(toggleLecturerBtn, showingLecturers);
            
            // Add confirmation for recommendation deletion
            const deleteForms = document.querySelectorAll('.delete-recommendation-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Apakah Anda yakin ingin menghapus rekomendasi ini?')) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endcomponent 