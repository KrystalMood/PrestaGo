document.addEventListener('DOMContentLoaded', function() {
    const userRoutes = window.userRoutes || {};
    const csrfToken = window.csrfToken || '';
    const defaultAvatarUrl = window.defaultAvatarUrl || '';
    
    // Initializes and sets up event listeners for user modals (add, edit, show).
    function setupUserModals() {
        window.addUserModal = document.getElementById('add-user-modal');
        window.editUserModal = document.getElementById('edit-user-modal');
        window.showUserModal = document.getElementById('show-user-modal');
        
        const addUserBtn = document.getElementById('open-add-user-modal');
        if (addUserBtn) {
            addUserBtn.addEventListener('click', function() {
                if (window.addUserModal) {
                    window.addUserModal.classList.remove('hidden');
                    resetFormErrors('add-user-form');
                    resetMultiStepForm();
                }
            });
        }
        
        const closeAddModalBtn = document.getElementById('close-add-modal');
        const cancelAddUserBtn = document.getElementById('cancel-add-user');
        [closeAddModalBtn, cancelAddUserBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.addUserModal) {
                        window.addUserModal.classList.add('hidden');
                        document.getElementById('add-user-form').reset();
                        resetFormErrors('add-user-form');
                        resetMultiStepForm();
                    }
                });
            }
        });
        
        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditUserBtn = document.getElementById('cancel-edit-user');
        [closeEditModalBtn, cancelEditUserBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.editUserModal) {
                        window.editUserModal.classList.add('hidden');
                        resetFormErrors('edit-user-form');
                    }
                });
            }
        });
        
        const showUserModalElement = document.getElementById('show-user-modal');
        const closeShowModalButton = document.getElementById('close-show-modal');
        const closeShowUserButton = document.getElementById('close-show-user');
        const editFromShowButton = document.getElementById('edit-from-show');
        
        [closeShowModalButton, closeShowUserButton].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (showUserModalElement) {
                        showUserModalElement.classList.add('hidden');
                    }
                });
            }
        });
        
        if (editFromShowButton) {
            editFromShowButton.addEventListener('click', function() {
                const userId = editFromShowButton.getAttribute('data-user-id');
                if (showUserModalElement) {
                    showUserModalElement.classList.add('hidden');
                }
                loadUserForEdit(userId);
            });
        }
        
        setupAddUserForm();
        setupRoleFieldsVisibility();
        attachEditButtonListeners();
        attachShowButtonListeners();
    }
    
    // Resets the multi-step form to its initial (first) step.
    function resetMultiStepForm() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-user');
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
            
            const meter = document.getElementById('password-strength-meter');
            const label = document.getElementById('password-strength-label');
            
            if (meter) meter.style.width = '0%';
            if (label) label.textContent = 'Belum Diisi'; // This is UI text, should be handled by i18n if needed, not a comment.
            
            const photoPreview = document.getElementById('photo-preview');
            if (photoPreview) photoPreview.src = defaultAvatarUrl;
            
            ['req-length', 'req-number', 'req-uppercase', 'req-special'].forEach(id => {
                updateRequirement(id, false);
            });
        }
    }
    
    // Sets up the add user form, including multi-step validation and AJAX submission.
    function setupAddUserForm() {
        const addForm = document.getElementById('add-user-form');
        if (!addForm) return;
        
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const passwordField = document.getElementById('add-password');
            const password = passwordField.value;
            const passwordConfirmField = document.getElementById('add-password-confirmation');
            const passwordConfirm = passwordConfirmField.value;
            
            let isValid = true;
            let errorMessage = '';
            
            const passwordError = document.getElementById('password-error');
            const confirmError = document.getElementById('password-confirmation-error');
            
            if (passwordError) passwordError.classList.add('hidden');
            if (confirmError) confirmError.classList.add('hidden');
            
            passwordField.classList.remove('border-red-500');
            passwordField.classList.add('border-gray-300');
            
            passwordConfirmField.classList.remove('border-red-500');
            passwordConfirmField.classList.add('border-gray-300');
            
            if (!password) {
                isValid = false;
                errorMessage += '<li>Kata Sandi wajib diisi</li>';
                if (passwordError) {
                    passwordError.textContent = 'Kata Sandi wajib diisi';
                    passwordError.classList.remove('hidden');
                }
                passwordField.classList.add('border-red-500');
                passwordField.classList.remove('border-gray-300');
            } else if (password.length < 6) {
                isValid = false;
                errorMessage += '<li>Kata Sandi minimal 6 karakter</li>';
                if (passwordError) {
                    passwordError.textContent = 'Kata Sandi minimal 6 karakter';
                    passwordError.classList.remove('hidden');
                }
                passwordField.classList.add('border-red-500');
                passwordField.classList.remove('border-gray-300');
            }
            
            if (password !== passwordConfirm) {
                isValid = false;
                errorMessage += '<li>Konfirmasi Kata Sandi tidak cocok</li>';
                if (confirmError) {
                    confirmError.textContent = 'Konfirmasi Kata Sandi tidak cocok';
                    confirmError.classList.remove('hidden');
                }
                passwordConfirmField.classList.add('border-red-500');
                passwordConfirmField.classList.remove('border-gray-300');
            }
            
            if (!isValid) {
                const errorContainer = document.getElementById('add-user-error');
                const errorList = document.getElementById('add-user-error-list');
                const errorCount = document.getElementById('add-user-error-count');
                
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessage;
                errorCount.textContent = errorMessage.split('<li>').length - 1;
                
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            document.getElementById('add-user-error')?.classList.add('hidden');
            
            const submitButton = document.getElementById('submit-add-user');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
            
            const formData = new FormData(addForm);
            
            fetch(userRoutes.store, {
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
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to add user');
                }
                
                addForm.reset();
                resetMultiStepForm();
                window.addUserModal.classList.add('hidden');
                
                showNotification(data.message || 'Pengguna berhasil ditambahkan', 'success');
                
                refreshUsersTable();
            })
            .catch(error => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                
                console.error('Error adding user:', error);
                
                if (error.response && error.response.status === 422) {
                    const errorData = error.response.data;
                    displayErrors(errorData.errors, addForm, 'add-user-error', 'add-user-error-list');
                } else {
                    showNotification(error.message || 'Gagal menambahkan pengguna. Silakan coba lagi.', 'error');
                }
            });
        });
        
        const togglePasswordBtn = document.getElementById('toggle-password');
        if (togglePasswordBtn) {
            togglePasswordBtn.addEventListener('click', function() {
                const passwordField = document.getElementById('add-password');
                const fieldType = passwordField.getAttribute('type');
                passwordField.setAttribute('type', fieldType === 'password' ? 'text' : 'password');
                
                const svg = this.querySelector('svg');
                if (fieldType === 'password') {
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                } else {
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                }
            });
        }
        
        const passwordField = document.getElementById('add-password');
        if (passwordField) {
            passwordField.addEventListener('input', validatePassword);
        }
        
        const confirmField = document.getElementById('add-password-confirmation');
        if (confirmField) {
            confirmField.addEventListener('input', function() {
                const password = document.getElementById('add-password').value;
                const confirmError = document.getElementById('password-confirmation-error');
                
                if (this.value && confirmError) {
                    if (this.value !== password) {
                        confirmError.textContent = 'Konfirmasi kata sandi tidak cocok';
                        confirmError.classList.remove('hidden');
                        this.classList.add('border-red-500');
                    } else {
                        confirmError.textContent = '';
                        confirmError.classList.add('hidden');
                        this.classList.remove('border-red-500');
                    }
                }
            });
        }
        
        const photoInput = document.getElementById('add-photo');
        if (photoInput) {
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('photo-preview').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                    
                    const fileSize = this.files[0].size / 1024 / 1024; // in MB
                    if (fileSize > 2) {
                        showNotification('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                        this.value = '';
                        document.getElementById('photo-preview').src = defaultAvatarUrl;
                    }
                }
            });
        }
        
        setupMultiStepFormNavigation();
    }
    
    // Sets up navigation (next/previous buttons) for the multi-step add user form.
    function setupMultiStepFormNavigation() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-user');
        
        if (!step1 || !step2 || !nextBtn || !prevBtn || !submitBtn) return;
        
        const stepItems = document.querySelectorAll('.step-item');
        
        nextBtn.addEventListener('click', function() {
            const name = document.getElementById('add-name');
            const email = document.getElementById('add-email');
            const role = document.getElementById('add-level-id');
            
            let isValid = true;
            let errorMessage = '';
            
            resetFieldErrors();
            
            if (!name.value.trim()) {
                isValid = false;
                errorMessage += '<li>Nama Lengkap wajib diisi</li>';
                showFieldError(name, 'Nama Lengkap wajib diisi');
            }
            
            if (!email.value.trim()) {
                isValid = false;
                errorMessage += '<li>Email wajib diisi</li>';
                showFieldError(email, 'Email wajib diisi');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                isValid = false;
                errorMessage += '<li>Format email tidak valid</li>';
                showFieldError(email, 'Format email tidak valid');
            }
            
            if (!role.value) {
                isValid = false;
                errorMessage += '<li>Peran wajib dipilih</li>';
                showFieldError(role, 'Peran wajib dipilih');
            }
            
            if (!isValid) {
                const errorContainer = document.getElementById('add-user-error');
                const errorList = document.getElementById('add-user-error-list');
                const errorCount = document.getElementById('add-user-error-count');
                
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessage;
                errorCount.textContent = errorMessage.split('<li>').length - 1;
                
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            document.getElementById('add-user-error').classList.add('hidden');
            
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
            const inputFields = document.getElementById('add-user-form').querySelectorAll('input, select');
            inputFields.forEach(field => {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            });
            
            const errorMessages = document.getElementById('add-user-modal').querySelectorAll('.error-message');
            errorMessages.forEach(error => {
                error.textContent = '';
                error.classList.add('hidden');
            });
            
            ['name-error', 'email-error', 'role-error', 'password-error', 'password-confirm-error'].forEach(id => {
                const errorElement = document.getElementById(id);
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.classList.add('hidden');
                }
            });
        }
    }
    
    // Validates password strength, updates the strength meter, and requirement indicators.
    function validatePassword() {
        const passwordField = document.getElementById('add-password');
        const password = passwordField.value;
        const meter = document.getElementById('password-strength-meter');
        const label = document.getElementById('password-strength-label');
        const errorElement = document.getElementById('password-error');
        
        if (!meter || !label) return;
        
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
        
        passwordField.classList.remove('border-red-500');
        
        if (password === '') {
            if (errorElement) {
                errorElement.classList.remove('hidden');
                errorElement.textContent = 'Kata Sandi wajib diisi';
            }
            passwordField.classList.add('border-red-500');
        } else if (password.length < 6) {
            if (errorElement) {
                errorElement.classList.remove('hidden');
                errorElement.textContent = 'Kata Sandi minimal 6 karakter';
            }
            passwordField.classList.add('border-red-500');
        }
        
        const hasLength = password.length >= 6;
        const hasNumber = /\d/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);
        
        updateRequirement('req-length', hasLength);
        updateRequirement('req-number', hasNumber);
        updateRequirement('req-uppercase', hasUppercase);
        updateRequirement('req-special', hasSpecial);
        
        let strength = 0;
        if (password.length > 0) strength += 20;
        if (hasLength) strength += 20;
        if (hasNumber) strength += 20;
        if (hasUppercase) strength += 20;
        if (hasSpecial) strength += 20;
        
        meter.style.width = `${strength}%`;
        
        if (strength <= 20) {
            meter.className = 'h-1.5 bg-red-500 rounded-full';
            label.textContent = 'Sangat Lemah';
            label.className = 'text-xs font-medium text-red-500';
        } else if (strength <= 40) {
            meter.className = 'h-1.5 bg-orange-500 rounded-full';
            label.textContent = 'Lemah';
            label.className = 'text-xs font-medium text-orange-500';
        } else if (strength <= 60) {
            meter.className = 'h-1.5 bg-yellow-500 rounded-full';
            label.textContent = 'Sedang';
            label.className = 'text-xs font-medium text-yellow-600';
        } else if (strength <= 80) {
            meter.className = 'h-1.5 bg-green-400 rounded-full';
            label.textContent = 'Kuat';
            label.className = 'text-xs font-medium text-green-600';
        } else {
            meter.className = 'h-1.5 bg-green-600 rounded-full';
            label.textContent = 'Sangat Kuat';
            label.className = 'text-xs font-medium text-green-700';
        }
        
        const confirmField = document.getElementById('add-password-confirmation');
        const confirmError = document.getElementById('password-confirmation-error');
        
        if (confirmField && confirmField.value && confirmError) {
            if (confirmField.value !== password) {
                confirmError.textContent = 'Konfirmasi kata sandi tidak cocok';
                confirmError.classList.remove('hidden');
                confirmField.classList.add('border-red-500');
                confirmField.classList.remove('border-gray-300');
            } else {
                confirmError.textContent = '';
                confirmError.classList.add('hidden');
                confirmField.classList.remove('border-red-500');
                confirmField.classList.add('border-gray-300');
            }
        }
        
        return hasLength;
    }
    
    // Updates the visual style of a password requirement element based on its validity.
    function updateRequirement(id, isValid) {
        const element = document.getElementById(id);
        if (!element) return;
        
        const icon = element.querySelector('svg');
        if (isValid) {
            element.className = 'text-xs text-green-600 flex items-center';
            if (icon) icon.setAttribute('class', 'h-3 w-3 mr-1 text-green-600');
        } else {
            element.className = 'text-xs text-gray-500 flex items-center';
            if (icon) icon.setAttribute('class', 'h-3 w-3 mr-1 text-gray-400');
        }
    }
    
    // Attaches click event listeners to all edit user buttons.
    function attachEditButtonListeners() {
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-id');
                loadUserForEdit(userId);
            });
        });
    }
    
    // Attaches click event listeners to all show user detail buttons.
    function attachShowButtonListeners() {
        document.querySelectorAll('.show-user').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-user-id');
                loadUserForView(userId);
            });
        });
    }
    
    // Fetches user data by ID and populates the edit user form.
    function loadUserForEdit(userId) {
        if (!userId) return;
        
        const editForm = document.getElementById('edit-user-form');
        const editUserIdField = document.getElementById('edit-user-id');
        
        if (!editForm || !editUserIdField) return;
        
        fetch(`${userRoutes.index}/${userId}/details`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load user details');
            }
            
            const user = data.data;
            
            // Set the user ID and display the edit modal
            editUserIdField.value = user.id;
            
            // Set the form action with the correct user ID
            editForm.setAttribute('action', `${userRoutes.index}/${user.id}`);
            
            // Populate form fields
            document.getElementById('edit-name').value = user.name || '';
            document.getElementById('edit-email').value = user.email || '';
            
            // Set role
            const levelIdSelect = document.getElementById('edit-level-id');
            if (levelIdSelect) {
                levelIdSelect.value = user.level_id || '';
            }
            
            // Set program studi if available
            const programStudiSelect = document.getElementById('edit-program-studi-id');
            if (programStudiSelect && user.program_studi_id) {
                programStudiSelect.value = user.program_studi_id;
            }
            
            // Set NIM or NIP based on role
            if (user.nim) {
                document.getElementById('edit-nim').value = user.nim;
            }
            
            if (user.nip) {
                document.getElementById('edit-nip').value = user.nip;
            }
            
            // Show/hide fields based on role
            const roleCode = user.role_code;
            const studentFields = document.querySelectorAll('#edit-user-form .student-field');
            const lecturerFields = document.querySelectorAll('#edit-user-form .lecturer-field');
            
            // Hide all role-specific fields first
            studentFields.forEach(field => field.style.display = 'none');
            lecturerFields.forEach(field => field.style.display = 'none');
            
            // Show fields based on role
            if (roleCode === 'MHS') {
                studentFields.forEach(field => field.style.display = 'block');
            } else if (roleCode === 'DSN') {
                lecturerFields.forEach(field => field.style.display = 'block');
            }
            
            // Show profile photo
            const photoPreview = document.getElementById('current-photo-preview');
            if (photoPreview) {
                photoPreview.src = user.photo || defaultAvatarUrl;
            }
            
            // Show the edit modal
            window.editUserModal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading user for edit:', error);
            showNotification(error.message || 'Gagal memuat data pengguna. Silakan coba lagi.', 'error');
        });
    }

    // Fetches user data by ID and populates the show user detail modal.
    function loadUserForView(userId) {
        fetch(`${userRoutes.index}/${userId}/details`)
            .then(response => response.json())
            .then(response => {
                if (!response.success) {
                    throw new Error('Error fetching user details');
                }
                
                const userData = response.data;
                const showUserModalElement = document.getElementById('show-user-modal');
                
                document.getElementById('show-id').textContent = userData.id;
                document.getElementById('show-name').textContent = userData.name;
                document.getElementById('show-email').textContent = userData.email || '-';
                document.getElementById('show-role').textContent = userData.role ? userData.role.charAt(0).toUpperCase() + userData.role.slice(1) : '-';
                document.getElementById('show-created-at').textContent = userData.created_at ? new Date(userData.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';
                document.getElementById('show-user-updated-at').textContent = userData.updated_at ? new Date(userData.updated_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : '-';
                
                // Handle program studi display
                if (userData.program_studi_id) {
                    // Fetch program studi name using the JSON endpoint
                    fetch(`/admin/programs/${userData.program_studi_id}/json`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(programData => {
                        if (programData.success && programData.data) {
                            document.getElementById('show-program-studi').textContent = 
                                programData.data.name + ' (' + programData.data.code + ')';
                        } else {
                            document.getElementById('show-program-studi').textContent = 'Program Studi #' + userData.program_studi_id;
                        }
                    })
                    .catch(() => {
                        document.getElementById('show-program-studi').textContent = 'Program Studi #' + userData.program_studi_id;
                    });
                } else {
                    document.getElementById('show-program-studi').textContent = '-';
                }
                
                // Handle NIM/NIP display based on role
                const nimContainer = document.getElementById('nim-container');
                const nipContainer = document.getElementById('nip-container');
                
                // Hide both containers initially
                if (nimContainer) nimContainer.style.display = 'none';
                if (nipContainer) nipContainer.style.display = 'none';
                
                // Show the appropriate container based on role_code
                if (userData.role_code === 'MHS' && nimContainer) {
                    nimContainer.style.display = 'block';
                    document.getElementById('show-nim').textContent = userData.nim || '-';
                } else if (userData.role_code === 'DSN' && nipContainer) {
                    nipContainer.style.display = 'block';
                    document.getElementById('show-nip').textContent = userData.nip || '-';
                }
                
                const roleElement = document.getElementById('show-role');
                if (userData.role_code === 'admin') {
                    roleElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800';
                } else if (userData.role_code === 'user') {
                    roleElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800';
                } else {
                    roleElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800';
                }
                
                document.getElementById('show-photo').src = userData.photo || defaultAvatarUrl;
                
                document.getElementById('edit-from-show').setAttribute('data-user-id', userData.id);
                
                if (showUserModalElement) {
                    showUserModalElement.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching user details:', error);
                showNotification('Error fetching user details: ' + error.message, 'error');
            });
    }

    // Asynchronously refreshes the users table content and re-attaches event listeners.
    async function refreshUsersTable() {
        const usersTableContainer = document.getElementById('users-table-container');
        const paginationContainer = document.getElementById('pagination-container');
        
        if (!usersTableContainer) return;

        try {
            const url = new URL(userRoutes.index);
            url.searchParams.append('ajax', '1');
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.tableHtml) {
                usersTableContainer.innerHTML = data.tableHtml;
            }
            
            if (data.paginationHtml && paginationContainer) {
                paginationContainer.innerHTML = data.paginationHtml;
            }
            
            attachEditButtonListeners();
            attachDeleteButtonListeners();
            attachShowButtonListeners();
            
            attachPaginationHandlers();
            
            updateStats();
            
        } catch (error) {
            console.error('Error refreshing users table:', error);
        }
    }
    
    // Attaches click event handlers to pagination links for AJAX table updates.
    function attachPaginationHandlers() {
        const paginationContainer = document.getElementById('pagination-container');
        if (!paginationContainer) return;
        
        const paginationLinks = paginationContainer.querySelectorAll('a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const url = new URL(link.href);
                const clickedPage = url.searchParams.get('page') || '1';
                
                updateActivePage(clickedPage);
                
                window.history.pushState({}, '', link.href);
                
                try {
                    const ajaxUrl = new URL(link.href);
                    ajaxUrl.searchParams.append('ajax', '1');
                    
                    const response = await fetch(ajaxUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    const usersTableContainer = document.getElementById('users-table-container');
                    
                    if (data.tableHtml && usersTableContainer) {
                        usersTableContainer.innerHTML = data.tableHtml;
                    }
                    
                    if (data.paginationHtml && paginationContainer) {
                        paginationContainer.innerHTML = data.paginationHtml;
                        updateActivePage(clickedPage);
                    }
                    
                    attachEditButtonListeners();
                    attachDeleteButtonListeners();
                    attachShowButtonListeners();
                    attachPaginationHandlers();
                    
                } catch (error) {
                    console.error('Error with pagination:', error);
                }
            });
        });
    }
    
    // Updates the visual styling of the active page link in the pagination UI.
    function updateActivePage(pageNumber) {
        const paginationContainer = document.getElementById('pagination-container');
        if (!paginationContainer) return;
        
        const activeSpans = paginationContainer.querySelectorAll('span[aria-current="page"]');
        activeSpans.forEach(span => {
            const innerSpan = span.querySelector('span');
            if (!innerSpan) return;
            
            const pageText = innerSpan.textContent.trim();
            if (!/^\d+$/.test(pageText)) return;
            
            const pageUrl = window.location.pathname + '?page=' + pageText;
            
            const newLink = document.createElement('a');
            newLink.href = pageUrl;
            newLink.className = 'relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-blue-100 transition ease-in-out duration-150';
            newLink.setAttribute('aria-label', `Go to page ${pageText}`);
            newLink.textContent = pageText;
            
            span.parentNode.replaceChild(newLink, span);
        });
        
        const links = paginationContainer.querySelectorAll('a');
        links.forEach(link => {
            if (link.querySelector('svg')) return;
            
            const url = new URL(link.href);
            const linkPage = url.searchParams.get('page') || '1';
            
            if (!/^\d+$/.test(link.textContent.trim())) return;
            
            if (linkPage === pageNumber) {
                const activeSpan = document.createElement('span');
                activeSpan.setAttribute('aria-current', 'page');
                
                const innerSpan = document.createElement('span');
                innerSpan.className = 'relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5';
                innerSpan.textContent = linkPage;
                
                activeSpan.appendChild(innerSpan);
                
                link.parentNode.replaceChild(activeSpan, link);
            }
        });
        
        attachPaginationHandlers();
    }
    
    // Asynchronously fetches and updates user statistics displayed on the page.
    async function updateStats() {
        try {
            const response = await fetch(userRoutes.index, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to update stats');
            
            const data = await response.json();
            
            if (data.stats) {
                const totalUsersElement = document.querySelector('.card [data-stat="totalUsers"]');
                const newUsersElement = document.querySelector('.card [data-stat="newUsers"]');
                const activeUsersElement = document.querySelector('.card [data-stat="activeUsers"]');
                
                if (totalUsersElement) totalUsersElement.textContent = data.stats.totalUsers;
                if (newUsersElement) newUsersElement.textContent = data.stats.newUsers;
                if (activeUsersElement) activeUsersElement.textContent = data.stats.activeUsers;
            }
        } catch (error) {
            console.error('Error updating stats:', error);
        }
    }
    
    // Attaches click event listeners to delete user buttons and configures the delete confirmation modal.
    function attachDeleteButtonListeners() {
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                const deleteModal = document.getElementById('delete-user-modal');
                
                if (deleteModal) {
                    const deleteForm = deleteModal.querySelector('form');
                    
                    if (deleteForm) {
                        deleteForm.action = `${userRoutes.index}/${userId}`;
                        
                        const titleElement = deleteModal.querySelector('h3');
                        if (titleElement) {
                            titleElement.textContent = `Yakin ingin menghapus ${userName || 'pengguna ini'}?`;
                        }
                        
                        document.dispatchEvent(new CustomEvent('delete-modal:show'));
                    }
                }
            });
        });
    }

    // Displays validation errors on a form, updating error messages and field styles.
    function displayErrors(errors, form, errorContainer, errorList) {
        errorList.innerHTML = '';
        let errorCount = 0;
        
        for (const field in errors) {
            const message = errors[field][0];
            const li = document.createElement('li');
            li.textContent = message;
            errorList.appendChild(li);
            errorCount++;
            
            const inputField = form.querySelector(`[name="${field}"]`);
            if (inputField) {
                const formGroup = inputField.closest('.form-control') || inputField.closest('.form-group');
                if (formGroup) {
                    const errorElement = formGroup.querySelector('.text-sm.text-red-600') || 
                                        formGroup.querySelector('.error-message');
                    if (errorElement) {
                        errorElement.textContent = message;
                        errorElement.classList.remove('hidden');
                    }
                    
                    inputField.classList.add('border-red-500');
                    
                    const selectContainer = formGroup.querySelector('.relative.rounded-md.shadow-sm');
                    if (selectContainer) {
                        selectContainer.classList.add('border-red-500', 'ring-red-500');
                    }
                }
            }
        }
        
        const errorCountEl = errorContainer.querySelector('[id$="-error-count"]');
        if (errorCountEl) {
            errorCountEl.textContent = errorCount;
        }
        
        errorContainer.classList.remove('hidden');
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Resets a given form and clears its associated validation errors.
    function resetForm(form) {
        if (form && typeof form.reset === 'function') {
            form.reset();
        }
        if (form && form.id) {
             resetFormErrors(form.id);
        } else if (form instanceof HTMLElement) {
            resetFormErrors(form);
        }
    }
    
    // Clears error messages and error-related styling from a form.
    function resetFormErrors(formOrId) {
        let formElement;
        if (typeof formOrId === 'string') {
            formElement = document.getElementById(formOrId);
        } else if (formOrId instanceof HTMLElement) {
            formElement = formOrId;
        }

        if (!formElement) return;

        formElement.querySelectorAll('.text-sm.text-red-600, .error-message').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        
        formElement.querySelectorAll('input, select, textarea').forEach(el => {
            el.classList.remove('border-red-500');
        });
        
        formElement.querySelectorAll('.relative.rounded-md.shadow-sm').forEach(el => {
            el.classList.remove('border-red-500', 'ring-red-500');
        });

        const errorContainer = formElement.id ? document.getElementById(formElement.id.replace('-form', '-error')) : null;
        if (errorContainer) {
            errorContainer.classList.add('hidden');
            const errorList = errorContainer.querySelector('ul');
            if (errorList) {
                errorList.innerHTML = '';
            }
        }
    }

    // Displays a toast-like notification message to the user.
    function showNotification(message, type = 'success') {
        let notificationContainer = document.getElementById('notification-container');
        
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notification-container';
            notificationContainer.className = 'fixed top-24 right-4 z-50 flex flex-col items-end space-y-2';
            document.body.appendChild(notificationContainer);
        }
        
        const notification = document.createElement('div');
        notification.className = `px-4 py-3 rounded-lg shadow-lg flex items-center justify-between max-w-md transform transition-all duration-500 ease-in-out translate-x-full opacity-0 ${
            type === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-500' : 
            type === 'error' ? 'bg-red-50 text-red-800 border-l-4 border-red-500' : 
            'bg-blue-50 text-blue-800 border-l-4 border-blue-500'
        }`;
        
        let icon = '';
        if (type === 'success') {
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>';
        } else if (type === 'error') {
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>';
        } else {
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2h.01a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>';
        }
        
        notification.innerHTML = `
            <div class="flex items-center">
                ${icon}
                <span class="text-sm font-medium">${message}</span>
            </div>
            <button class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        
        notificationContainer.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 10);
        
        const progressBar = document.createElement('div');
        progressBar.className = `absolute bottom-0 left-0 h-1 bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-400 rounded-b-lg`;
        progressBar.style.width = '100%';
        progressBar.style.transition = 'width 4.9s linear';
        notification.appendChild(progressBar);
        
        setTimeout(() => {
            progressBar.style.width = '0%';
        }, 100);
        
        const closeButton = notification.querySelector('button');
        closeButton.addEventListener('click', () => {
            closeNotification(notification);
        });
        
        const timeout = setTimeout(() => {
            closeNotification(notification);
        }, 5000);
        
        function closeNotification(notification) {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                notification.remove();
                if (notificationContainer.children.length === 0) {
                    notificationContainer.remove();
                }
            }, 500);
        }

        if (type === 'error' && message.includes('has already been taken')) {
            message = 'Email sudah digunakan.'; // This is UI text, should be handled by i18n if needed.
        }
    }

    const passwordToggle = document.getElementById('update-password-toggle');
    const passwordFields = document.getElementById('password-fields');
    
    if (passwordToggle && passwordFields) {
        passwordToggle.addEventListener('change', function() {
            if (this.checked) {
                passwordFields.classList.remove('hidden');
            } else {
                passwordFields.classList.add('hidden');
                const editPassword = document.getElementById('edit-password');
                const editPasswordConfirmation = document.getElementById('edit-password-confirmation');
                if (editPassword) editPassword.value = '';
                if (editPasswordConfirmation) editPasswordConfirmation.value = '';
            }
        });
    }

    const editUserForm = document.getElementById('edit-user-form');
    if (editUserForm) {
        const editUserErrorContainer = document.getElementById('edit-user-error');
        const editUserErrorList = document.getElementById('edit-user-error-list');
        
        editUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            resetFormErrors(editUserForm);
            if (editUserErrorContainer) editUserErrorContainer.classList.add('hidden');
            if (editUserErrorList) editUserErrorList.innerHTML = '';
            
            try {
                const formData = new FormData(editUserForm);
                formData.append('_method', 'PUT'); 
                
                if (!formData.has('_token')) {
                    formData.append('_token', csrfToken);
                }
                
                const userId = document.getElementById('edit-user-id').value;
                const action = editUserForm.getAttribute('action') || `${userRoutes.index}/${userId}`;
                
                const response = await fetch(action, {
                    method: 'POST', 
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    if (data.errors && editUserErrorContainer && editUserErrorList) {
                        displayErrors(data.errors, editUserForm, editUserErrorContainer, editUserErrorList);
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menyimpan perubahan.');
                    }
                    return;
                }
                
                refreshUsersTable();
                if (window.editUserModal) window.editUserModal.classList.add('hidden');
                
                showNotification('Berhasil menyimpan perubahan!', 'success');
            } catch (error) {
                console.error('Error updating user:', error);
                showNotification(error.message || 'Terjadi kesalahan saat menyimpan perubahan.', 'error');
            }
        });
    }

    // Sets up the submission handler for the delete confirmation modal form.
    function setupDeleteForm() {
        const deleteForm = document.querySelector('#delete-user-modal form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const userId = deleteForm.action.split('/').pop();
                
                const submitButton = deleteForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghapus...
                `;
                
                try {
                    const response = await fetch(deleteForm.action, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const result = await response.json();
                    
                    const deleteModal = document.getElementById('delete-user-modal');
                    if (deleteModal) {
                        deleteModal.classList.add('hidden');
                    }
                    
                    if (response.ok) {
                        const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                        if (userRow) {
                            userRow.style.transition = 'all 0.5s ease-out';
                            userRow.style.opacity = '0';
                            userRow.style.background = '#FEE2E2';
                            setTimeout(() => {
                                userRow.style.height = '0';
                                userRow.style.overflow = 'hidden';
                                userRow.style.padding = '0';
                                userRow.style.margin = '0';
                                setTimeout(() => {
                                    refreshUsersTable();
                                }, 300);
                            }, 300);
                        } else {
                            refreshUsersTable();
                        }
                        
                        showNotification(result.message || 'Pengguna berhasil dihapus!', 'success');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    } else {
                        showNotification(result.message || 'Gagal menghapus pengguna.', 'error');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                    showNotification('Terjadi kesalahan saat menghapus pengguna.', 'error');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            });
        }
    }

    // Handle showing/hiding NIM and NIP fields based on selected role
    function setupRoleFieldsVisibility() {
        // For Add User form
        const addLevelSelect = document.getElementById('add-level-id');
        if (addLevelSelect) {
            addLevelSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const levelText = selectedOption.text.toLowerCase();
                
                const studentFields = document.querySelectorAll('.student-field');
                const lecturerFields = document.querySelectorAll('.lecturer-field');
                
                // Hide all role-specific fields first
                studentFields.forEach(field => field.style.display = 'none');
                lecturerFields.forEach(field => field.style.display = 'none');
                
                // Show fields based on selected role
                if (levelText.includes('mahasiswa')) {
                    studentFields.forEach(field => field.style.display = 'block');
                } else if (levelText.includes('dosen')) {
                    lecturerFields.forEach(field => field.style.display = 'block');
                }
            });
        }
        
        // For Edit User form
        const editLevelSelect = document.getElementById('edit-level-id');
        if (editLevelSelect) {
            editLevelSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const levelText = selectedOption.text.toLowerCase();
                
                const studentFields = document.querySelectorAll('#edit-user-form .student-field');
                const lecturerFields = document.querySelectorAll('#edit-user-form .lecturer-field');
                
                // Hide all role-specific fields first
                studentFields.forEach(field => field.style.display = 'none');
                lecturerFields.forEach(field => field.style.display = 'none');
                
                // Show fields based on selected role
                if (levelText.includes('mahasiswa')) {
                    studentFields.forEach(field => field.style.display = 'block');
                } else if (levelText.includes('dosen')) {
                    lecturerFields.forEach(field => field.style.display = 'block');
                }
            });
        }
    }

    setupUserModals();
    setupDeleteForm();
    attachDeleteButtonListeners();
    attachShowButtonListeners();
    attachPaginationHandlers();
}); 