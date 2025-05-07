@component('layouts.admin', ['title' => $competition->name ?? 'Detail Kompetisi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Detail Kompetisi</h2>
                <p class="text-gray-600">Informasi detail tentang kompetisi.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.competitions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Kompetisi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $competition->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $competition->category->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Penyelenggara</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $competition->organizer }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @php
                                    $statusClass = [
                                        'upcoming' => 'bg-yellow-100 text-yellow-800',
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ][$competition->status] ?? 'bg-gray-100 text-gray-800';
                                    
                                    $statusText = [
                                        'upcoming' => 'Akan Datang',
                                        'active' => 'Aktif',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ][$competition->status] ?? $competition->status;
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $competition->start_date ? $competition->start_date->format('d M Y') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $competition->end_date ? $competition->end_date->format('d M Y') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Website</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($competition->url)
                                    <a href="{{ $competition->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $competition->url }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $competition->created_at ? $competition->created_at->format('d M Y H:i') : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi</h3>
                    <div class="prose max-w-none">
                        {!! $competition->description ?? 'Tidak ada deskripsi.' !!}
                    </div>
                </div>

                <!-- Requirements -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Persyaratan & Ketentuan</h3>
                    <div class="prose max-w-none">
                        {!! $competition->requirements ?? 'Tidak ada persyaratan.' !!}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Competition Image -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Kompetisi</h3>
                    @if($competition->image)
                        <img src="{{ asset('storage/' . $competition->image) }}" alt="{{ $competition->name }}" class="w-full h-auto rounded-lg">
                    @else
                        <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-sm mt-2">Tidak ada gambar</p>
                        </div>
                    @endif
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Total Peserta</span>
                            <span class="text-sm font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full">{{ $competition->participants_count ?? 0 }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Jumlah Pengunjung</span>
                            <span class="text-sm font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full">{{ $competition->views_count ?? 0 }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Dibagikan</span>
                            <span class="text-sm font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full">{{ $competition->shares_count ?? 0 }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tindakan</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.competitions.participants', $competition->id) }}" class="inline-flex w-full justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Kelola Peserta
                        </a>
                        <button type="button" class="inline-flex w-full justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Bagikan
                        </button>
                        <button 
                            type="button" 
                            class="inline-flex w-full justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            data-modal-target="deleteModal"
                            data-modal-toggle="deleteModal"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Kompetisi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div 
        id="deleteModal" 
        tabindex="-1" 
        aria-hidden="true" 
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
    >
        @component('admin.components.modals.delete-modal')
            @slot('title', 'Hapus Kompetisi')
            @slot('body')
                <p class="text-gray-500 dark:text-gray-400">
                    Apakah Anda yakin ingin menghapus kompetisi <span class="font-bold">{{ $competition->name }}</span>?
                </p>
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Tindakan ini tidak dapat dibatalkan, dan akan menghapus semua data terkait kompetisi ini.
                </p>
            @endslot
            @slot('formAction', route('admin.competitions.destroy', $competition->id))
        @endcomponent
 