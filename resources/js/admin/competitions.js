document.addEventListener('DOMContentLoaded', function() {
    const competitionRoutes = window.competitionRoutes || {};
    const csrfToken = window.csrfToken || '';
    
    // Initialize and set up event listeners for competition modals
    function setupCompetitionModals() {
        window.addCompetitionModal = document.getElementById('add-competition-modal');
        window.editCompetitionModal = document.getElementById('edit-competition-modal');
        window.showCompetitionModal = document.getElementById('show-competition-modal');
        window.deleteCompetitionModal = document.getElementById('delete-competition-modal');
        
        const openAddModalBtn = document.getElementById('open-add-competition-modal');
        if (openAddModalBtn) {
            openAddModalBtn.addEventListener('click', function() {
                if (window.addCompetitionModal) {
                    window.addCompetitionModal.classList.remove('hidden');
                    resetFormErrors('add-competition-form');
                }
            });
        }
        
        const closeAddModalBtn = document.getElementById('close-add-modal');
        const cancelAddBtn = document.getElementById('cancel-add-competition');
        [closeAddModalBtn, cancelAddBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.addCompetitionModal) {
                        window.addCompetitionModal.classList.add('hidden');
                        document.getElementById('add-competition-form').reset();
                        resetFormErrors('add-competition-form');
                    }
                });
            }
        });
        
        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-competition');
        [closeEditModalBtn, cancelEditBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.editCompetitionModal) {
                        window.editCompetitionModal.classList.add('hidden');
                        resetFormErrors('edit-competition-form');
                    }
                });
            }
        });
        
        const closeShowModalBtn = document.getElementById('close-show-modal');
        const closeShowBtn = document.getElementById('close-show-competition');
        const editFromShowBtn = document.getElementById('edit-from-show');
        
        [closeShowModalBtn, closeShowBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.showCompetitionModal) {
                        window.showCompetitionModal.classList.add('hidden');
                    }
                });
            }
        });
        
        if (editFromShowBtn) {
            editFromShowBtn.addEventListener('click', function() {
                const competitionId = editFromShowBtn.getAttribute('data-competition-id');
                if (window.showCompetitionModal) {
                    window.showCompetitionModal.classList.add('hidden');
                }
                loadCompetitionForEdit(competitionId);
            });
        }
        
        const cancelDeleteBtn = document.getElementById('cancel-delete-competition');
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                if (window.deleteCompetitionModal) {
                    window.deleteCompetitionModal.classList.add('hidden');
                }
            });
        }
        
        setupAddCompetitionForm();
        attachEditButtonListeners();
        attachShowButtonListeners();
        attachDeleteButtonListeners();
    }
    
    // Set up the add competition form with AJAX submission
    function setupAddCompetitionForm() {
        const addForm = document.getElementById('add-competition-form');
        if (!addForm) return;
        
        const submitBtn = document.getElementById('submit-add-competition');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                const form = document.getElementById('add-competition-form');
                
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let errorMessages = [];
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        const fieldName = field.previousElementSibling.textContent.replace('*', '').trim();
                        errorMessages.push(`<li>${fieldName} wajib diisi</li>`);
                        field.classList.add('border-red-500');
                        field.classList.remove('border-gray-300');
                    } else {
                        field.classList.remove('border-red-500');
                        field.classList.add('border-gray-300');
                    }
                });
                
                const startDate = document.getElementById('add-start-date').value;
                const endDate = document.getElementById('add-end-date').value;
                
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    isValid = false;
                    errorMessages.push('<li>Tanggal Selesai tidak boleh kurang dari Tanggal Mulai</li>');
                }
                
                if (!isValid) {
                    const errorContainer = document.getElementById('add-competition-error');
                    const errorList = document.getElementById('add-competition-error-list');
                    const errorCount = document.getElementById('add-competition-error-count');
                    
                    errorContainer.classList.remove('hidden');
                    errorList.innerHTML = errorMessages.join('');
                    errorCount.textContent = errorMessages.length;
                    
                    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
                
                document.getElementById('add-competition-error')?.classList.add('hidden');
                
                const originalButtonText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                
                const formData = new FormData(addForm);
                
                fetch(competitionRoutes.store, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalButtonText;
                    
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to add competition');
                    }
                    
                    addForm.reset();
                    window.addCompetitionModal.classList.add('hidden');
                    
                    showNotification(data.message || 'Kompetisi berhasil ditambahkan', 'success');
                    
                    refreshCompetitionsTable();
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalButtonText;
                    
                    console.error('Error adding competition:', error);
                    
                    if (error.response && error.response.status === 422) {
                        const errorData = error.response.data;
                        displayErrors(errorData.errors, addForm, 'add-competition-error', 'add-competition-error-list');
                    } else {
                        showNotification(error.message || 'Gagal menambahkan kompetisi. Silakan coba lagi.', 'error');
                    }
                });
            });
        }
    }
    
    // Attach event listeners to edit buttons
    function attachEditButtonListeners() {
        const editButtons = document.querySelectorAll('.edit-competition');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.getAttribute('data-competition-id');
                loadCompetitionForEdit(competitionId);
            });
        });
    }
    
    // Attach event listeners to show buttons
    function attachShowButtonListeners() {
        const showButtons = document.querySelectorAll('.show-competition');
        showButtons.forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.getAttribute('data-competition-id');
                loadCompetitionForView(competitionId);
            });
        });
    }
    
    // Load competition data for editing
    function loadCompetitionForEdit(competitionId) {
        const editModal = window.editCompetitionModal;
        if (!editModal) return;
        
        fetch(competitionRoutes.show.replace('__id__', competitionId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load competition');
            }
            
            const competition = data.data;
            
            document.getElementById('edit-competition-id').value = competition.id;
            document.getElementById('edit-name').value = competition.name;
            document.getElementById('edit-organizer').value = competition.organizer;
            document.getElementById('edit-category').value = competition.category_id || '';
            document.getElementById('edit-level').value = competition.level || '';
            document.getElementById('edit-status').value = competition.status;
            
            if (competition.start_date) {
                document.getElementById('edit-start-date').value = competition.start_date.split('T')[0];
            }
            
            if (competition.end_date) {
                document.getElementById('edit-end-date').value = competition.end_date.split('T')[0];
            }
            
            if (competition.registration_start) {
                document.getElementById('edit-registration-start').value = competition.registration_start.split('T')[0];
            }
            
            if (competition.registration_end) {
                document.getElementById('edit-registration-end').value = competition.registration_end.split('T')[0];
            }
            
            document.getElementById('edit-description').value = competition.description || '';
            
            const submitEditBtn = document.getElementById('submit-edit-competition');
            if (submitEditBtn) {
                submitEditBtn.onclick = function() {
                    updateCompetition(competition.id);
                };
            }
            
            resetFormErrors('edit-competition-form');
            editModal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading competition:', error);
            showNotification('Gagal memuat data kompetisi. Silakan coba lagi.', 'error');
        });
    }
    
    // Load competition data for viewing
    function loadCompetitionForView(competitionId) {
        const showModal = window.showCompetitionModal;
        if (!showModal) return;
        
        fetch(competitionRoutes.show.replace('__id__', competitionId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load competition');
            }
            
            const competition = data.data;
            
            document.getElementById('competition-id').textContent = competition.id;
            document.getElementById('competition-name').textContent = competition.name;
            document.getElementById('competition-level').textContent = competition.level || 'Umum';
            document.getElementById('competition-organizer').textContent = competition.organizer;
            document.getElementById('competition-category').textContent = competition.category?.name || 'N/A';
            
            let startDate = new Date(competition.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            let endDate = new Date(competition.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('competition-dates').textContent = `${startDate} - ${endDate}`;
            
            if (competition.registration_start && competition.registration_end) {
                let regStartDate = new Date(competition.registration_start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                let regEndDate = new Date(competition.registration_end).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                document.getElementById('competition-registration').textContent = `${regStartDate} - ${regEndDate}`;
            } else {
                document.getElementById('competition-registration').textContent = 'Tidak Ada Informasi';
            }
            
            const statusElement = document.getElementById('competition-status');
            statusElement.textContent = getStatusText(competition.status);
            statusElement.className = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' + getStatusClass(competition.status);
            
            document.getElementById('competition-description').textContent = competition.description || 'Tidak ada deskripsi tersedia';
            
            document.getElementById('edit-from-show').setAttribute('data-competition-id', competition.id);
            
            showModal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading competition:', error);
            showNotification('Gagal memuat data kompetisi. Silakan coba lagi.', 'error');
        });
    }
    
    // Update competition data
    function updateCompetition(competitionId) {
        const form = document.getElementById('edit-competition-form');
        
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        let errorMessages = [];
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                const fieldName = field.previousElementSibling.textContent.replace('*', '').trim();
                errorMessages.push(`<li>${fieldName} wajib diisi</li>`);
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            }
        });
        
        const startDate = document.getElementById('edit-start-date').value;
        const endDate = document.getElementById('edit-end-date').value;
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errorMessages.push('<li>Tanggal Selesai tidak boleh kurang dari Tanggal Mulai</li>');
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('edit-competition-error');
            const errorList = document.getElementById('edit-competition-error-list');
            const errorCount = document.getElementById('edit-competition-error-count');
            
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = errorMessages.join('');
            errorCount.textContent = errorMessages.length;
            
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        document.getElementById('edit-competition-error')?.classList.add('hidden');
        
        const submitBtn = document.getElementById('submit-edit-competition');
        const originalButtonText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        const formData = new FormData(form);
        
        fetch(competitionRoutes.update.replace('__id__', competitionId), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to update competition');
            }
            
            form.reset();
            window.editCompetitionModal.classList.add('hidden');
            
            showNotification(data.message || 'Kompetisi berhasil diperbarui', 'success');
            
            refreshCompetitionsTable();
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            console.error('Error updating competition:', error);
            
            if (error.response && error.response.status === 422) {
                const errorData = error.response.data;
                displayErrors(errorData.errors, form, 'edit-competition-error', 'edit-competition-error-list');
            } else {
                showNotification(error.message || 'Gagal memperbarui kompetisi. Silakan coba lagi.', 'error');
            }
        });
    }
    
    // Attach event listeners to delete buttons
    function attachDeleteButtonListeners() {
        const deleteButtons = document.querySelectorAll('.delete-competition');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.getAttribute('data-competition-id');
                const competitionName = this.getAttribute('data-competition-name');
                
                document.getElementById('competition-name-to-delete').textContent = competitionName;
                const confirmDeleteBtn = document.getElementById('confirm-delete-competition');
                
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.setAttribute('data-competition-id', competitionId);
                    confirmDeleteBtn.onclick = function() {
                        deleteCompetition(competitionId);
                    };
                }
                
                window.deleteCompetitionModal.classList.remove('hidden');
            });
        });
    }
    
    // Delete competition
    function deleteCompetition(competitionId) {
        const confirmBtn = document.getElementById('confirm-delete-competition');
        const originalButtonText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        fetch(competitionRoutes.destroy.replace('__id__', competitionId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to delete competition');
            }
            
            window.deleteCompetitionModal.classList.add('hidden');
            
            showNotification(data.message || 'Kompetisi berhasil dihapus', 'success');
            
            refreshCompetitionsTable();
        })
        .catch(error => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;
            
            console.error('Error deleting competition:', error);
            window.deleteCompetitionModal.classList.add('hidden');
            showNotification(error.message || 'Gagal menghapus kompetisi. Silakan coba lagi.', 'error');
        });
    }
    
    // Refresh the competitions table
    async function refreshCompetitionsTable() {
        const tableContainer = document.getElementById('competitions-table-container');
        const paginationContainer = document.getElementById('pagination-container');
        
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('ajax', 'true');
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to refresh competitions');
            }
            
            tableContainer.innerHTML = data.table;
            paginationContainer.innerHTML = data.pagination;
            
            if (data.stats) {
                updateStats(data.stats);
            }
            
            attachEditButtonListeners();
            attachShowButtonListeners();
            attachDeleteButtonListeners();
            attachPaginationHandlers();
            
        } catch (error) {
            console.error('Error refreshing competitions table:', error);
            showNotification('Gagal memuat tabel kompetisi. Silakan muat ulang halaman.', 'error');
        }
    }
    
    // Attach pagination handlers
    function attachPaginationHandlers() {
        const pagButtons = document.querySelectorAll('.pagination-button');
        pagButtons.forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const pageUrl = this.getAttribute('href');
                if (!pageUrl) return;
                
                try {
                    const url = new URL(pageUrl);
                    url.searchParams.set('ajax', 'true');
                    
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to refresh competitions');
                    }
                    
                    document.getElementById('competitions-table-container').innerHTML = data.table;
                    document.getElementById('pagination-container').innerHTML = data.pagination;
                    
                    window.history.pushState({}, '', pageUrl);
                    
                    attachEditButtonListeners();
                    attachShowButtonListeners();
                    attachDeleteButtonListeners();
                    attachPaginationHandlers();
                    
                } catch (error) {
                    console.error('Error loading page:', error);
                    showNotification('Gagal memuat halaman. Silakan coba lagi.', 'error');
                }
            });
        });
    }
    
    // Update stats cards
    function updateStats(stats) {
        if (!stats) return;
        
        for (const key in stats) {
            if (stats.hasOwnProperty(key)) {
                const statElement = document.querySelector(`[data-stat-key="${key}"]`);
                if (statElement) {
                    statElement.textContent = stats[key];
                }
            }
        }
    }
    
    // Helper function to get status text
    function getStatusText(status) {
        const statusMap = {
            'upcoming': 'Akan Datang',
            'active': 'Aktif',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan'
        };
        
        return statusMap[status] || status;
    }
    
    // Helper function to get status class
    function getStatusClass(status) {
        const classMap = {
            'upcoming': 'bg-yellow-100 text-yellow-800',
            'active': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        
        return classMap[status] || 'bg-gray-100 text-gray-800';
    }
    
    // Display validation errors
    function displayErrors(errors, form, errorContainer, errorList) {
        const container = document.getElementById(errorContainer);
        const list = document.getElementById(errorList);
        const errorCount = container.querySelector(`#${errorContainer}-count`);
        
        if (!container || !list || !errorCount) return;
        
        form.querySelectorAll('p[id$="-error"]').forEach(errorElement => {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        });
        
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
        
        if (typeof errors === 'string') {
            list.innerHTML = `<li>${errors}</li>`;
            errorCount.textContent = '1';
        } else {
            let errorMessages = [];
            let count = 0;
            
            for (const field in errors) {
                if (errors.hasOwnProperty(field)) {
                    const message = errors[field][0];
                    errorMessages.push(`<li>${message}</li>`);
                    count++;
                    
                    const fieldElement = form.querySelector(`[name="${field}"]`);
                    const errorElement = document.getElementById(`${field.replace(/\./g, '-')}-error`) || 
                                        document.getElementById(`${field}-error`);
                    
                    if (fieldElement) {
                        fieldElement.classList.add('border-red-500');
                        fieldElement.classList.remove('border-gray-300');
                    }
                    
                    if (errorElement) {
                        errorElement.textContent = message;
                        errorElement.classList.remove('hidden');
                    }
                }
            }
            
            list.innerHTML = errorMessages.join('');
            errorCount.textContent = count;
        }
        
        container.classList.remove('hidden');
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Reset form errors
    function resetFormErrors(formOrId) {
        const form = typeof formOrId === 'string' ? document.getElementById(formOrId) : formOrId;
        if (!form) return;
        
        form.querySelectorAll('p[id$="-error"]').forEach(errorElement => {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        });
        
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
        
        const formId = form.id;
        const errorContainerId = formId.replace('form', 'error');
        const errorContainer = document.getElementById(errorContainerId);
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
    }
    
    // Show notification
    function showNotification(message, type = 'success') {
        const existingNotification = document.getElementById('notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.id = 'notification';
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg flex items-center transition-all duration-300 transform translate-x-full`;
        notification.style.minWidth = '320px';
        notification.style.maxWidth = '420px';
        
        if (type === 'success') {
            notification.classList.add('bg-green-50', 'border-l-4', 'border-green-500');
        } else if (type === 'error') {
            notification.classList.add('bg-red-50', 'border-l-4', 'border-red-500');
        } else {
            notification.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
        }
        
        let iconSvg = '';
        if (type === 'success') {
            iconSvg = `
                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
        } else if (type === 'error') {
            iconSvg = `
                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
        } else {
            iconSvg = `
                <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
        }
        
        notification.innerHTML = `
            <div class="flex-shrink-0">
                ${iconSvg}
            </div>
            <div class="ml-3 flex-1 mr-2">
                <p class="${type === 'success' ? 'text-green-700' : type === 'error' ? 'text-red-700' : 'text-blue-700'} text-sm font-medium">
                    ${message}
                </p>
            </div>
            <div class="pl-3 ml-auto">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="close-notification inline-flex text-gray-500 hover:text-gray-700 focus:outline-none">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        // Close notification function
        function closeNotification(notif) {
            notif.classList.remove('translate-x-0');
            notif.classList.add('translate-x-full');
            
            setTimeout(() => {
                notif.remove();
            }, 300);
        }
        
        const closeBtn = notification.querySelector('.close-notification');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeNotification(notification));
        }
        
        setTimeout(() => {
            if (document.body.contains(notification)) {
                closeNotification(notification);
            }
        }, 5000);
        
        return notification;
    }
    
    setupCompetitionModals();
    attachPaginationHandlers();
}); 