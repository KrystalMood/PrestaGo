@props(['achievements' => []])
<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="achievements-table">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Mahasiswa
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        NIM
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Program Studi
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($achievements as $achievement)
                <tr class="hover:bg-gray-50 transition-colors achievement-row" data-achievement-id="{{ $achievement->id ?? $achievement->achievement_id }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($achievements instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($achievements->currentPage() - 1) * $achievements->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $achievement->user->profile_photo ?? asset('images/default-profile.png') }}" alt="{{ $achievement->user->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $achievement->user->name ?? 'Tidak Diketahui' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $achievement->user->nim }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            {{ $achievement->user->program_studi->name ?? 'Tidak Diketahui' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <a href="{{ route('lecturer.achievements.show', $achievement->user->id)}}" class="btn btn-sm btn-ghost text-purple-600 hover:bg-purple-50 transition-colors" title="Lihat Sub-Kompetisi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    {{ $achievement->total_achievements }}
                                </a>
                    </td>
                    @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Belum ada data prestasi</p>
                            <p class="text-gray-500 mt-1 text-sm">Silakan tambahkan prestasi baru dengan mengklik tombol "Tambah Prestasi"</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
