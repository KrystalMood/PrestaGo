@props(['verifications' => []])

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
                        Pengguna
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Diajukan Pada
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($verifications as $verification)
                <tr class="hover:bg-gray-50 transition-colors" data-verification-id="{{ $verification->id }}">
                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                            {{ ($verifications instanceof \Illuminate\Pagination\LengthAwarePaginator) ? (($verifications->currentPage() - 1) * $verifications->perPage() + $loop->iteration) : $loop->iteration }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $verification->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($verification->user->photo)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $verification->user->photo) }}" alt="{{ $verification->user->name }}" loading="lazy">
                                @else
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($verification->user->name) }}&background=4338ca&color=fff" alt="{{ $verification->user->name }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $verification->user->name }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $verification->user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($verification->status == 'approved') 
                                bg-green-100 text-green-800
                            @elseif($verification->status == 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            @if($verification->status == 'approved')
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
                            <button type="button" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors show-verification" data-verification-id="{{ $verification->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            
                            @if($verification->status == 'pending')
                            <button type="button" class="btn btn-sm btn-ghost text-green-600 hover:bg-green-50 transition-colors approve-verification" data-id="{{ $verification->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            
                            <button type="button" class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 transition-colors reject-verification" data-id="{{ $verification->id }}">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada data verifikasi yang ditemukan</p>
                            <p class="text-gray-500 mt-1 text-sm">Silakan coba ubah filter pencarian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- View Verification Modal -->
<div id="view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-custom max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-800" id="verification-title">Detail Verifikasi</h3>
                <button id="close-view-modal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div id="verification-content" class="mb-4">
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
            <p class="text-gray-600 text-center mb-6 text-sm">Apakah Anda yakin ingin menyetujui verifikasi <span id="verification-name-to-approve" class="font-semibold"></span>?</p>
            
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
            <p class="text-gray-600 text-center mb-6 text-sm">Apakah Anda yakin ingin menolak verifikasi <span id="verification-name-to-reject" class="font-semibold"></span>?</p>
            
            <form id="reject-form" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label for="reject_note" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-600">*</span></label>
                    <textarea id="reject_note" name="note" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Berikan alasan mengapa verifikasi ini ditolak..." required></textarea>
                    <p class="mt-1 text-sm text-gray-500">Alasan ini akan ditampilkan kepada pengguna.</p>
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
    const verificationContent = document.getElementById('verification-content');
    const actionButtons = document.getElementById('action-buttons');
    const approveFromView = document.getElementById('approve-from-view');
    const rejectFromView = document.getElementById('reject-from-view');
    
    const approveModal = document.getElementById('approve-modal');
    const approveForm = document.getElementById('approve-form');
    const verificationNameToApprove = document.getElementById('verification-name-to-approve');
    const cancelApprove = document.getElementById('cancel-approve');
    
    const rejectModal = document.getElementById('reject-modal');
    const rejectForm = document.getElementById('reject-form');
    const verificationNameToReject = document.getElementById('verification-name-to-reject');
    const cancelReject = document.getElementById('cancel-reject');
    
    document.querySelectorAll('.show-verification').forEach(button => {
        button.addEventListener('click', () => {
            const verificationId = button.getAttribute('data-verification-id');
            
            verificationContent.innerHTML = `
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
                verificationContent.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Judul Verifikasi</h4>
                                <p class="text-gray-900">Verifikasi Identitas</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Pengguna</h4>
                                <p class="text-gray-900">Budi Santoso</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal</h4>
                                <p class="text-gray-900">15 Mei 2023</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                                <p class="text-gray-900">Menunggu</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Diajukan Pada</h4>
                                <p class="text-gray-900">10 Juni 2023</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                                <p class="text-gray-900">budi.santoso@example.com</p>
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
                            Verifikasi ini dilakukan untuk memastikan identitas pengguna. Data yang akan diverifikasi meliputi nama, email, dan foto.
                        </p>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Dokumen</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <img src="https://via.placeholder.com/800x600?text=Verification+Preview" alt="Verification" class="w-full h-auto">
                        </div>
                        <div class="mt-2 flex justify-end">
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Dokumen
                            </a>
                        </div>
                    </div>
                `;
                
                actionButtons.classList.remove('hidden');
                
                approveFromView.setAttribute('data-verification-id', verificationId);
                rejectFromView.setAttribute('data-verification-id', verificationId);
            }, 1000);
            
            viewModal.classList.remove('hidden');
        });
    });
    
    document.querySelectorAll('.approve-verification').forEach(button => {
        button.addEventListener('click', () => {
            const verificationId = button.getAttribute('data-id');
            
            approveForm.action = `/admin/verification/${verificationId}/approve`;
            verificationNameToApprove.textContent = verificationId;
            approveModal.classList.remove('hidden');
        });
    });
    
    document.querySelectorAll('.reject-verification').forEach(button => {
        button.addEventListener('click', () => {
            const verificationId = button.getAttribute('data-id');
            
            rejectForm.action = `/admin/verification/${verificationId}/reject`;
            verificationNameToReject.textContent = verificationId;
            rejectModal.classList.remove('hidden');
        });
    });
    
    approveFromView.addEventListener('click', () => {
        const verificationId = approveFromView.getAttribute('data-verification-id');
        
        approveForm.action = `/admin/verification/${verificationId}/approve`;
        viewModal.classList.add('hidden');
        approveModal.classList.remove('hidden');
    });
    
    rejectFromView.addEventListener('click', () => {
        const verificationId = rejectFromView.getAttribute('data-verification-id');
        
        rejectForm.action = `/admin/verification/${verificationId}/reject`;
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