@props(['students' => []])

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="students-table">
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kompetisi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50 transition-colors student-row" data-student-id="{{ $student['id'] }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($students instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($students->currentPage() - 1) * $students->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $student['name'] ?? 'Nama tidak tersedia' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $student['nim'] ?? 'NIM tidak tersedia' }} <!-- Pastikan kolom nim ada di UserModel -->
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $student['program_studi'] ?? 'Program Studi tidak tersedia' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $student['competition'] ?? 'Kompetisi tidak tersedia' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($student['status'] == 'registered')
                                bg-green-100 text-green-800
                            @elseif($student['status'] == 'on going')
                                bg-amber-100 text-amber-800
                            @elseif($student['status'] == 'rejected')
                                bg-red-100 text-amber-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            @if($student['status'] == 'registered')
                                Teregristasi
                            @elseif($student['status'] == 'on going')
                                Sedang Berlangsung
                            @elseif($student['status'] == 'rejected')
                                Ditolak
                            @else
                                {{ ucfirst($student['status']) ?? 'Tidak Diketahui' }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-student" data-student-id="{{ $student['id'] }}">
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
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Belum ada data mahasiswa yang dibimbing</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>