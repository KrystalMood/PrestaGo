document.addEventListener('DOMContentLoaded', function() {
    const programRoutes = window.programRoutes || {};
    const csrfToken = window.csrfToken || '';

    setupProgramModals();

    attachPaginationHandlers();

    // Function to initialize and set up event listeners for program modals
    function setupProgramModals() {
        window.addProgramModal = document.getElementById('add-program-modal');
        window.editProgramModal = document.getElementById('edit-program-modal');
        window.showProgramModal = document.getElementById('show-program-modal');
        window.deleteProgramModal = document.getElementById('delete-program-modal');

        const openAddModalBtn = document.getElementById('open-add-program-modal');
        if (openAddModalBtn) {
            openAddModalBtn.addEventListener('click', function() {
                if (window.addProgramModal) {
                    window.addProgramModal.classList.remove('hidden');
                    resetFormErrors('add-program-form');
                    resetMultiStepForm();
                }
            });
        }

        const closeAddModalBtn = document.getElementById('close-add-modal');
        const cancelAddBtn = document.getElementById('cancel-add-program');
        [closeAddModalBtn, cancelAddBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.addProgramModal) {
                        window.addProgramModal.classList.add('hidden');
                        document.getElementById('add-program-form').reset();
                        resetFormErrors('add-program-form');
                        resetMultiStepForm();
                    }
                });
            }
        });

        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-program');
        [closeEditModalBtn, cancelEditBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.editProgramModal) {
                        window.editProgramModal.classList.add('hidden');
                        resetFormErrors('edit-program-form');
                    }
                });
            }
        });

        const closeShowModalBtn = document.getElementById('close-show-modal');
        const closeShowBtn = document.getElementById('close-show-program');
        const editFromShowBtn = document.getElementById('edit-from-show');

        [closeShowModalBtn, closeShowBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.showProgramModal) {
                        window.showProgramModal.classList.add('hidden');
                    }
                });
            }
        });

        if (editFromShowBtn) {
            editFromShowBtn.addEventListener('click', function() {
                const programId = editFromShowBtn.getAttribute('data-program-id');
                if (window.showProgramModal) {
                    window.showProgramModal.classList.add('hidden');
                }
                loadProgramForEdit(programId);
            });
        }

        const closeDeleteModalBtn = document.getElementById('close-delete-modal');
        const cancelDeleteBtn = document.getElementById('cancel-delete-program');
        [closeDeleteModalBtn, cancelDeleteBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.deleteProgramModal) {
                        window.deleteProgramModal.classList.add('hidden');
                    }
                });
            }
        });

        setupAddProgramForm();
        setupMultiStepFormNavigation();
        attachEditButtonListeners();
        attachShowButtonListeners();
        attachDeleteButtonListeners();
    }

    // Function to reset the multi-step form to its initial (first) step
    function resetMultiStepForm() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-program');
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

    // Function to set up the multi-step form navigation
    function setupMultiStepFormNavigation() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-program');

        if (!step1 || !step2 || !nextBtn || !prevBtn || !submitBtn) return;

        const stepItems = document.querySelectorAll('.step-item');

        nextBtn.addEventListener('click', function() {
            const code = document.getElementById('add-code');
            const name = document.getElementById('add-name');
            const faculty = document.getElementById('add-faculty');
            const degreeLevel = document.getElementById('add-degree-level');

            let isValid = true;
            let errorMessage = '';

            resetFieldErrors();

            if (!code.value.trim()) {
                isValid = false;
                errorMessage += '<li>Kode Program Studi wajib diisi</li>';
                showFieldError(code, 'Kode Program Studi wajib diisi');
            }

            if (!name.value.trim()) {
                isValid = false;
                errorMessage += '<li>Nama Program Studi wajib diisi</li>';
                showFieldError(name, 'Nama Program Studi wajib diisi');
            }

            if (!faculty.value.trim()) {
                isValid = false;
                errorMessage += '<li>Fakultas wajib diisi</li>';
                showFieldError(faculty, 'Fakultas wajib diisi');
            }

            if (!degreeLevel.value) {
                isValid = false;
                errorMessage += '<li>Jenjang wajib dipilih</li>';
                showFieldError(degreeLevel, 'Jenjang wajib dipilih');
            }

            if (!isValid) {
                const errorContainer = document.getElementById('add-program-error');
                const errorList = document.getElementById('add-program-error-list');
                const errorCount = document.getElementById('add-program-error-count');

                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessage;
                errorCount.textContent = errorMessage.split('<li>').length - 1;

                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            document.getElementById('add-program-error').classList.add('hidden');

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

        // Function to show a field-specific error message
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

        // Function to reset field-specific error messages
        function resetFieldErrors() {
            const formInputs = document.querySelectorAll('#add-program-form input, #add-program-form select, #add-program-form textarea');
            formInputs.forEach(input => {
                input.classList.remove('border-red-500');
                input.classList.add('border-gray-300');

                const formGroup = input.closest('.form-group');
                let errorElement;

                if (formGroup) {
                    errorElement = formGroup.querySelector('.error-message');
                }

                if (!errorElement) {
                    errorElement = document.getElementById(`${input.id.replace('add-', '')}-error`);
                }

                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.classList.add('hidden');
                }
            });
        }
    }

    // Function to set up the form submission for adding programs
    function setupAddProgramForm() {
        const addForm = document.getElementById('add-program-form');
        if (!addForm) return;

        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAddProgramForm();
        });

        // Function to submit the add program form data via AJAX
        function submitAddProgramForm() {
            const submitButton = document.getElementById('submit-add-program');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 01-8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;

            const formData = new FormData(addForm);

            fetch(programRoutes.store, {
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
                    throw new Error(data.message || 'Failed to add program');
                }

                addForm.reset();
                resetMultiStepForm();
                window.addProgramModal.classList.add('hidden');

                showNotification(data.message || 'Program added successfully', 'success');

                refreshProgramsTable();
            })
            .catch(error => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;

                console.error('Error adding program:', error);

                if (error.response && error.response.status === 422) {
                    const errorData = error.response.data;
                    displayErrors(errorData.errors, addForm, 'add-program-error', 'add-program-error-list');
                } else {
                    showNotification(error.message || 'Failed to add program. Please try again.', 'error');
                }
            });
        }
    }

    // Function to attach click event listeners to edit buttons
    function attachEditButtonListeners() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit')) {
                const btn = e.target.closest('.btn-edit');
                const programId = btn.getAttribute('data-program-id');
                loadProgramForEdit(programId);
            }
        });
    }

    // Function to attach click event listeners to show (view detail) buttons
    function attachShowButtonListeners() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.show-program')) {
                const btn = e.target.closest('.show-program');
                const programId = btn.getAttribute('data-program-id');
                loadProgramForView(programId);
            }
        });
    }

    // Function to load program data for editing
    function loadProgramForEdit(programId) {
        if (!programId) return;

        const url = programRoutes.edit.replace('__id__', programId);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load program');
            }

            const program = data.data;

            document.getElementById('edit-program-id').value = program.id;
            document.getElementById('edit-code').value = program.code || '';
            document.getElementById('edit-name').value = program.name || '';
            document.getElementById('edit-faculty').value = program.faculty || '';
            document.getElementById('edit-degree-level').value = program.degree_level || '';
            document.getElementById('edit-accreditation').value = program.accreditation || '';
            document.getElementById('edit-year-established').value = program.year_established || '';
            document.getElementById('edit-description').value = program.description || '';

            const isActiveToggle = document.getElementById('edit-is-active');
            if (isActiveToggle) {
                isActiveToggle.checked = program.is_active;

                const toggleVisual = document.querySelector(`label[for="edit-is-active"] .toggle-visual`);
                if (toggleVisual) {
                    if (program.is_active) {
                        toggleVisual.classList.add('bg-blue-600');
                        toggleVisual.classList.remove('bg-gray-200');
                    } else {
                        toggleVisual.classList.remove('bg-blue-600');
                        toggleVisual.classList.add('bg-gray-200');
                    }
                }
            }

            resetFormErrors('edit-program-form');
            window.editProgramModal.classList.remove('hidden');

            const editForm = document.getElementById('edit-program-form');
            if (editForm) {
                editForm.onsubmit = function(e) {
                    e.preventDefault();
                    updateProgram(program.id);
                };
            }
        })
        .catch(error => {
            console.error('Error loading program for edit:', error);
            showNotification('Failed to load program data. Please try again.', 'error');
        });
    }

    // Function to load program data for viewing
    function loadProgramForView(programId) {
        if (!programId) return;

        const url = programRoutes.show.replace('__id__', programId);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load program');
            }

            const program = data.data;

            document.getElementById('show-program-name').textContent = program.name || '-';
            document.getElementById('show-program-code').textContent = program.code || '-';
            document.getElementById('show-program-faculty').textContent = program.faculty || '-';
            document.getElementById('show-program-degree-level').textContent = program.degree_level || '-';
            document.getElementById('show-program-accreditation').textContent = program.accreditation || '-';
            document.getElementById('show-program-year-established').textContent = program.year_established || '-';
            document.getElementById('show-program-description').textContent = program.description || '-';

            const statusSpan = document.getElementById('show-program-status');
            statusSpan.textContent = program.is_active ? 'Aktif' : 'Tidak Aktif';
            if (program.is_active) {
                statusSpan.classList.remove('bg-gray-100', 'text-gray-800');
                statusSpan.classList.add('bg-green-100', 'text-green-800');
            } else {
                statusSpan.classList.remove('bg-green-100', 'text-green-800');
                statusSpan.classList.add('bg-gray-100', 'text-gray-800');
            }

            document.getElementById('show-program-updated-at').textContent = program.updated_at_formatted || '-';

            const editFromShowBtn = document.getElementById('edit-from-show');
            if (editFromShowBtn) {
                editFromShowBtn.setAttribute('data-program-id', program.id);
            }

            window.showProgramModal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading program for view:', error);
            showNotification('Failed to load program data. Please try again.', 'error');
        });
    }

    // Function to update program data via AJAX
    function updateProgram(programId) {
        if (!programId) return;

        const editForm = document.getElementById('edit-program-form');
        const submitButton = document.getElementById('submit-edit-program');
        const originalButtonText = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 01-8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;

        const formData = new FormData(editForm);
        formData.append('_method', 'PUT');

        const url = programRoutes.update.replace('__id__', programId);

        fetch(url, {
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
                throw new Error(data.message || 'Failed to update program');
            }

            window.editProgramModal.classList.add('hidden');

            showNotification(data.message || 'Program updated successfully', 'success');

            refreshProgramsTable();
        })
        .catch(error => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;

            console.error('Error updating program:', error);

            if (error.response && error.response.status === 422) {
                const errorData = error.response.data;
                displayErrors(errorData.errors, editForm, 'edit-program-error', 'edit-program-error-list');
            } else {
                showNotification(error.message || 'Failed to update program. Please try again.', 'error');
            }
        });
    }

    // Function to attach event listeners to delete buttons
    function attachDeleteButtonListeners() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-program')) {
                const btn = e.target.closest('.delete-program');
                const programId = btn.getAttribute('data-program-id');
                const programName = btn.getAttribute('data-program-name');

                document.getElementById('program-name-to-delete').textContent = programName || 'this program';
                const confirmBtn = document.getElementById('confirm-delete-program');
                if (confirmBtn) {
                    confirmBtn.setAttribute('data-program-id', programId);
                    confirmBtn.onclick = function() {
                        deleteProgram(programId);
                    };
                }

                window.deleteProgramModal.classList.remove('hidden');
            }
        });
    }

    // Function to delete a program via AJAX
    function deleteProgram(programId) {
        if (!programId) return;

        const confirmBtn = document.getElementById('confirm-delete-program');
        const originalButtonText = confirmBtn.innerHTML;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 01-8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;

        const url = programRoutes.destroy.replace('__id__', programId);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;

            if (!data.success) {
                throw new Error(data.message || 'Failed to delete program');
            }

            window.deleteProgramModal.classList.add('hidden');

            showNotification(data.message || 'Program deleted successfully', 'success');

            refreshProgramsTable();
        })
        .catch(error => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;

            console.error('Error deleting program:', error);
            window.deleteProgramModal.classList.add('hidden');

            showNotification(error.message || 'Failed to delete program. Please try again.', 'error');
        });
    }

    // Function to refresh the programs table content using AJAX
    async function refreshProgramsTable() {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('ajax', 'true');

        try {
            const response = await fetch(currentUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to refresh table');
            }

            document.getElementById('programs-table-container').innerHTML = data.table;
            document.getElementById('pagination-container').innerHTML = data.pagination;

            if (data.stats) {
                updateStats(data.stats);
            }

            attachPaginationHandlers();

        } catch (error) {
            console.error('Error refreshing programs table:', error);
            showNotification('Failed to update table. Please reload the page.', 'error');
        }
    }

    // Function to attach event handlers to pagination links
    function attachPaginationHandlers() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const href = this.getAttribute('href');

                const url = new URL(href, window.location.origin);
                const pageNumber = url.searchParams.get('page') || 1;

                updateActivePage(pageNumber);

                window.history.pushState({}, '', href);

                refreshProgramsTable();
            });
        });
    }

    // Function to update the visual state of the active page in pagination
    function updateActivePage(pageNumber) {
        const paginationLinks = document.querySelectorAll('.pagination a');

        paginationLinks.forEach(link => {
            const href = link.getAttribute('href');
            const url = new URL(href, window.location.origin);
            const linkPage = url.searchParams.get('page') || 1;

            if (linkPage == pageNumber) {
                link.classList.add('active', 'bg-blue-600', 'text-white');
                link.classList.remove('text-gray-700', 'hover:bg-gray-100');
            } else {
                link.classList.remove('active', 'bg-blue-600', 'text-white');
                link.classList.add('text-gray-700', 'hover:bg-gray-100');
            }
        });
    }

    // Function to update statistics cards with new data
    function updateStats(stats) {
        if (!stats) return;

        Object.keys(stats).forEach(key => {
            const value = stats[key];
            const element = document.querySelector(`[data-stat-key="${key}"]`);
            if (element) {
                element.textContent = value;
            }
        });
    }

    // Function to display form validation errors
    function displayErrors(errors, form, errorContainer, errorList) {
        const errorElement = document.getElementById(errorContainer);
        const errorListElement = document.getElementById(errorList);
        const errorCountElement = document.getElementById(`${errorContainer}-count`);

        if (!errorElement || !errorListElement || !errorCountElement) return;

        let errorHTML = '';
        let errorCount = 0;

        const formInputs = form.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');

            const formGroup = input.closest('.form-group');
            if (formGroup) {
                const errorMsg = formGroup.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.classList.add('hidden');
                    errorMsg.textContent = '';
                }
            }
        });

        if (errors && typeof errors === 'object') {
            Object.keys(errors).forEach(field => {
                const messages = Array.isArray(errors[field]) ? errors[field] : [errors[field]];

                messages.forEach(message => {
                    errorHTML += `<li>${message}</li>`;
                    errorCount++;

                    const inputField = form.querySelector(`[name="${field}"]`);
                    if (inputField) {
                        inputField.classList.add('border-red-500');
                        inputField.classList.remove('border-gray-300');

                        const formGroup = inputField.closest('.form-group');
                        if (formGroup) {
                            const errorMsg = formGroup.querySelector('.error-message');
                            if (errorMsg) {
                                errorMsg.textContent = message;
                                errorMsg.classList.remove('hidden');
                            }
                        }
                    }
                });
            });
        } else if (typeof errors === 'string') {
            errorHTML = `<li>${errors}</li>`;
            errorCount = 1;
        }

        errorListElement.innerHTML = errorHTML;
        errorCountElement.textContent = errorCount;
        errorElement.classList.remove('hidden');

        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Function to reset form errors
    function resetFormErrors(formOrId) {
        const form = typeof formOrId === 'string' ? document.getElementById(formOrId) : formOrId;
        if (!form) return;

        let formPrefix = 'add';
        
        if (form.id && typeof form.id === 'string') {
            const formIdParts = form.id.split('-');
            formPrefix = formIdParts[0];
        } else if (typeof formOrId === 'string') {
            const formIdParts = formOrId.split('-');
            formPrefix = formIdParts[0];
        }

        const errorContainer = document.getElementById(`${formPrefix}-program-error`);
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }

        const formInputs = form.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');

            const formGroup = input.closest('.form-group');
            if (formGroup) {
                const errorMsg = formGroup.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.classList.add('hidden');
                    errorMsg.textContent = '';
                }
            }
        });
    }

    // Function to show a notification message
    function showNotification(message, type = 'success') {
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => {
            notif.remove();
        });

        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-transform duration-300 ease-in-out flex items-start space-x-4 ${
            type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'
        }`;

        const iconSvg = type === 'success'
            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>`;

        notification.innerHTML = `
            <div class="shrink-0">
                ${iconSvg}
            </div>
            <div class="flex-1">
                <p class="${type === 'success' ? 'text-green-800' : 'text-red-800'} font-medium">
                    ${message}
                </p>
            </div>
            <div>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="this.closest('.notification').remove()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        notification.style.transform = 'translateY(-20px)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            notification.style.transform = 'translateY(0)';
            notification.style.opacity = '1';
        }, 10);
        
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
        
        // Function to close the notification with an animation
        function closeNotification(notif) {
            if (!notif) return;
            
            notif.style.transform = 'translateY(-20px)';
            notif.style.opacity = '0';
            
            setTimeout(() => {
                notif.remove();
            }, 300);
        }
    }
}); 