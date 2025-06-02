@props([
    'verifications' => [],
    'activeFilterStatus' => 'all' // Default to 'all' if not provided
])

@php
    $isEmpty = $verifications->isEmpty();
    $emptyMessage = 'Tidak ada prestasi yang ditemukan.';
    $emptySubMessage = 'Coba ubah filter pencarian Anda atau ajukan prestasi baru.';

    if ($isEmpty) {
        switch ($activeFilterStatus) {
            case 'pending':
                $emptyMessage = 'Tidak ada prestasi yang perlu diverifikasi.';
                $emptySubMessage = 'Semua prestasi yang menunggu telah diproses atau tidak ada yang diajukan.';
                break;
            case 'verified':
                $emptyMessage = 'Tidak ada prestasi yang disetujui.';
                $emptySubMessage = 'Belum ada prestasi yang disetujui untuk filter ini.';
                break;
            case 'rejected':
                $emptyMessage = 'Tidak ada prestasi yang ditolak.';
                $emptySubMessage = 'Tidak ada prestasi yang ditolak untuk filter ini.';
                break;
            case 'all':
            default:
                $emptyMessage = 'Tidak ada prestasi ditemukan.';
                $emptySubMessage = 'Tidak ada data prestasi yang cocok dengan kriteria pencarian Anda saat ini.';
                break;
        }
    }
@endphp

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mahasiswa
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Judul Prestasi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jenis
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Pengajuan
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($verifications as $verification)
                <tr class="hover:bg-gray-50 transition-colors" data-verification-id="{{ $verification->achievement_id }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($verifications instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($verifications->currentPage() - 1) * $verifications->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $verification->achievement_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($verification->user && $verification->user->photo)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $verification->user->photo) }}" alt="{{ $verification->user->name }}" loading="lazy">
                                @else
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($verification->user ? $verification->user->name : 'User') }}&background=4338ca&color=fff" alt="{{ $verification->user ? $verification->user->name : 'User' }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $verification->user ? $verification->user->name : 'User Not Found' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $verification->title }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($verification->type == 'academic') 
                                bg-blue-100 text-blue-800
                            @elseif($verification->type == 'technology')
                                bg-indigo-100 text-indigo-800
                            @elseif($verification->type == 'arts')
                                bg-purple-100 text-purple-800
                            @elseif($verification->type == 'sports')
                                bg-green-100 text-green-800
                            @elseif($verification->type == 'entrepreneurship')
                                bg-amber-100 text-amber-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            @if($verification->type == 'academic')
                                Akademik
                            @elseif($verification->type == 'technology')
                                Teknologi
                            @elseif($verification->type == 'arts')
                                Seni
                            @elseif($verification->type == 'sports')
                                Olahraga
                            @elseif($verification->type == 'entrepreneurship')
                                Kewirausahaan
                            @else
                                {{ ucfirst($verification->type) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($verification->status == 'verified') 
                                bg-green-100 text-green-800
                            @elseif($verification->status == 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            @if($verification->status == 'verified')
                                Disetujui
                            @elseif($verification->status == 'rejected')
                                Ditolak
                            @else
                                Menunggu
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $verification->created_at ? $verification->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-verification" 
                                data-verification-id="{{ $verification->achievement_id ?? $verification->id ?? '' }}"
                                data-id="{{ $verification->achievement_id ?? $verification->id ?? '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            
                            @if($verification->status == 'pending')
                            <button type="button" class="btn btn-sm btn-ghost text-green-600 hover:bg-green-50 transition-colors approve-verification" 
                                data-id="{{ $verification->achievement_id ?? $verification->id ?? '' }}"
                                data-verification-id="{{ $verification->achievement_id ?? $verification->id ?? '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            
                            <button type="button" class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 transition-colors reject-verification" 
                                data-id="{{ $verification->achievement_id ?? $verification->id ?? '' }}"
                                data-verification-id="{{ $verification->achievement_id ?? $verification->id ?? '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            @endif
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
                            <p class="text-gray-600 font-medium">{{ $emptyMessage }}</p>
                            <p class="text-gray-500 mt-1 text-sm">{{ $emptySubMessage }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 