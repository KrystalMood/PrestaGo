@props(['competitions' => []])

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="competitions-table">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Kompetisi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tingkat
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kategori
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Batas Akhir
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($competitions as $competition)
                <tr class="hover:bg-gray-50 transition-colors competition-row" 
                    data-competition-id="{{ $competition->id }}"
                    data-start-date="{{ $competition->start_date ? $competition->start_date->format('Y-m-d') : '' }}"
                    data-end-date="{{ $competition->end_date ? $competition->end_date->format('Y-m-d') : '' }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($competitions instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($competitions->currentPage() - 1) * $competitions->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $competition->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $competition->organizer }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($competition->level == 'international') 
                                bg-red-100 text-red-800
                            @elseif($competition->level == 'national')
                                bg-orange-100 text-orange-800
                            @elseif($competition->level == 'provincial')
                                bg-yellow-100 text-yellow-800
                            @elseif($competition->level == 'regional')
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            {{ $competition->level_formatted ?? ucfirst($competition->level) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ isset($competition->subCompetitions) ? $competition->subCompetitions->count() : 0 }} kategori
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'Tidak ditentukan' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($competition->status == 'open') 
                                bg-emerald-100 text-emerald-800
                            @elseif($competition->status == 'upcoming')
                                bg-blue-100 text-blue-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ $competition->status_indonesian }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('student.competitions.show', $competition->id) }}" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada data kompetisi yang ditemukan</p>
                            <p class="text-gray-500 mt-1 text-sm">Silakan ubah filter pencarian atau cek kembali nanti</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
