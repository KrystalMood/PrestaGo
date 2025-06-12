<!-- Profile Section -->
<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-6">
    <div class="flex items-start space-x-6">
        <div class="flex-shrink-0">
            @if($participant->user->profile_picture)
                <img class="h-24 w-24 rounded-full object-cover"
                    src="{{ asset('storage/' . $participant->user->profile_picture) }}"
                    alt="{{ $participant->user->name }}">
            @else
                <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span
                        class="text-2xl text-indigo-600 font-medium">{{ strtoupper(substr($participant->user->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $participant->user->name }}</h2>
            <p class="text-gray-600">{{ $participant->user->email }}</p>

            <div class="mt-4 flex items-center">
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $participant->status === 'registered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $participant->status === 'registered' ? 'Terdaftar' : 'Menunggu' }}
                </span>

                @if($participant->team_name)
                    <span
                        class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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

<!-- Team Information -->
@if($participant->team_name)
<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="bg-indigo-50 px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            <span class="text-indigo-600 mr-2">●</span>
            Informasi Tim
        </h3>
    </div>
    <div class="p-6">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <div>
                <h4 class="text-md font-semibold text-gray-900">{{ $participant->team_name }}</h4>
                <p class="text-sm text-gray-500">
                    Ketua Tim: {{ $participant->user->name }}
                    @if(!empty($participant->team_members))
                        <span class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-800 text-xs rounded-full">
                            {{ count(json_decode($participant->team_members)) }} Anggota
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endif

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

<!-- Team Members Section -->
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

<!-- Action Buttons -->
<div class="flex justify-end space-x-3">
    <button id="approve-btn"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Setujui
    </button>
    <button id="reject-btn"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Tolak
    </button>
    <button id="close-modal"
        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
        Tutup
    </button>
</div>