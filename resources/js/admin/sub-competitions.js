// Function to initialize the sub-competition management interface when the DOM is ready.
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
                    document.getElementById('add-sub-competition-modal').classList.remove('hidden');
                    resetFormErrors('add-sub-competition-form');
                });
            }
        });

        const closeAddModalBtn = document.getElementById('close-add-modal');
        if (closeAddModalBtn) {
            closeAddModalBtn.addEventListener('click', function() {
                document.getElementById('add-sub-competition-modal').classList.add('hidden');
                document.getElementById('add-sub-competition-form').reset();
                resetFormErrors('add-sub-competition-form');
            });
        }

        const closeEditModalBtn = document.getElementById('close-edit-modal');
        if (closeEditModalBtn) {
            closeEditModalBtn.addEventListener('click', function() {
                document.getElementById('edit-sub-competition-modal').classList.add('hidden');
                resetFormErrors('edit-sub-competition-form');
            });
        }

        const closeDeleteModalBtn = document.getElementById('close-delete-modal');
        if (closeDeleteModalBtn) {
            closeDeleteModalBtn.addEventListener('click', function() {
                document.getElementById('delete-sub-competition-modal').classList.add('hidden');
            });
        }

        const submitAddBtn = document.getElementById('submit-add-sub-competition');
        if (submitAddBtn) {
            submitAddBtn.addEventListener('click', function() {
                submitAddSubCompetition();
            });
        }

        const submitEditBtn = document.getElementById('submit-edit-sub-competition');
        if (submitEditBtn) {
            submitEditBtn.addEventListener('click', function() {
                submitEditSubCompetition();
            });
        }

        const submitDeleteBtn = document.getElementById('confirm-delete-sub-competition');
        if (submitDeleteBtn) {
            submitDeleteBtn.addEventListener('click', function() {
                submitDeleteSubCompetition();
            });
        }
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
            
            document.getElementById('edit_id').value = subCompetition.id;
            document.getElementById('edit_name').value = subCompetition.name;
            document.getElementById('edit_description').value = subCompetition.description || '';
            
            if (subCompetition.category_id) {
                document.getElementById('edit_category_id').value = subCompetition.category_id;
            }
            
            if (subCompetition.start_date) {
                document.getElementById('edit_start_date').value = subCompetition.start_date.split('T')[0];
            }
            
            if (subCompetition.end_date) {
                document.getElementById('edit_end_date').value = subCompetition.end_date.split('T')[0];
            }
            
            if (subCompetition.registration_start) {
                document.getElementById('edit_registration_start').value = subCompetition.registration_start.split('T')[0];
            }
            
            if (subCompetition.registration_end) {
                document.getElementById('edit_registration_end').value = subCompetition.registration_end.split('T')[0];
            }
            
            if (subCompetition.competition_date) {
                document.getElementById('edit_competition_date').value = subCompetition.competition_date.split('T')[0];
            }
            
            document.getElementById('edit_registration_link').value = subCompetition.registration_link || '';
            document.getElementById('edit_requirements').value = subCompetition.requirements || '';
            document.getElementById('edit_status').value = subCompetition.status;
            
            resetFormErrors('edit-sub-competition-form');
            document.getElementById('edit-sub-competition-modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading sub-competition:', error);
            showNotification('Failed to load sub-competition data. Please try again.', 'error');
        });
    };

    // Function to prepare for deleting sub-competition data.
    window.deleteSubCompetition = function(id) {
        document.getElementById('delete_id').value = id;
        document.getElementById('delete-sub-competition-modal').classList.remove('hidden');
    };

    // Function to handle the submission of the add sub-competition form.
    function submitAddSubCompetition() {
        const form = document.getElementById('add-sub-competition-form');
        
        let isValid = true;
        let errorMessages = [];
        
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                const fieldLabel = field.closest('.form-group').querySelector('label');
                const fieldName = fieldLabel ? fieldLabel.textContent.replace('*', '').trim() : 'Field';
                errorMessages.push(`<li>${fieldName} is required</li>`);
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
                
                const errorElement = field.closest('.form-group').querySelector('.error-message');
                if (errorElement) {
                    errorElement.textContent = `${fieldName} is required`;
                    errorElement.classList.remove('hidden');
                }
            }
        });
        
        const startDate = document.getElementById('add-sub-start-date').value;
        const endDate = document.getElementById('add-sub-end-date').value;
        const regStart = document.getElementById('add-sub-registration-start').value;
        const regEnd = document.getElementById('add-sub-registration-end').value;
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errorMessages.push('<li>End Date cannot be earlier than Start Date</li>');
            document.getElementById('add-sub-end-date').classList.add('border-red-500');
            
            const errorElement = document.getElementById('sub-end-date-error');
            if (errorElement) {
                errorElement.textContent = 'End Date cannot be earlier than Start Date';
                errorElement.classList.remove('hidden');
            }
        }
        
        if (regStart && regEnd && new Date(regEnd) < new Date(regStart)) {
            isValid = false;
            errorMessages.push('<li>Registration End Date cannot be earlier than Registration Start Date</li>');
            document.getElementById('add-sub-registration-end').classList.add('border-red-500');
            
            const errorElement = document.getElementById('sub-registration-end-error');
            if (errorElement) {
                errorElement.textContent = 'Registration End Date cannot be earlier than Registration Start Date';
                errorElement.classList.remove('hidden');
            }
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('add-sub-competition-error');
            const errorList = document.getElementById('add-sub-competition-error-list');
            const errorCount = document.getElementById('add-sub-competition-error-count');
            
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = errorMessages.join('');
            errorCount.textContent = errorMessages.length;
            
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        document.getElementById('add-sub-competition-error').classList.add('hidden');
        
        const submitBtn = document.getElementById('submit-add-sub-competition');
        const originalButtonText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;
        
        const formData = new FormData(form);
        
        fetch(window.subCompetitionRoutes.store, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to add sub-competition');
            }
            
            form.reset();
            document.getElementById('add-sub-competition-modal').classList.add('hidden');
            
            showNotification(data.message || 'Sub-Competition added successfully', 'success');
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            console.error('Error adding sub-competition:', error);
            
            if (error.response && error.response.status === 422) {
                const errorData = error.response.data;
                displayValidationErrors(errorData.errors, form);
            } else {
                showNotification(error.message || 'Failed to add sub-competition. Please try again.', 'error');
            }
        });
    }
    
    // Function to handle the submission of the edit sub-competition form.
    function submitEditSubCompetition() {
        const form = document.getElementById('edit-sub-competition-form');
        const id = document.getElementById('edit_id').value;
        
        let isValid = true;
        let errorMessages = [];
        
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                const fieldLabel = field.closest('.form-group').querySelector('label');
                const fieldName = fieldLabel ? fieldLabel.textContent.replace('*', '').trim() : 'Field';
                errorMessages.push(`<li>${fieldName} is required</li>`);
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
                
                const errorElement = field.closest('.form-group').querySelector('.error-message');
                if (errorElement) {
                    errorElement.textContent = `${fieldName} is required`;
                    errorElement.classList.remove('hidden');
                }
            }
        });
        
        const startDate = document.getElementById('edit_start_date').value;
        const endDate = document.getElementById('edit_end_date').value;
        const regStart = document.getElementById('edit_registration_start').value;
        const regEnd = document.getElementById('edit_registration_end').value;
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errorMessages.push('<li>End Date cannot be earlier than Start Date</li>');
            document.getElementById('edit_end_date').classList.add('border-red-500');
            
            const errorElement = document.getElementById('edit-sub-end-date-error');
            if (errorElement) {
                errorElement.textContent = 'End Date cannot be earlier than Start Date';
                errorElement.classList.remove('hidden');
            }
        }
        
        if (regStart && regEnd && new Date(regEnd) < new Date(regStart)) {
            isValid = false;
            errorMessages.push('<li>Registration End Date cannot be earlier than Registration Start Date</li>');
            document.getElementById('edit_registration_end').classList.add('border-red-500');
            
            const errorElement = document.getElementById('edit-sub-registration-end-error');
            if (errorElement) {
                errorElement.textContent = 'Registration End Date cannot be earlier than Registration Start Date';
                errorElement.classList.remove('hidden');
            }
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('edit-sub-competition-error');
            const errorList = document.getElementById('edit-sub-competition-error-list');
            const errorCount = document.getElementById('edit-sub-competition-error-count');
            
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = errorMessages.join('');
            errorCount.textContent = errorMessages.length;
            
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        document.getElementById('edit-sub-competition-error').classList.add('hidden');
        
        const submitBtn = document.getElementById('submit-edit-sub-competition');
        const originalButtonText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        fetch(window.subCompetitionRoutes.update.replace('__id__', id), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to update sub-competition');
            }
            
            form.reset();
            document.getElementById('edit-sub-competition-modal').classList.add('hidden');
            
            showNotification(data.message || 'Sub-Competition updated successfully', 'success');
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            console.error('Error updating sub-competition:', error);
            
            if (error.response && error.response.status === 422) {
                const errorData = error.response.data;
                displayValidationErrors(errorData.errors, form);
            } else {
                showNotification(error.message || 'Failed to update sub-competition. Please try again.', 'error');
            }
        });
    }
    
    // Function to handle the submission of the delete sub-competition form.
    function submitDeleteSubCompetition() {
        const id = document.getElementById('delete_id').value;
        
        const submitBtn = document.getElementById('confirm-delete-sub-competition');
        const originalButtonText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;
        
        fetch(window.subCompetitionRoutes.destroy.replace('__id__', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to delete sub-competition');
            }
            
            document.getElementById('delete-sub-competition-modal').classList.add('hidden');
            
            showNotification(data.message || 'Sub-Competition deleted successfully', 'success');
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            console.error('Error deleting sub-competition:', error);
            showNotification(error.message || 'Failed to delete sub-competition. Please try again.', 'error');
        });
    }
    
    // Function to display validation error messages on the form.
    function displayValidationErrors(errors, form) {
        const errorContainer = form.id === 'add-sub-competition-form' 
            ? document.getElementById('add-sub-competition-error')
            : document.getElementById('edit-sub-competition-error');
            
        const errorList = form.id === 'add-sub-competition-form'
            ? document.getElementById('add-sub-competition-error-list')
            : document.getElementById('edit-sub-competition-error-list');
            
        const errorCount = form.id === 'add-sub-competition-form'
            ? document.getElementById('add-sub-competition-error-count')
            : document.getElementById('edit-sub-competition-error-count');
        
        resetFormErrors(form.id);
        
        let errorMessages = [];
        let count = 0;
        
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                const messages = errors[field];
                count += messages.length;
                
                messages.forEach(message => {
                    errorMessages.push(`<li>${message}</li>`);
                });
                
                const fieldIdPrefix = form.id === 'add-sub-competition-form' ? 'add-sub-' : 'edit_';
                const fieldId = fieldIdPrefix + field.replace('_', '-');
                const inputField = document.getElementById(fieldId);
                
                if (inputField) {
                    inputField.classList.add('border-red-500');
                    inputField.classList.remove('border-gray-300');
                    
                    const errorElementId = form.id === 'add-sub-competition-form' 
                        ? 'sub-' + field.replace('_', '-') + '-error'
                        : 'edit-sub-' + field.replace('_', '-') + '-error';
                    
                    const errorElement = document.getElementById(errorElementId);
                    if (errorElement) {
                        errorElement.textContent = messages[0];
                        errorElement.classList.remove('hidden');
                    }
                }
            }
        }
        
        if (errorList) errorList.innerHTML = errorMessages.join('');
        if (errorCount) errorCount.textContent = count;
        if (errorContainer) {
            errorContainer.classList.remove('hidden');
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    // Function to remove error messages from the form.
    function resetFormErrors(formId) {
        const form = typeof formId === 'string' ? document.getElementById(formId) : formId;
        
        if (!form) return;
        
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
        
        const errorElements = form.closest('.fixed')?.querySelectorAll('.error-message') || [];
        errorElements.forEach(error => {
            error.textContent = '';
            error.classList.add('hidden');
        });
        
        const errorContainer = formId === 'add-sub-competition-form' 
            ? document.getElementById('add-sub-competition-error')
            : document.getElementById('edit-sub-competition-error');
            
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
    }
    
    // Function to display notification messages to the user.
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
                        <span class="sr-only">Close</span>
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
        
        // Function to close the currently displayed notification.
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