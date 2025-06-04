document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = window.csrfToken;
    const achievementRoutes = window.achievementRoutes;

    let handleDeleteSubmitCallCount = 0;
    let submitDeleteFormCallCount = 0;
    let isDeletingAchievement = false;
    
    initializeEventListeners();

    /**
     * Handles the submission of the delete achievement form.
     * @param {Event} event - The form submission event.
     */
    function handleDeleteFormSubmit(event) {
        handleDeleteSubmitCallCount++;
        console.log(`handleDeleteFormSubmit called. Count: ${handleDeleteSubmitCallCount}. Timestamp: ${new Date().toISOString()}`);
        event.preventDefault();

        if (isDeletingAchievement) {
            console.warn('handleDeleteFormSubmit: Delete operation already in progress. Ignoring call.');
            return;
        }
        isDeletingAchievement = true;
        console.log('handleDeleteFormSubmit: Set isDeletingAchievement = true');
        const form = event.target;
        const submitButton = form.querySelector('#confirm-delete-achievement-btn');
        submitDeleteForm(form, submitButton)
            .finally(() => {
                isDeletingAchievement = false;
                console.log('handleDeleteFormSubmit (in submitDeleteForm.finally chain): Reset isDeletingAchievement = false');
            });
    }
    
    /**
     * Initializes all event listeners for achievement operations.
     */
    function initializeEventListeners() {
        initializeShowAchievement();
    }
    
    /**
     * Initializes event listeners for showing achievement details.
     */
    function initializeShowAchievement() {
        const showButtons = document.querySelectorAll('.show-achievement');
        const showModal = document.getElementById('show-achievement-modal');
        const closeButtons = document.querySelectorAll('#close-show-modal, #close-show-achievement-btn');
        
        showButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                let userId = this.getAttribute('data-user-id');
                if (!userId || userId.trim() === '') {
                    const row = this.closest('tr');
                    if (row) {
                        userId = row.getAttribute('data-user-id');
                    }
                }
                let achievementId = this.getAttribute('data-achievement-id');
                
                if (!achievementId || achievementId.trim() === '') {
                    const row = this.closest('tr');
                    if (row) {
                        achievementId = row.getAttribute('data-achievement-id');
                    }
                }
                
                if (achievementId && achievementId.trim() !== '') {
                    fetchAchievementDetails(achievementId, userId);
                    showModal.classList.remove('hidden');
                } else {
                    console.error('Achievement ID is empty or undefined');
                    showNotification('Terjadi kesalahan: ID prestasi tidak valid', 'error');
                }
            });
        });
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                showModal.classList.add('hidden');
            });
        });
        
        showModal.addEventListener('click', function(e) {
            if (e.target === showModal) {
                showModal.classList.add('hidden');
            }
        });
    }
    
    /**
     * Fetches achievement details from the server via AJAX.
     * @param {string} achievementId - The ID of the achievement to fetch.
     */
    function fetchAchievementDetails(achievementId, userId) {
        if (!achievementId || achievementId.trim() === '') {
            console.error('Invalid achievement ID');
            showNotification('ID prestasi tidak valid', 'error');
            return;
        }
        
        const showModal = document.getElementById('show-achievement-modal');
        const showContent = document.getElementById('show-achievement-content');
        const showSkeleton = document.getElementById('show-achievement-skeleton');
        
        showModal.classList.remove('hidden');
        if (showContent) showContent.classList.add('hidden');
        if (showSkeleton) showSkeleton.classList.remove('hidden');
        
        const url = `/lecturer/achievements/${achievementId}/details/${userId}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error('Prestasi tidak ditemukan');
                    } else if (response.status === 403) {
                        throw new Error('Anda tidak memiliki akses ke prestasi ini');
                    } else {
                        throw new Error(`Server responded with status: ${response.status}`);
                    }
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success === false) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memuat data prestasi');
                }
                
                displayAchievementDetails(data);
            })
            .catch(error => {
                console.error('Error fetching achievement details:', error);
                
                let errorMessage = 'Terjadi kesalahan saat mengambil data prestasi.';
                if (error.message) {
                    errorMessage += ' ' + error.message;
                }
                showNotification(errorMessage, 'error');
                
                showModal.classList.add('hidden');
                
                if (showContent) showContent.classList.remove('hidden');
                if (showSkeleton) showSkeleton.classList.add('hidden');
            });
    }
    
    /**
     * Displays the fetched achievement details in the show modal.
     * @param {Object} data - The achievement data object from the server.
     */
    function displayAchievementDetails(data) {
        if (!data || typeof data !== 'object') {
            console.error('Invalid data format received:', data);
            showNotification('Terjadi kesalahan: Format data tidak valid', 'error');
            return;
        }
        
        const achievement = data.achievement;
        const attachments = data.attachments || [];
        
        if (!achievement || typeof achievement !== 'object') {
            console.error('Invalid achievement data format:', data);
            showNotification('Format data prestasi tidak valid', 'error');
            return;
        }
        
        console.log('Achievement data:', achievement);
        console.log('Attachments:', attachments);
        
        const showContent = document.getElementById('show-achievement-content');
        const showSkeleton = document.getElementById('show-achievement-skeleton');
        if (showContent) showContent.classList.remove('hidden');
        if (showSkeleton) showSkeleton.classList.add('hidden');
        
        document.getElementById('show-achievement-title').textContent = achievement.title || 'Tidak ada judul';
        document.getElementById('show-achievement-competition').textContent = achievement.competition_name || 'Tidak ada nama kompetisi';
        
        const statusElement = document.getElementById('show-achievement-status');
        statusElement.textContent = achievement.status === 'verified' ? 'Terverifikasi' : 
                                   (achievement.status === 'pending' ? 'Menunggu' : 'Ditolak');
        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
        
        const rejectionContainer = document.getElementById('rejection-reason-container');
        if (achievement.status === 'rejected' && achievement.rejected_reason) {
            rejectionContainer.classList.remove('hidden');
            document.getElementById('show-achievement-rejection-reason').textContent = achievement.rejected_reason;
        } else {
            rejectionContainer.classList.add('hidden');
        }
        
        if (achievement.status === 'verified') {
            statusElement.classList.add('bg-green-100', 'text-green-800');
        } else if (achievement.status === 'pending') {
            statusElement.classList.add('bg-amber-100', 'text-amber-800');
        } else {
            statusElement.classList.add('bg-red-100', 'text-red-800');
        }
        
        let dateText = 'Tidak ada tanggal';
        if (achievement.date) {
            try {
                const date = new Date(achievement.date);
                dateText = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            } catch (e) {
                console.error('Error formatting date:', e);
            }
        }
        document.getElementById('show-achievement-date').textContent = dateText;
        
        const levelElement = document.getElementById('show-achievement-level');
        levelElement.textContent = achievement.level === 'international' ? 'Internasional' : 
                                  (achievement.level === 'national' ? 'Nasional' : 'Regional');
        levelElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
        
        if (achievement.level === 'international') {
            levelElement.classList.add('bg-red-100', 'text-red-800');
        } else if (achievement.level === 'national') {
            levelElement.classList.add('bg-blue-100', 'text-blue-800');
        } else {
            levelElement.classList.add('bg-green-100', 'text-green-800');
        }
        
        const typeMap = {
            'academic': 'Akademik',
            'technology': 'Teknologi',
            'arts': 'Seni',
            'sports': 'Olahraga',
            'entrepreneurship': 'Kewirausahaan'
        };
        document.getElementById('show-achievement-type').textContent = typeMap[achievement.type] || achievement.type || 'Tidak diketahui';
        
        document.getElementById('show-achievement-description').textContent = achievement.description || 'Tidak ada deskripsi';
        
        const attachmentsContainer = document.getElementById('show-achievement-attachments');
        
        if (Array.isArray(attachments) && attachments.length > 0) {
            let attachmentsHTML = '';
            
            attachments.forEach(attachment => {
                if (!attachment) return;
                
                const fileExtension = attachment.file_path ? attachment.file_path.split('.').pop().toLowerCase() : '';
                const isImage = ['jpg', 'jpeg', 'png'].includes(fileExtension);
                
                attachmentsHTML += `
                    <div class="flex items-center p-2 border border-gray-200 rounded-md">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isImage ? 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' : 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'}" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                ${attachment.file_name || 'Dokumen'}
                            </p>
                            <p class="text-xs text-gray-500">
                                ${formatFileSize(attachment.file_size || 0)}
                            </p>
                        </div>
                        <div>
                            <a href="/storage/${attachment.file_path}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Lihat
                            </a>
                        </div>
                    </div>
                `;
            });
            
            attachmentsContainer.innerHTML = attachmentsHTML;
        } else {
            attachmentsContainer.innerHTML = '<p class="text-sm text-gray-500">Tidak ada lampiran</p>';
        }
        
        document.getElementById('show-achievement-modal').classList.remove('hidden');
    }
    
    /**
     * Initializes event listeners for editing an achievement.
     */
    function initializeEditAchievement() {
        const editButtons = document.querySelectorAll('.btn-edit');
        const editModal = document.getElementById('edit-achievement-modal');
        const closeButtons = document.querySelectorAll('#close-edit-modal, #close-edit-achievement-btn');
        const editForm = document.getElementById('edit-achievement-form');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                let achievementId = this.getAttribute('data-achievement-id');
                
                if (!achievementId || achievementId.trim() === '') {
                    const row = this.closest('tr');
                    if (row) {
                        achievementId = row.getAttribute('data-achievement-id');
                    }
                }
                
                if (achievementId && achievementId.trim() !== '') {
                    console.log('Fetching achievement for edit with ID:', achievementId);
                    fetchAchievementForEdit(achievementId);
                } else {
                    console.error('Achievement ID is empty or undefined');
                    showNotification('Terjadi kesalahan: ID prestasi tidak valid', 'error');
                }
            });
        });
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                editModal.classList.add('hidden');
                
                const editContent = document.getElementById('edit-achievement-content');
                const editSkeleton = document.getElementById('edit-achievement-skeleton');
                
                if (editContent) editContent.classList.remove('hidden');
                if (editSkeleton) editSkeleton.classList.add('hidden');
                
                document.getElementById('edit-achievement-error').classList.add('hidden');
            });
        });
        
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                editModal.classList.add('hidden');
                
                const editContent = document.getElementById('edit-achievement-content');
                const editSkeleton = document.getElementById('edit-achievement-skeleton');
                
                if (editContent) editContent.classList.remove('hidden');
                if (editSkeleton) editSkeleton.classList.add('hidden');
                
                document.getElementById('edit-achievement-error').classList.add('hidden');
            }
        });
        
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditForm(this);
            });
        }
        
        const nextStepButton = document.getElementById('edit-next-step');
        const prevStepButton = document.getElementById('edit-prev-step');
        
        if (nextStepButton) {
            nextStepButton.addEventListener('click', function() {
                if (validateEditStep(1)) {
                    goToEditStep(2);
                }
            });
        }
        
        if (prevStepButton) {
            prevStepButton.addEventListener('click', function() {
                goToEditStep(1);
            });
        }
    }
    
    /**
     * Fetches achievement data to populate the edit form.
     * @param {string} achievementId - The ID of the achievement to edit.
     */
    function fetchAchievementForEdit(achievementId) {
        if (!achievementId || achievementId.trim() === '') {
            console.error('Invalid achievement ID for edit');
            showNotification('ID prestasi tidak valid', 'error');
            return;
        }
        
        const editModal = document.getElementById('edit-achievement-modal');
        const editContent = document.getElementById('edit-achievement-content');
        const editSkeleton = document.getElementById('edit-achievement-skeleton');
        
        editModal.classList.remove('hidden');
        if (editContent) editContent.classList.add('hidden');
        if (editSkeleton) editSkeleton.classList.remove('hidden');
        
        const url = `/student/achievements/${achievementId}/details`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error('Prestasi tidak ditemukan');
                    } else if (response.status === 403) {
                        throw new Error('Anda tidak memiliki akses ke prestasi ini');
                    } else {
                        throw new Error(`Server responded with status: ${response.status}`);
                    }
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success === false) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memuat data prestasi');
                }
                
                populateEditForm(data);
            })
            .catch(error => {
                console.error('Error fetching achievement details for edit:', error);
                
                let errorMessage = 'Terjadi kesalahan saat mengambil data prestasi.';
                if (error.message) {
                    errorMessage += ' ' + error.message;
                }
                showNotification(errorMessage, 'error');
                
                editModal.classList.add('hidden');
                
                if (editContent) editContent.classList.remove('hidden');
                if (editSkeleton) editSkeleton.classList.add('hidden');
            });
    }
    
    /**
     * Populates the edit achievement form with fetched data.
     * @param {Object} data - The achievement data object from the server.
     */
    function populateEditForm(data) {
        if (!data || typeof data !== 'object') {
            console.error('Invalid data format received:', data);
            showNotification('Terjadi kesalahan: Format data tidak valid', 'error');
            return;
        }
        
        const achievement = data.achievement;
        const attachments = data.attachments || [];
        
        if (!achievement || typeof achievement !== 'object') {
            console.error('Invalid achievement data format:', data);
            showNotification('Format data prestasi tidak valid', 'error');
            return;
        }
        
        console.log('Achievement data for edit:', achievement);
        console.log('Attachments for edit:', attachments);
        
        const editForm = document.getElementById('edit-achievement-form');
        const editContent = document.getElementById('edit-achievement-content');
        const editSkeleton = document.getElementById('edit-achievement-skeleton');
        const editStepIndicator = document.getElementById('edit-step-indicator');
        
        const step1Content = document.getElementById('edit-step-1-content');
        const step2Content = document.getElementById('edit-step-2-content');
        if (step1Content) step1Content.classList.remove('hidden');
        if (step2Content) step2Content.classList.add('hidden');
        
        if (editContent) editContent.classList.remove('hidden');
        if (editStepIndicator) editStepIndicator.classList.remove('hidden');
        if (editSkeleton) editSkeleton.classList.add('hidden');
        
        updateEditStepIndicator(1);
        
        const nextButton = document.getElementById('edit-next-step');
        const prevButton = document.getElementById('edit-prev-step');
        const submitButton = document.getElementById('edit-submit-achievement');
        
        if (nextButton) nextButton.classList.remove('hidden');
        if (submitButton) submitButton.classList.add('hidden');
        if (prevButton) prevButton.classList.add('hidden');
        
        const achievementId = achievement.id || achievement.achievement_id;
        editForm.action = `/student/achievements/${achievementId}`;
        
        document.getElementById('edit-achievement-id').value = achievementId;
        
        document.getElementById('edit-title').value = achievement.title || '';
        document.getElementById('edit-competition-name').value = achievement.competition_name || '';
        document.getElementById('edit-type').value = achievement.type || '';
        document.getElementById('edit-level').value = achievement.level || '';
        document.getElementById('edit-date').value = achievement.date || '';
        document.getElementById('edit-description').value = achievement.description || '';
        
        if (achievement.competition_id) {
            document.getElementById('edit-competition-id').value = achievement.competition_id;
        }
        
        const attachmentsContainer = document.getElementById('edit-current-attachments');
        
        if (Array.isArray(attachments) && attachments.length > 0) {
            let attachmentsHTML = '';
            
            attachments.forEach(attachment => {
                if (!attachment) return;
                
                const fileExtension = attachment.file_path ? attachment.file_path.split('.').pop().toLowerCase() : '';
                const isImage = ['jpg', 'jpeg', 'png'].includes(fileExtension);
                
                attachmentsHTML += `
                    <div class="flex items-center p-2 border border-gray-200 rounded-md">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isImage ? 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' : 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'}" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                ${attachment.file_name || 'Dokumen'}
                            </p>
                            <p class="text-xs text-gray-500">
                                ${formatFileSize(attachment.file_size || 0)}
                            </p>
                        </div>
                        <div>
                            <a href="/storage/${attachment.file_path}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Lihat
                            </a>
                        </div>
                    </div>
                `;
            });
            
            attachmentsContainer.innerHTML = attachmentsHTML;
        } else {
            attachmentsContainer.innerHTML = '<p class="text-sm text-gray-500">Tidak ada lampiran</p>';
        }
        
        document.getElementById('edit-achievement-error').classList.add('hidden');
        document.getElementById('edit-achievement-error-list').innerHTML = '';
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        document.getElementById('edit-achievement-modal').classList.remove('hidden');
    }
    
    /**
     * Submits the edited achievement data to the server via AJAX.
     * @param {HTMLFormElement} form - The edit achievement form element.
     */
    function submitEditForm(form) {
        const formData = new FormData(form);
        const achievementId = document.getElementById('edit-achievement-id').value;
        
        showNotification('Sedang menyimpan perubahan...', 'info');
        
        fetch(`/student/achievements/${achievementId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('edit-achievement-modal').classList.add('hidden');
                refreshAchievementsTable();
                showNotification(data.message, 'success');
            } else {
                displayValidationErrors(data.errors);
                showNotification('Terdapat kesalahan pada form. Silakan periksa kembali.', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating achievement:', error);
            showNotification('Terjadi kesalahan saat memperbarui prestasi.', 'error');
        });
    }
    
    /**
     * Initializes event listeners for deleting an achievement.
     */
    function initializeDeleteAchievement() {
        const deleteButtons = document.querySelectorAll('.delete-achievement');
        const deleteModal = document.getElementById('delete-achievement-modal');
        const closeButtons = document.querySelectorAll('#close-delete-modal, #cancel-delete-achievement');
        const deleteForm = document.getElementById('delete-achievement-form');
        const confirmBtn = document.getElementById('confirm-delete-achievement-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const achievementId = this.getAttribute('data-achievement-id');
                const achievementTitle = this.getAttribute('data-achievement-title');
                
                const row = this.closest('tr');
                if (row) {
                    const statusCell = row.querySelector('td:nth-child(6)');
                    if (statusCell && statusCell.textContent.trim().includes('Terverifikasi')) {
                        showNotification('Prestasi yang sudah terverifikasi tidak dapat dihapus.', 'error');
                        return;
                    }
                }
                
                document.getElementById('delete-achievement-id').value = achievementId;
                document.getElementById('delete-achievement-title').textContent = achievementTitle;
                if(deleteForm) deleteForm.action = `/student/achievements/${achievementId}`;
                
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = 'Hapus';
                }
                
                if(deleteModal) deleteModal.classList.remove('hidden');
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if(deleteModal) deleteModal.classList.add('hidden');
            });
        });
        
        if(deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
            });
        }

        if (deleteForm) {
            deleteForm.removeEventListener('submit', handleDeleteFormSubmit);
            deleteForm.addEventListener('submit', handleDeleteFormSubmit);
        }
    }

    /**
     * Submits the delete achievement request to the server via AJAX.
     * @param {HTMLFormElement} form - The delete achievement form element.
     * @param {HTMLElement} submitButton - The submit button element of the delete form.
     * @returns {Promise} The fetch promise.
     */
    function submitDeleteForm(form, submitButton) {
    submitDeleteFormCallCount++;
    console.log(`submitDeleteForm called. Count: ${submitDeleteFormCallCount}. Timestamp: ${new Date().toISOString()}`);
    const achievementId = document.getElementById('delete-achievement-id').value;
    const originalButtonText = submitButton ? submitButton.innerHTML : 'Hapus';

    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = 'Menghapus...';
    }

    return fetch(`/student/achievements/${achievementId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            console.error(`Server responded with ${response.status}. Attempting to parse error as JSON.`);
            return response.json()
                .then(errData => {
                    throw {
                        isOperational: true,
                        status: response.status,
                        message: (errData && errData.message) ? errData.message : `Server error ${response.status}: Prestasi tidak ditemukan atau tidak dapat diproses.`
                    };
                })
                .catch(jsonParseError => {
                    console.error('Failed to parse server error response as JSON. Raw Error:', jsonParseError);
                    throw {
                        isOperational: true,
                        status: response.status,
                        message: `Server error ${response.status}: Respon tidak valid atau prestasi tidak ditemukan.`
                    };
                });
        }
        return response.json();
    })
    .then(data => {
        const deleteModal = document.getElementById('delete-achievement-modal');
        if(deleteModal) deleteModal.classList.add('hidden');
        
        if (data.success) { 
            refreshAchievementsTable();
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Operasi dilaporkan gagal oleh server.', 'error');
        }
    })
    .catch(error => {
        const deleteModal = document.getElementById('delete-achievement-modal');
        if(deleteModal) deleteModal.classList.add('hidden');
        
        console.error('Error caught in submitDeleteForm .catch():', error);
        let messageToShow = 'Terjadi kesalahan umum saat menghapus.';
        if (error && error.message) { 
            messageToShow = error.message;
        }
        
        showNotification(messageToShow, 'error');
    })
    .finally(() => {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
        console.log('submitDeleteForm (internal finally): Button state restored.');
    });
    }

    /**
     * Displays validation errors in the specified form.
     * @param {Object} errors - An object containing field names as keys and arrays of error messages as values.
     * @param {string} [formType='edit'] - The type of form ('create' or 'edit') to display errors for.
     */
    function displayValidationErrors(errors, formType = 'edit') {
        const prefix = formType === 'create' ? 'create' : 'edit';
        const errorContainer = document.getElementById(`${prefix}-achievement-error`);
        const errorList = document.getElementById(`${prefix}-achievement-error-list`);
        const errorCount = document.getElementById(`${prefix}-achievement-error-count`);
        
        if (errorContainer && errorList && errors) {
            errorList.innerHTML = '';
            
            let count = 0;
            for (const field in errors) {
                if (errors.hasOwnProperty(field)) {
                    errors[field].forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        errorList.appendChild(li);
                        count++;
                    });
                }
            }
            
            errorCount.textContent = count;
            
            errorContainer.classList.remove('hidden');
        }
    }
    
    /**
     * Refreshes the achievements table with updated data from the server and re-initializes event listeners.
     */
    function refreshAchievementsTable() {
        const tableContainer = document.getElementById('achievements-table-container');
        if (tableContainer) {
            tableContainer.innerHTML = `
                <div class="flex justify-center items-center py-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            `;
        }
        
        fetch(window.achievementRoutes.index, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (tableContainer && data.table) {
                    tableContainer.innerHTML = data.table;
                }
                
                const paginationContainer = document.getElementById('pagination-container');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                initializeShowAchievement();
            } else {
                console.error('Error refreshing table:', data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error refreshing achievements table:', error);
        });
    }
    
    /**
     * Displays a notification message to the user.
     * @param {string} message - The message to display in the notification.
     * @param {string} type - The type of notification ('success' or 'error').
     */
    function showNotification(message, type) {
        let container = document.getElementById('notification-container');
        
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-4 max-w-md';
            document.body.appendChild(container);
        }
        
        const notification = document.createElement('div');
        notification.className = `p-4 rounded-lg shadow-lg flex items-start gap-3 transform translate-x-full transition-transform duration-300 ease-out ${type === 'success' ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500'}`;
        
        notification.innerHTML = `
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    ${ '<!-- SVG Path Placeholder -->' }
                </svg>
            </div>
            <div class="ml-3 flex-1 break-words">
                <p class="text-sm ${type === 'success' ? 'text-green-700' : 'text-red-700'}">${message}</p>
            </div>
            <div class="flex-shrink-0 flex ml-2">
                <button class="inline-flex text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);
        
        const closeButton = notification.querySelector('button');
        closeButton.addEventListener('click', () => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
    
    /**
     * Formats a file size from bytes to a human-readable string (e.g., KB, MB).
     * @param {number} bytes - The file size in bytes.
     * @returns {string} The formatted file size string.
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Updates the visual step indicator for the edit achievement form.
     * @param {number} stepNumber - The current step number.
     */
    function updateEditStepIndicator(stepNumber) {
        document.querySelectorAll('.edit-step-item').forEach((item, index) => {
            if (index + 1 === stepNumber) {
                item.classList.add('active');
                item.querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
                item.querySelector('div').classList.add('bg-blue-600', 'text-white');
            } else if (index + 1 < stepNumber) {
                item.classList.add('completed');
                item.querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
                item.querySelector('div').classList.add('bg-green-500', 'text-white');
            } else {
                item.classList.remove('active', 'completed');
                item.querySelector('div').classList.remove('bg-blue-600', 'bg-green-500', 'text-white');
                item.querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
            }
        });
    }

    /**
     * Navigates to a specific step in the edit achievement form.
     * @param {number} stepNumber - The step number to navigate to.
     */
    function goToEditStep(stepNumber) {
        const step1Content = document.getElementById('edit-step-1-content');
        const step2Content = document.getElementById('edit-step-2-content');
        
        if (step1Content) step1Content.classList.add('hidden');
        if (step2Content) step2Content.classList.add('hidden');
        
        if (stepNumber === 1 && step1Content) {
            step1Content.classList.remove('hidden');
        } else if (stepNumber === 2 && step2Content) {
            step2Content.classList.remove('hidden');
        }
        
        updateEditStepIndicator(stepNumber);
        
        const nextButton = document.getElementById('edit-next-step');
        const prevButton = document.getElementById('edit-prev-step');
        const submitButton = document.getElementById('edit-submit-achievement');
        
        if (stepNumber > 1) {
            if (prevButton) prevButton.classList.remove('hidden');
        } else {
            if (prevButton) prevButton.classList.add('hidden');
        }
        
        if (stepNumber === 2) {
            if (nextButton) nextButton.classList.add('hidden');
            if (submitButton) submitButton.classList.remove('hidden');
        } else {
            if (nextButton) nextButton.classList.remove('hidden');
            if (submitButton) submitButton.classList.add('hidden');
        }
    }

    /**
     * Validates the fields of a specific step in the edit achievement form.
     * @param {number} stepNumber - The step number to validate.
     * @returns {boolean} True if the step is valid, false otherwise.
     */
    function validateEditStep(stepNumber) {
        let isValid = true;
        const errorMessages = [];
        
        if (stepNumber === 1) {
            const title = document.getElementById('edit-title').value.trim();
            if (!title) {
                isValid = false;
                errorMessages.push('Judul prestasi wajib diisi');
                document.getElementById('edit-title-error').textContent = 'Judul prestasi wajib diisi';
                document.getElementById('edit-title-error').classList.remove('hidden');
            } else {
                document.getElementById('edit-title-error').classList.add('hidden');
            }
            
            const compName = document.getElementById('edit-competition-name').value.trim();
            if (!compName) {
                isValid = false;
                errorMessages.push('Nama kompetisi/event wajib diisi');
                document.getElementById('edit-competition-name-error').textContent = 'Nama kompetisi/event wajib diisi';
                document.getElementById('edit-competition-name-error').classList.remove('hidden');
            } else {
                document.getElementById('edit-competition-name-error').classList.add('hidden');
            }
            
            const type = document.getElementById('edit-type').value;
            if (!type) {
                isValid = false;
                errorMessages.push('Jenis prestasi wajib dipilih');
                document.getElementById('edit-type-error').textContent = 'Jenis prestasi wajib dipilih';
                document.getElementById('edit-type-error').classList.remove('hidden');
            } else {
                document.getElementById('edit-type-error').classList.add('hidden');
            }
            
            const level = document.getElementById('edit-level').value;
            if (!level) {
                isValid = false;
                errorMessages.push('Tingkat prestasi wajib dipilih');
                document.getElementById('edit-level-error').textContent = 'Tingkat prestasi wajib dipilih';
                document.getElementById('edit-level-error').classList.remove('hidden');
            } else {
                document.getElementById('edit-level-error').classList.add('hidden');
            }
            
            const date = document.getElementById('edit-date').value;
            if (!date) {
                isValid = false;
                errorMessages.push('Tanggal prestasi wajib diisi');
                document.getElementById('edit-date-error').textContent = 'Tanggal prestasi wajib diisi';
                document.getElementById('edit-date-error').classList.remove('hidden');
            } else {
                document.getElementById('edit-date-error').classList.add('hidden');
            }
        } else if (stepNumber === 2) {
            const description = document.getElementById('edit-description').value.trim();
            if (!description) {
                isValid = false;
                errorMessages.push('Deskripsi prestasi wajib diisi');
                document.getElementById('edit-description-error').textContent = 'Deskripsi prestasi wajib diisi';
                document.getElementById('edit-description-error').classList.remove('hidden');
            } else {
                document.getElementById('edit-description-error').classList.add('hidden');
            }
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('edit-achievement-error');
            const errorList = document.getElementById('edit-achievement-error-list');
            const errorCount = document.getElementById('edit-achievement-error-count');
            
            errorContainer.classList.remove('hidden');
            errorCount.textContent = errorMessages.length;
            
            errorList.innerHTML = '';
            
            errorMessages.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
            });
        } else {
            document.getElementById('edit-achievement-error').classList.add('hidden');
        }
        
        return isValid;
    }

    /**
     * Navigates to a specific step in the create achievement form.
     * @param {number} stepNumber - The step number to navigate to.
     */
    function goToStep(stepNumber) {
        Object.values(stepContents).forEach(content => {
            content.classList.add('hidden');
        });
        
        stepContents[stepNumber].classList.remove('hidden');
        
        document.querySelectorAll('.step-item').forEach((item, index) => {
            if (index + 1 === stepNumber) {
                item.classList.add('active');
                item.querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
                item.querySelector('div').classList.add('bg-blue-600', 'text-white');
            } else if (index + 1 < stepNumber) {
                item.classList.add('completed');
                item.querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
                item.querySelector('div').classList.add('bg-green-500', 'text-white');
            } else {
                item.classList.remove('active', 'completed');
                item.querySelector('div').classList.remove('bg-blue-600', 'bg-green-500', 'text-white');
                item.querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
            }
        });
        
        if (stepNumber > 1) {
            prevStepBtn.classList.remove('hidden');
        } else {
            prevStepBtn.classList.add('hidden');
        }
        
        if (stepNumber === totalSteps) {
            nextStepBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextStepBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
        
        currentStep = stepNumber;
    }
    
    /**
     * Validates the fields of a specific step in the create achievement form.
     * @param {number} stepNumber - The step number to validate.
     * @returns {boolean} True if the step is valid, false otherwise.
     */
    function validateStep(stepNumber) {
        let isValid = true;
        const errorMessages = [];
        
        if (stepNumber === 1) {
            const title = document.getElementById('create-title').value.trim();
            if (!title) {
                isValid = false;
                errorMessages.push('Judul prestasi wajib diisi');
                document.getElementById('title-error').textContent = 'Judul prestasi wajib diisi';
                document.getElementById('title-error').classList.remove('hidden');
            } else {
                document.getElementById('title-error').classList.add('hidden');
            }
            
            const compName = document.getElementById('create-competition-name').value.trim();
            if (!compName) {
                isValid = false;
                errorMessages.push('Nama kompetisi/event wajib diisi');
                document.getElementById('competition-name-error').textContent = 'Nama kompetisi/event wajib diisi';
                document.getElementById('competition-name-error').classList.remove('hidden');
            } else {
                document.getElementById('competition-name-error').classList.add('hidden');
            }
            
            const type = document.getElementById('create-type').value;
            if (!type) {
                isValid = false;
                errorMessages.push('Jenis prestasi wajib dipilih');
                document.getElementById('type-error').textContent = 'Jenis prestasi wajib dipilih';
                document.getElementById('type-error').classList.remove('hidden');
            } else {
                document.getElementById('type-error').classList.add('hidden');
            }
            
            const level = document.getElementById('create-level').value;
            if (!level) {
                isValid = false;
                errorMessages.push('Tingkat prestasi wajib dipilih');
                document.getElementById('level-error').textContent = 'Tingkat prestasi wajib dipilih';
                document.getElementById('level-error').classList.remove('hidden');
            } else {
                document.getElementById('level-error').classList.add('hidden');
            }
            
            const date = document.getElementById('create-date').value;
            if (!date) {
                isValid = false;
                errorMessages.push('Tanggal prestasi wajib diisi');
                document.getElementById('date-error').textContent = 'Tanggal prestasi wajib diisi';
                document.getElementById('date-error').classList.remove('hidden');
            } else {
                document.getElementById('date-error').classList.add('hidden');
            }
        } else if (stepNumber === 2) {
            const description = document.getElementById('create-description').value.trim();
            if (!description) {
                isValid = false;
                errorMessages.push('Deskripsi prestasi wajib diisi');
                document.getElementById('description-error').textContent = 'Deskripsi prestasi wajib diisi';
                document.getElementById('description-error').classList.remove('hidden');
            } else {
                document.getElementById('description-error').classList.add('hidden');
            }
            
            const attachments = document.getElementById('create-attachments').files;
            if (attachments.length === 0) {
                isValid = false;
                errorMessages.push('Bukti prestasi wajib diunggah');
                document.getElementById('attachments-error').textContent = 'Bukti prestasi wajib diunggah';
                document.getElementById('attachments-error').classList.remove('hidden');
            } else {
                let validSize = true;
                for (let i = 0; i < attachments.length; i++) {
                    if (attachments[i].size > 2 * 1024 * 1024) { // 2MB
                        validSize = false;
                        break;
                    }
                }
                
                if (!validSize) {
                    isValid = false;
                    errorMessages.push('Ukuran file tidak boleh melebihi 2MB');
                    document.getElementById('attachments-error').textContent = 'Ukuran file tidak boleh melebihi 2MB';
                    document.getElementById('attachments-error').classList.remove('hidden');
                } else {
                    document.getElementById('attachments-error').classList.add('hidden');
                }
            }
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('create-achievement-error');
            const errorList = document.getElementById('create-achievement-error-list');
            const errorCount = document.getElementById('create-achievement-error-count');
            
            errorContainer.classList.remove('hidden');
            errorCount.textContent = errorMessages.length;
            
            errorList.innerHTML = '';
            
            errorMessages.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
            });
        } else {
            document.getElementById('create-achievement-error').classList.add('hidden');
        }
        
        return isValid;
    }
    
    /**
     * Submits the new achievement data to the server.
     * @param {HTMLFormElement} form - The create achievement form element.
     */
    function submitCreateForm(form) {
        const submitBtn = document.getElementById('submit-achievement');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        }
        
        const errorContainer = document.getElementById('create-achievement-error');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
        
        const formData = new FormData(form);
        
        // Use the route from window.achievementRoutes
        const url = window.achievementRoutes.store;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success notification
                showNotification('Prestasi berhasil ditambahkan', 'success');
                
                // Close the modal
                const createModal = document.getElementById('create-achievement-modal');
                if (createModal) {
                    createModal.classList.add('hidden');
                }
                
                // Reset the form
                form.reset();
                goToStep(1);
                
                // Refresh the table
                refreshAchievementsTable();
            } else {
                // Show error notification
                showNotification('Gagal menambahkan prestasi', 'error');
                
                // Display validation errors if any
                if (data.errors) {
                    displayValidationErrors(data.errors, 'create');
                }
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            showNotification('Terjadi kesalahan saat menambahkan prestasi', 'error');
        })
        .finally(() => {
            // Re-enable the submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Prestasi';
            }
        });
    }
});
