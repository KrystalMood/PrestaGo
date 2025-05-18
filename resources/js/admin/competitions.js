document.addEventListener('DOMContentLoaded', function() {
    const competitionRoutes = window.competitionRoutes || {};
    const csrfToken = window.csrfToken || '';
    
    setupCompetitionModals();
    
    attachPaginationHandlers();
    
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
                    resetMultiStepForm();
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
                        resetMultiStepForm();
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
        setupMultiStepFormNavigation();
        attachEditButtonListeners();
        attachShowButtonListeners();
        attachDeleteButtonListeners();
    }
    
    // Resets the multi-step form to its initial (first) step
    function resetMultiStepForm() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-competition');
        const stepItems = document.querySelectorAll('.step-item');
        
        if (step1 && step2) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            
            stepItems[0].classList.add('active');
            stepItems[0].classList.remove('completed');
            stepItems[1].classList.remove('active');
            
            document.querySelector('.step-line')?.classList.remove('bg-blue-600');
            
            const step2Indicator = stepItems[1].querySelector('div');
            if (step2Indicator) {
                step2Indicator.classList.remove('bg-blue-600', 'text-white');
                step2Indicator.classList.add('bg-gray-200', 'text-gray-600');
            }
            
            if (nextBtn) nextBtn.classList.remove('hidden');
            if (prevBtn) prevBtn.classList.add('hidden');
            if (submitBtn) submitBtn.classList.add('hidden');
        }
    }
    
    // Set up the multi-step form navigation
    function setupMultiStepFormNavigation() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-competition');
        
        if (!step1 || !step2 || !nextBtn || !prevBtn || !submitBtn) return;
        
        const stepItems = document.querySelectorAll('.step-item');
        
        nextBtn.addEventListener('click', function() {
            const name = document.getElementById('add-name');
            const organizer = document.getElementById('add-organizer');
            const category = document.getElementById('add-category');
            const status = document.getElementById('add-status');
            const type = document.getElementById('add-type');
            const period = document.getElementById('add-period');
            
            let isValid = true;
            let errorMessage = '';
            
            resetFieldErrors();
            
            if (!name.value.trim()) {
                isValid = false;
                errorMessage += '<li>Nama Kompetisi wajib diisi</li>';
                showFieldError(name, 'Nama Kompetisi wajib diisi');
            }
            
            if (!organizer.value.trim()) {
                isValid = false;
                errorMessage += '<li>Penyelenggara wajib diisi</li>';
                showFieldError(organizer, 'Penyelenggara wajib diisi');
            }
            
            if (!category.value) {
                isValid = false;
                errorMessage += '<li>Kategori wajib dipilih</li>';
                showFieldError(category, 'Kategori wajib dipilih');
            }
            
            if (!period.value) {
                isValid = false;
                errorMessage += '<li>Periode wajib dipilih</li>';
                showFieldError(period, 'Periode wajib dipilih');
            }
            
            if (!status.value) {
                isValid = false;
                errorMessage += '<li>Status wajib dipilih</li>';
                showFieldError(status, 'Status wajib dipilih');
            }
            
            if (!type.value) {
                isValid = false;
                errorMessage += '<li>Tipe Kompetisi wajib dipilih</li>';
                showFieldError(type, 'Tipe Kompetisi wajib dipilih');
            }
            
            if (!isValid) {
                const errorContainer = document.getElementById('add-competition-error');
                const errorList = document.getElementById('add-competition-error-list');
                const errorCount = document.getElementById('add-competition-error-count');
                
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessage;
                errorCount.textContent = errorMessage.split('<li>').length - 1;
                
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            document.getElementById('add-competition-error').classList.add('hidden');
            
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
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
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
            
            stepItems[1].classList.remove('active');
            document.querySelector('.step-line').classList.remove('bg-blue-600');
            stepItems[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
            stepItems[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
            
            nextBtn.classList.remove('hidden');
            prevBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');
        });
        
        function showFieldError(field, message) {
            const formGroup = field.closest('.form-group');
            
            field.classList.add('border-red-500');
            field.classList.remove('border-gray-300');
            
            let errorElement;
            if (formGroup) {
                errorElement = formGroup.querySelector('.error-message');
            }
            
            if (!errorElement) {
                errorElement = document.getElementById(`${field.id.replace('add-', '')}-error`);
            }
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        }
        
        function resetFieldErrors() {
            const inputFields = document.getElementById('add-competition-form').querySelectorAll('input, select, textarea');
            inputFields.forEach(field => {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            });
            
            const errorMessages = document.getElementById('add-competition-modal').querySelectorAll('.error-message');
            errorMessages.forEach(error => {
                error.textContent = '';
                error.classList.add('hidden');
            });
        }
    }
    
    // Set up the add competition form with AJAX submission
    function setupAddCompetitionForm() {
        const addForm = document.getElementById('add-competition-form');
        if (!addForm) return;
        
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAddCompetitionForm();
        });
        
        const submitBtn = document.getElementById('submit-add-competition');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                submitAddCompetitionForm();
            });
        }
        
        function submitAddCompetitionForm() {
            const form = document.getElementById('add-competition-form');
            
            // Validate form fields
            const startDate = document.getElementById('add-start-date').value;
            const endDate = document.getElementById('add-end-date').value;
            const regStart = document.getElementById('add-registration-start').value;
            const regEnd = document.getElementById('add-registration-end').value;
            
            let isValid = true;
            let errorMessages = [];
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    const fieldName = field.previousElementSibling?.textContent?.replace('*', '').trim() || 'Field';
                    errorMessages.push(`<li>${fieldName} wajib diisi</li>`);
                    field.classList.add('border-red-500');
                    field.classList.remove('border-gray-300');
                } else {
                    field.classList.remove('border-red-500');
                    field.classList.add('border-gray-300');
                }
            });
            
            // Validate date logic
            if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                isValid = false;
                errorMessages.push('<li>Tanggal Selesai tidak boleh kurang dari Tanggal Mulai</li>');
                document.getElementById('add-end-date').classList.add('border-red-500');
            }
            
            if (regStart && regEnd && new Date(regEnd) < new Date(regStart)) {
                isValid = false;
                errorMessages.push('<li>Tanggal Selesai Pendaftaran tidak boleh kurang dari Tanggal Mulai Pendaftaran</li>');
                document.getElementById('add-registration-end').classList.add('border-red-500');
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
            
            const submitBtn = document.getElementById('submit-add-competition');
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
                
                form.reset();
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
                    displayErrors(errorData.errors, form, 'add-competition-error', 'add-competition-error-list');
                } else {
                    showNotification(error.message || 'Gagal menambahkan kompetisi. Silakan coba lagi.', 'error');
                }
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
            document.getElementById('edit-period').value = competition.period_id || '';
            document.getElementById('edit-level').value = competition.level || '';
            document.getElementById('edit-status').value = competition.status;
            document.getElementById('edit-type').value = competition.type || 'individual';
            
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
            
            if (competition.competition_date) {
                document.getElementById('edit-competition-date').value = competition.competition_date.split('T')[0];
            }
            
            document.getElementById('edit-description').value = competition.description || '';
            
            document.getElementById('edit-requirements').value = competition.requirements || '';
            
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
            document.getElementById('competition-period').textContent = competition.period?.name || 'N/A';
            
            // Set competition type
            const typeText = {
                'individual': 'Individual',
                'team': 'Tim',
                'both': 'Keduanya'
            };
            document.getElementById('competition-type').textContent = typeText[competition.type] || 'Tidak Ditentukan';
            
            // Set competition level
            const levelText = {
                'international': 'Internasional',
                'national': 'Nasional',
                'regional': 'Regional',
                'provincial': 'Provinsi',
                'university': 'Universitas'
            };
            document.getElementById('competition-level').textContent = levelText[competition.level] || 'Umum';
            
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
            
            if (competition.competition_date) {
                let competitionDate = new Date(competition.competition_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                document.getElementById('competition-date').textContent = competitionDate;
            } else {
                document.getElementById('competition-date').textContent = 'Tidak Ada Informasi';
            }
            
            const statusElement = document.getElementById('competition-status');
            statusElement.textContent = getStatusText(competition.status);
            statusElement.className = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' + getStatusClass(competition.status);
            
            document.getElementById('competition-description').textContent = competition.description || 'Tidak ada deskripsi tersedia';
            
            document.getElementById('competition-requirements').textContent = competition.requirements || 'Tidak ada persyaratan tersedia';
            
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
        const type = document.getElementById('edit-type').value;
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errorMessages.push('<li>Tanggal Selesai tidak boleh kurang dari Tanggal Mulai</li>');
        }
        
        if (!type) {
            isValid = false;
            errorMessages.push('<li>Tipe Kompetisi wajib dipilih</li>');
            document.getElementById('edit-type').classList.add('border-red-500');
        }
        
        // Check if period is selected
        const period = document.getElementById('edit-period').value;
        if (!period) {
            isValid = false;
            errorMessages.push('<li>Periode wajib dipilih</li>');
            document.getElementById('edit-period').classList.add('border-red-500');
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
                
                document.dispatchEvent(new CustomEvent('delete-modal:show'));
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
            
            // Find and trigger click on a button that has data-modal-hide attribute
            const closeButtons = document.querySelectorAll('[data-modal-hide="delete-competition-modal"]');
            if (closeButtons.length > 0) {
                closeButtons[0].click();
            } else {
                // Fallback if no button is found
                window.deleteCompetitionModal.classList.add('hidden');
            }
            
            showNotification(data.message || 'Kompetisi berhasil dihapus', 'success');
            
            refreshCompetitionsTable();
        })
        .catch(error => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;
            
            console.error('Error deleting competition:', error);
            // Find and trigger click on a button that has data-modal-hide attribute
            const closeButtons = document.querySelectorAll('[data-modal-hide="delete-competition-modal"]');
            if (closeButtons.length > 0) {
                closeButtons[0].click();
            } else {
                // Fallback if no button is found
                window.deleteCompetitionModal.classList.add('hidden');
            }
            
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
        const countElement = document.getElementById(`${errorContainer}-count`);
        
        if (!container || !list) return;
        
        // Reset previous errors
        list.innerHTML = '';
        
        // Reset form field errors
        const inputFields = form.querySelectorAll('input, select, textarea');
        inputFields.forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
            
            const fieldName = field.getAttribute('name');
            const errorElement = document.getElementById(`${field.id.replace('add-', '')}-error`) || 
                                document.getElementById(`${field.id.replace('edit-', '')}-error`);
            
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
        });
        
        // Process new errors
        let errorCount = 0;
        let errorMessages = [];
        
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                const messages = errors[field];
                errorCount += messages.length;
                
                messages.forEach(message => {
                    errorMessages.push(`<li>${message}</li>`);
                });
                
                // Find the input field and highlight it
                const inputField = form.querySelector(`[name="${field}"]`);
                if (inputField) {
                    inputField.classList.add('border-red-500');
                    inputField.classList.remove('border-gray-300');
                    
                    // Find the corresponding error message element
                    const fieldId = inputField.id;
                    const errorElement = document.getElementById(`${fieldId.replace('add-', '')}-error`) || 
                                        document.getElementById(`${fieldId.replace('edit-', '')}-error`);
                    
                    if (errorElement) {
                        errorElement.textContent = messages[0]; // Show the first error message
                        errorElement.classList.remove('hidden');
                    }
                }
            }
        }
        
        // Update error list and show container
        list.innerHTML = errorMessages.join('');
        if (countElement) countElement.textContent = errorCount;
        container.classList.remove('hidden');
        
        // If we're in a multi-step form, make sure we show the right step that has errors
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        
        if (step1 && step2) {
            // Check if step 1 has errors
            const step1Fields = ['name', 'organizer', 'category_id', 'status', 'level', 'type'];
            const hasStep1Errors = step1Fields.some(field => errors[field]);
            
            // Check if step 2 has errors
            const step2Fields = ['start_date', 'end_date', 'registration_start', 'registration_end', 'description'];
            const hasStep2Errors = step2Fields.some(field => errors[field]);
            
            // Show the step with errors
            if (hasStep1Errors) {
                showStep(1);
            } else if (hasStep2Errors) {
                showStep(2);
            }
        }
        
        // Scroll to error container
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function showStep(stepNumber) {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-competition');
        const stepItems = document.querySelectorAll('.step-item');
        
        if (stepNumber === 1) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            
            stepItems[0].classList.add('active');
            stepItems[1].classList.remove('active');
            
            document.querySelector('.step-line')?.classList.remove('bg-blue-600');
            
            const step2Indicator = stepItems[1].querySelector('div');
            if (step2Indicator) {
                step2Indicator.classList.remove('bg-blue-600', 'text-white');
                step2Indicator.classList.add('bg-gray-200', 'text-gray-600');
            }
            
            if (nextBtn) nextBtn.classList.remove('hidden');
            if (prevBtn) prevBtn.classList.add('hidden');
            if (submitBtn) submitBtn.classList.add('hidden');
        } else if (stepNumber === 2) {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            stepItems[0].classList.add('completed');
            stepItems[1].classList.add('active');
            document.querySelector('.step-line')?.classList.add('bg-blue-600');
            
            const step2Indicator = stepItems[1].querySelector('div');
            if (step2Indicator) {
                step2Indicator.classList.add('bg-blue-600', 'text-white');
                step2Indicator.classList.remove('bg-gray-200', 'text-gray-600');
            }
            
            if (nextBtn) nextBtn.classList.add('hidden');
            if (prevBtn) prevBtn.classList.remove('hidden');
            if (submitBtn) submitBtn.classList.remove('hidden');
        }
    }
    
    // Reset form errors
    function resetFormErrors(formOrId) {
        const form = typeof formOrId === 'string' ? document.getElementById(formOrId) : formOrId;
        
        if (!form) return;
        
        // Reset form field errors
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
        
        // Reset error messages
        const errorElements = form.closest('.fixed')?.querySelectorAll('.error-message') || [];
        errorElements.forEach(error => {
            error.textContent = '';
            error.classList.add('hidden');
        });
        
        // Hide error containers
        const errorContainers = form.closest('.fixed')?.querySelectorAll('[id$="-error"]') || [];
        errorContainers.forEach(container => {
            if (container.id.endsWith('-error') && !container.classList.contains('error-message')) {
                container.classList.add('hidden');
            }
        });
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
}); 