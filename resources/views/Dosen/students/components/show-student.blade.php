<!-- filepath: /mnt/windows/Kuliah/WEB LANJUT/aa/B/PBL2/PrestaGo/resources/views/Dosen/students/components/show-student.blade.php -->
@component('layouts.dosen', ['title' => 'Detail Mahasiswa Bimbingan'])

<div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
    <div class="mb-6">
        <!-- Back Button -->
        <div class="mb-5">
            <a href="{{ route('lecturer.students.index') }}" class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Mahasiswa
            </a>
        </div>

        <!-- Header -->
        <div class="space-y-2">
            <h2 class="text-2xl font-bold text-gray-900">Detail Persetujuan Dosen</h2>
            <p class="text-gray-600">Informasi lengkap mahasiswa dan tim yang membutuhkan persetujuan</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Team Information Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-100">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Informasi Tim</h3>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Nama Tim:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $team->name ?? '-' }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Kompetisi:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $competition->name ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Status Admin:</span>
                    @php
                        $statusClass = match($adminStatus) {
                            'accepted', 'Disetujui' => 'bg-green-100 text-green-800 border-green-200',
                            'rejected', 'Ditolak' => 'bg-red-100 text-red-800 border-red-200',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                        };
                        $statusText = match($adminStatus) {
                            'accepted' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'pending' => 'Menunggu',
                            default => $adminStatus ?? 'Menunggu'
                        };
                    @endphp
                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Team Members Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border border-green-100">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Daftar Peserta</h3>
            </div>

            <div class="overflow-hidden">
                <div class="space-y-3">
                    @forelse($team->members as $member)
                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                                                @if($member['photo'] && file_exists(public_path('storage/' . $member['photo'])))
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $member['photo']) }}" alt="{{ $user->name }}" loading="lazy">
                        @else
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member['name']) }}&background=4338ca&color=fff&size=150" alt="{{ $member['name'] }}" loading="lazy">
                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $member['name'] }}</p>
                                        <p class="text-xs text-gray-600">{{ $member['nim'] }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $member['role'] == 'Ketua' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $member['role'] }}
                                </span>
                            </div>
                        </div>
                        @forelse($member['team_member'] as $teamMember)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $teamMember['name'] }}</p>
                                            <p class="text-xs text-gray-600">{{ $teamMember['nim'] }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $member['role'] == 'Ketua' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        Anggota
                                    </span>
                                </div>
                            </div>
                            @empty
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500 text-sm">Tidak ada peserta</p>
                        </div>
                        @endforelse
                    @empty
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500 text-sm">Tidak ada peserta</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Aksi Persetujuan</h4>
        <p class="text-sm text-gray-600 mb-6">Pilih tindakan yang akan dilakukan terhadap permintaan bimbingan ini.</p>
        
        @if(request()->route()->getName() == 'lecturer.students.details')
            <form action="{{ route('lecturer.students.approve',$id ) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <button type="submit" name="approval" value="accept" class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Terima Bimbingan
                </button>
                
                <button type="submit" name="approval" value="reject" class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-red-300 text-base font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak Bimbingan
                </button>
            </form>
        @else
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('lecturer.students.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                
                <form action="{{ route('lecturer.students.destroy', $id) }}?jenis={{ request()->query('jenis') }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-red-300 text-base font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Data Mahasiswa
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@endcomponent