document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('add-achievement-modal');
    const editModal = document.getElementById('edit-achievement-modal');
    const showModal = document.getElementById('show-achievement-modal');
    const deleteModal = document.getElementById('delete-achievement-modal');

    const addModalContainer = document.getElementById('add-modal-container');
    const deleteModalContainer = document.getElementById('delete-modal-container');

    const addForm = document.getElementById('add-achievement-form');
    const editForm = document.getElementById('edit-achievement-form');
    const deleteForm = document.getElementById('delete-achievement-form');

    const confirmDeleteBtn = document.getElementById('confirm-delete-achievement');

    setupMultiStepForm();

    // Function to show the add achievement modal
    function showAddModal() {
        if (!addModal || !addModalContainer) return;
        
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
        setTimeout(() => {
            addModalContainer.classList.add('animate-modal-appear');
        }, 10);
        
        resetMultiStepForm();
    }

    // Function to hide the add achievement modal
    function hideAddModal() {
        if (!addModal || !addModalContainer) return;
        
        addModalContainer.classList.remove('animate-modal-appear');
        addModalContainer.classList.add('animate-modal-disappear');
        setTimeout(() => {
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
            addModalContainer.classList.remove('animate-modal-disappear');
            
            if (addForm) {
                addForm.reset();
                resetMultiStepForm();
                resetFormErrors();
            }
        }, 300);
    }

    // Function to show the edit achievement modal
    function showEditModal() {
        if (!editModal) return;
        
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    }

    // Function to hide the edit achievement modal
    function hideEditModal() {
        if (!editModal) return;
        
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
        
        resetFieldErrors();
    }

    // Function to show the delete confirmation modal
    function showDeleteModal() {
        if (!deleteModal || !deleteModalContainer) return;
        
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        setTimeout(() => {
            deleteModalContainer.classList.add('animate-modal-appear');
        }, 10);
    }

    // Function to hide the delete confirmation modal
    function hideDeleteModal() {
        if (!deleteModal || !deleteModalContainer) return;
        
        deleteModalContainer.classList.remove('animate-modal-appear');
        deleteModalContainer.classList.add('animate-modal-disappear');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            deleteModalContainer.classList.remove('animate-modal-disappear');
        }, 300);
    }

    // Function to show the achievement details modal
    function showAchievementDetailsModal() {
        if (!showModal) return;
        
        showModal.classList.remove('hidden');
        showModal.classList.add('flex');
    }

    // Function to hide the achievement details modal
    function hideAchievementDetailsModal() {
        if (!showModal) return;
        
        showModal.classList.add('hidden');
        showModal.classList.remove('flex');
    }

    window.showAddAchievementModal = showAddModal;

    if (addModal) {
        const closeAddModalBtn = document.getElementById('close-add-modal');
        const cancelAddBtn = document.getElementById('cancel-add-achievement');
        
        [closeAddModalBtn, cancelAddBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', hideAddModal);
            }
        });
        
        addModal.addEventListener('click', function(e) {
            if (e.target === addModal) {
                hideAddModal();
            }
        });
    }

    if (editModal) {
        const closeEditBtn = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-achievement');
        
        [closeEditBtn, cancelEditBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', hideEditModal);
            }
        });
        
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                hideEditModal();
            }
        });
    }

    if (deleteModal) {
        const deleteCloseButtons = document.querySelectorAll('[data-modal-hide="delete-achievement-modal"]');
        
        deleteCloseButtons.forEach(button => {
            button.addEventListener('click', hideDeleteModal);
        });
        
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                hideDeleteModal();
            }
        });
        
        if (confirmDeleteBtn && deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const url = this.action;
                const formData = new FormData(this);
                
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.innerHTML = `
                    <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    <span class="ml-2">Menghapus...</span>
                `;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideDeleteModal();
                    
                    if (data.success) {
                        refreshAchievementsTable();
                    } else {
                        alert(data.message || 'Gagal menghapus prestasi.');
                    }
                })
                .catch(error => {
                    console.error('Error deleting achievement:', error);
                    hideDeleteModal();
                    alert('Terjadi kesalahan saat menghapus prestasi. Silakan coba lagi.');
                })
                .finally(() => {
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Ya, Hapus
                    `;
                });
            });
        }
    }

    if (showModal) {
        const showCloseButtons = document.querySelectorAll('#close-show-modal, #close-show-achievement');
        
        showCloseButtons.forEach(button => {
            button.addEventListener('click', hideAchievementDetailsModal);
        });
        
        showModal.addEventListener('click', function(e) {
            if (e.target === showModal) {
                hideAchievementDetailsModal();
            }
        });
        
        const deleteAchievementBtn = document.getElementById('delete-achievement-btn');
        if (deleteAchievementBtn) {
            deleteAchievementBtn.addEventListener('click', function() {
                const achievementId = this.getAttribute('data-achievement-id');
                const achievementTitle = this.getAttribute('data-achievement-title');
                
                hideAchievementDetailsModal();
                
                const achievementNameSpan = document.getElementById('achievement-name-to-delete');
                
                if (deleteForm && achievementNameSpan) {
                    deleteForm.action = window.achievementRoutes.delete.replace('__ID__', achievementId);
                    achievementNameSpan.textContent = achievementTitle;
                    
                    showDeleteModal();
                }
            });
        }
        
        const editFromShowBtn = document.getElementById('edit-from-show');
        if (editFromShowBtn) {
            editFromShowBtn.addEventListener('click', function() {
                const achievementId = this.getAttribute('data-achievement-id');
                
                hideAchievementDetailsModal();
                loadAchievementForEdit(achievementId);
            });
        }
    }
    
    // Function to load achievement details for view
    function loadAchievementForView(achievementId) {
        showAchievementDetailsModal();
        
        document.getElementById('show-title').textContent = '';
        document.getElementById('show-competition-name').textContent = '';
        document.getElementById('show-competition').textContent = '';
        document.getElementById('show-type').textContent = '';
        document.getElementById('show-level').textContent = '';
        document.getElementById('show-date').textContent = '';
        document.getElementById('show-description').textContent = '';
        document.getElementById('show-attachments').innerHTML = '';
        document.getElementById('show-updated-at').textContent = '-';
        
        document.getElementById('show-title').textContent = 'Memuat...';
        document.getElementById('show-description').textContent = 'Memuat...';
        
        const deleteBtn = document.getElementById('delete-achievement-btn');
        const editBtn = document.getElementById('edit-from-show');
        if (deleteBtn) deleteBtn.setAttribute('data-achievement-id', achievementId);
        if (editBtn) editBtn.setAttribute('data-achievement-id', achievementId);
        
        fetch(window.achievementRoutes.show.replace('__ID__', achievementId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load achievement details');
            }
            return response.json();
        })
        .then(data => {
            const achievement = data.achievement;
            
            document.getElementById('show-title').textContent = achievement.title;
            document.getElementById('show-competition-name').textContent = achievement.competition_name || '-';
            document.getElementById('show-competition').textContent = achievement.competition ? achievement.competition.name : 'Tidak Ada';
            
            let typeText = '';
            switch(achievement.type) {
                case 'academic': typeText = 'Akademik'; break;
                case 'technology': typeText = 'Teknologi'; break;
                case 'arts': typeText = 'Seni'; break;
                case 'sports': typeText = 'Olahraga'; break;
                case 'entrepreneurship': typeText = 'Kewirausahaan'; break;
                default: typeText = achievement.type;
            }
            document.getElementById('show-type').textContent = typeText;
            
            let levelText = '';
            switch(achievement.level) {
                case 'international': levelText = 'Internasional'; break;
                case 'national': levelText = 'Nasional'; break;
                case 'regional': levelText = 'Regional'; break;
                default: levelText = achievement.level;
            }
            document.getElementById('show-level').textContent = levelText;
            
            const date = new Date(achievement.date);
            const formattedDate = date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('show-date').textContent = formattedDate;
            
            document.getElementById('show-description').textContent = achievement.description || '-';
            
            const updatedAt = new Date(achievement.updated_at);
            const formattedUpdatedAt = updatedAt.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('show-updated-at').textContent = formattedUpdatedAt;
            
            const statusBadge = document.getElementById('show-status');
            statusBadge.textContent = achievement.status === 'approved' ? 'Disetujui' : 
                                     achievement.status === 'rejected' ? 'Ditolak' : 'Menunggu';
            
            statusBadge.className = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full';
            if (achievement.status === 'approved') {
                statusBadge.classList.add('bg-green-100', 'text-green-800');
            } else if (achievement.status === 'rejected') {
                statusBadge.classList.add('bg-red-100', 'text-red-800');
            } else {
                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
            }
            
            const attachmentsContainer = document.getElementById('show-attachments');
            attachmentsContainer.innerHTML = '';
            
            if (achievement.attachments && achievement.attachments.length > 0) {
                const attachmentsGrid = document.createElement('div');
                attachmentsGrid.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
                
                achievement.attachments.forEach(attachment => {
                    const attachmentItem = document.createElement('div');
                    attachmentItem.className = 'border rounded-md overflow-hidden';
                    
                    if (attachment.mime_type && attachment.mime_type.startsWith('image/')) {
                        attachmentItem.innerHTML = `
                            <a href="${attachment.url}" target="_blank" class="block">
                                <img src="${attachment.url}" alt="${attachment.filename}" class="w-full h-32 object-cover">
                                <div class="p-2 text-sm truncate">${attachment.filename}</div>
                            </a>
                        `;
                    } else {
                        attachmentItem.innerHTML = `
                            <a href="${attachment.url}" target="_blank" class="block p-4">
                                <div class="flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-center text-sm truncate">${attachment.filename}</div>
                            </a>
                        `;
                    }
                    
                    attachmentsGrid.appendChild(attachmentItem);
                });
                
                attachmentsContainer.appendChild(attachmentsGrid);
            } else {
                attachmentsContainer.innerHTML = '<p class="text-gray-500">Tidak ada bukti yang diunggah</p>';
            }
            
            if (deleteBtn) {
                deleteBtn.setAttribute('data-achievement-title', achievement.title);
            }
        })
        .catch(error => {
            console.error('Error loading achievement details:', error);
            document.getElementById('show-title').textContent = 'Error';
            document.getElementById('show-description').textContent = 'Gagal memuat detail prestasi. Silakan coba lagi.';
        });
    }
    
    // Function to load achievement data for edit
    function loadAchievementForEdit(achievementId) {
        showEditModal();
        
        if (editForm) {
            editForm.reset();
            resetFieldErrors();
        }
        
        if (editForm) {
            editForm.action = window.achievementRoutes.update.replace('__ID__', achievementId);
        }
        
        const editAchievementIdField = document.getElementById('edit-achievement-id');
        if (editAchievementIdField) {
            editAchievementIdField.value = achievementId;
        }
        
        document.getElementById('edit-title').value = 'Memuat...';
        document.getElementById('edit-description').value = 'Memuat...';
        
        const existingAttachmentsContainer = document.getElementById('existing-attachments');
        if (existingAttachmentsContainer) {
            existingAttachmentsContainer.innerHTML = '<div class="text-center py-2">Memuat...</div>';
        }
        
        fetch(window.achievementRoutes.edit.replace('__ID__', achievementId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load achievement data');
            }
            return response.json();
        })
        .then(data => {
            const achievement = data.achievement;
            const competitions = data.competitions || [];
            
            document.getElementById('edit-title').value = achievement.title || '';
            document.getElementById('edit-competition-name').value = achievement.competition_name || '';
            document.getElementById('edit-type').value = achievement.type || '';
            document.getElementById('edit-level').value = achievement.level || '';
            document.getElementById('edit-date').value = achievement.date || '';
            document.getElementById('edit-description').value = achievement.description || '';
            
            const competitionSelect = document.getElementById('edit-competition-id');
            if (competitionSelect) {
                while (competitionSelect.options.length > 1) {
                    competitionSelect.remove(1);
                }
                
                competitions.forEach(competition => {
                    const option = document.createElement('option');
                    option.value = competition.id;
                    option.textContent = competition.name;
                    
                    if (achievement.competition_id === competition.id) {
                        option.selected = true;
                    }
                    
                    competitionSelect.appendChild(option);
                });
            }
            
            const existingAttachmentsContainer = document.getElementById('existing-attachments');
            if (existingAttachmentsContainer && achievement.attachments && achievement.attachments.length > 0) {
                existingAttachmentsContainer.innerHTML = '';
                
                achievement.attachments.forEach(attachment => {
                    const attachmentItem = document.createElement('div');
                    attachmentItem.className = 'border rounded-md overflow-hidden relative';
                    
                    if (attachment.mime_type && attachment.mime_type.startsWith('image/')) {
                        attachmentItem.innerHTML = `
                            <div class="relative group">
                                <img src="${attachment.url}" alt="${attachment.filename}" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-center justify-center">
                                    <a href="${attachment.url}" target="_blank" class="p-1 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="p-2 text-xs truncate">${attachment.filename}</div>
                        `;
                    } else {
                        attachmentItem.innerHTML = `
                            <div class="p-2 text-center">
                                <a href="${attachment.url}" target="_blank" class="block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div class="text-xs mt-1 truncate">${attachment.filename}</div>
                                </a>
                            </div>
                        `;
                    }
                    
                    existingAttachmentsContainer.appendChild(attachmentItem);
                });
            } else if (existingAttachmentsContainer) {
                existingAttachmentsContainer.innerHTML = '<p class="text-gray-500">Tidak ada bukti yang diunggah</p>';
            }
        })
        .catch(error => {
            console.error('Error loading achievement for edit:', error);
            document.getElementById('edit-title').value = '';
            document.getElementById('edit-description').value = '';
            
            const existingAttachmentsContainer = document.getElementById('existing-attachments');
            if (existingAttachmentsContainer) {
                existingAttachmentsContainer.innerHTML = '<div class="text-red-500">Gagal memuat data prestasi. Silakan coba lagi.</div>';
            }
        });
    }
    
    const viewButtons = document.querySelectorAll('.show-achievement');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const achievementId = this.getAttribute('data-id');
            loadAchievementForView(achievementId);
        });
    });
    
    const editButtons = document.querySelectorAll('.edit-achievement');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const achievementId = this.getAttribute('data-id');
            loadAchievementForEdit(achievementId);
        });
    });
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideEditModal();
                    
                    if (typeof refreshAchievementsTable === 'function') {
                        refreshAchievementsTable();
                    } else {
                        window.location.reload();
                    }
                } else {
                    if (data.errors) {
                        resetFieldErrors();
                        
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(`edit-${field}-error`);
                            const inputElement = document.getElementById(`edit-${field}`);
                            
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                            
                            if (inputElement) {
                                inputElement.classList.add('border-red-500');
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                alert('Terjadi kesalahan saat menyimpan perubahan. Silakan coba lagi.');
            });
        });
    }
    
    document.addEventListener('add-modal:show', showAddModal);
    document.addEventListener('edit-modal:show', showEditModal);
    document.addEventListener('delete-modal:show', showDeleteModal);
    document.addEventListener('show-modal:show', showAchievementDetailsModal);
    
    // Function to refresh the achievements table
    function refreshAchievementsTable() {
        const tableContainer = document.querySelector('#achievements-table-container');
        if (!tableContainer) return;
        
        tableContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        `;
        
        const url = new URL(window.location.href);
        url.searchParams.set('ajax', '1');
        
        fetch(url.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tableContainer.innerHTML = data.table;
                
                const paginationContainer = document.querySelector('#achievements-pagination');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                attachTableEventListeners();
            } else {
                tableContainer.innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-red-500 text-lg mb-2">Error</div>
                        <p class="text-gray-600">Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error refreshing table:', error);
            tableContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 text-lg mb-2">Error</div>
                    <p class="text-gray-600">Gagal memuat data. Silakan coba lagi.</p>
                </div>
            `;
        });
    }
    
    // Function to attach event listeners to table buttons
    function attachTableEventListeners() {
        document.querySelectorAll('.show-achievement').forEach(button => {
            button.addEventListener('click', function() {
                const achievementId = this.getAttribute('data-id');
                loadAchievementForView(achievementId);
            });
        });
        
        document.querySelectorAll('.edit-achievement').forEach(button => {
            button.addEventListener('click', function() {
                const achievementId = this.getAttribute('data-id');
                loadAchievementForEdit(achievementId);
            });
        });
        
        document.querySelectorAll('.delete-achievement').forEach(button => {
            button.addEventListener('click', function() {
                const achievementId = this.getAttribute('data-id');
                const achievementTitle = this.getAttribute('data-title');
                
                const deleteForm = document.getElementById('delete-achievement-form');
                const achievementNameToDelete = document.getElementById('achievement-name-to-delete');
                
                if (deleteForm && achievementNameToDelete) {
                    deleteForm.action = window.achievementRoutes.delete.replace('__ID__', achievementId);
                    achievementNameToDelete.textContent = achievementTitle;
                    
                    const deleteModal = document.getElementById('delete-achievement-modal');
                    if (deleteModal) {
                        deleteModal.classList.remove('hidden');
                        deleteModal.classList.add('flex');
                        
                        setTimeout(() => {
                            const deleteModalContainer = document.getElementById('delete-modal-container');
                            if (deleteModalContainer) {
                                deleteModalContainer.classList.add('animate-modal-appear');
                            }
                        }, 10);
                    }
                }
            });
        });
    }
    
    window.refreshAchievementsTable = refreshAchievementsTable;
    
    attachTableEventListeners();
    
    // Function to set up the multi-step form
    function setupMultiStepForm() {
        const step1Content = document.getElementById('step-1-content');
        const step2Content = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-achievement');
        
        if (!step1Content || !step2Content || !nextBtn || !prevBtn || !submitBtn) return;
        
        const stepItems = document.querySelectorAll('.step-item');
        
        nextBtn.addEventListener('click', function() {
            const title = document.getElementById('add-title');
            const competitionName = document.getElementById('add-competition-name');
            const type = document.getElementById('add-type');
            const level = document.getElementById('add-level');
            const date = document.getElementById('add-date');
            
            let isValid = true;
            let errorMessage = '';
            
            resetFieldErrors();
            
            if (!title.value.trim()) {
                isValid = false;
                errorMessage += '<li>Judul Prestasi wajib diisi</li>';
                showFieldError(title, 'Judul Prestasi wajib diisi');
            }
            
            if (!competitionName.value.trim()) {
                isValid = false;
                errorMessage += '<li>Nama Kompetisi wajib diisi</li>';
                showFieldError(competitionName, 'Nama Kompetisi wajib diisi');
            }
            
            if (!type.value) {
                isValid = false;
                errorMessage += '<li>Jenis Prestasi wajib dipilih</li>';
                showFieldError(type, 'Jenis Prestasi wajib dipilih');
            }
            
            if (!level.value) {
                isValid = false;
                errorMessage += '<li>Tingkat Prestasi wajib dipilih</li>';
                showFieldError(level, 'Tingkat Prestasi wajib dipilih');
            }
            
            if (!date.value) {
                isValid = false;
                errorMessage += '<li>Tanggal Prestasi wajib diisi</li>';
                showFieldError(date, 'Tanggal Prestasi wajib diisi');
            }
            
            if (!isValid) {
                const errorContainer = document.getElementById('add-achievement-error');
                const errorList = document.getElementById('add-achievement-error-list');
                const errorCount = document.getElementById('add-achievement-error-count');
                
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessage;
                errorCount.textContent = errorMessage.split('<li>').length - 1;
                
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            document.getElementById('add-achievement-error').classList.add('hidden');
            
            step1Content.classList.add('hidden');
            step2Content.classList.remove('hidden');
            
            stepItems[0].classList.add('completed');
            stepItems[1].classList.add('active');
            document.querySelector('.step-line').classList.add('bg-blue-600');
            stepItems[1].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
            stepItems[1].querySelector('div').classList.add('bg-blue-600', 'text-white');
            
            nextBtn.classList.add('hidden');
            prevBtn.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
        });
        
        prevBtn.addEventListener('click', function() {
            step2Content.classList.add('hidden');
            step1Content.classList.remove('hidden');
            
            stepItems[1].classList.remove('active');
            document.querySelector('.step-line').classList.remove('bg-blue-600');
            stepItems[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
            stepItems[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
            
            nextBtn.classList.remove('hidden');
            prevBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');
        });
        
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const description = document.getElementById('add-description');
                const attachments = document.getElementById('add-attachments');
                
                let isValid = true;
                let errorMessage = '';
                
                if (!description.value.trim()) {
                    isValid = false;
                    errorMessage += '<li>Deskripsi Prestasi wajib diisi</li>';
                    showFieldError(description, 'Deskripsi Prestasi wajib diisi');
                }
                
                if (attachments.files.length === 0) {
                    isValid = false;
                    errorMessage += '<li>Bukti Prestasi wajib diunggah</li>';
                    showFieldError(attachments, 'Bukti Prestasi wajib diunggah');
                }
                
                if (!isValid) {
                    e.preventDefault();
                    
                    const errorContainer = document.getElementById('add-achievement-error');
                    const errorList = document.getElementById('add-achievement-error-list');
                    const errorCount = document.getElementById('add-achievement-error-count');
                    
                    errorContainer.classList.remove('hidden');
                    errorList.innerHTML = errorMessage;
                    errorCount.textContent = errorMessage.split('<li>').length - 1;
                    
                    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    }
    
    // Function to reset the multi-step form
    function resetMultiStepForm() {
        const step1Content = document.getElementById('step-1-content');
        const step2Content = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-achievement');
        
        if (!step1Content || !step2Content) return;
        
        const stepItems = document.querySelectorAll('.step-item');
        
        step1Content.classList.remove('hidden');
        step2Content.classList.add('hidden');
        
        if (stepItems.length >= 2) {
            stepItems[0].classList.add('active');
            stepItems[0].classList.remove('completed');
            stepItems[1].classList.remove('active');
            
            const stepLine = document.querySelector('.step-line');
            if (stepLine) stepLine.classList.remove('bg-blue-600');
            
            const step2Indicator = stepItems[1].querySelector('div');
            if (step2Indicator) {
                step2Indicator.classList.remove('bg-blue-600', 'text-white');
                step2Indicator.classList.add('bg-gray-200', 'text-gray-600');
            }
        }
        
        if (nextBtn) nextBtn.classList.remove('hidden');
        if (prevBtn) prevBtn.classList.add('hidden');
        if (submitBtn) submitBtn.classList.add('hidden');
        
        const errorContainer = document.getElementById('add-achievement-error');
        if (errorContainer) errorContainer.classList.add('hidden');
    }
    
    // Function to reset form errors
    function resetFormErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(error => {
            error.textContent = '';
            error.classList.add('hidden');
        });
        
        const errorContainer = document.getElementById('add-achievement-error');
        if (errorContainer) errorContainer.classList.add('hidden');
        
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
        });
    }
    
    // Function to show a field error
    function showFieldError(field, message) {
        field.classList.add('border-red-500');
        
        const errorId = field.id.replace('add-', '') + '-error';
        const errorElement = document.getElementById(errorId);
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }
    
    // Function to reset field errors
    function resetFieldErrors() {
        const inputs = document.querySelectorAll('#add-achievement-form input, #add-achievement-form select, #add-achievement-form textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
        });
        
        const errorMessages = document.querySelectorAll('#add-achievement-form .error-message');
        errorMessages.forEach(error => {
            error.textContent = '';
            error.classList.add('hidden');
        });
    }
});