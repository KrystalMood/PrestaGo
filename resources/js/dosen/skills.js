document.addEventListener('DOMContentLoaded', function() {
    initSkills();
});

// Initialize all skill-related functions
function initSkills() {
    setupSkillsTable();
    setupAddSkillModal();
    setupEditSkillModal();
    setupDeleteSkillModal();
}

// Setup skill table interactions
function setupSkillsTable() {
    document.querySelectorAll('.edit-skill-btn').forEach(button => {
        button.addEventListener('click', function() {
            const skillPivotId = this.getAttribute('data-pivot-id');
            const skillName = this.getAttribute('data-name');
            const skillCategory = this.getAttribute('data-category');
            const importanceLevel = this.getAttribute('data-importance');
            const weightValue = this.getAttribute('data-weight');
            const criterionType = this.getAttribute('data-criterion');
            
            openEditSkillModal(skillPivotId, skillName, skillCategory, importanceLevel, weightValue, criterionType);
        });
    });

    document.querySelectorAll('.delete-skill-btn').forEach(button => {
        button.addEventListener('click', function() {
            const skillPivotId = this.getAttribute('data-pivot-id');
            const skillName = this.getAttribute('data-name');
            
            openDeleteSkillModal(skillPivotId, skillName);
        });
    });
}

// Setup modal for adding skills
function setupAddSkillModal() {
    const modal = document.getElementById('add-skill-modal');
    const openBtn = document.getElementById('open-add-skill-modal');
    const closeBtn = document.getElementById('close-add-skill-modal');
    const form = document.getElementById('add-skill-form');
    const submitBtn = document.getElementById('add-skill-submit');
    const errorAlert = document.getElementById('add-skill-errors');
    const errorList = document.getElementById('add-skill-error-list');
    const loadingState = document.getElementById('add-skill-loading') || document.getElementById('loading-state');
    const normalState = document.getElementById('add-skill-normal') || document.getElementById('normal-state');
    const cancelBtn = document.getElementById('cancel-add-skill');
    
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.modal-content').classList.remove('opacity-0', 'translate-y-4');
                modal.querySelector('.modal-content').classList.add('opacity-100', 'translate-y-0');
                modal.querySelector('.modal-backdrop').classList.remove('opacity-0');
                modal.querySelector('.modal-backdrop').classList.add('opacity-75');
            }, 10);
        });
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeAddSkillModal);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeAddSkillModal);
    }
    
    if (modal) {
        modal.querySelector('.modal-backdrop').addEventListener('click', closeAddSkillModal);
    }
    
    function closeAddSkillModal() {
        modal.querySelector('.modal-content').classList.remove('opacity-100', 'translate-y-0');
        modal.querySelector('.modal-content').classList.add('opacity-0', 'translate-y-4');
        modal.querySelector('.modal-backdrop').classList.remove('opacity-75');
        modal.querySelector('.modal-backdrop').classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            form.reset();
            errorAlert.classList.add('hidden');
            errorList.innerHTML = '';
        }, 300);
    }
    
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            normalState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            
            errorAlert.classList.add('hidden');
            
            const formData = new FormData(form);
            
            try {
                const response = await axios.post(window.skillRoutes.store, formData, {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.data.success) {
                    showNotification('success', response.data.message);
                    
                    if (response.data.html) {
                        const tableContainer = document.querySelector('#skillsTable').parentNode;
                        tableContainer.innerHTML = response.data.html;
                    }
                    
                    closeAddSkillModal();
                }
            } catch (error) {
                errorAlert.classList.remove('hidden');
                
                errorList.innerHTML = '';
                
                if (error.response && error.response.data && error.response.data.errors) {
                    Object.keys(error.response.data.errors).forEach(key => {
                        const li = document.createElement('li');
                        li.textContent = error.response.data.errors[key][0];
                        errorList.appendChild(li);
                    });
                } else if (error.response && error.response.data && error.response.data.message) {
                    const li = document.createElement('li');
                    li.textContent = error.response.data.message;
                    errorList.appendChild(li);
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'Terjadi kesalahan saat menambahkan skill.';
                    errorList.appendChild(li);
                }
            } finally {
                loadingState.classList.add('hidden');
                normalState.classList.remove('hidden');
            }
        });
    }
}

// Setup modal for editing skills
function setupEditSkillModal() {
    const modal = document.getElementById('edit-skill-modal');
    const closeBtn = document.getElementById('close-edit-skill-modal');
    const form = document.getElementById('edit-skill-form');
    const submitBtn = document.getElementById('edit-skill-submit');
    const errorAlert = document.getElementById('edit-skill-errors');
    const errorList = document.getElementById('edit-skill-error-list');
    const loadingState = document.getElementById('edit-skill-loading') || document.getElementById('loading-state');
    const normalState = document.getElementById('edit-skill-normal') || document.getElementById('normal-state');
    const skillIdInput = document.getElementById('edit_skill_id');
    const skillPivotIdInput = document.getElementById('edit_skill_pivot_id');
    const skillNameDisplay = document.getElementById('edit_skill_name');
    const cancelBtn = document.getElementById('cancel-edit-skill');
    
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.edit-skill-btn')) {
            const btn = e.target.closest('.edit-skill-btn');
            const skillId = btn.dataset.pivotId || btn.dataset.skillId;
            const skillName = btn.dataset.name;
            const skillCategory = btn.dataset.category;
            const importanceLevel = btn.dataset.importance || btn.dataset.importanceLevel;
            const weightValue = btn.dataset.weight || btn.dataset.weightValue;
            const criterionType = btn.dataset.criterion || btn.dataset.criterionType;
            
            skillIdInput.value = skillId;
            skillNameDisplay.textContent = skillName;
            document.getElementById('edit_importance_level').value = importanceLevel;
            document.getElementById('edit_weight_value').value = weightValue;
            document.getElementById('edit_criterion_type').value = criterionType;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.modal-content').classList.remove('opacity-0', 'translate-y-4');
                modal.querySelector('.modal-content').classList.add('opacity-100', 'translate-y-0');
                modal.querySelector('.modal-backdrop').classList.remove('opacity-0');
                modal.querySelector('.modal-backdrop').classList.add('opacity-75');
            }, 10);
        }
    });
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', closeEditSkillModal);
    }
    
    // Close with cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeEditSkillModal);
    }
    
    // Close modal when clicking outside
    if (modal) {
        modal.querySelector('.modal-backdrop').addEventListener('click', closeEditSkillModal);
    }
    
    function closeEditSkillModal() {
        modal.querySelector('.modal-content').classList.remove('opacity-100', 'translate-y-0');
        modal.querySelector('.modal-content').classList.add('opacity-0', 'translate-y-4');
        modal.querySelector('.modal-backdrop').classList.remove('opacity-75');
        modal.querySelector('.modal-backdrop').classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            form.reset();
            errorAlert.classList.add('hidden');
            errorList.innerHTML = '';
        }, 300);
    }
    
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            normalState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            
            errorAlert.classList.add('hidden');
            
            const formData = new FormData(form);
            const skillId = skillIdInput.value;
            
            try {
                const updateUrl = window.skillRoutes.update.replace('__id__', skillId);
                const response = await axios.put(updateUrl, formData, {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.data.success) {
                    showNotification('success', response.data.message);
                    
                    if (response.data.html) {
                        const tableContainer = document.querySelector('#skillsTable').parentNode;
                        tableContainer.innerHTML = response.data.html;
                    }
                    
                    closeEditSkillModal();
                }
            } catch (error) {
                errorAlert.classList.remove('hidden');
                
                errorList.innerHTML = '';
                
                if (error.response && error.response.data && error.response.data.errors) {
                    Object.keys(error.response.data.errors).forEach(key => {
                        const li = document.createElement('li');
                        li.textContent = error.response.data.errors[key][0];
                        errorList.appendChild(li);
                    });
                } else if (error.response && error.response.data && error.response.data.message) {
                    const li = document.createElement('li');
                    li.textContent = error.response.data.message;
                    errorList.appendChild(li);
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'Terjadi kesalahan saat memperbarui skill.';
                    errorList.appendChild(li);
                }
            } finally {
                loadingState.classList.add('hidden');
                normalState.classList.remove('hidden');
            }
        });
    }
}

// Open edit skill modal with specific skill data
function openEditSkillModal(skillPivotId, skillName, skillCategory, importanceLevel, weightValue, criterionType) {
    const editModal = document.getElementById('edit-skill-modal');
    const errorsContainer = document.getElementById('edit-skill-errors');
    const errorsList = document.getElementById('edit-skill-error-list');
    
    document.getElementById('edit_skill_pivot_id').value = skillPivotId;
    document.getElementById('edit_skill_id').value = skillPivotId;
    document.getElementById('edit_skill_name').textContent = skillName;
    document.getElementById('edit_skill_category').textContent = skillCategory;
    document.getElementById('edit_importance_level').value = importanceLevel;
    document.getElementById('edit_weight_value').value = weightValue;
    document.getElementById('edit_criterion_type').value = criterionType;
    
    errorsContainer.classList.add('hidden');
    errorsList.innerHTML = '';
    
    editModal.classList.remove('hidden');
}

// Setup modal for deleting skills
function setupDeleteSkillModal() {
    const modal = document.getElementById('delete-skill-modal');
    const closeBtn = document.getElementById('close-delete-skill-modal');
    const form = document.getElementById('delete-skill-form');
    const submitBtn = document.getElementById('delete-skill-submit');
    const errorAlert = document.getElementById('delete-skill-errors');
    const errorList = document.getElementById('delete-skill-error-list');
    const loadingState = document.getElementById('delete-skill-loading') || document.getElementById('loading-state');
    const normalState = document.getElementById('delete-skill-normal') || document.getElementById('normal-state');
    const skillIdInput = document.getElementById('delete_skill_id');
    const skillPivotIdInput = document.getElementById('delete_skill_pivot_id');
    const skillNameDisplay = document.getElementById('delete_skill_name');
    const confirmBtn = document.getElementById('confirm-delete-skill');
    const cancelBtn = document.getElementById('cancel-delete-skill');
    
    // Setup event delegation for delete buttons
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.delete-skill-btn')) {
            const btn = e.target.closest('.delete-skill-btn');
            const skillId = btn.dataset.pivotId || btn.dataset.skillId;
            const skillName = btn.dataset.name;
            
            // Set form values
            skillIdInput.value = skillId;
            skillNameDisplay.textContent = skillName;
            
            // Open modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.modal-content').classList.remove('opacity-0', 'translate-y-4');
                modal.querySelector('.modal-content').classList.add('opacity-100', 'translate-y-0');
                modal.querySelector('.modal-backdrop').classList.remove('opacity-0');
                modal.querySelector('.modal-backdrop').classList.add('opacity-75');
            }, 10);
        }
    });
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDeleteSkillModal);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeDeleteSkillModal);
    }
    
    if (modal) {
        modal.querySelector('.modal-backdrop').addEventListener('click', closeDeleteSkillModal);
    }
    
    function closeDeleteSkillModal() {
        modal.querySelector('.modal-content').classList.remove('opacity-100', 'translate-y-0');
        modal.querySelector('.modal-content').classList.add('opacity-0', 'translate-y-4');
        modal.querySelector('.modal-backdrop').classList.remove('opacity-75');
        modal.querySelector('.modal-backdrop').classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            form.reset();
            errorAlert.classList.add('hidden');
            errorList.innerHTML = '';
        }, 300);
    }
    
    // Handle delete confirmation
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            form.dispatchEvent(new Event('submit'));
        });
    }
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            normalState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            
            errorAlert.classList.add('hidden');
            
            const skillId = skillIdInput.value;
            
            try {
                const deleteUrl = window.skillRoutes.destroy.replace('__id__', skillId);
                const response = await axios.delete(deleteUrl, {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.data.success) {
                    showNotification('success', response.data.message);
                    
                    if (response.data.html) {
                        const tableContainer = document.querySelector('#skillsTable').parentNode;
                        tableContainer.innerHTML = response.data.html;
                    }
                    
                    closeDeleteSkillModal();
                }
            } catch (error) {
                errorAlert.classList.remove('hidden');
                
                errorList.innerHTML = '';
                
                if (error.response && error.response.data && error.response.data.message) {
                    const li = document.createElement('li');
                    li.textContent = error.response.data.message;
                    errorList.appendChild(li);
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'Terjadi kesalahan saat menghapus skill.';
                    errorList.appendChild(li);
                }
            } finally {
                // Hide loading state
                loadingState.classList.add('hidden');
                normalState.classList.remove('hidden');
            }
        });
    }
}

/**
 * Open the delete skill modal
 */
function openDeleteSkillModal(skillPivotId, skillName) {
    const deleteModal = document.getElementById('delete-skill-modal');
    const errorsContainer = document.getElementById('delete-skill-errors');
    const errorsList = document.getElementById('delete-skill-error-list');
    
    document.getElementById('delete_skill_pivot_id').value = skillPivotId;
    document.getElementById('delete_skill_id').value = skillPivotId;
    document.getElementById('delete_skill_name').textContent = skillName;
    
    errorsContainer.classList.add('hidden');
    errorsList.innerHTML = '';
    
    deleteModal.classList.remove('hidden');
}

/**
 * Display form validation errors
 */
function showFormErrors(errors, container, list) {
    list.innerHTML = '';
    
    Object.keys(errors).forEach(field => {
        errors[field].forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            list.appendChild(li);
        });
    });
    
    container.classList.remove('hidden');
}

/**
 * Show a notification toast
 */
function showNotification(type, message) {
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 right-4 z-50 flex flex-col space-y-4';
        document.body.appendChild(notificationContainer);
    }
    
    const notification = document.createElement('div');
    notification.className = `notification transform transition-all duration-300 ease-in-out translate-x-full opacity-0 flex items-center p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500'
    }`;
    
    const icon = document.createElement('div');
    icon.className = 'flex-shrink-0 mr-3';
    icon.innerHTML = type === 'success' 
        ? `<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>`
        : `<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>`;
    
    const messageElement = document.createElement('div');
    messageElement.className = `text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}`;
    messageElement.textContent = message;
    
    const closeButton = document.createElement('button');
    closeButton.className = `ml-auto pl-3 ${type === 'success' ? 'text-green-600' : 'text-red-600'} hover:opacity-75 transition-opacity duration-200`;
    closeButton.innerHTML = `<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>`;
    closeButton.addEventListener('click', () => {
        closeNotification(notification);
    });
    
    notification.appendChild(icon);
    notification.appendChild(messageElement);
    notification.appendChild(closeButton);
    
    notificationContainer.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    setTimeout(() => {
        closeNotification(notification);
    }, 5000);
    
    function closeNotification(notification) {
        notification.classList.remove('translate-x-0', 'opacity-100');
        notification.classList.add('translate-x-full', 'opacity-0');
        
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
} 