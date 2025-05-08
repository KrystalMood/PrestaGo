@component('layouts.admin', ['title' => 'Detail Program Studi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'title' => 'Detail Program Studi',
                'subtitle' => 'Halaman ini menampilkan detail informasi program studi.',
                'back_url' => route('admin.programs.index'),
            ])
        </div>
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">{{ $program->name }}</h2>
           
        </div>

        <div class="mb-8 flex flex-col md:flex-row">
            <div class="w-full md:w-2/3 pr-0 md:pr-6">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-800">Informasi Umum</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Program Studi</dt>
                                <dd class="mt-1 text-base font-medium text-gray-900">{{ $program->code }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jenjang Pendidikan</dt>
                                <dd class="mt-1 text-base text-gray-900">{{ $program->degree_level }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fakultas</dt>
                                <dd class="mt-1 text-base text-gray-900">{{ $program->faculty }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $program->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $program->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-base text-gray-900">{{ $program->created_at ? $program->created_at->format('d M Y, H:i') : 'Tidak ada data' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Diperbarui</dt>
                                <dd class="mt-1 text-base text-gray-900">{{ $program->updated_at ? $program->updated_at->format('d M Y, H:i') : 'Tidak ada data' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                @if($program->description)
                <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-800">Deskripsi Program Studi</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700">{{ $program->description }}</p>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="w-full md:w-1/3 mt-6 md:mt-0">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-800">Statistik</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Total Mahasiswa</h4>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $totalStudents ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-800">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <x-ui.button 
                            variant="primary"
                            tag="a"
                            href="{{ route('admin.programs.edit', $program->id) }}"
                            class="w-full justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Program Studi
                        </x-ui.button>
                        
                        <form action="{{ route('admin.programs.toggle-active', $program->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-ui.button 
                                variant="{{ $program->is_active ? 'warning' : 'success' }}"
                                type="submit"
                                class="w-full justify-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($program->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                                {{ $program->is_active ? 'Nonaktifkan Program Studi' : 'Aktifkan Program Studi' }}
                            </x-ui.button>
                        </form>
                        
                        <x-ui.button 
                            variant="danger"
                            onclick="confirmDelete({{ $program->id }}, '{{ $program->name }}')"
                            class="w-full justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Program Studi
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md mx-auto p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
                <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus program studi "<span id="programNameToDelete" class="font-medium"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <x-ui.button 
                    variant="secondary" 
                    onclick="closeDeleteModal()"
                >
                    Batal
                </x-ui.button>
                
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <x-ui.button 
                        variant="danger"
                        type="submit"
                    >
                        Hapus
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            document.getElementById('programNameToDelete').textContent = name || 'Program Studi';
            document.getElementById('deleteForm').action = `{{ route('admin.programs.destroy', '') }}/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
@endcomponent 