<!-- Show Verification Modal -->
<div id="showVerificationModal" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="modal-dialog relative p-4 mx-auto max-w-3xl">
        <div class="modal-content bg-white rounded-lg shadow-xl p-6">
            <!-- Header -->
            <div class="flex items-center justify-between pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Detail Verifikasi</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none close-modal">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="mt-4">
                <!-- Loading State -->
                <div id="verification-loading" class="verification-info">
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                    </div>
                </div>

                <!-- Content -->
                <div id="verification-info" class="verification-info hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500">ID Verifikasi</label>
                                <div id="verification-id" class="mt-1 text-gray-900"></div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500">Nama Pengguna</label>
                                <div id="verification-user-name" class="mt-1 text-gray-900"></div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <div id="verification-email" class="mt-1 text-gray-900"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <div id="verification-status" class="mt-1"></div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500">Diajukan Pada</label>
                                <div id="verification-created" class="mt-1 text-gray-900"></div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500">Diperbarui Pada</label>
                                <div id="verification-updated" class="mt-1 text-gray-900"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Dokumen Pendukung</label>
                        <div id="verification-documents" class="mt-2 flex flex-wrap gap-2">
                            <!-- Documents will be dynamically populated here -->
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Catatan</label>
                        <div id="verification-notes" class="mt-1 text-gray-900 border p-3 rounded-md bg-gray-50 min-h-[80px]"></div>
                    </div>

                    <div class="mt-4" id="rejection-reason-container">
                        <label class="block text-sm font-medium text-gray-500">Alasan Penolakan</label>
                        <div id="verification-rejection-reason" class="mt-1 text-gray-900 border p-3 rounded-md bg-red-50 min-h-[80px]"></div>
                    </div>
                </div>

                <!-- Error -->
                <div id="verification-error" class="verification-info hidden">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Terjadi kesalahan!</strong>
                        <span class="block sm:inline"> Tidak dapat memuat data verifikasi. Silakan coba lagi nanti.</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 flex justify-end gap-3" id="verification-actions">
                <button type="button" class="btn btn-secondary close-modal">
                    Tutup
                </button>
                
                <div id="pending-actions" class="flex gap-2">
                    <button type="button" id="approve-button" class="btn bg-green-600 hover:bg-green-700 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Setujui
                    </button>
                    <button type="button" id="reject-button" class="btn bg-red-600 hover:bg-red-700 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="modal-dialog relative p-4 mx-auto max-w-md">
        <div class="modal-content bg-white rounded-lg shadow-xl p-6">
            <div class="text-center">
                <svg class="h-12 w-12 text-green-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Persetujuan</h3>
                <p class="text-sm text-gray-600 mt-2">Apakah Anda yakin ingin menyetujui verifikasi ini?</p>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="btn btn-secondary cancel-approve">Batal</button>
                <button type="button" id="confirm-approve" class="btn bg-green-600 hover:bg-green-700 text-white">Setujui</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="modal-dialog relative p-4 mx-auto max-w-md">
        <div class="modal-content bg-white rounded-lg shadow-xl p-6">
            <div class="text-center mb-4">
                <svg class="h-12 w-12 text-red-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Penolakan</h3>
                <p class="text-sm text-gray-600 mt-2">Apakah Anda yakin ingin menolak verifikasi ini?</p>
            </div>
            <form id="reject-form">
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-600">*</span></label>
                    <textarea id="rejection_reason" name="reason" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Alasan penolakan verifikasi ini..." required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="btn btn-secondary cancel-reject">Batal</button>
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show modal functionality
    const showVerificationModal = (verificationId) => {
        document.querySelectorAll('.verification-info').forEach(el => el.classList.add('hidden'));
        document.getElementById('verification-loading').classList.remove('hidden');
        document.getElementById('showVerificationModal').classList.remove('hidden');
        
        // Hide pending actions by default
        document.getElementById('pending-actions').classList.add('hidden');
        
        // Hide rejection reason container by default
        document.getElementById('rejection-reason-container').classList.add('hidden');
        
        // Replace '__ID__' in the route template with the actual ID
        const detailUrl = window.verificationRoutes.index + '/' + verificationId;
        
        // Fetch verification details
        fetch(detailUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const verification = data.verification;
            
            // Populate the modal with data
            document.getElementById('verification-id').textContent = verification.id;
            document.getElementById('verification-user-name').textContent = verification.user.name;
            document.getElementById('verification-email').textContent = verification.user.email;
            
            // Format dates
            document.getElementById('verification-created').textContent = new Date(verification.created_at).toLocaleString();
            document.getElementById('verification-updated').textContent = new Date(verification.updated_at).toLocaleString();
            
            // Status with appropriate styling
            let statusHtml = '';
            if (verification.status === 'pending') {
                statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>';
                document.getElementById('pending-actions').classList.remove('hidden');
            } else if (verification.status === 'approved') {
                statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>';
            } else if (verification.status === 'rejected') {
                statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>';
                
                // Show rejection reason if available
                if (verification.rejection_reason) {
                    document.getElementById('rejection-reason-container').classList.remove('hidden');
                    document.getElementById('verification-rejection-reason').textContent = verification.rejection_reason;
                }
            }
            document.getElementById('verification-status').innerHTML = statusHtml;
            
            // Notes
            document.getElementById('verification-notes').textContent = verification.notes || 'Tidak ada catatan';
            
            // Documents (if any)
            const documentsContainer = document.getElementById('verification-documents');
            documentsContainer.innerHTML = ''; // Clear previous content
            
            if (verification.documents && verification.documents.length > 0) {
                verification.documents.forEach(doc => {
                    const docElement = document.createElement('a');
                    docElement.href = doc.url;
                    docElement.target = '_blank';
                    docElement.className = 'px-3 py-2 bg-blue-50 text-blue-700 rounded-md flex items-center';
                    docElement.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        ${doc.name || 'Dokumen'}
                    `;
                    documentsContainer.appendChild(docElement);
                });
            } else {
                documentsContainer.innerHTML = '<div class="text-gray-500 italic">Tidak ada dokumen</div>';
            }
            
            // Set action buttons data
            document.getElementById('approve-button').setAttribute('data-verification-id', verification.id);
            document.getElementById('reject-button').setAttribute('data-verification-id', verification.id);
            document.getElementById('confirm-approve').setAttribute('data-verification-id', verification.id);
            document.getElementById('reject-form').setAttribute('data-verification-id', verification.id);
            
            // Show the info and hide the loading state
            document.getElementById('verification-loading').classList.add('hidden');
            document.getElementById('verification-info').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching verification details:', error);
            document.getElementById('verification-loading').classList.add('hidden');
            document.getElementById('verification-error').classList.remove('hidden');
        });
    };
    
    // Close modal functionality
    const closeModals = () => {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.add('hidden');
        });
    };
    
    // Attach event listeners for show buttons
    document.querySelectorAll('.show-verification').forEach(button => {
        button.addEventListener('click', () => {
            const verificationId = button.getAttribute('data-verification-id');
            showVerificationModal(verificationId);
        });
    });
    
    // Attach event listeners for close buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', closeModals);
    });
    
    // Approve functionality
    document.getElementById('approve-button').addEventListener('click', function() {
        const verificationId = this.getAttribute('data-verification-id');
        document.getElementById('confirm-approve').setAttribute('data-verification-id', verificationId);
        document.getElementById('showVerificationModal').classList.add('hidden');
        document.getElementById('approveModal').classList.remove('hidden');
    });
    
    document.getElementById('confirm-approve').addEventListener('click', function() {
        const verificationId = this.getAttribute('data-verification-id');
        const url = window.verificationRoutes.update.replace('__ID__', verificationId);
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'approved'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            closeModals();
            // Reload the page or update the UI as needed
            window.location.reload();
        })
        .catch(error => {
            console.error('Error approving verification:', error);
            alert('Terjadi kesalahan saat menyetujui verifikasi. Silakan coba lagi.');
        });
    });
    
    // Reject functionality
    document.getElementById('reject-button').addEventListener('click', function() {
        const verificationId = this.getAttribute('data-verification-id');
        document.getElementById('reject-form').setAttribute('data-verification-id', verificationId);
        document.getElementById('showVerificationModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('hidden');
    });
    
    document.getElementById('reject-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const verificationId = this.getAttribute('data-verification-id');
        const reason = document.getElementById('rejection_reason').value;
        const url = window.verificationRoutes.update.replace('__ID__', verificationId);
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'rejected',
                reason: reason
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            closeModals();
            // Reload the page or update the UI as needed
            window.location.reload();
        })
        .catch(error => {
            console.error('Error rejecting verification:', error);
            alert('Terjadi kesalahan saat menolak verifikasi. Silakan coba lagi.');
        });
    });
    
    // Cancel buttons
    document.querySelectorAll('.cancel-approve, .cancel-reject').forEach(button => {
        button.addEventListener('click', function() {
            closeModals();
            document.getElementById('showVerificationModal').classList.remove('hidden');
        });
    });
});
</script> 