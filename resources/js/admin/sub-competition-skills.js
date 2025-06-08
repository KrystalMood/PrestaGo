document.addEventListener('DOMContentLoaded', function() {
    const skillRoutes = window.skillRoutes || {};
    const csrfToken = window.csrfToken || '';

    setupSkillModals();
    setupEventListeners();

    /**
     * Sets up the modal dialogs for adding, editing, and deleting skills.
     */
    function setupSkillModals() {
        window.addSkillModal = document.getElementById('add-skill-modal');
        window.editSkillModal = document.getElementById('edit-skill-modal');
        window.deleteSkillModal = document.getElementById('delete-skill-modal');

        const openAddSkillBtn = document.getElementById('open-add-skill-modal');
        const closeAddSkillBtn = document.getElementById('close-add-skill-modal');
        const cancelAddSkillBtn = document.getElementById('cancel-add-skill');

        if (openAddSkillBtn) {
            openAddSkillBtn.addEventListener('click', function() {
                if (window.addSkillModal) {
                    openModal(window.addSkillModal);
                    document.getElementById('add-skill-skeleton').classList.remove('hidden');
                    document.getElementById('add-skill-form').classList.add('hidden');
                    loadAvailableSkills();
                    resetForm('add-skill-form');
                }
            });
        }

        if (closeAddSkillBtn) {
            closeAddSkillBtn.addEventListener('click', function() {
                if (window.addSkillModal) {
                    closeModal(window.addSkillModal);
                    resetForm('add-skill-form');
                }
            });
        }

        if (cancelAddSkillBtn) {
            cancelAddSkillBtn.addEventListener('click', function() {
                if (window.addSkillModal) {
                    closeModal(window.addSkillModal);
                    resetForm('add-skill-form');
                }
            });
        }

        if (window.addSkillModal) {
            window.addSkillModal.addEventListener('click', function(e) {
                if (e.target === window.addSkillModal) {
                    closeModal(window.addSkillModal);
                    resetForm('add-skill-form');
                }
            });
        }

        const closeEditSkillBtn = document.getElementById('close-edit-skill-modal');
        const cancelEditSkillBtn = document.getElementById('cancel-edit-skill');

        if (closeEditSkillBtn) {
            closeEditSkillBtn.addEventListener('click', function() {
                if (window.editSkillModal) {
                    closeModal(window.editSkillModal);
                    resetForm('edit-skill-form');
                }
            });
        }

        if (cancelEditSkillBtn) {
            cancelEditSkillBtn.addEventListener('click', function() {
                if (window.editSkillModal) {
                    closeModal(window.editSkillModal);
                    resetForm('edit-skill-form');
                }
            });
        }

        if (window.editSkillModal) {
            window.editSkillModal.addEventListener('click', function(e) {
                if (e.target === window.editSkillModal) {
                    closeModal(window.editSkillModal);
                    resetForm('edit-skill-form');
                }
            });
        }

        const closeDeleteSkillBtn = document.getElementById('close-delete-skill-modal');
        const cancelDeleteSkillBtn = document.getElementById('cancel-delete-skill');

        if (closeDeleteSkillBtn) {
            closeDeleteSkillBtn.addEventListener('click', function() {
                if (window.deleteSkillModal) {
                    closeModal(window.deleteSkillModal);
                }
            });
        }

        if (cancelDeleteSkillBtn) {
            cancelDeleteSkillBtn.addEventListener('click', function() {
                if (window.deleteSkillModal) {
                    closeModal(window.deleteSkillModal);
                }
            });
        }

        if (window.deleteSkillModal) {
            window.deleteSkillModal.addEventListener('click', function(e) {
                if (e.target === window.deleteSkillModal) {
                    closeModal(window.deleteSkillModal);
                }
            });
        }
    }

    /**
     * Sets up event listeners for various UI elements related to skill management.
     */
    function setupEventListeners() {
        const addSkillForm = document.getElementById('add-skill-form');
        if (addSkillForm) {
            addSkillForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddSkill();
            });
        }

        const editSkillForm = document.getElementById('edit-skill-form');
        if (editSkillForm) {
            editSkillForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditSkill();
            });
        }

        const deleteSkillForm = document.getElementById('delete-skill-form');
        if (deleteSkillForm) {
            deleteSkillForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitDeleteSkill();
            });
        }

        const editButtons = document.querySelectorAll('.edit-skill-btn');
        if (editButtons.length > 0) {
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const skillId = this.getAttribute('data-skill-id');
                    const skillName = this.getAttribute('data-skill-name');
                    const importanceLevel = this.getAttribute('data-importance-level');
                    const weightValue = this.getAttribute('data-weight-value');
                    const criterionType = this.getAttribute('data-criterion-type');

                    openModal(window.editSkillModal);
                    document.getElementById('edit-skill-skeleton').classList.remove('hidden');
                    document.getElementById('edit-skill-form').classList.add('hidden');
                    
                    setTimeout(() => {
                        document.getElementById('edit_skill_id').value = skillId;
                        document.getElementById('edit_skill_name').value = skillName;
                        document.getElementById('edit_importance_level').value = importanceLevel;
                        document.getElementById('edit_weight_value').value = weightValue;
                        document.getElementById('edit_criterion_type').value = criterionType;
                        
                        document.getElementById('edit-skill-skeleton').classList.add('hidden');
                        document.getElementById('edit-skill-form').classList.remove('hidden');
                    }, 800);
                });
            });
        }

        const deleteButtons = document.querySelectorAll('.delete-skill-btn');
        if (deleteButtons.length > 0) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const skillId = this.getAttribute('data-skill-id');
                    const skillName = this.getAttribute('data-skill-name');

                    document.getElementById('delete_skill_id').value = skillId;
                    document.getElementById('delete_skill_name').textContent = skillName;

                    openModal(window.deleteSkillModal);
                });
            });
        }
    }

    /**
     * Handles the submission of the add skill form.
     */
    function submitAddSkill() {
        const form = document.getElementById('add-skill-form');
        const skillId = document.getElementById('skill_id').value;
        const importanceLevel = document.getElementById('importance_level').value;
        const submitBtn = document.getElementById('submit-add-skill');
        
        if (!skillId) {
            displayFormErrors('add-skill-form', { skill_id: ['Silahkan pilih skill terlebih dahulu'] });
            return;
        }
        
        if (!importanceLevel || importanceLevel < 1 || importanceLevel > 10) {
            displayFormErrors('add-skill-form', { importance_level: ['Masukkan tingkat kepentingan yang valid (1-10)'] });
            return;
        }
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menambahkan...
            `;
        }
        
        const formData = new FormData(form);
        
        fetch(skillRoutes.store, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Skill berhasil ditambahkan', 'success');
                closeModal(window.addSkillModal);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Tambah Skill';
                }
                showNotification(data.message || 'Gagal menambahkan skill', 'error');
                if (data.errors) {
                    displayFormErrors('add-skill-form', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error adding skill:', error);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Tambah Skill';
            }
            showNotification('Terjadi kesalahan. Silahkan coba lagi.', 'error');
        });
    }

    /**
     * Handles the submission of the edit skill form.
     */
    function submitEditSkill() {
        const form = document.getElementById('edit-skill-form');
        const skillId = document.getElementById('edit_skill_id').value;
        const submitBtn = document.getElementById('submit-edit-skill');
        
        if (!skillId) {
            showNotification('ID skill tidak valid', 'error');
            return;
        }
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        }
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        let url = skillRoutes.update.replace('__id__', skillId);
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Skill berhasil diperbarui', 'success');
                closeModal(window.editSkillModal);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Update Skill';
                }
                showNotification(data.message || 'Gagal memperbarui skill', 'error');
                if (data.errors) {
                    displayFormErrors('edit-skill-form', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error updating skill:', error);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Skill';
            }
            showNotification('Terjadi kesalahan. Silahkan coba lagi.', 'error');
        });
    }

    /**
     * Handles the submission of the delete skill form.
     */
    function submitDeleteSkill() {
        const skillId = document.getElementById('delete_skill_id').value;
        const submitBtn = document.getElementById('submit-delete-skill');
        
        if (!skillId) {
            showNotification('ID skill tidak valid', 'error');
            return;
        }
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menghapus...
            `;
        }
        
        let url = skillRoutes.destroy.replace('__id__', skillId);
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', csrfToken);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Skill berhasil dihapus', 'success');
                closeModal(window.deleteSkillModal);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Hapus';
                }
                showNotification(data.message || 'Gagal menghapus skill', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting skill:', error);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Hapus';
            }
            showNotification('Terjadi kesalahan. Silahkan coba lagi.', 'error');
        });
    }

    /**
     * Loads available skills from the server to populate a select dropdown.
     */
    function loadAvailableSkills() {
        const skillSelect = document.getElementById('skill_id');
        const availableSkillsCount = document.getElementById('availableSkillsCount');

        if (!skillSelect || !skillRoutes.available) {
            return;
        }

        skillSelect.innerHTML = '<option value="">Memuat skills...</option>';
        if (availableSkillsCount) {
            availableSkillsCount.textContent = 'Memuat data...';
        }

        fetch(skillRoutes.available, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            skillSelect.innerHTML = '<option value="">Pilih Skill</option>';

            if (data.success) {
                const skills = data.skills || data.data || [];
                
                if (skills.length > 0) {
                    const hasCategories = skills.some(skill => skill.category);
                    
                    if (hasCategories) {
                        const categories = {};
                        
                        skills.forEach(skill => {
                            const category = skill.category || 'Lainnya';
                            if (!categories[category]) {
                                categories[category] = [];
                            }
                            categories[category].push(skill);
                        });
                        
                        Object.keys(categories).sort().forEach(category => {
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = category;
                            
                            categories[category].sort((a, b) => a.name.localeCompare(b.name)).forEach(skill => {
                                const option = document.createElement('option');
                                option.value = skill.id;
                                option.textContent = skill.name;
                                optgroup.appendChild(option);
                            });
                            
                            skillSelect.appendChild(optgroup);
                        });
                    } else {
                        skills.sort((a, b) => a.name.localeCompare(b.name)).forEach(skill => {
                            const option = document.createElement('option');
                            option.value = skill.id;
                            option.textContent = skill.name;
                            skillSelect.appendChild(option);
                        });
                    }

                    if (availableSkillsCount) {
                        availableSkillsCount.textContent = `${skills.length} skill tersedia untuk dipilih`;
                    }
                } else {
                    if (availableSkillsCount) {
                        availableSkillsCount.textContent = 'Tidak ada skill tersedia';
                    }
                }
            } else {
                console.error('Failed to load available skills');
                if (availableSkillsCount) {
                    availableSkillsCount.textContent = 'Gagal memuat data skill';
                }
            }
            
            document.getElementById('add-skill-skeleton').classList.add('hidden');
            document.getElementById('add-skill-form').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading available skills:', error);
            skillSelect.innerHTML = '<option value="">Error: Gagal memuat skills</option>';
            document.getElementById('add-skill-skeleton').classList.add('hidden');
            document.getElementById('add-skill-form').classList.remove('hidden');
            
            if (availableSkillsCount) {
                availableSkillsCount.textContent = 'Gagal memuat data skill';
            }
        });
    }

    /**
     * Utility function to open a modal dialog.
     * @param {HTMLElement} modal - The modal element to open.
     */
    function openModal(modal) {
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    /**
     * Utility function to close a modal dialog.
     * @param {HTMLElement} modal - The modal element to close.
     */
    function closeModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    /**
     * Resets a form and clears its error messages.
     * @param {string|HTMLElement} formId - The ID of the form or the form element itself.
     */
    function resetForm(formId) {
        const form = typeof formId === 'string' ? document.getElementById(formId) : formId;
        if (form) {
            form.reset();
            const errorContainer = form.querySelector(`#${formId.replace('form', 'errors')}`);
            if (errorContainer) {
                errorContainer.classList.add('hidden');
            }
        }
    }

    /**
     * Displays validation errors on a form.
     * @param {string} formId - The ID of the form.
     * @param {object} errors - An object containing field names as keys and arrays of error messages as values.
     */
    function displayFormErrors(formId, errors) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const errorContainer = document.getElementById(`${formId.replace('-form', '-errors')}`);
        const errorList = document.getElementById(`${formId.replace('-form', '-error-list')}`);
        
        if (!errorContainer || !errorList) return;
        
        errorList.innerHTML = '';
        let hasErrors = false;
        
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                const messages = errors[field];
                messages.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorList.appendChild(li);
                    hasErrors = true;
                });
                
                const inputField = form.querySelector(`[name="${field}"]`);
                if (inputField) {
                    inputField.classList.add('border-red-500');
                    inputField.classList.add('focus:border-red-500');
                    inputField.classList.add('focus:ring-red-500');
                    
                    inputField.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                        this.classList.remove('focus:border-red-500');
                        this.classList.remove('focus:ring-red-500');
                    }, { once: true });
                }
            }
        }
        
        if (hasErrors) {
            errorContainer.classList.remove('hidden');
        } else {
            errorContainer.classList.add('hidden');
        }
    }

    /**
     * Displays a notification message to the user.
     * @param {string} message - The message to display.
     * @param {string} [type='success'] - The type of notification ('success', 'error', or other for info).
     */
    function showNotification(message, type = 'success') {
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => {
            notification.remove();
        });
        
        const notification = document.createElement('div');
        notification.className = `notification fixed right-5 top-5 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ease-in-out translate-x-0 opacity-100 ${
            type === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-400' : 
            type === 'error' ? 'bg-red-50 text-red-800 border-l-4 border-red-400' : 
            'bg-blue-50 text-blue-800 border-l-4 border-blue-400'
        }`;
        
        notification.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    ${type === 'success' ? 
                        `<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>` : 
                        type === 'error' ? 
                        `<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>` : 
                        `<svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>`
                    }
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">
                        ${message}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button class="notification-close inline-flex bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-50 rounded-md p-1.5 text-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-500 hover:bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-500">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        notification.querySelector('.notification-close').addEventListener('click', function() {
            closeNotification(notification);
        });
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
    }

    /**
     * Closes a notification message.
     * @param {HTMLElement} notification - The notification element to close.
     */
    function closeNotification(notification) {
        notification.classList.remove('translate-x-0', 'opacity-100');
        notification.classList.add('translate-x-full', 'opacity-0');
        
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
});