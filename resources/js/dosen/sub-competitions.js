document.addEventListener('DOMContentLoaded', function() {
    window.subCompetitionSetup = true;
    
    setupSubCompetitionEventListeners();
    autoUpdateSubCompetitionStatuses();
    attachActionListeners();
    
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
            'close-show-sub-modal': 'show-sub-competition-modal'
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
                        }
                        resetFormErrors(modalId.replace('-modal', '-form'));
                    }
                });
            }
        });

        const submitButtons = {
            'submit-add-sub-competition': submitAddSubCompetition,
            'submit-edit-sub-competition': submitEditSubCompetition
        };

        Object.entries(submitButtons).forEach(([buttonId, handler]) => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.addEventListener('click', function() {
                    if (buttonId === 'submit-add-sub-competition') {
                        window.updateSubCompetitionStatus('add');
                    }
                    handler();
                });
            }
        });

        const modals = ['add-sub-competition-modal', 'edit-sub-competition-modal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        if (modalId === 'add-sub-competition-modal') {
                            document.getElementById('add-sub-competition-form').reset();
                            showStep(1, 'add');
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
        
        if (nextStepBtn) {
            nextStepBtn.addEventListener('click', function() {
                if (validateStep1()) {
                    showStep(2);
                }
            });
        }
        
        if (prevStepBtn) {
            prevStepBtn.addEventListener('click', function() {
                showStep(1);
            });
        }

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
    }
    
    // Function to show a specific step in the multi-step form.
    function showStep(stepNumber, prefix = 'add') {
        let step1Content, step2Content, nextBtn, prevBtn, submitBtn, modal;

        if (prefix === 'add') {
            step1Content = document.getElementById('step-1-content');
            step2Content = document.getElementById('step-2-content');
            nextBtn = document.getElementById('next-step');
            prevBtn = document.getElementById('prev-step');
            submitBtn = document.getElementById('submit-add-sub-competition');
            modal = document.getElementById('add-sub-competition-modal');
        } else {
            step1Content = document.getElementById('edit-step-1-content');
            step2Content = document.getElementById('edit-step-2-content');
            nextBtn = document.getElementById('edit-next-step');
            prevBtn = document.getElementById('edit-prev-step');
            submitBtn = document.getElementById('submit-edit-sub-competition');
            modal = document.getElementById('edit-sub-competition-modal');
        }

        if (!step1Content || !step2Content || !nextBtn || !prevBtn || !submitBtn || !modal) return;

        // Use only step items inside content (exclude skeleton loaders)
        const stepItems = Array.from(modal.querySelectorAll('.step-item')).filter(item => !item.closest('.edit-sub-competition-skeleton'));

        // Find the step line that belongs to visible content
        let stepLine = null;
        for (const item of stepItems) {
            const siblingLine = item.parentElement.querySelector('.step-line');
            if (siblingLine && !siblingLine.closest('.edit-sub-competition-skeleton')) {
                stepLine = siblingLine;
                break;
            }
        }
        if (!stepLine) {
            // fallback to first matching element
            stepLine = modal.querySelector('.step-line');
        }

        if (stepNumber === 1) {
            step1Content.classList.remove('hidden');
            step2Content.classList.add('hidden');
            nextBtn.classList.remove('hidden');
            prevBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');

            if (stepItems[1]) {
                stepItems[1].classList.remove('active');
                stepItems[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
                stepItems[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
            }
            if (stepLine) stepLine.classList.remove('bg-blue-600', 'active');
        } else {
            step1Content.classList.add('hidden');
            step2Content.classList.remove('hidden');
            nextBtn.classList.add('hidden');
            prevBtn.classList.remove('hidden');
            submitBtn.classList.remove('hidden');

            if (stepItems[1]) {
                stepItems[1].classList.add('active');
                stepItems[1].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
                stepItems[1].querySelector('div').classList.add('bg-blue-600', 'text-white');
            }
            if (stepLine) stepLine.classList.add('bg-blue-600', 'active');
        }
    }
    
    // Function to validate step 1 of the form.
    function validateStep1() {
        const name = document.getElementById('add-sub-name').value;
        const category = document.getElementById('add-sub-category').value;
        
        let isValid = true;
        
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        if (!name.trim()) {
            document.getElementById('sub-name-error').textContent = 'Nama sub-kompetisi wajib diisi';
            document.getElementById('sub-name-error').classList.remove('hidden');
            isValid = false;
        }
        
        return isValid;
    }
    
    // Function to submit the add sub-competition form.
    function submitAddSubCompetition() {
        const form = document.getElementById('add-sub-competition-form');
        const formData = new FormData(form);
        
        const submitBtn = document.getElementById('submit-add-sub-competition');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        const competitionId = formData.get('competition_id');
        
        document.getElementById('add-sub-competition-error').classList.add('hidden');
        document.getElementById('add-sub-competition-error-list').innerHTML = '';
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
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
            submitBtn.innerHTML = originalBtnText;
            
            if (data.success) {
                showNotification('Sub-kompetisi berhasil ditambahkan', 'success');
                
                document.getElementById('add-sub-competition-modal').classList.add('hidden');
                
                if (data.html) {
                    document.getElementById('sub-competitions-table-container').innerHTML = data.html;
                    attachActionListeners();
                } else {
                    window.location.reload();
                }
            } else {
                showNotification('Gagal menambahkan sub-kompetisi', 'error');
                
                if (data.errors) {
                    const errorList = document.getElementById('add-sub-competition-error-list');
                    let errorCount = 0;
                    
                    for (const field in data.errors) {
                        data.errors[field].forEach(message => {
                            const li = document.createElement('li');
                            li.textContent = message;
                            errorList.appendChild(li);
                            errorCount++;
                        });
                        
                        const fieldId = 'sub-' + field.replace('_', '-') + '-error';
                        const fieldError = document.getElementById(fieldId);
                        if (fieldError) {
                            fieldError.textContent = data.errors[field][0];
                            fieldError.classList.remove('hidden');
                        }
                    }
                    
                    document.getElementById('add-sub-competition-error').classList.remove('hidden');
                    document.getElementById('add-sub-competition-error-count').textContent = errorCount;
                    
                    const step1Fields = ['name', 'category_id'];
                    const hasStep1Errors = step1Fields.some(field => data.errors && data.errors[field]);
                    
                    if (hasStep1Errors) {
                        showStep(1);
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error adding sub-competition:', error);
            showNotification('Terjadi kesalahan saat menambahkan sub-kompetisi', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    }
    
    // Helper to format date as YYYY-MM-DD for date inputs
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        // If already formatted
        if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            return dateString;
        }
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // Load sub-competition data for editing and open modal
    window.editSubCompetition = function(id) {
        const modal = document.getElementById('edit-sub-competition-modal');
        if (!modal) return;

        modal.classList.remove('hidden');

        const skeletonEls = modal.querySelectorAll('.edit-sub-competition-skeleton');
        const contentEls = modal.querySelectorAll('.edit-sub-competition-content');

        skeletonEls.forEach(el => el.classList.remove('hidden'));
        contentEls.forEach(el => el.classList.add('hidden'));

        const url = window.subCompetitionRoutes.show.replace('__id__', id);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error('Gagal memuat data');

            const sc = data.data;

            document.getElementById('edit_id').value = sc.id;
            document.getElementById('edit_name').value = sc.name ?? '';
            if (document.getElementById('edit_category_id')) {
                document.getElementById('edit_category_id').value = sc.category_id ?? '';
            }
            document.getElementById('edit_description').value = sc.description ?? '';
            document.getElementById('edit_start_date').value = formatDateForInput(sc.start_date);
            document.getElementById('edit_end_date').value = formatDateForInput(sc.end_date);
            document.getElementById('edit_registration_start').value = formatDateForInput(sc.registration_start);
            document.getElementById('edit_registration_end').value = formatDateForInput(sc.registration_end);
            document.getElementById('edit_competition_date').value = formatDateForInput(sc.competition_date);
            document.getElementById('edit_status').value = sc.status ?? 'upcoming';
            document.getElementById('edit_registration_link').value = sc.registration_link || '';
            document.getElementById('edit_requirements').value = sc.requirements || '';

            // Hide skeleton, show content
            skeletonEls.forEach(el => el.classList.add('hidden'));
            contentEls.forEach(el => el.classList.remove('hidden'));

            showStep(1, 'edit');
            window.updateSubCompetitionStatus('edit');
        })
        .catch(err => {
            console.error(err);
            showNotification('Terjadi kesalahan saat memuat data', 'error');
            modal.classList.add('hidden');
        });
    };

    // Submit edit sub-competition via AJAX
    function submitEditSubCompetition() {
        const form = document.getElementById('edit-sub-competition-form');
        if (!form) return;

        const id = document.getElementById('edit_id').value;
        if (!id) return;

        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        const submitBtn = document.getElementById('submit-edit-sub-competition');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline-block text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

        fetch(window.subCompetitionRoutes.update.replace('__id__', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
            if (data.success) {
                document.getElementById('edit-sub-competition-modal').classList.add('hidden');
                showNotification('Sub-kompetisi berhasil diperbarui', 'success');
                window.location.reload();
            } else {
                throw data;
            }
        })
        .catch(err => {
            console.error('Update error', err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
            if (err && err.errors) {
                displayValidationErrors(err.errors, 'edit-sub-competition-form');
            } else {
                showNotification('Terjadi kesalahan saat memperbarui data', 'error');
            }
        });
    }
    
    // Function to display validation errors on the form.
    function displayValidationErrors(errors, formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const errorAlert = document.getElementById(`${formId}-error`);
        const errorList = document.getElementById(`${formId}-error-list`);
        const errorCount = document.getElementById(`${formId}-error-count`);
        
        if (!errorAlert || !errorList || !errorCount) return;
        
        errorList.innerHTML = '';
        let count = 0;
        
        for (const field in errors) {
            errors[field].forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
                count++;
                
                let inputId;
                if (formId === 'add-sub-competition-form') {
                    inputId = `sub-${field.replace('_', '-')}-error`;
                } else if (formId === 'edit-sub-competition-form') {
                    inputId = `edit-sub-${field.replace('_', '-')}-error`;
                } else {
                    inputId = `${field.replace('_', '-')}-error`;
                }
                
                const fieldError = document.getElementById(inputId);
                if (fieldError) {
                    fieldError.textContent = message;
                    fieldError.classList.remove('hidden');
                }
            });
        }
        
        errorCount.textContent = count;
        errorAlert.classList.remove('hidden');
    }
    
    // Function to reset error messages on the form.
    function resetFormErrors(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const errorAlert = document.getElementById(`${formId}-error`);
        if (errorAlert) errorAlert.classList.add('hidden');
        
        const errorList = document.getElementById(`${formId}-error-list`);
        if (errorList) errorList.innerHTML = '';
        
        form.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }
    
    // Function to display notifications to the user.
    function showNotification(message, type = 'success') {
        const notificationContainer = document.getElementById('notification-container');
        
        if (!notificationContainer) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 flex flex-col items-end space-y-2';
            document.body.appendChild(container);
        }
        
        const notification = document.createElement('div');
        notification.className = `transform transition-all duration-300 ease-out translate-x-full opacity-0 flex items-center p-4 rounded-lg shadow-lg max-w-sm ${type === 'success' ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500'}`;
        
        const iconColor = type === 'success' ? 'text-green-500' : 'text-red-500';
        const iconPath = type === 'success' 
            ? 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z'
            : 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z';
        
        notification.innerHTML = `
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 ${iconColor}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button type="button" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
        
        document.getElementById('notification-container').appendChild(notification);
        
        notification.querySelector('button').addEventListener('click', function() {
            closeNotification(notification);
        });
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 10);
        
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
        
        function closeNotification(notif) {
            notif.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                notif.remove();
            }, 300);
        }
    }
    
    // Function to automatically update sub-competition statuses based on dates.
    function autoUpdateSubCompetitionStatuses() {
        const subCompetitionRows = document.querySelectorAll('[data-sub-competition-id]');
        
        subCompetitionRows.forEach(row => {
            const startDate = row.dataset.startDate ? new Date(row.dataset.startDate) : null;
            const endDate = row.dataset.endDate ? new Date(row.dataset.endDate) : null;
            
            if (!startDate || !endDate) return;
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            let status = 'upcoming';
            
            if (today < startDate) {
                status = 'upcoming';
            } else if (today >= startDate && today <= endDate) {
                status = 'ongoing';
            } else if (today > endDate) {
                status = 'completed';
            }
            
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.textContent = getStatusText(status);
                statusBadge.className = `status-badge px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(status)}`;
            }
        });
    }
    
    // Function to get the text representation of a status.
    function getStatusText(status) {
        const statusTexts = {
            'upcoming': 'Akan Datang',
            'ongoing': 'Sedang Berlangsung',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan'
        };
        
        return statusTexts[status] || status;
    }
    
    // Function to get the CSS class for a status badge.
    function getStatusClass(status) {
        const statusClasses = {
            'upcoming': 'bg-yellow-100 text-yellow-800',
            'ongoing': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        
        return statusClasses[status] || 'bg-gray-100 text-gray-800';
    }
    
    // Function to attach action listeners to buttons.
    function attachActionListeners() {
        document.querySelectorAll('.show-sub-competition').forEach(button => {
            button.addEventListener('click', function() {
                const subCompetitionId = this.dataset.subCompetitionId;
                if (subCompetitionId) {
                    // Show the modal instead of navigating
                    const modal = document.getElementById('show-sub-competition-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        
                        // Show loading state
                        document.querySelector('.sub-competition-detail-skeleton').classList.remove('hidden');
                        document.querySelectorAll('.sub-competition-detail-content').forEach(el => el.classList.add('hidden'));
                        
                        // Fetch the sub-competition details using AJAX
                        let showUrl = window.subCompetitionRoutes.show.replace('__id__', subCompetitionId);
                        
                        fetch(showUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data) {
                                populateSubCompetitionModal(data.data);
                            } else {
                                showNotification('Gagal memuat data sub-kompetisi', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching sub-competition:', error);
                            showNotification('Terjadi kesalahan saat memuat data sub-kompetisi', 'error');
                        })
                        .finally(() => {
                            // Hide loading state
                            document.querySelector('.sub-competition-detail-skeleton').classList.add('hidden');
                            document.querySelectorAll('.sub-competition-detail-content').forEach(el => el.classList.remove('hidden'));
                        });
                    }
                }
            });
        });
        
        document.querySelectorAll('.edit-sub-competition').forEach(button => {
            button.addEventListener('click', function() {
                const subCompetitionId = this.dataset.subCompetitionId;
                if (subCompetitionId) {
                    editSubCompetition(subCompetitionId);
                }
            });
        });
    }
    
    // Function to populate the sub-competition modal with data
    function populateSubCompetitionModal(subCompetition) {
        // Populate basic information
        document.getElementById('sub-competition-name').textContent = subCompetition.name;
        document.getElementById('sub-competition-id').textContent = subCompetition.id;
        
        // Set status with appropriate class
        const statusElement = document.getElementById('sub-competition-status');
        statusElement.textContent = getStatusText(subCompetition.status);
        statusElement.className = `px-3 py-1 text-xs leading-5 font-semibold rounded-full ${getStatusClass(subCompetition.status)}`;
        
        // Set category
        document.getElementById('sub-competition-category').textContent = 
            subCompetition.category ? subCompetition.category.name : 'Tidak ada kategori';
        document.getElementById('sub-competition-category-detail').textContent = 
            subCompetition.category ? subCompetition.category.name : 'Tidak ada kategori';
        
        // Set dates
        const startDate = subCompetition.start_date ? new Date(subCompetition.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
        const endDate = subCompetition.end_date ? new Date(subCompetition.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
        document.getElementById('sub-competition-dates').textContent = `${startDate} - ${endDate}`;
        
        // Set registration dates
        const regStartDate = subCompetition.registration_start ? new Date(subCompetition.registration_start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
        const regEndDate = subCompetition.registration_end ? new Date(subCompetition.registration_end).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
        document.getElementById('sub-competition-registration').textContent = `${regStartDate} - ${regEndDate}`;
        
        // Set competition date
        const competitionDate = subCompetition.competition_date ? new Date(subCompetition.competition_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
        document.getElementById('sub-competition-date').textContent = competitionDate;
        
        // Set description
        document.getElementById('sub-competition-description').textContent = subCompetition.description || 'Tidak ada deskripsi';
        
        // Set participants count
        document.getElementById('sub-competition-participants').textContent = 
            subCompetition.participants ? subCompetition.participants.length : '0';
        
        // Set skills
        const skillsContainer = document.getElementById('sub-competition-skills').querySelector('.flex');
        skillsContainer.innerHTML = '';
        
        if (subCompetition.skills && subCompetition.skills.length > 0) {
            subCompetition.skills.forEach(skill => {
                const skillElement = document.createElement('span');
                skillElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                skillElement.textContent = skill.name;
                skillsContainer.appendChild(skillElement);
            });
        } else {
            const noSkillElement = document.createElement('span');
            noSkillElement.className = 'text-gray-500 text-sm';
            noSkillElement.textContent = 'Tidak ada skill yang diperlukan';
            skillsContainer.appendChild(noSkillElement);
        }
        
        // Populate student preview section
        document.getElementById('sub-competition-name-preview').textContent = subCompetition.name;
        
        const statusPreviewElement = document.getElementById('sub-competition-status-preview');
        statusPreviewElement.textContent = getStatusText(subCompetition.status);
        statusPreviewElement.className = `px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(subCompetition.status)}`;
        
        document.getElementById('sub-competition-category-preview').textContent = 
            subCompetition.category ? subCompetition.category.name : 'Tidak ada kategori';
        document.getElementById('sub-competition-description-preview').textContent = 
            subCompetition.description || 'Tidak ada deskripsi';
        document.getElementById('sub-competition-dates-preview').textContent = `${startDate} - ${endDate}`;
        document.getElementById('sub-competition-registration-preview').textContent = `Pendaftaran: ${regStartDate} - ${regEndDate}`;
        
        const skillsPreviewContainer = document.getElementById('sub-competition-skills-preview');
        skillsPreviewContainer.innerHTML = '';
        
        if (subCompetition.skills && subCompetition.skills.length > 0) {
            subCompetition.skills.forEach(skill => {
                const skillElement = document.createElement('span');
                skillElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                skillElement.textContent = skill.name;
                skillsPreviewContainer.appendChild(skillElement);
            });
        } else {
            const noSkillElement = document.createElement('span');
            noSkillElement.className = 'text-gray-500 text-sm';
            noSkillElement.textContent = 'Tidak ada skill yang diperlukan';
            skillsPreviewContainer.appendChild(noSkillElement);
        }
    }
}); 