// Initializes the page once the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeModals();
    initializeFormHandlers();
    addTableButtonListeners();
});

// Function to initialize modal interactions (open/close)
function initializeModals() {
    const openAddModalBtn = document.getElementById('open-add-period-modal');
    if (openAddModalBtn) {
        openAddModalBtn.addEventListener('click', function() {
            document.getElementById('add-period-modal').classList.remove('hidden');
        });
    }

    document.getElementById('close-add-modal')?.addEventListener('click', function() {
        document.getElementById('add-period-modal').classList.add('hidden');
    });

    document.getElementById('close-edit-modal')?.addEventListener('click', function() {
        document.getElementById('edit-period-modal').classList.add('hidden');
    });

    document.getElementById('close-show-modal')?.addEventListener('click', function() {
        document.getElementById('show-period-modal').classList.add('hidden');
    });

    document.getElementById('cancel-add-period')?.addEventListener('click', function() {
        document.getElementById('add-period-modal').classList.add('hidden');
    });

    document.getElementById('cancel-edit-period')?.addEventListener('click', function() {
        document.getElementById('edit-period-modal').classList.add('hidden');
    });

    document.getElementById('cancel-show-period')?.addEventListener('click', function() {
        document.getElementById('show-period-modal').classList.add('hidden');
    });

    const modals = document.querySelectorAll('.fixed.inset-0');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                modal.classList.add('hidden');
            }
        });
    });
}

// Function to initialize form submission handlers
function initializeFormHandlers() {
    const addPeriodForm = document.getElementById('add-period-form');
    if (addPeriodForm) {
        addPeriodForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAddPeriodForm();
        });
    }

    const editPeriodForm = document.getElementById('edit-period-form');
    if (editPeriodForm) {
        editPeriodForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitEditPeriodForm();
        });
    }

    const deletePeriodForm = document.getElementById('delete-period-form');
    if (deletePeriodForm) {
        deletePeriodForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const periodId = document.getElementById('delete-period-id').value;
            if (periodId) {
                deletePeriod(periodId);
            }
        });
    }
}

// Function to handle the submission of the add period form
async function submitAddPeriodForm() {
    clearErrors('add-period-error');
    const form = document.getElementById('add-period-form');
    const formData = new FormData(form);

    try {
        const response = await fetch(window.periodRoutes.store, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            displayErrors('add-period-error', 'add-period-error-count', 'add-period-error-list', result.errors);
            return;
        }

        await refreshTable();
        document.getElementById('add-period-modal').classList.add('hidden');
        showNotification('Periode berhasil ditambahkan', 'success');
        
        form.reset();
    } catch (error) {
        console.error('Error submitting form:', error);
        showNotification('Terjadi kesalahan saat memproses data', 'error');
    }
}

// Function to handle the submission of the edit period form
async function submitEditPeriodForm() {
    clearErrors('edit-period-error');
    const form = document.getElementById('edit-period-form');
    const formData = new FormData(form);
    const periodId = document.getElementById('edit-period-id').value;

    try {
        const response = await fetch(window.periodRoutes.update(periodId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            displayErrors('edit-period-error', 'edit-period-error-count', 'edit-period-error-list', result.errors);
            return;
        }

        await refreshTable();
        document.getElementById('edit-period-modal').classList.add('hidden');
        showNotification('Periode berhasil diperbarui', 'success');
    } catch (error) {
        console.error('Error updating period:', error);
        showNotification('Terjadi kesalahan saat memperbarui data', 'error');
    }
}

// Function to handle the deletion of a period
async function deletePeriod(periodId) {
    try {
        const response = await fetch(window.periodRoutes.destroy(periodId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            const result = await response.json();
            throw new Error(result.message || 'Failed to delete period');
        }

        await refreshTable();
        document.getElementById('delete-period-modal').classList.add('hidden');
        showNotification('Periode berhasil dihapus', 'success');
    } catch (error) {
        console.error('Error deleting period:', error);
        showNotification('Terjadi kesalahan saat menghapus periode', 'error');
    }
}

// Function to refresh the period table with new data
async function refreshTable() {
    try {
        const url = new URL(window.periodRoutes.index);
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.forEach((value, key) => {
            url.searchParams.append(key, value);
        });
        url.searchParams.append('ajax', 'true');

        const response = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }

        const data = await response.json();

        document.getElementById('periods-table-container').innerHTML = data.table;
        document.getElementById('pagination-container').innerHTML = data.pagination;

        if (data.stats) {
            updateStats(data.stats);
        }

        addTableButtonListeners();

    } catch (error) {
        console.error('Error refreshing table:', error);
        showNotification('Terjadi kesalahan saat memuat data', 'error');
    }
}

// Function to update the statistics display
function updateStats(stats) {
    Object.keys(stats).forEach(key => {
        const element = document.getElementById(`stat-${key}`);
        if (element) {
            element.textContent = stats[key];
        }
    });
}

// Function to display a notification message
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-5 right-5 px-6 py-3 rounded-lg shadow-lg z-50 notification ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'}`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            ${type === 'success' 
                ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                   </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                   </svg>`
            }
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0');
        notification.style.transition = 'opacity 0.5s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Function to add event listeners to buttons within the table (view, edit, delete, toggle status)
function addTableButtonListeners() {
    document.querySelectorAll('.view-period-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const periodId = this.getAttribute('data-id');
            loadAndShowPeriodDetails(periodId);
        });
    });

    document.querySelectorAll('.edit-period-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const periodId = this.getAttribute('data-id');
            loadAndShowPeriodEdit(periodId);
        });
    });

    document.querySelectorAll('.delete-period-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const periodId = this.getAttribute('data-id');
            const periodName = this.getAttribute('data-name');
            
            document.getElementById('delete-period-id').value = periodId;
            
            document.dispatchEvent(new CustomEvent('delete-modal:show'));
        });
    });
}

// Function to load and display the details of a specific period in a modal
async function loadAndShowPeriodDetails(periodId) {
    try {
        const response = await fetch(window.periodRoutes.show(periodId), {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch period details');
        }

        const period = await response.json();
        
        console.log('Period data received (show):', period);

        document.getElementById('period-detail-name').textContent = period.name || '-';
        document.getElementById('period-detail-start-date').textContent = period.start_date ? formatDate(period.start_date) : '-';
        document.getElementById('period-detail-end-date').textContent = period.end_date ? formatDate(period.end_date) : '-';
        document.getElementById('show-period-description').textContent = period.description || 'Tidak ada keterangan.';

        const statusElement = document.getElementById('period-detail-status');
        statusElement.textContent = period.status === 'active' ? 'Aktif' : (period.status === 'upcoming' ? 'Akan Datang' : 'Selesai');
        statusElement.className = `px-2 py-1 text-xs rounded-full ${
            period.status === 'active' ? 'bg-green-100 text-green-800' :
            (period.status === 'upcoming' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
        }`;
        
        document.getElementById('show-period-updated-at').textContent = period.updated_at ? new Date(period.updated_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : '-';
        
        document.getElementById('show-period-modal').classList.remove('hidden');
        document.getElementById('show-period-modal').classList.add('flex');

    } catch (error) {
        console.error('Error loading period details:', error);
        showNotification('Terjadi kesalahan saat memuat detail periode', 'error');
    }
}

// Function to format a date string for display purposes (DD MMMM YYYY)
function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    } catch (e) {
        console.error('Error formatting date:', e);
        return dateString; // Return original if formatting fails
    }
}

// Function to load period data into the edit form and show the edit modal
async function loadAndShowPeriodEdit(periodId) {
    clearErrors('edit-period-error');
    
    try {
        const response = await fetch(window.periodRoutes.show(periodId), {
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch period details');
        }

        const period = await response.json();
        console.log('Period data received (edit):', period);

        document.getElementById('edit-period-id').value = period.id;
        document.getElementById('edit-name').value = period.name || '';
        document.getElementById('edit-start-date').value = formatDateForInput(period.start_date_raw);
        document.getElementById('edit-end-date').value = formatDateForInput(period.end_date_raw);
        
        document.getElementById('edit-period-modal').classList.remove('hidden');
        document.getElementById('edit-period-modal').classList.add('flex');

    } catch (error) {
        console.error('Error loading period for edit:', error);
        showNotification('Terjadi kesalahan saat memuat data periode', 'error');
    }
}

// Function to format a date string for input fields (YYYY-MM-DD)
function formatDateForInput(dateString) {
    if (!dateString) return '';
    
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) return dateString;
    
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

// Function to display validation errors on a form
function displayErrors(errorContainerId, errorCountId, errorListId, errors) {
    const errorContainer = document.getElementById(errorContainerId);
    const errorCountElement = document.getElementById(errorCountId);
    const errorList = document.getElementById(errorListId);
    
    if (!errorContainer || !errorCountElement || !errorList) return;
    
    errorList.innerHTML = '';
    
    if (!errors) {
        errorContainer.classList.add('hidden');
        return;
    }
    
    let errorCount = 0;
    
    Object.keys(errors).forEach(field => {
        errors[field].forEach(message => {
            const li = document.createElement('li');
            li.textContent = message;
            errorList.appendChild(li);
            errorCount++;
            
            const fieldElement = document.getElementById(`edit-${field}`) || document.getElementById(`add-${field}`);
            const errorMessageElement = document.getElementById(`${field}-error`);
            
            if (fieldElement) {
                fieldElement.classList.add('border-red-500');
            }
            
            if (errorMessageElement) {
                errorMessageElement.textContent = message;
                errorMessageElement.classList.remove('hidden');
            }
        });
    });
    
    errorCountElement.textContent = errorCount;
    errorContainer.classList.remove('hidden');
}

// Function to clear validation errors from a form
function clearErrors(errorContainerId) {
    const errorContainer = document.getElementById(errorContainerId);
    if (errorContainer) {
        errorContainer.classList.add('hidden');
    }
    
    document.querySelectorAll('.error-message').forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
    
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.classList.remove('border-red-500');
    });
}