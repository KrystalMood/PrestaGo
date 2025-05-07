@props(['achievements' => []])

<div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Prestasi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mahasiswa
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kategori
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tingkat
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
                @forelse($achievements as $achievement)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $achievement->id }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($achievement->certificate_file)
                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ asset('storage/' . $achievement->certificate_file) }}" alt="Certificate" loading="lazy">
                                @else
                                    <div class="h-10 w-10 rounded-md bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $achievement->title }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $achievement->organizer }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $achievement->student->name ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $achievement->student->nim ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $achievement->category->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $achievement->level->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($achievement->status == 'pending') 
                                bg-yellow-100 text-yellow-800
                            @elseif($achievement->status == 'approved')
                                bg-green-100 text-green-800
                            @elseif($achievement->status == 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            @if($achievement->status == 'pending') 
                                Menunggu
                            @elseif($achievement->status == 'approved')
                                Disetujui
                            @elseif($achievement->status == 'rejected')
                                Ditolak
                            @else
                                {{ $achievement->status }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button 
                                type="button" 
                                class="btn btn-sm btn-ghost text-brand hover:bg-brand-light hover:bg-opacity-10" 
                                data-achievement-id="{{ $achievement->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#view-modal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            
                            @if($achievement->status == 'pending')
                            <button 
                                type="button" 
                                class="btn btn-sm btn-ghost text-green-600 hover:bg-green-50" 
                                data-achievement-id="{{ $achievement->id }}"
                                data-achievement-title="{{ $achievement->title }}"
                                data-bs-toggle="modal"
                                data-bs-target="#approve-modal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            
                            <button 
                                type="button" 
                                class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50" 
                                data-achievement-id="{{ $achievement->id }}"
                                data-achievement-title="{{ $achievement->title }}"
                                data-bs-toggle="modal"
                                data-bs-target="#reject-modal"
                            >
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
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada data prestasi yang ditemukan</p>
                            <p class="text-gray-500 mt-1 text-sm">Silakan ubah filter pencarian atau tunggu mahasiswa mengajukan prestasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- View Achievement Modal -->
<div id="view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-custom max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-800" id="achievement-title">Detail Prestasi</h3>
                <button id="close-view-modal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div id="achievement-content" class="mb-4">
                <div class="animate-pulse">
                    <div class="h-5 bg-gray-200 rounded w-1/2 mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-full mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-32 bg-gray-200 rounded w-full mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-1/2 mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-2/3 mb-4"></div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button id="close-view-btn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    Tutup
                </button>
                
                <div id="action-buttons" class="hidden gap-2">
                    <button id="approve-from-view" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Setujui
                    </button>
                    <button id="reject-from-view" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-custom max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-green-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Konfirmasi Persetujuan</h3>
            <p class="text-gray-600 text-center mb-6 text-sm">Apakah Anda yakin ingin menyetujui prestasi <span id="achievement-name-to-approve" class="font-semibold"></span>?</p>
            
            <form id="approve-form" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label for="approve_note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="approve_note" name="note" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                
                <div class="flex justify-center gap-4">
                    <button type="button" id="cancel-approve" class="btn btn-sm btn-ghost">
                        Batal
                    </button>
                    
                    <button type="submit" class="btn btn-sm bg-green-600 hover:bg-green-700 text-white">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-custom max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-red-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Konfirmasi Penolakan</h3>
            <p class="text-gray-600 text-center mb-6 text-sm">Apakah Anda yakin ingin menolak prestasi <span id="achievement-name-to-reject" class="font-semibold"></span>?</p>
            
            <form id="reject-form" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label for="reject_note" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-600">*</span></label>
                    <textarea id="reject_note" name="note" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Berikan alasan mengapa prestasi ini ditolak..." required></textarea>
                    <p class="mt-1 text-sm text-gray-500">Alasan ini akan ditampilkan kepada mahasiswa.</p>
                </div>
                
                <div class="flex justify-center gap-4">
                    <button type="button" id="cancel-reject" class="btn btn-sm btn-ghost">
                        Batal
                    </button>
                    
                    <button type="submit" class="btn btn-sm bg-red-600 hover:bg-red-700 text-white">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const viewModal = document.getElementById('view-modal');
    const closeViewModal = document.getElementById('close-view-modal');
    const closeViewBtn = document.getElementById('close-view-btn');
    const achievementContent = document.getElementById('achievement-content');
    const actionButtons = document.getElementById('action-buttons');
    const approveFromView = document.getElementById('approve-from-view');
    const rejectFromView = document.getElementById('reject-from-view');
    
    const approveModal = document.getElementById('approve-modal');
    const approveForm = document.getElementById('approve-form');
    const achievementNameToApprove = document.getElementById('achievement-name-to-approve');
    const cancelApprove = document.getElementById('cancel-approve');
    
    const rejectModal = document.getElementById('reject-modal');
    const rejectForm = document.getElementById('reject-form');
    const achievementNameToReject = document.getElementById('achievement-name-to-reject');
    const cancelReject = document.getElementById('cancel-reject');
    
    document.querySelectorAll('.view-achievement').forEach(button => {
        button.addEventListener('click', () => {
            const achievementId = button.getAttribute('data-achievement-id');
            
            achievementContent.innerHTML = `
                <div class="animate-pulse">
                    <div class="h-5 bg-gray-200 rounded w-1/2 mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-full mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-32 bg-gray-200 rounded w-full mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-1/2 mb-4"></div>
                    <div class="h-5 bg-gray-200 rounded w-2/3 mb-4"></div>
                </div>
            `;
            
            setTimeout(() => {
                achievementContent.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Judul Prestasi</h4>
                                <p class="text-gray-900">Juara 1 Lomba Karya Tulis Ilmiah</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Penyelenggara</h4>
                                <p class="text-gray-900">Universitas Indonesia</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal</h4>
                                <p class="text-gray-900">15 Mei 2023</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Kategori</h4>
                                <p class="text-gray-900">Karya Tulis</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Tingkat</h4>
                                <p class="text-gray-900">Nasional</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Mahasiswa</h4>
                                <p class="text-gray-900">Budi Santoso</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">NIM</h4>
                                <p class="text-gray-900">2205123456</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Program Studi</h4>
                                <p class="text-gray-900">Teknik Informatika</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Diajukan Pada</h4>
                                <p class="text-gray-900">10 Juni 2023</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h4>
                        <p class="text-gray-900 text-sm">
                            Prestasi ini diperoleh dalam kompetisi karya tulis ilmiah antar universitas tingkat nasional. Tema lomba adalah "Inovasi Teknologi untuk Pembangunan Berkelanjutan". Karya yang diajukan berjudul "Implementasi IoT untuk Monitoring Kualitas Air Berbasis Machine Learning".
                        </p>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Sertifikat</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <img src="https://via.placeholder.com/800x600?text=Certificate+Preview" alt="Certificate" class="w-full h-auto">
                        </div>
                        <div class="mt-2 flex justify-end">
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Sertifikat
                            </a>
                        </div>
                    </div>
                `;
                
                actionButtons.classList.remove('hidden');
                
                approveFromView.setAttribute('data-achievement-id', achievementId);
                rejectFromView.setAttribute('data-achievement-id', achievementId);
            }, 1000);
            
            viewModal.classList.remove('hidden');
        });
    });
    
    document.querySelectorAll('.approve-achievement').forEach(button => {
        button.addEventListener('click', () => {
            const achievementId = button.getAttribute('data-achievement-id');
            const achievementTitle = button.getAttribute('data-achievement-title');
            
            approveForm.action = `/admin/verification/${achievementId}/approve`;
            achievementNameToApprove.textContent = achievementTitle;
            approveModal.classList.remove('hidden');
        });
    });
    
    document.querySelectorAll('.reject-achievement').forEach(button => {
        button.addEventListener('click', () => {
            const achievementId = button.getAttribute('data-achievement-id');
            const achievementTitle = button.getAttribute('data-achievement-title');
            
            rejectForm.action = `/admin/verification/${achievementId}/reject`;
            achievementNameToReject.textContent = achievementTitle;
            rejectModal.classList.remove('hidden');
        });
    });
    
    approveFromView.addEventListener('click', () => {
        const achievementId = approveFromView.getAttribute('data-achievement-id');
        
        approveForm.action = `/admin/verification/${achievementId}/approve`;
        viewModal.classList.add('hidden');
        approveModal.classList.remove('hidden');
    });
    
    rejectFromView.addEventListener('click', () => {
        const achievementId = rejectFromView.getAttribute('data-achievement-id');
        
        rejectForm.action = `/admin/verification/${achievementId}/reject`;
        viewModal.classList.add('hidden');
        rejectModal.classList.remove('hidden');
    });
    
    closeViewModal.addEventListener('click', () => {
        viewModal.classList.add('hidden');
        actionButtons.classList.add('hidden');
    });
    
    closeViewBtn.addEventListener('click', () => {
        viewModal.classList.add('hidden');
        actionButtons.classList.add('hidden');
    });
    
    cancelApprove.addEventListener('click', () => {
        approveModal.classList.add('hidden');
    });
    
    cancelReject.addEventListener('click', () => {
        rejectModal.classList.add('hidden');
    });
    
    viewModal.addEventListener('click', (e) => {
        if (e.target === viewModal) {
            viewModal.classList.add('hidden');
            actionButtons.classList.add('hidden');
        }
    });
    
    approveModal.addEventListener('click', (e) => {
        if (e.target === approveModal) {
            approveModal.classList.add('hidden');
        }
    });
    
    rejectModal.addEventListener('click', (e) => {
        if (e.target === rejectModal) {
            rejectModal.classList.add('hidden');
        }
    });
</script> 