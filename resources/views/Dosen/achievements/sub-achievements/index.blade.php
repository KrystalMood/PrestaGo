@component('layouts.dosen', ['title' => 'Prestasi ' . $user->name])
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2">
               <div class="mb-5">
                    <a href="{{ route('lecturer.achievements.index') }}" class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Prestasi Mahasiswa
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 mr-4">
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ $user->photo ?? asset('images/default-profile.png') }}" alt="{{ $user->name }}">
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-gray-500 text-sm">NIM: {{ $user->nim ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievement List -->
        <div class="mt-6">
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Prestasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kompetisi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subAchievements as $achievement)
                                <tr class="hover:bg-gray-50 transition-colors" data-achievement-id="{{ $achievement->id }}">
                                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                                            {{ ($subAchievements instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($subAchievements->currentPage() - 1) * $subAchievements->perPage() + $loop->iteration) : $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            {{ $achievement->title }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($achievement->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $achievement->competition_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $achievement->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $achievement->level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($achievement->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'verified' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ][$achievement->status] ?? 'bg-gray-100 text-gray-800';
                                            
                                            $statusText = [
                                                'pending' => 'Menunggu',
                                                'verified' => 'Terverifikasi',
                                                'rejected' => 'Ditolak',
                                            ][$achievement->status] ?? ucfirst($achievement->status);
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-1">
                                            <button class="btn btn-sm btn-ghost text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors show-achievement" data-user-id="{{$user->id}}" data-achievement-id="{{ $achievement->id ?? $achievement->achievement_id }}" title="Lihat Detail Prestasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-600 font-medium">Belum ada prestasi yang tersedia</p>
                                            <p class="text-gray-500 mt-1 text-sm">Mahasiswa ini belum menambahkan prestasi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($subAchievements instanceof \Illuminate\Pagination\LengthAwarePaginator && $subAchievements->count() > 0)
            <div class="mt-6">
                {{ $subAchievements->links() }}
            </div>
        @endif
    </div>

@include('Dosen.achievements.sub-achievements.components.show-achievement-modal')
    <!-- JavaScript Variables and Setup -->
    <script>
        window.userId = {{ $user->id }};
        window.achievementRoutes = {
            show: "{{ route('lecturer.achievements.show', ':id') }}",
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <!-- Include achievements.js -->
    @vite(['resources/js/dosen/achievements.js'])
@endcomponent