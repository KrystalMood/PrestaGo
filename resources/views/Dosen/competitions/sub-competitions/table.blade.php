<div class="bg-white rounded-lg shadow-custom overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sub-Kompetisi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subCompetitions as $index => $subCompetition)
                    <tr class="hover:bg-gray-50 transition-colors" data-sub-competition-id="{{ $subCompetition->id }}" data-start-date="{{ $subCompetition->start_date }}" data-end-date="{{ $subCompetition->end_date }}">
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $subCompetition->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                {{ $subCompetition->name }}
                            </div>
                            <div class="text-xs text-gray-500">{{ Str::limit($subCompetition->description, 50) ?: 'Tidak ada deskripsi' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = [
                                    'upcoming' => 'bg-yellow-100 text-yellow-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'ongoing' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ][$subCompetition->status] ?? 'bg-gray-100 text-gray-800';
                                
                                $statusText = [
                                    'upcoming' => 'Akan Datang',
                                    'active' => 'Aktif',
                                    'ongoing' => 'Berlangsung',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ][$subCompetition->status] ?? ucfirst($subCompetition->status);
                            @endphp
                            <span class="status-badge px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subCompetition->category ? $subCompetition->category->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($subCompetition->start_date && $subCompetition->end_date)
                                {{ \Carbon\Carbon::parse($subCompetition->start_date)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($subCompetition->end_date)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="group flex items-center text-sm text-gray-500">
                                <div class="bg-blue-50 rounded-full p-1.5 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $subCompetition->participants ? $subCompetition->participants->count() : 0 }}</span>
                                @php
                                    $pendingRegistrations = $subCompetition->participants ? $subCompetition->participants->where('status', 'pending')->count() : 0;
                                @endphp
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center space-x-1">
                                <a href="{{ route('lecturer.competitions.sub-competitions.skills', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" class="btn btn-sm btn-ghost text-green-600 hover:bg-green-50 transition-colors" title="Kelola Skill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </a>
                                <button type="button" class="btn btn-sm btn-ghost text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors show-sub-competition" data-sub-competition-id="{{ $subCompetition->id }}" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors edit-sub-competition" data-sub-competition-id="{{ $subCompetition->id }}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
                                <p class="text-gray-600 font-medium">Belum ada sub-kompetisi yang tersedia</p>
                                <p class="text-gray-500 mt-1 text-sm">Silakan tambahkan sub-kompetisi baru untuk kompetisi ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>