@component('layouts.admin', ['title' => 'Detail Periode'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'subtitle' => 'Informasi lengkap mengenai periode semester',
                'actionText' => 'Kembali ke Daftar',
                'actionUrl' => route('admin.periods.index')
            ])
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-custom p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Periode</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama Periode</p>
                            <p class="text-base font-medium text-gray-900">{{ $period->name }}</p>
                        </div>
                        <div class="flex flex-col md:flex-row md:space-x-6">
                            <div class="mb-2 md:mb-0">
                                <p class="text-sm font-medium text-gray-500">Tanggal Mulai</p>
                                <p class="text-base text-gray-900">{{ $period->start_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Selesai</p>
                                <p class="text-base text-gray-900">{{ $period->end_date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $period->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $period->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Kompetisi</p>
                            <p class="text-base text-gray-900">{{ $period->competitions->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Dibuat</p>
                            <p class="text-base text-gray-900">{{ $period->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between space-x-3">
                            <a href="{{ route('admin.periods.edit', $period->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center justify-center transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.periods.toggle-active', $period->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-{{ $period->is_active ? 'amber' : 'green' }}-600 hover:bg-{{ $period->is_active ? 'amber' : 'green' }}-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center justify-center transition-colors duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($period->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                    {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                        
                        @if($period->competitions->count() == 0)
                            <form action="{{ route('admin.periods.destroy', $period->id) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus periode ini? Tindakan ini tidak dapat dibatalkan.')" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center justify-center transition-colors duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Periode
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-custom p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Kompetisi Dalam Periode Ini</h3>
                    
                    @if($period->competitions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kompetisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyelenggara</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($period->competitions as $competition)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $competition->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $competition->organizer }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $competition->registration_start_date->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $competition->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $competition->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.competitions.show', $competition->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada kompetisi yang terdaftar dalam periode ini.</p>
                            <a href="{{ route('admin.competitions.create') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Tambah Kompetisi
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="bg-white rounded-lg shadow-custom p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline Periode</h3>
                    
                    <div class="relative pb-10">
                        <div class="absolute left-5 top-0 h-full border-l-2 border-gray-200"></div>
                        
                        <div class="relative mb-8">
                            <div class="absolute left-4 -top-1 mt-3 h-4 w-4 rounded-full bg-indigo-500 border-4 border-white"></div>
                            <div class="ml-12">
                                <div class="text-sm font-medium text-indigo-600">Pembuatan Periode</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $period->created_at->format('d M Y, H:i') }}</div>
                                <div class="mt-2 text-sm text-gray-600">Periode {{ $period->name }} dibuat dalam sistem.</div>
                            </div>
                        </div>
                        
                        <div class="relative mb-8">
                            <div class="absolute left-4 -top-1 mt-3 h-4 w-4 rounded-full bg-blue-500 border-4 border-white"></div>
                            <div class="ml-12">
                                <div class="text-sm font-medium text-blue-600">Periode Dimulai</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $period->start_date->format('d M Y') }}</div>
                                <div class="mt-2 text-sm text-gray-600">Tanggal mulai periode {{ $period->name }}.</div>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <div class="absolute left-4 -top-1 mt-3 h-4 w-4 rounded-full bg-gray-500 border-4 border-white"></div>
                            <div class="ml-12">
                                <div class="text-sm font-medium text-gray-600">Periode Berakhir</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $period->end_date->format('d M Y') }}</div>
                                <div class="mt-2 text-sm text-gray-600">Tanggal berakhir periode {{ $period->name }}.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcomponent 