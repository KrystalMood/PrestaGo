@component('layouts.admin', ['title' => 'Detail Peserta: ' . $participant->user->name])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Peserta</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $subCompetition->name }} - {{ $competition->name }}</p>
                <div class="mt-2">
                    <a href="{{ route('admin.competitions.sub-competitions.participants', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Peserta
                    </a>
                </div>
            </div>
            <div>
                <form action="{{ route('admin.competitions.sub-competitions.participants.destroy', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => $participant->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Peserta
                    </button>
                </form>
            </div>
        </div>

        <!-- Skeleton Loading (Initially Visible) -->
        <div id="skeleton-loading" class="animate-pulse">
            <div class="flex-1 space-y-6 py-5">
                <!-- Profile Section Skeleton -->
                <div class="flex items-center space-x-6">
                    <div class="rounded-full bg-gray-200 h-20 w-20"></div>
                    <div class="space-y-3 flex-1">
                        <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                    </div>
                </div>
                
                <!-- Basic Info Skeleton -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="space-y-4">
                        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-10 bg-gray-200 rounded"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-10 bg-gray-200 rounded"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-10 bg-gray-200 rounded"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-10 bg-gray-200 rounded"></div>
                    </div>
                </div>
                
                <!-- Status Skeleton -->
                <div class="space-y-4 mt-8">
                    <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                    <div class="h-10 bg-gray-200 rounded w-1/3"></div>
                </div>
            </div>
        </div>

        <!-- Content (Initially Hidden) -->
        <div id="content" class="hidden">
            <!-- Profile Section -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-6">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        @if($participant->user->profile_picture)
                            <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('storage/' . $participant->user->profile_picture) }}" alt="{{ $participant->user->name }}">
                        @else
                            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-2xl text-indigo-600 font-medium">{{ strtoupper(substr($participant->user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $participant->user->name }}</h2>
                        <p class="text-gray-600">{{ $participant->user->email }}</p>
                        
                        <div class="mt-4 flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $participant->status === 'registered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $participant->status === 'registered' ? 'Terdaftar' : 'Menunggu' }}
                            </span>
                            
                            @if($participant->team_name)
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tim: {{ $participant->team_name }}
                                </span>
                            @endif
                            
                            <span class="ml-3 text-sm text-gray-500">
                                Bergabung pada {{ $participant->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Student Information -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <span class="text-indigo-600 mr-2">●</span>
                        Informasi Mahasiswa
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">NIM</label>
                            <p class="text-gray-900">{{ $participant->user->nim ?? 'Tidak ada data' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Program Studi</label>
                            <p class="text-gray-900">{{ $participant->user->study_program ?? 'Tidak ada data' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Fakultas</label>
                            <p class="text-gray-900">{{ $participant->user->faculty ?? 'Tidak ada data' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tahun Angkatan</label>
                            <p class="text-gray-900">{{ $participant->user->year ?? 'Tidak ada data' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Dosen Pendamping</label>
                            <p class="text-gray-900">{{ $participant->advisor_name ?: 'Tidak ada data' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Members -->
            @if(!empty($participant->team_members))
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <span class="text-indigo-600 mr-2">●</span>
                        Anggota Tim
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-hidden bg-white shadow sm:rounded-md">
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach(json_decode($participant->team_members) as $member)
                                <li class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <p class="truncate text-sm font-medium text-indigo-600">{{ $member->name }}</p>
                                            <div class="mt-1 flex">
                                                <p class="text-sm text-gray-500">{{ $member->nim ?? 'NIM tidak tersedia' }}</p>
                                                <p class="ml-4 text-sm text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Registration Status -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <span class="text-indigo-600 mr-2">●</span>
                        Status Pendaftaran
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.competitions.sub-competitions.participants.update', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'participant' => $participant->id]) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="registered" {{ $participant->status === 'registered' ? 'selected' : '' }}>Terdaftar</option>
                                <option value="pending" {{ $participant->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="team_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Tim (Opsional)</label>
                            <input type="text" id="team_name" name="team_name" value="{{ $participant->team_name }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="pt-5 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate loading time
            setTimeout(function() {
                document.getElementById('skeleton-loading').classList.add('hidden');
                document.getElementById('content').classList.remove('hidden');
            }, 1000);
        });
    </script>
@endcomponent 