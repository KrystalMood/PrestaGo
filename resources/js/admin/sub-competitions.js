document.addEventListener('DOMContentLoaded', function() {
    window.subCompetitionSetup = true;
    
    setupSubCompetitionEventListeners();
    
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
                    }
                });
            }
        });

        const closeButtons = {
            'close-add-modal': 'add-sub-competition-modal',
            'close-edit-modal': 'edit-sub-competition-modal',
            'close-delete-modal': 'delete-sub-competition-modal'
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
                button.addEventListener('click', handler);
            }
        });

        const modals = ['add-sub-competition-modal', 'edit-sub-competition-modal', 'delete-sub-competition-modal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        if (modalId === 'add-sub-competition-modal') {
                            document.getElementById('add-sub-competition-form').reset();
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

        if (nextStepBtn && prevStepBtn && step1Content && step2Content) {
            let currentStep = 1;

            nextStepBtn.addEventListener('click', function() {
                if (currentStep === 1) {
                    if (validateStep1()) {
                        currentStep = 2;
                        step1Content.classList.add('hidden');
                        step2Content.classList.remove('hidden');
                        prevStepBtn.classList.remove('hidden');
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
        fetch(window.subCompetitionRoutes.show.replace('__id__', id), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load sub-competition');
            }
            
            const subCompetition = data.data;
            
            const fields = {
                'edit_id': 'id',
                'edit_name': 'name',
                'edit_description': 'description',
                'edit_category_id': 'category_id',
                'edit_start_date': 'start_date',
                'edit_end_date': 'end_date',
                'edit_registration_start': 'registration_start',
                'edit_registration_end': 'registration_end',
                'edit_competition_date': 'competition_date',
                'edit_registration_link': 'registration_link',
                'edit_requirements': 'requirements',
                'edit_status': 'status'
            };

            Object.entries(fields).forEach(([elementId, dataField]) => {
                const element = document.getElementById(elementId);
                if (element) {
                    let value = subCompetition[dataField] || '';
                    if (value && ['start_date', 'end_date', 'registration_start', 'registration_end', 'competition_date'].includes(dataField)) {
                        value = value.split('T')[0];
                    }
                    element.value = value;
                }
            });
            
            resetFormErrors('edit-sub-competition-form');
            document.getElementById('edit-sub-competition-modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading sub-competition:', error);
            showNotification('Failed to load sub-competition data. Please try again.', 'error');
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
                showNotification('Sub-competition deleted successfully');
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
}); 