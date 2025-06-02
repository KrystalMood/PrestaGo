<!-- Show Verification Modal -->
<div id="showVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Verifikasi</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 close-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-6">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID Verifikasi</label>
                            <div id="verification-id" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div id="verification-status" class=""></div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengguna</label>
                            <div id="verification-user-name" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div id="verification-email" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diajukan Pada</label>
                            <div id="verification-created" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diperbarui Pada</label>
                            <div id="verification-updated" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen Pendukung</label>
                        <div id="verification-documents" class="flex flex-wrap gap-2 p-3 bg-gray-50 rounded-md border border-gray-300" style="min-height: 60px;">
                            <!-- Documents will be dynamically populated here -->
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <div id="verification-notes" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700 min-h-[80px]"></div>
                    </div>

                    <div class="form-group mb-4" id="rejection-reason-container">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                        <div id="verification-rejection-reason" class="bg-red-50 px-3 py-2 rounded-md border border-red-300 text-red-700 min-h-[80px]"></div>
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

            <div class="flex items-center justify-end pt-5 border-t border-gray-200 mt-6" id="verification-actions">
                <div class="flex space-x-3">
                    <div id="pending-actions" class="flex gap-2">
                        <x-ui.button 
                            variant="success"
                            id="approve-button"
                            type="button"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Setujui
                        </x-ui.button>
                        <x-ui.button 
                            variant="danger"
                            id="reject-button"
                            type="button"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Tolak
                        </x-ui.button>
                    </div>
                    
                    <x-ui.button 
                        variant="secondary" 
                        id="close-show-modal"
                        type="button"
                    >
                        Tutup
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="text-center">
                <svg class="h-12 w-12 text-green-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Persetujuan</h3>
                <p class="text-sm text-gray-600 mt-2">Apakah Anda yakin ingin menyetujui verifikasi ini?</p>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-ui.button variant="secondary" class="cancel-approve" type="button">Batal</x-ui.button>
                <x-ui.button variant="success" id="confirm-approve" type="button">Setujui</x-ui.button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="text-center mb-4">
                <svg class="h-12 w-12 text-red-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Penolakan</h3>
                <p class="text-sm text-gray-600 mt-2">Apakah Anda yakin ingin menolak verifikasi ini?</p>
            </div>
            <form id="reject-form">
                <div class="form-group mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-600">*</span></label>
                    <textarea id="rejection_reason" name="reason" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Alasan penolakan verifikasi ini..." required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <x-ui.button variant="secondary" class="cancel-reject" type="button">Batal</x-ui.button>
                    <x-ui.button variant="danger" type="submit">Tolak</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Make showVerificationModal function globally accessible
window.showVerificationModal = function(verificationId) {
    if (!verificationId) {
        console.error('No verification ID provided');
        return;
    }
    
    // Get all elements with class 'verification-info' and hide them
    document.querySelectorAll('.verification-info').forEach(el => el.classList.add('hidden'));
    
    // Display loading indicator
    document.getElementById('verification-loading').classList.remove('hidden');
    
    // Show the modal
    const modalElement = document.getElementById('showVerificationModal');
    if (modalElement) {
        console.log('[showVerificationModal] Before class change - classList:', modalElement.classList.toString(), 'computed display:', window.getComputedStyle(modalElement).display, 'computed visibility:', window.getComputedStyle(modalElement).visibility, 'computed opacity:', window.getComputedStyle(modalElement).opacity);
        modalElement.classList.remove('hidden'); // Removes display:none from Tailwind's .hidden
        modalElement.classList.add('modal-open'); // DaisyUI class to make modal visible (opacity, visibility)
        console.log('[showVerificationModal] After class change - classList:', modalElement.classList.toString(), 'computed display:', window.getComputedStyle(modalElement).display, 'computed visibility:', window.getComputedStyle(modalElement).visibility, 'computed opacity:', window.getComputedStyle(modalElement).opacity);
        void modalElement.offsetHeight; // Force repaint
    } else {
        console.error('[showVerificationModal] Modal element #showVerificationModal not found!');
        return; // Critical if modal doesn't exist
    }
    
    // Hide pending actions by default
    document.getElementById('pending-actions').classList.add('hidden');
    
    // Hide rejection reason container by default
    document.getElementById('rejection-reason-container').classList.add('hidden');
    
    // Construct the URL for fetching verification details
    const url = window.verificationRoutes.show.replace('__ID__', verificationId);
    console.log('Fetching from URL:', url);
    
    // Fetch verification details
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('API Response:', data);
        
        // Extract verification data safely from various possible response formats
        let verification;
        if (data.achievement) {
            verification = data.achievement;
        } else if (data.verification) {
            verification = data.verification;
        } else if (data.id || data.achievement_id) {
            // The data itself might be the verification object
            verification = data;
        } else {
            throw new Error('Invalid response: verification data is missing');
        }
        
        console.log('Verification object:', verification);
        
        // Ensure we have a valid verification object
        if (!verification || typeof verification !== 'object') {
            throw new Error('Invalid verification data structure');
        }
        
        // Update modal UI with verification data
        document.getElementById('verification-loading').classList.add('hidden');
        document.getElementById('verification-info').classList.remove('hidden');
        console.log('Verification info element classList after unhiding:', document.getElementById('verification-info').classList);
        
        // Use achievement_id if id is not available
        const verificationId = verification.id || verification.achievement_id;
        if (!verificationId) {
            throw new Error('Verification ID is missing in the response');
        }
        
        // Populate the modal with data
        document.getElementById('verification-id').textContent = verificationId;
        
        // User information
        if (verification.user) {
            document.getElementById('verification-user-name').textContent = verification.user.name || 'N/A';
            document.getElementById('verification-email').textContent = verification.user.email || 'N/A';
        } else {
            document.getElementById('verification-user-name').textContent = 'User Not Found';
            document.getElementById('verification-email').textContent = 'N/A';
        }
        
        // Format dates - add error handling
        try {
            document.getElementById('verification-created').textContent = verification.created_at ? 
                new Date(verification.created_at).toLocaleString() : 'N/A';
            document.getElementById('verification-updated').textContent = verification.updated_at ? 
                new Date(verification.updated_at).toLocaleString() : 'N/A';
        } catch (e) {
            console.error('Error formatting dates:', e);
            document.getElementById('verification-created').textContent = verification.created_at || 'N/A';
            document.getElementById('verification-updated').textContent = verification.updated_at || 'N/A';
        }
        
        // Status with appropriate styling
        let statusHtml = '';
        if (verification.status === 'pending') {
            statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>';
            document.getElementById('pending-actions').classList.remove('hidden');
        } else if (verification.status === 'verified' || verification.status === 'approved') {
            statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>';
        } else if (verification.status === 'rejected') {
            statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>';
            
            if (verification.rejection_reason) {
                document.getElementById('rejection-reason-container').classList.remove('hidden');
                document.getElementById('verification-rejection-reason').textContent = verification.rejection_reason;
            }
        }
        document.getElementById('verification-status').innerHTML = statusHtml;
        
        // Notes
        document.getElementById('verification-notes').textContent = verification.notes || verification.description || 'Tidak ada catatan tambahan.';
        
        // Documents
        const documentsContainer = document.getElementById('verification-documents');
        if (documentsContainer) {
            documentsContainer.innerHTML = '';
            
            // Check for attachments array first
            if (verification.attachments && verification.attachments.length > 0) {
                verification.attachments.forEach(attachment => {
                    const doc = document.createElement('a');
                    doc.href = `/storage/${attachment.path}`;
                    doc.target = '_blank';
                    doc.className = 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2 mb-2';
                    doc.innerHTML = '<svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> ' + (attachment.name || 'Dokumen');
                    documentsContainer.appendChild(doc);
                });
            } 
            // Single document path as fallback
            else if (verification.document_path) {
                const doc = document.createElement('a');
                doc.href = `/storage/${verification.document_path}`;
                doc.target = '_blank';
                doc.className = 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
                doc.innerHTML = '<svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Lihat Dokumen';
                documentsContainer.appendChild(doc);
            } else {
                documentsContainer.innerHTML = '<span class="text-gray-500">Tidak ada dokumen terlampir</span>';
            }
        }
        
        // Set button data attributes for actions
        const approveBtn = document.getElementById('approve-button');
        const rejectBtn = document.getElementById('reject-button');
        
        if (approveBtn) {
            approveBtn.setAttribute('data-verification-id', verificationId);
            approveBtn.setAttribute('data-id', verificationId);
        }
        
        if (rejectBtn) {
            rejectBtn.setAttribute('data-verification-id', verificationId);
            rejectBtn.setAttribute('data-id', verificationId);
        }
    })
    .catch(error => {
        console.error('Error fetching verification details:', error);
        document.getElementById('verification-loading').classList.add('hidden');
        
        // Show error message
        const errorElement = document.getElementById('verification-error');
        if (errorElement) {
            errorElement.classList.remove('hidden');
        } else {
            ensureErrorElement();
            document.getElementById('verification-error').classList.remove('hidden');
        }
    });
};

/**
 * Ensure error element exists in the modal
 */
function ensureErrorElement() {
    const errorContainer = document.getElementById('verification-error');
    if (!errorContainer) {
        // If error container doesn't exist, create it
        const modalContent = document.querySelector('#showVerificationModal .modal-content');
        if (modalContent) {
            const errorDiv = document.createElement('div');
            errorDiv.id = 'verification-error';
            errorDiv.className = 'hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
            
            const errorMessage = document.createElement('span');
            errorMessage.id = 'error-message';
            errorMessage.className = 'block sm:inline';
            errorMessage.textContent = 'Failed to load verification details. Please try again.';
            
            errorDiv.appendChild(errorMessage);
            modalContent.appendChild(errorDiv);
        }
    }
    
    // Also ensure the error message element exists
    if (!document.getElementById('error-message') && document.getElementById('verification-error')) {
        const errorMessage = document.createElement('span');
        errorMessage.id = 'error-message';
        errorMessage.className = 'block sm:inline';
        errorMessage.textContent = 'Failed to load verification details. Please try again.';
        document.getElementById('verification-error').appendChild(errorMessage);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners to show verification buttons
    document.querySelectorAll('.show-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = this.getAttribute('data-verification-id');
            
            // Try alternative data attributes if primary one isn't available
            if (!verificationId) {
                verificationId = this.getAttribute('data-id');
            }
            
            // Try to get from parent row
            if (!verificationId) {
                const row = this.closest('tr');
                if (row) {
                    verificationId = row.getAttribute('data-verification-id') || row.getAttribute('data-id');
                }
            }
            
            if (!verificationId) {
                console.error('Show button clicked without verification ID');
                return;
            }
            
            window.showVerificationModal(verificationId);
        });
    });
    
    // Close modal on close button click
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.classList.add('hidden');
            });
        });
    });
});
</script> 