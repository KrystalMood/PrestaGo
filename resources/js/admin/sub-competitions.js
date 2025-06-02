document.addEventListener('DOMContentLoaded', function() { // Main function to set up sub-competition features when the DOM is ready.
    window.subCompetitionSetup = true;
    
    setupSubCompetitionEventListeners();
    autoUpdateSubCompetitionStatuses();
    setupSkillsManagement();
    
    // Function to set default dates to today for the sub-competition form.
    function setDefaultDates() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        
        const startDateField = document.getElementById('add-sub-start-date');
        const endDateField = document.getElementById('add-sub-end-date');
        const regStartField = document.getElementById('add-sub-registration-start');
        const regEndField = document.getElementById('add-sub-registration-end');
        const compDateField = document.getElementById('add-sub-competition-date');
        
        if (startDateField) startDateField.value = formattedDate;
        if (endDateField) endDateField.value = formattedDate;
        if (regStartField) regStartField.value = formattedDate;
        if (regEndField) regEndField.value = formattedDate;
        if (compDateField) compDateField.value = formattedDate;
    }
    
    // Function to automatically determine sub-competition status based on dates.
    window.updateSubCompetitionStatus = function(formPrefix) {
        const prefix = formPrefix || 'add';
        
        let startDateEl, endDateEl, statusEl;
        
        if (prefix === 'add') {
            startDateEl = document.getElementById(`${prefix}-sub-start-date`);
            endDateEl = document.getElementById(`${prefix}-sub-end-date`);
            statusEl = document.getElementById(`${prefix}-sub-status`);
        } else if (prefix === 'edit') {
            startDateEl = document.getElementById('edit_start_date');
            endDateEl = document.getElementById('edit_end_date');
            statusEl = document.getElementById('edit_status');
        }
        
        if (!statusEl) return;
        if (!startDateEl || !endDateEl) return;
        
        const startDate = startDateEl.value ? new Date(startDateEl.value) : null;
        const endDate = endDateEl.value ? new Date(endDateEl.value) : null;
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        let status = 'upcoming';
        
        if (startDate && endDate) {
            if (today < startDate) {
                status = 'upcoming';
            } else if (today >= startDate && today <= endDate) {
                status = 'ongoing';
            } else if (today > endDate) {
                status = 'completed';
            }
        }
        
        statusEl.value = status;
        statusEl.disabled = true;
        statusEl.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-75');
        
        const statusLabel = statusEl.previousElementSibling;
        if (statusLabel && statusLabel.tagName === 'LABEL') {
            if (!statusLabel.querySelector('.auto-label')) {
                const autoLabel = document.createElement('span');
                autoLabel.className = 'auto-label ml-2 text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded';
                autoLabel.textContent = '(Otomatis)';
                statusLabel.appendChild(autoLabel);
            }
        }
        
        const statusGroup = statusEl.closest('.form-group');
        if (statusGroup) {
            let noteEl = statusGroup.querySelector('.status-auto-note');
            if (!noteEl) {
                noteEl = document.createElement('p');
                noteEl.className = 'status-auto-note text-xs text-gray-500 mt-1';
                statusGroup.appendChild(noteEl);
            }
            noteEl.textContent = 'Status ditentukan otomatis berdasarkan tanggal.';
            noteEl.innerHTML = '<svg class="inline-block h-3 w-3 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' + noteEl.textContent;
        }
    };
    
    // Function to set up event listeners for sub-competition modals and forms.
    function setupSubCompetitionEventListeners() {
        const addButtons = [
            document.getElementById('open-add-sub-competition-modal'),
            document.getElementById('open-add-sub-competition-modal-empty')
        ];

        addButtons.forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('add-sub-competition-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        resetFormErrors('add-sub-competition-form');
                        document.getElementById('add-sub-competition-form').reset();
                        window.updateSubCompetitionStatus('add');
                        setDefaultDates();
                    }
                });
            }
        });

        const closeButtons = {
            'close-add-modal': 'add-sub-competition-modal',
            'close-edit-modal': 'edit-sub-competition-modal',
            'close-delete-modal': 'delete-sub-competition-modal',
            'close-show-sub-modal': 'show-sub-competition-modal',
            'close-show-sub-competition': 'show-sub-competition-modal'
        };

        Object.entries(closeButtons).forEach(([buttonId, modalId]) => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.addEventListener('click', function() {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                        if (modalId === 'add-sub-competition-modal') {
                            document.getElementById('add-sub-competition-form').reset();
                            showStep(1, 'add');
                        } else if (modalId === 'edit-sub-competition-modal') {
                            document.getElementById('edit-sub-competition-form').reset();
                            showStep(1, 'edit');
                        }
                        resetFormErrors(modalId.replace('-modal', '-form'));
                    }
                });
            }
        });
        
        const cancelButtons = {
            'cancel-add-sub-competition': 'add-sub-competition-modal',
            'cancel-edit-sub-competition': 'edit-sub-competition-modal'
        };
        
        Object.entries(cancelButtons).forEach(([buttonId, modalId]) => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.addEventListener('click', function() {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                        if (modalId === 'add-sub-competition-modal') {
                            document.getElementById('add-sub-competition-form').reset();
                            showStep(1, 'add');
                        } else if (modalId === 'edit-sub-competition-modal') {
                            document.getElementById('edit-sub-competition-form').reset();
                            showStep(1, 'edit');
                        }
                        resetFormErrors(modalId.replace('-modal', '-form'));
                    }
                });
            }
        });

        const submitButtons = {
            'submit-add-sub-competition': submitAddSubCompetition,
            'submit-edit-sub-competition': submitEditSubCompetition,
            'confirm-delete-sub-competition': submitDeleteSubCompetition
        };

        Object.entries(submitButtons).forEach(([buttonId, handler]) => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.addEventListener('click', function() {
                    if (buttonId === 'submit-add-sub-competition') {
                        window.updateSubCompetitionStatus('add');
                    } else if (buttonId === 'submit-edit-sub-competition') {
                        window.updateSubCompetitionStatus('edit');
                    }
                    handler();
                });
            }
        });
        
        const editNextButton = document.getElementById('edit-next-step');
        const editPrevButton = document.getElementById('edit-prev-step');
        
        if (editNextButton) {
            editNextButton.addEventListener('click', function() {
                showStep(2, 'edit');
                window.updateSubCompetitionStatus('edit');
                const statusEl = document.getElementById('edit_status');
                if (statusEl) {
                    statusEl.disabled = true;
                    statusEl.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-75');
                }
            });
        }
        
        if (editPrevButton) {
            editPrevButton.addEventListener('click', function() {
                showStep(1, 'edit');
            });
        }

        const modals = ['add-sub-competition-modal', 'edit-sub-competition-modal', 'delete-sub-competition-modal', 'show-sub-competition-modal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        if (modalId === 'add-sub-competition-modal') {
                            document.getElementById('add-sub-competition-form').reset();
                            showStep(1, 'add');
                        } else if (modalId === 'edit-sub-competition-modal') {
                            document.getElementById('edit-sub-competition-form').reset();
                            showStep(1, 'edit');
                        }
                        resetFormErrors(modalId.replace('-modal', '-form'));
                    }
                });
            }
        });

        const nextStepBtn = document.getElementById('next-step');
        const prevStepBtn = document.getElementById('prev-step');
        const step1Content = document.getElementById('step-1-content');
        const step2Content = document.getElementById('step-2-content');
        const stepIndicators = document.querySelectorAll('.step-item');
        const stepLine = document.querySelector('.step-line');
        
        const startDateField = document.getElementById('add-sub-start-date');
        const endDateField = document.getElementById('add-sub-end-date');
        
        if (startDateField) {
            startDateField.addEventListener('change', function() {
                window.updateSubCompetitionStatus('add');
            });
        }
        
        if (endDateField) {
            endDateField.addEventListener('change', function() {
                window.updateSubCompetitionStatus('add');
            });
        }

        if (nextStepBtn && prevStepBtn && step1Content && step2Content) {
            let currentStep = 1;

            nextStepBtn.addEventListener('click', function() {
                if (currentStep === 1) {
                    if (validateStep1()) {
                        currentStep = 2;
                        step1Content.classList.add('hidden');
                        step2Content.classList.remove('hidden');
                        prevStepBtn.classList.remove('hidden');
                        window.updateSubCompetitionStatus('add');
                        nextStepBtn.classList.add('hidden');
                        stepIndicators[1].classList.add('active');
                        stepLine.classList.add('active');
                    }
                }
            });

            prevStepBtn.addEventListener('click', function() {
                if (currentStep === 2) {
                    currentStep = 1;
                    step2Content.classList.add('hidden');
                    step1Content.classList.remove('hidden');
                    prevStepBtn.classList.add('hidden');
                    nextStepBtn.classList.remove('hidden');
                    stepIndicators[1].classList.remove('active');
                    stepLine.classList.remove('active');
                }
            });
        }

        const editFromShowButton = document.getElementById('edit-from-show-sub');
        if (editFromShowButton) {
            editFromShowButton.addEventListener('click', function() {
                const subCompetitionId = this.getAttribute('data-sub-competition-id');
                if (subCompetitionId) {
                    document.getElementById('show-sub-competition-modal').classList.add('hidden');
                    editSubCompetition(subCompetitionId);
                }
            });
        }
    }

    // Function to show a specific step in the add or edit sub-competition form.
    function showStep(stepNumber, prefix = 'add') {
        let step1Content, step2Content, nextButton, prevButton, submitButton, steps, stepLine;
        
        if (prefix === 'add') {
            step1Content = document.getElementById('step-1-content');
            step2Content = document.getElementById('step-2-content');
            nextButton = document.getElementById('next-step');
            prevButton = document.getElementById('prev-step');
            submitButton = document.getElementById('submit-add-sub-competition');
        } else if (prefix === 'edit') {
            step1Content = document.getElementById('edit-step-1-content');
            step2Content = document.getElementById('edit-step-2-content');
            nextButton = document.getElementById('edit-next-step');
            prevButton = document.getElementById('edit-prev-step');
            submitButton = document.getElementById('submit-edit-sub-competition');
        }
        
        const modal = document.getElementById(`${prefix}-sub-competition-modal`);
        if (!modal) return;
        
        steps = modal.querySelectorAll('.step-item');
        stepLine = modal.querySelector('.step-line');
        
        if (!step1Content || !step2Content || !nextButton || !prevButton || !submitButton || !steps.length) {
            return;
        }
        
        if (stepNumber === 1) {
            step1Content.classList.remove('hidden');
            step2Content.classList.add('hidden');
            nextButton.classList.remove('hidden');
            submitButton.classList.add('hidden');
            prevButton.classList.add('hidden');
            steps[0].classList.add('active');
            steps[0].querySelector('div').classList.add('bg-blue-600', 'text-white');
            steps[0].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
            steps[1].classList.remove('active');
            steps[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
            steps[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
            stepLine.classList.remove('active');
        } else {
            step1Content.classList.add('hidden');
            step2Content.classList.remove('hidden');
            nextButton.classList.add('hidden');
            submitButton.classList.remove('hidden');
            prevButton.classList.remove('hidden');
            steps[1].classList.add('active');
            steps[1].querySelector('div').classList.add('bg-blue-600', 'text-white');
            steps[1].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
            stepLine.classList.add('active');
        }
    }

    // Function to validate step 1 of the add sub-competition form.
    function validateStep1() {
        const form = document.getElementById('add-sub-competition-form');
        let isValid = true;
        let errorMessages = [];

        const requiredFields = ['add-sub-name', 'add-sub-category', 'add-sub-status'];
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                isValid = false;
                const fieldLabel = field.closest('.form-group').querySelector('label');
                const fieldName = fieldLabel ? fieldLabel.textContent.replace('*', '').trim() : 'Field';
                errorMessages.push(`<li>${fieldName} is required</li>`);
                field.classList.add('border-red-500');
                
                const errorElement = document.getElementById(`${fieldId}-error`);
                if (errorElement) {
                    errorElement.textContent = `${fieldName} is required`;
                    errorElement.classList.remove('hidden');
                }
            }
        });

        if (!isValid) {
            const errorContainer = document.getElementById('add-sub-competition-error');
            const errorList = document.getElementById('add-sub-competition-error-list');
            const errorCount = document.getElementById('add-sub-competition-error-count');
            
            if (errorContainer && errorList && errorCount) {
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessages.join('');
                errorCount.textContent = errorMessages.length;
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isValid;
    }

    // Function to load sub-competition data for editing.
    window.editSubCompetition = function(id) {
        const modal = document.getElementById('edit-sub-competition-modal');
        if (!modal) return;
        
        modal.classList.remove('hidden');
        
        const skeletonElements = modal.querySelectorAll('.edit-sub-competition-skeleton');
        const contentElements = modal.querySelectorAll('.edit-sub-competition-content');
        
        skeletonElements.forEach(el => el.classList.remove('hidden'));
        contentElements.forEach(el => el.classList.add('hidden'));
        
        const url = window.subCompetitionRoutes.show.replace('__id__', id);
        fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load sub-competition');
            }
            
            skeletonElements.forEach(el => el.classList.add('hidden'));
            contentElements.forEach(el => el.classList.remove('hidden'));
            
            const subCompetition = data.data;
            
            // Helper function to format dates for HTML date inputs (YYYY-MM-DD).
            function formatDateForInput(dateString) {
                if (!dateString) return '';
                
                let date;
                if (dateString.includes('T')) {
                    date = new Date(dateString);
                } else if (dateString.includes('-')) {
                    const parts = dateString.split('-');
                    if (parts.length === 3) {
                        return dateString;
                    }
                    date = new Date(dateString);
                } else {
                    date = new Date(dateString);
                }
                
                if (isNaN(date.getTime())) {
                    console.warn('Invalid date:', dateString);
                    return '';
                }
                
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            
            document.getElementById('edit_id').value = subCompetition.id;
            document.getElementById('edit_name').value = subCompetition.name;
            document.getElementById('edit_category_id').value = subCompetition.category_id;
            document.getElementById('edit_description').value = subCompetition.description || '';
            document.getElementById('edit_start_date').value = formatDateForInput(subCompetition.start_date);
            document.getElementById('edit_end_date').value = formatDateForInput(subCompetition.end_date);
            document.getElementById('edit_registration_start').value = formatDateForInput(subCompetition.registration_start);
            document.getElementById('edit_registration_end').value = formatDateForInput(subCompetition.registration_end);
            document.getElementById('edit_competition_date').value = formatDateForInput(subCompetition.competition_date);
            document.getElementById('edit_registration_link').value = subCompetition.registration_link || '';
            document.getElementById('edit_requirements').value = subCompetition.requirements || '';
            document.getElementById('edit_status').value = subCompetition.status;
            
            showStep(1, 'edit');
            window.updateSubCompetitionStatus('edit');
            
            const editStartDateField = document.getElementById('edit_start_date');
            const editEndDateField = document.getElementById('edit_end_date');
            
            // Function to update status for edit form.
            function updateEditStatus() {
                window.updateSubCompetitionStatus('edit');
            }

            if (editStartDateField) {
                editStartDateField.removeEventListener('change', updateEditStatus);
                editStartDateField.addEventListener('change', updateEditStatus);
            }
            
            if (editEndDateField) {
                editEndDateField.removeEventListener('change', updateEditStatus);
                editEndDateField.addEventListener('change', updateEditStatus);
            }
            
            resetFormErrors('edit-sub-competition-form');
            document.getElementById('edit-sub-competition-modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading sub-competition:', error);
            showNotification('Failed to load sub-competition data. Please try again.', 'error');
            
            const skeletonElements = modal.querySelectorAll('.edit-sub-competition-skeleton');
            skeletonElements.forEach(el => el.classList.add('hidden'));
            
            const contentElements = modal.querySelectorAll('.edit-sub-competition-content');
            contentElements.forEach(el => el.classList.remove('hidden'));
            
            resetFormErrors('edit-sub-competition-form');
            document.getElementById('edit-sub-competition-form').reset();
        });
    };

    // Function to prepare for deleting a sub-competition.
    window.deleteSubCompetition = function(id) {
        const modal = document.getElementById('delete-sub-competition-modal');
        const idField = document.getElementById('delete_id');
        if (modal && idField) {
            idField.value = id;
            modal.classList.remove('hidden');
        }
    };

    // Function to handle the submission of the add sub-competition form.
    function submitAddSubCompetition() {
        const form = document.getElementById('add-sub-competition-form');
        if (!form) return;

        const formData = new FormData(form);
        
        fetch(window.subCompetitionRoutes.store, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('add-sub-competition-modal').classList.add('hidden');
                showNotification('Sub-competition added successfully');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to add sub-competition');
            }
        })
        .catch(error => {
            console.error('Error adding sub-competition:', error);
            if (error.response && error.response.status === 422) {
                displayValidationErrors(error.response.data.errors, 'add-sub-competition-form');
            } else {
                showNotification(error.message || 'Failed to add sub-competition. Please try again.', 'error');
            }
        });
    }

    // Function to handle the submission of the edit sub-competition form.
    function submitEditSubCompetition() {
        const form = document.getElementById('edit-sub-competition-form');
        const id = document.getElementById('edit_id').value;
        if (!form || !id) return;

        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        fetch(window.subCompetitionRoutes.update.replace('__id__', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit-sub-competition-modal').classList.add('hidden');
                showNotification('Sub-competition updated successfully');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to update sub-competition');
            }
        })
        .catch(error => {
            console.error('Error updating sub-competition:', error);
            if (error.response && error.response.status === 422) {
                displayValidationErrors(error.response.data.errors, 'edit-sub-competition-form');
            } else {
                showNotification(error.message || 'Failed to update sub-competition. Please try again.', 'error');
            }
        });
    }

    // Function to handle the deletion of a sub-competition.
    function submitDeleteSubCompetition() {
        const id = document.getElementById('delete_id').value;
        if (!id) return;

        fetch(window.subCompetitionRoutes.destroy.replace('__id__', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('delete-sub-competition-modal').classList.add('hidden');
                showNotification('Sub-kompetisi berhasil dihapus');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to delete sub-competition');
            }
        })
        .catch(error => {
            console.error('Error deleting sub-competition:', error);
            showNotification(error.message || 'Failed to delete sub-competition. Please try again.', 'error');
        });
    }

    // Function to display validation errors on a form.
    function displayValidationErrors(errors, formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        resetFormErrors(formId);

        const errorMessages = [];
        Object.entries(errors).forEach(([field, messages]) => {
            const inputField = form.querySelector(`[name="${field}"]`);
            if (inputField) {
                inputField.classList.add('border-red-500');
                inputField.classList.remove('border-gray-300');
                
                const errorElement = inputField.closest('.form-group').querySelector('.error-message');
                if (errorElement) {
                    errorElement.textContent = messages[0];
                    errorElement.classList.remove('hidden');
                }
            }
            errorMessages.push(`<li>${messages[0]}</li>`);
        });

        const errorContainer = document.getElementById(`${formId}-error`);
        const errorList = document.getElementById(`${formId}-error-list`);
        const errorCount = document.getElementById(`${formId}-error-count`);
        
        if (errorContainer && errorList && errorCount) {
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = errorMessages.join('');
            errorCount.textContent = errorMessages.length;
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Function to reset errors on a form.
    function resetFormErrors(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });

        form.querySelectorAll('.error-message').forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });

        const errorContainer = document.getElementById(`${formId}-error`);
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
    }

    // Function to display a notification message.
    function showNotification(message, type = 'success') {
        const notificationId = 'notification-' + Date.now();
        const notificationHtml = `
            <div id="${notificationId}" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 translate-x-full">
                <div class="rounded-lg shadow-lg p-4 ${type === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'} flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        ${type === 'success' 
                            ? `<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                               </svg>`
                            : `<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                               </svg>`
                        }
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="closeNotification('${notificationId}')" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', notificationHtml);
        
        setTimeout(() => {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.classList.remove('translate-x-full');
            }
        }, 100);

        setTimeout(() => {
            closeNotification(notificationId);
        }, 5000);
    }

    // Function to automatically check and update sub-competition statuses based on current date.
    function autoUpdateSubCompetitionStatuses() {
        if (!window.subCompetitionRoutes || !window.subCompetitionRoutes.index || !window.competitionId) {
            console.log('Sub-competition routes not available, skipping auto status update');
            return;
        }
        
        fetch(window.subCompetitionRoutes.index, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to fetch sub-competitions for status update');
                return;
            }
            
            const subCompetitions = data.data;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const subCompetitionsToUpdate = [];
            
            subCompetitions.forEach(subComp => {
                let newStatus = null;
                const startDate = subComp.start_date ? new Date(subComp.start_date) : null;
                const endDate = subComp.end_date ? new Date(subComp.end_date) : null;
                
                if (startDate && endDate) {
                    if (today < startDate && subComp.status !== 'upcoming') {
                        newStatus = 'upcoming';
                    } else if (today >= startDate && today <= endDate && subComp.status !== 'ongoing') {
                        newStatus = 'ongoing';
                    } else if (today > endDate && subComp.status !== 'completed') {
                        newStatus = 'completed';
                    }
                    
                    if (newStatus) {
                        subCompetitionsToUpdate.push({
                            id: subComp.id,
                            currentStatus: subComp.status,
                            newStatus: newStatus
                        });
                    }
                }
            });
            
            if (subCompetitionsToUpdate.length > 0) {
                console.log(`Found ${subCompetitionsToUpdate.length} sub-competitions that need status updates`);
                
                const updatePromises = subCompetitionsToUpdate.map(subComp => {
                    const formData = new FormData();
                    formData.append('status', subComp.newStatus);
                    formData.append('_method', 'PATCH');
                    
                    const updateUrl = window.subCompetitionRoutes.update.replace('__id__', subComp.id);
                    
                    return fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`Updated sub-competition ${subComp.id} status from ${subComp.currentStatus} to ${subComp.newStatus}`);
                            return true;
                        } else {
                            console.error(`Failed to update sub-competition ${subComp.id} status`, data);
                            return false;
                        }
                    })
                    .catch(error => {
                        console.error(`Error updating sub-competition ${subComp.id} status:`, error);
                        return false;
                    });
                });
                
                Promise.all(updatePromises)
                    .then(results => {
                        const successCount = results.filter(result => result === true).length;
                        if (successCount > 0) {
                            console.log(`Successfully updated ${successCount} sub-competition statuses`);
                            showNotification(`${successCount} sub-competition statuses were automatically updated based on current date.`, 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        }
                    });
            } else {
                console.log('All sub-competition statuses are up to date');
            }
        })
        .catch(error => {
            console.error('Error fetching sub-competitions for status update:', error);
        });
    }
    
    // Function to close a notification message.
    window.closeNotification = function(notificationId) {
        const notification = document.getElementById(notificationId);
        if (notification) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    };

    // Function to show sub-competition details in a modal.
    window.showSubCompetition = function(subCompetitionId) {
        const modal = document.getElementById('show-sub-competition-modal');
        if (!modal) return;
        
        modal.classList.remove('hidden');
        
        const skeletonElements = modal.querySelectorAll('.sub-competition-detail-skeleton');
        const contentElements = modal.querySelectorAll('.sub-competition-detail-content');
        
        skeletonElements.forEach(el => el.classList.remove('hidden'));
        contentElements.forEach(el => el.classList.add('hidden'));
        
        const editButton = document.getElementById('edit-from-show-sub');
        if (editButton) {
            editButton.setAttribute('data-sub-competition-id', subCompetitionId);
        }
        
        fetch(window.subCompetitionRoutes.show.replace('__id__', subCompetitionId))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(response => {
                skeletonElements.forEach(el => el.classList.add('hidden'));
                contentElements.forEach(el => el.classList.remove('hidden'));
                
                const data = response.data ? response.data : response;
                
                console.log('Sub-competition data:', data);
                
                document.getElementById('sub-competition-name').textContent = data.name || 'Unnamed';
                document.getElementById('sub-competition-category').textContent = data.category ? data.category.name : 'Tidak ada kategori';
                document.getElementById('sub-competition-id').textContent = data.id || '-';
                
                const statusElement = document.getElementById('sub-competition-status');
                const status = data.status || 'unknown';
                statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusElement.className = 'px-3 py-1 text-xs leading-5 font-semibold rounded-full';
                
                if (status === 'upcoming') {
                    statusElement.classList.add('bg-blue-100', 'text-blue-800');
                } else if (status === 'ongoing') {
                    statusElement.classList.add('bg-green-100', 'text-green-800');
                } else if (status === 'completed') {
                    statusElement.classList.add('bg-gray-100', 'text-gray-800');
                } else {
                    statusElement.classList.add('bg-gray-100', 'text-gray-800');
                }
                
                document.getElementById('sub-competition-category-detail').textContent = data.category ? data.category.name : 'Tidak ada kategori';
                document.getElementById('sub-competition-participants').textContent = data.participants_count || '0';
                
                const startDate = data.start_date ? new Date(data.start_date) : null;
                const endDate = data.end_date ? new Date(data.end_date) : null;
                const regStartDate = data.registration_start ? new Date(data.registration_start) : null;
                const regEndDate = data.registration_end ? new Date(data.registration_end) : null;
                const compDate = data.competition_date ? new Date(data.competition_date) : null;
                
                // Function to format a date for display.
                const formatDate = (date) => {
                    if (!date) return '-';
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                };
                
                document.getElementById('sub-competition-dates').textContent = startDate && endDate ? 
                    `${formatDate(startDate)} - ${formatDate(endDate)}` : '-';
                    
                document.getElementById('sub-competition-registration').textContent = regStartDate && regEndDate ? 
                    `${formatDate(regStartDate)} - ${formatDate(regEndDate)}` : '-';
                    
                document.getElementById('sub-competition-date').textContent = compDate ? formatDate(compDate) : '-';
                
                document.getElementById('sub-competition-description').textContent = data.description || 'Tidak ada deskripsi';
                
                const skillsContainer = document.getElementById('sub-competition-skills').querySelector('.flex');
                skillsContainer.innerHTML = '';
                
                if (data.skills && data.skills.length > 0) {
                    data.skills.forEach(skill => {
                        const skillBadge = document.createElement('span');
                        skillBadge.className = 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                        skillBadge.textContent = skill.name;
                        skillsContainer.appendChild(skillBadge);
                    });
                } else {
                    const noSkills = document.createElement('span');
                    noSkills.className = 'text-gray-500 text-sm';
                    noSkills.textContent = 'Tidak ada skill yang dibutuhkan';
                    skillsContainer.appendChild(noSkills);
                }
                
                if (data.updated_at) {
                    const updatedDate = new Date(data.updated_at);
                    document.getElementById('show-sub-competition-updated-at').textContent = 
                        updatedDate.toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'long', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                }
                
                document.getElementById('sub-competition-name-preview').textContent = data.name || 'Unnamed';
                
                const statusPreviewElement = document.getElementById('sub-competition-status-preview');
                statusPreviewElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusPreviewElement.className = 'px-2 py-1 text-xs font-medium rounded-full';
                
                if (status === 'upcoming') {
                    statusPreviewElement.classList.add('bg-blue-100', 'text-blue-800');
                } else if (status === 'ongoing') {
                    statusPreviewElement.classList.add('bg-green-100', 'text-green-800');
                } else if (status === 'completed') {
                    statusPreviewElement.classList.add('bg-gray-100', 'text-gray-800');
                } else {
                    statusPreviewElement.classList.add('bg-gray-100', 'text-gray-800');
                }
                
                document.getElementById('sub-competition-category-preview').textContent = data.category ? data.category.name : 'Tidak ada kategori';
                document.getElementById('sub-competition-description-preview').textContent = data.description || 'Tidak ada deskripsi';
                document.getElementById('sub-competition-dates-preview').textContent = startDate && endDate ? 
                    `${formatDate(startDate)} - ${formatDate(endDate)}` : '-';
                document.getElementById('sub-competition-registration-preview').textContent = regStartDate && regEndDate ? 
                    `Pendaftaran: ${formatDate(regStartDate)} - ${formatDate(regEndDate)}` : '-';
                
                const skillsPreviewContainer = document.getElementById('sub-competition-skills-preview');
                skillsPreviewContainer.innerHTML = '';
                
                if (data.skills && data.skills.length > 0) {
                    data.skills.forEach(skill => {
                        const skillBadge = document.createElement('span');
                        skillBadge.className = 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                        skillBadge.textContent = skill.name;
                        skillsPreviewContainer.appendChild(skillBadge);
                    });
                } else {
                    const noSkills = document.createElement('span');
                    noSkills.className = 'text-gray-500 text-sm';
                    noSkills.textContent = 'Tidak ada skill yang dibutuhkan';
                    skillsPreviewContainer.appendChild(noSkills);
                }
            })
            .catch(error => {
                console.error('Error fetching sub-competition details:', error);
                showNotification('Gagal memuat detail sub-kompetisi. Silakan coba lagi.', 'error');
                
                skeletonElements.forEach(el => el.classList.add('hidden'));
                
                const contentArea = modal.querySelector('.sub-competition-detail-content');
                if (contentArea) {
                    contentArea.classList.remove('hidden');
                    contentArea.innerHTML = `
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal memuat data</h3>
                            <p class="text-gray-500">Terjadi kesalahan saat memuat detail sub-kompetisi. Silakan coba lagi nanti.</p>
                        </div>
                    `;
                }
            });
    };

    // Function to set up skills management functionality.
    function setupSkillsManagement() {
        const skillsTable = document.getElementById('skills-table');
        if (!skillsTable) return;
        
        console.log('Setting up skills management');
        
        setupAddSkillModal();
        setupSkillActions();
    }
    
    // Function to set up event listeners for the add skill modal.
    function setupAddSkillModal() {
        const addSkillButton = document.getElementById('open-add-skill-modal');
        if (addSkillButton) {
            addSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('add-skill-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    resetFormErrors('add-skill-form');
                    document.getElementById('add-skill-form').reset();
                }
            });
        }
        
        const closeAddSkillButton = document.getElementById('close-add-skill-modal');
        if (closeAddSkillButton) {
            closeAddSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('add-skill-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.getElementById('add-skill-form').reset();
                    resetFormErrors('add-skill-form');
                }
            });
        }
        
        const cancelAddSkillButton = document.getElementById('cancel-add-skill');
        if (cancelAddSkillButton) {
            cancelAddSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('add-skill-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.getElementById('add-skill-form').reset();
                    resetFormErrors('add-skill-form');
                }
            });
        }
        
        const submitAddSkillButton = document.getElementById('submit-add-skill');
        if (submitAddSkillButton) {
            submitAddSkillButton.addEventListener('click', submitAddSkill);
        }
        
        const addSkillModal = document.getElementById('add-skill-modal');
        if (addSkillModal) {
            addSkillModal.addEventListener('click', function(e) {
                if (e.target === addSkillModal) {
                    addSkillModal.classList.add('hidden');
                    document.getElementById('add-skill-form').reset();
                    resetFormErrors('add-skill-form');
                }
            });
        }
    }
    
    // Function to handle the submission of the add skill form.
    function submitAddSkill() {
        const form = document.getElementById('add-skill-form');
        if (!form) return;
        
        const formData = new FormData(form);
        
        const currentUrl = window.location.pathname;
        const storeUrl = `${currentUrl}/store`;
        
        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('add-skill-modal').classList.add('hidden');
                showNotification('Skill added successfully');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to add skill');
            }
        })
        .catch(error => {
            console.error('Error adding skill:', error);
            if (error.response && error.response.status === 422) {
                displayValidationErrors(error.response.data.errors, 'add-skill-form');
            } else {
                showNotification(error.message || 'Failed to add skill. Please try again.', 'error');
            }
        });
    }
    
    // Function to set up event listeners for edit and delete skill actions.
    function setupSkillActions() {
        document.querySelectorAll('.edit-skill-btn').forEach(button => {
            button.addEventListener('click', function() {
                const skillId = this.getAttribute('data-skill-id');
                if (skillId) {
                    editSkill(skillId);
                }
            });
        });
        
        document.querySelectorAll('.delete-skill-btn').forEach(button => {
            button.addEventListener('click', function() {
                const skillId = this.getAttribute('data-skill-id');
                if (skillId) {
                    deleteSkill(skillId);
                }
            });
        });
        
        setupEditSkillModal();
        setupDeleteSkillModal();
    }
    
    // Function to set up event listeners for the edit skill modal.
    function setupEditSkillModal() {
        const closeEditSkillButton = document.getElementById('close-edit-skill-modal');
        if (closeEditSkillButton) {
            closeEditSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('edit-skill-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.getElementById('edit-skill-form').reset();
                    resetFormErrors('edit-skill-form');
                }
            });
        }
        
        const cancelEditSkillButton = document.getElementById('cancel-edit-skill');
        if (cancelEditSkillButton) {
            cancelEditSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('edit-skill-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.getElementById('edit-skill-form').reset();
                    resetFormErrors('edit-skill-form');
                }
            });
        }
        
        const submitEditSkillButton = document.getElementById('submit-edit-skill');
        if (submitEditSkillButton) {
            submitEditSkillButton.addEventListener('click', submitEditSkill);
        }
        
        const editSkillModal = document.getElementById('edit-skill-modal');
        if (editSkillModal) {
            editSkillModal.addEventListener('click', function(e) {
                if (e.target === editSkillModal) {
                    editSkillModal.classList.add('hidden');
                    document.getElementById('edit-skill-form').reset();
                    resetFormErrors('edit-skill-form');
                }
            });
        }
    }
    
    // Function to load skill data for editing.
    function editSkill(skillId) {
        const currentUrl = window.location.pathname;
        const showUrl = `${currentUrl}/${skillId}`;
        
        fetch(showUrl, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load skill');
            }
            
            const skill = data.data;
            
            document.getElementById('edit_skill_id').value = skill.id;
            document.getElementById('edit_skill_name').value = skill.name;
            document.getElementById('edit_skill_description').value = skill.description || '';
            
            resetFormErrors('edit-skill-form');
            document.getElementById('edit-skill-modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading skill:', error);
            showNotification('Failed to load skill data. Please try again.', 'error');
        });
    }
    
    // Function to handle the submission of the edit skill form.
    function submitEditSkill() {
        const form = document.getElementById('edit-skill-form');
        const skillId = document.getElementById('edit_skill_id').value;
        if (!form || !skillId) return;
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        const currentUrl = window.location.pathname;
        const updateUrl = `${currentUrl}/${skillId}`;
        
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit-skill-modal').classList.add('hidden');
                showNotification('Skill updated successfully');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to update skill');
            }
        })
        .catch(error => {
            console.error('Error updating skill:', error);
            if (error.response && error.response.status === 422) {
                displayValidationErrors(error.response.data.errors, 'edit-skill-form');
            } else {
                showNotification(error.message || 'Failed to update skill. Please try again.', 'error');
            }
        });
    }
    
    // Function to set up event listeners for the delete skill modal.
    function setupDeleteSkillModal() {
        const closeDeleteSkillButton = document.getElementById('close-delete-skill-modal');
        if (closeDeleteSkillButton) {
            closeDeleteSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('delete-skill-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            });
        }
        
        const cancelDeleteSkillButton = document.getElementById('cancel-delete-skill');
        if (cancelDeleteSkillButton) {
            cancelDeleteSkillButton.addEventListener('click', function() {
                const modal = document.getElementById('delete-skill-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            });
        }
        
        const confirmDeleteSkillButton = document.getElementById('confirm-delete-skill');
        if (confirmDeleteSkillButton) {
            confirmDeleteSkillButton.addEventListener('click', submitDeleteSkill);
        }
        
        const deleteSkillModal = document.getElementById('delete-skill-modal');
        if (deleteSkillModal) {
            deleteSkillModal.addEventListener('click', function(e) {
                if (e.target === deleteSkillModal) {
                    deleteSkillModal.classList.add('hidden');
                }
            });
        }
    }
    
    // Function to prepare for deleting a skill.
    function deleteSkill(skillId) {
        const modal = document.getElementById('delete-skill-modal');
        const idField = document.getElementById('delete_skill_id');
        if (modal && idField) {
            idField.value = skillId;
            modal.classList.remove('hidden');
        }
    }
    
    // Function to handle the deletion of a skill.
    function submitDeleteSkill() {
        const skillId = document.getElementById('delete_skill_id').value;
        if (!skillId) return;
        
        const currentUrl = window.location.pathname;
        const deleteUrl = `${currentUrl}/${skillId}`;
        
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('delete-skill-modal').classList.add('hidden');
                showNotification('Skill berhasil dihapus');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to delete skill');
            }
        })
        .catch(error => {
            console.error('Error deleting skill:', error);
            showNotification(error.message || 'Failed to delete skill. Please try again.', 'error');
        });
    }
}); 