console.log('Verification.js loading...');
try {
    /**
     * Initializes the verification module when the DOM is fully loaded.
     */
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Verification.js DOM loaded');
        window.verificationRoutes = window.verificationRoutes || {
            index: '/admin/verification',
            show: '/admin/verification/__ID__',
            update: '/admin/verification/__ID__'
        };
        
        window.csrfToken = window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const searchForm = document.getElementById('search-form');
        const filterSelect = document.getElementById('filter-select');
        
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                updateVerificationTable(false);
            });
        }
        
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                updateVerificationTable(false);
            });
        }
        
        initVerificationSystem();
        
        window.initVerificationSystem = initVerificationSystem;
    });
} catch (error) {
    console.error('Error in verification.js initialization:', error);
}

/**
 * Initializes the verification system by attaching event listeners to various elements.
 */
function initVerificationSystem() {
    document.querySelectorAll('.show-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = getVerificationId(this);
            if (!verificationId) {
                console.error('Show button clicked without verification ID');
                return;
            }
            openVerificationModal(verificationId);
        });
    });
    
    document.querySelectorAll('.close-modal, #close-show-modal').forEach(button => {
        button.addEventListener('click', closeModals);
    });
    
    document.getElementById('approve-button')?.addEventListener('click', function() {
        let verificationId = getVerificationId(this);
        if (!verificationId) {
            console.error('Approve button clicked without verification ID');
            return;
        }
        document.getElementById('confirm-approve').setAttribute('data-verification-id', verificationId);
        document.getElementById('showVerificationModal').classList.add('hidden');
        document.getElementById('approveModal').classList.remove('hidden');
    });
    
    document.getElementById('reject-button')?.addEventListener('click', function() {
        let verificationId = getVerificationId(this);
        if (!verificationId) {
            console.error('Reject button clicked without verification ID');
            return;
        }
        document.getElementById('reject-form').setAttribute('data-verification-id', verificationId);
        document.getElementById('showVerificationModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('hidden');
    });
    
    document.querySelectorAll('.cancel-approve, .cancel-reject').forEach(button => {
        button.addEventListener('click', function() {
            closeModals();
        });
    });
    
    document.querySelectorAll('.approve-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = getVerificationId(this);
            if (!verificationId) {
                console.error('Approve verification button clicked without ID');
                return;
            }
            showApproveModal(verificationId);
        });
    });
    
    document.querySelectorAll('.reject-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = getVerificationId(this);
            if (!verificationId) {
                console.error('Reject verification button clicked without ID');
                return;
            }
            showRejectModal(verificationId);
        });
    });
}

/**
 * Retrieves the verification ID from a given HTML element.
 */
function getVerificationId(element) {
    let verificationId = element.getAttribute('data-verification-id');
    
    if (!verificationId) {
        verificationId = element.getAttribute('data-id');
    }
    
    if (!verificationId) {
        const row = element.closest('tr');
        if (row) {
            verificationId = row.getAttribute('data-verification-id') || row.getAttribute('data-id');
        }
    }
    
    return verificationId;
}

/**
 * Opens the verification detail modal and fetches verification data.
 */
function openVerificationModal(verificationId) {
    console.log('Opening verification modal for ID:', verificationId);
    
    const modalElement = document.getElementById('showVerificationModal');
    if (modalElement) {
        modalElement.classList.remove('hidden');
        void modalElement.offsetHeight;
    } else {
        console.error('Modal element not found in the DOM');
        return;
    }
    
    document.querySelectorAll('.verification-info').forEach(el => el.classList.add('hidden'));
    
    const loadingElement = document.getElementById('verification-loading');
    if (loadingElement) {
        loadingElement.classList.remove('hidden');
    }
    
    const pendingActionsElement = document.getElementById('pending-actions');
    if (pendingActionsElement) {
        pendingActionsElement.classList.add('hidden');
    }
    
    const rejectionReasonElement = document.getElementById('rejection-reason-container');
    if (rejectionReasonElement) {
        rejectionReasonElement.classList.add('hidden');
    }
    
    const url = window.verificationRoutes.show.replace('__ID__', verificationId);
    console.log('Fetching from URL:', url);
    
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
        
        let verification;
        if (data.achievement) {
            verification = data.achievement;
        } else if (data.verification) {
            verification = data.verification;
        } else if (data.id || data.achievement_id) {
            verification = data;
        } else {
            throw new Error('Invalid response: verification data is missing');
        }
        
        console.log('Verification object:', verification);
        
        if (loadingElement) {
            loadingElement.classList.add('hidden');
        }
        
        const infoElement = document.getElementById('verification-info');
        if (infoElement) {
            infoElement.classList.remove('hidden');
            console.log('Verification info element classList after unhiding:', infoElement.classList);
        }
        
        populateVerificationDetails(verification);
    })
    .catch(error => {
        console.error('Error fetching verification details:', error);
        if (loadingElement) {
            loadingElement.classList.add('hidden');
        }
        
        const errorElement = document.getElementById('verification-error');
        if (errorElement) {
            errorElement.classList.remove('hidden');
        } else {
            ensureErrorElement();
            const newErrorElement = document.getElementById('verification-error');
            if (newErrorElement) {
                newErrorElement.classList.remove('hidden');
            }
        }
    });
}

/**
 * Populates the verification detail modal with the provided verification data.
 */
function populateVerificationDetails(verification) {
    const verificationId = verification.id || verification.achievement_id;
    if (!verificationId) {
        console.error('Verification ID is missing in the response');
        return;
    }
    
    document.getElementById('verification-id').textContent = verificationId;
    
    if (verification.user) {
        document.getElementById('verification-user-name').textContent = verification.user.name || 'N/A';
        document.getElementById('verification-email').textContent = verification.user.email || 'N/A';
    } else {
        document.getElementById('verification-user-name').textContent = 'User Not Found';
        document.getElementById('verification-email').textContent = 'N/A';
    }
    
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
    
    let statusHtml = '';
    if (verification.status === 'pending') {
        statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>';
        document.getElementById('pending-actions')?.classList.remove('hidden');
    } else if (verification.status === 'verified' || verification.status === 'approved') {
        statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>';
    } else if (verification.status === 'rejected') {
        statusHtml = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>';
        
        if (verification.rejection_reason) {
            document.getElementById('rejection-reason-container')?.classList.remove('hidden');
            document.getElementById('verification-rejection-reason').textContent = verification.rejection_reason;
        }
    }
    document.getElementById('verification-status').innerHTML = statusHtml;
    
    document.getElementById('verification-notes').textContent = verification.notes || 'Tidak ada catatan tambahan.';
    
    const documentsContainer = document.getElementById('verification-documents');
    if (documentsContainer) {
        documentsContainer.innerHTML = '';
        
        if (verification.attachments && verification.attachments.length > 0) {
            verification.attachments.forEach(attachment => {
                const doc = document.createElement('a');
                const filePath = typeof attachment === 'object' ? (attachment.path || attachment.file_path) : attachment;
                doc.href = filePath.startsWith('/') ? filePath : `/storage/${filePath}`;
                doc.target = '_blank';
                doc.className = 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
                doc.innerHTML = '<svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Dokumen '+(verification.attachments.indexOf(attachment)+1);
                documentsContainer.appendChild(doc);
            });
        } 
        else if (verification.document_path) {
            const doc = document.createElement('a');
            const filePath = verification.document_path;
            doc.href = filePath.startsWith('/') ? filePath : `/storage/${filePath}`;
            doc.target = '_blank';
            doc.className = 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
            doc.innerHTML = '<svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Lihat Dokumen';
            documentsContainer.appendChild(doc);
        } 
        else if (verification.documents && verification.documents.length > 0) {
            verification.documents.forEach((documentData, index) => {
                const doc = document.createElement('a');
                let filePath;
                if (typeof documentData === 'object') {
                    filePath = documentData.path || documentData.file_path;
                } else {
                    filePath = documentData;
                }
                
                doc.href = filePath.startsWith('/') ? filePath : `/storage/${filePath}`;
                doc.target = '_blank';
                doc.className = 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
                doc.innerHTML = '<svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Dokumen '+(index+1);
                documentsContainer.appendChild(doc);
            });
        }
        else {
            documentsContainer.innerHTML = '<span class="text-gray-500">Tidak ada dokumen terlampir</span>';
        }
    }
    
    const approveBtn = document.getElementById('approve-button');
    const rejectBtn = document.getElementById('reject-button');
    
    if (approveBtn) {
        approveBtn.setAttribute('data-verification-id', verificationId);
    }
    
    if (rejectBtn) {
        rejectBtn.setAttribute('data-verification-id', verificationId);
    }
}

/**
 * Displays the modal for confirming verification approval.
 */
function showApproveModal(verificationId) {
    if (!verificationId) {
        console.error('No verification ID provided for approval');
        return;
    }
    
    document.getElementById('confirm-approve').setAttribute('data-verification-id', verificationId);
    document.getElementById('approveModal').classList.remove('hidden');
}

/**
 * Displays the modal for confirming verification rejection.
 */
function showRejectModal(verificationId) {
    if (!verificationId) {
        console.error('No verification ID provided for rejection');
        return;
    }
    
    document.getElementById('reject-form').setAttribute('data-verification-id', verificationId);
    document.getElementById('rejectModal').classList.remove('hidden');
}

/**
 * Closes all active verification-related modals.
 */
function closeModals() {
    const modals = ['showVerificationModal', 'approveModal', 'rejectModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    });
}

/**
 * Applies the current filter settings to update the verification table.
 */
function applyFilter() {
    updateVerificationTable(false);
}

/**
 * Updates the verification table content based on search and filter parameters via an AJAX request.
 */
function updateVerificationTable(preserveUrl = true, explicitSearchQuery = null, explicitFilterValue = null) {
    let searchQuery, filterValue;

    if (explicitSearchQuery !== null && explicitFilterValue !== null) {
        searchQuery = explicitSearchQuery;
        filterValue = explicitFilterValue;
        console.log(`updateVerificationTable: Using explicit searchQuery='${searchQuery}', filterValue='${filterValue}'`);
    } else {
        const searchInput = document.getElementById('search-input');
        const filterSelect = document.getElementById('filter-select');
        searchQuery = searchInput ? searchInput.value : '';
        filterValue = filterSelect ? filterSelect.value : 'all';
        console.log(`updateVerificationTable: Using DOM searchQuery='${searchQuery}', filterValue='${filterValue}'`);
    }
    
    const url = new URL(window.verificationRoutes.index, window.location.origin);
    url.searchParams.append('search', searchQuery);
    url.searchParams.append('status', filterValue);
    url.searchParams.append('ajax', 1);
    
    if (!preserveUrl) {
        const newUrl = new URL(window.verificationRoutes.index, window.location.origin);
        if (searchQuery) newUrl.searchParams.append('search', searchQuery);
        if (filterValue && filterValue !== 'all') newUrl.searchParams.append('status', filterValue);
        window.history.pushState({}, '', newUrl.toString());
    }
    
    const tableContainer = document.getElementById('verifications-table-container');
    const paginationContainer = document.getElementById('pagination-container');
    
    if (tableContainer) {
        tableContainer.innerHTML = `
            <div class="flex justify-center items-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
            </div>
        `;
    }
    
    console.log('Fetching verification data from:', url.toString());
    
    fetch(url.toString(), {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            console.error('Server response not OK:', response.status, response.statusText);
            throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        if (data && data.tableHtml) {
            if (tableContainer) tableContainer.innerHTML = data.tableHtml;
            if (paginationContainer && data.paginationHtml) paginationContainer.innerHTML = data.paginationHtml;
            attachEventListeners();
        } else {
            console.error('Invalid data format received:', data);
            throw new Error('Invalid data format received from server');
        }
    })
    .catch(error => {
        console.error('Error fetching verification data:', error);
        if (tableContainer) {
            tableContainer.innerHTML = `
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">An error occurred!</strong>
                    <span class="block sm:inline"> Failed to load verification data. Please refresh the page.</span>
                </div>
            `;
        }
    });
}

/**
 * Attaches event listeners to dynamically loaded elements, typically after table updates.
 */
function attachEventListeners() {
    document.querySelectorAll('.show-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = getVerificationId(this);
            if (!verificationId) {
                console.error('Show verification button clicked without ID');
                return;
            }
            openVerificationModal(verificationId);
        });
    });
    
    document.querySelectorAll('.approve-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = getVerificationId(this);
            if (!verificationId) {
                console.error('Approve verification button clicked without ID');
                return;
            }
            showApproveModal(verificationId);
        });
    });
    
    document.querySelectorAll('.reject-verification').forEach(button => {
        button.addEventListener('click', function() {
            let verificationId = getVerificationId(this);
            if (!verificationId) {
                console.error('Reject verification button clicked without ID');
                return;
            }
            showRejectModal(verificationId);
        });
    });
}

/**
 * Updates the status of a verification item via an API call.
 */
function updateVerificationStatus(id, status, reasonOrNote = null) {
    console.log('Starting updateVerificationStatus with:', { id, status, reasonOrNote });
    
    if (!id) {
        console.error('Invalid ID provided to updateVerificationStatus');
        return;
    }
    
    if (!status) {
        console.error('Invalid status provided to updateVerificationStatus');
        return;
    }
    
    const url = window.verificationRoutes.update.replace('__ID__', id);
    console.log('Update URL:', url);

    const searchInput = document.getElementById('search-input');
    const filterSelect = document.getElementById('filter-select');
    const currentSearchQuery = searchInput ? searchInput.value : '';
    const currentFilterValue = filterSelect ? filterSelect.value : 'all';
    
    const payload = { status };
    
    if (status === 'rejected') {
        if (reasonOrNote) {
            payload.reason = reasonOrNote;
        }
    } else if (status === 'verified') {
        if (reasonOrNote) {
            payload.note = reasonOrNote;
        }
    }

    console.log('Payload:', payload);

    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(function(response) {
        if (!response.ok) {
            return response.json().then(function(errData) {
                throw { 
                    success: false, 
                    message: errData.message || 'Server error: ' + response.status, 
                    data: errData 
                };
            }).catch(function() {
                throw { 
                    success: false, 
                    message: 'Network response was not ok (' + response.status + ')' 
                };
            });
        }
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            let message = data.message;
            if (!message) {
                message = (status === 'verified') ? 
                    'Prestasi berhasil diverifikasi.' : 
                    'Prestasi berhasil ditolak.';
            }
            showNotification(message, 'success');
            closeModals();
            window.location.reload();
        } else {
            showNotification(
                data.message || 'Gagal memperbarui status. Silakan coba lagi.', 
                'error'
            );
        }
    })
    .catch(function(error) {
        console.error('Error in updateVerificationStatus:', error);
        const errorMessage = error.message || 'Terjadi kesalahan teknis. Silakan coba lagi.';
        showNotification(errorMessage, 'error');
    });
}

attachEventListeners();

/**
 * Refreshes notifications in the sidebar (placeholder function).
 */
function refreshSidebarNotifications() {
    console.log('Attempting to refresh sidebar notifications...');
}

/**
 * Displays a notification message to the user.
 * @param {string} message - The message to display.
 * @param {string} type - The type of notification (success, error).
 */
function showNotification(message, type = 'success') {
    const existingNotifications = document.querySelectorAll('#notification-container > div');
    for (const notification of existingNotifications) {
        const messageElement = notification.querySelector('.text-sm');
        if (messageElement && messageElement.textContent === message) {
            return;
        }
    }
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 right-4 z-50';
        document.body.appendChild(notificationContainer);
    }
    
    const notification = document.createElement('div');
    notification.className = `flex items-center p-4 mb-4 rounded-lg shadow-md transition-all transform translate-x-full ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    
    const icon = document.createElement('div');
    icon.className = 'mr-3 flex-shrink-0';
    
    if (type === 'success') {
        icon.innerHTML = `<svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>`;
    } else {
        icon.innerHTML = `<svg class="w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>`;
    }
    
    notification.appendChild(icon);
    
    const messageElement = document.createElement('div');
    messageElement.className = 'text-sm font-medium';
    messageElement.textContent = message;
    notification.appendChild(messageElement);
    
    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex h-8 w-8 text-gray-500 hover:text-gray-700 hover:bg-gray-100';
    closeButton.innerHTML = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
    </svg>`;
    
    closeButton.addEventListener('click', () => {
        removeNotification(notification);
    });
    
    notification.appendChild(closeButton);
    notificationContainer.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);
    
    setTimeout(() => {
        removeNotification(notification);
    }, 5000);
}

/**
 * Removes a specific notification element from the DOM.
 * @param {HTMLElement} notification - The notification element to remove.
 */
function removeNotification(notification) {
    notification.classList.remove('translate-x-0');
    notification.classList.add('translate-x-full');
    
    setTimeout(() => {
        notification.remove();
    }, 300);
}

const approveModal = document.getElementById('approveModal');
const rejectModal = document.getElementById('rejectModal');

if (approveModal) {
    document.querySelectorAll('.cancel-approve').forEach(button => {
        button.addEventListener('click', function() {
            approveModal.classList.add('hidden');
        });
    });
    
    document.getElementById('confirm-approve')?.addEventListener('click', function() {
        const id = this.getAttribute('data-verification-id');
        const noteInput = document.getElementById('approve_note'); 
        const note = noteInput ? noteInput.value : '';
        if (id) {
            updateVerificationStatus(id, 'verified', note); 
        }
    });
}

if (rejectModal) {
    document.querySelectorAll('.cancel-reject').forEach(button => {
        button.addEventListener('click', function() {
            rejectModal.classList.add('hidden');
        });
    });
    
    document.getElementById('reject-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-verification-id');
        const reasonInput = document.getElementById('rejection_reason'); 
        const reason = reasonInput ? reasonInput.value : '';
        if (id && reason) {
            updateVerificationStatus(id, 'rejected', reason);
        } else if (!reason) {
            showNotification('Alasan penolakan harus diisi.', 'error');
        }
    });
}

/**
 * Ensures that an error display element exists within the verification modal.
 */
function ensureErrorElement() {
    const errorContainer = document.getElementById('verification-error');
    if (!errorContainer) {
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
    
    if (!document.getElementById('error-message') && document.getElementById('verification-error')) {
        const errorMessage = document.createElement('span');
        errorMessage.id = 'error-message';
        errorMessage.className = 'block sm:inline';
        errorMessage.textContent = 'Failed to load verification details. Please try again.';
        document.getElementById('verification-error').appendChild(errorMessage);
    }
} 