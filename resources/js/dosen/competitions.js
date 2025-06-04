document.addEventListener('DOMContentLoaded', function() {
    const competitionRoutes = window.competitionRoutes || {};
    const csrfToken = window.csrfToken || '';

    setupCompetitionModals();
    attachPaginationHandlers();

    // Function to initialize and set up event listeners for competition modals.
    function setupCompetitionModals() {
        window.showCompetitionModal = document.getElementById('show-competition-modal');
        window.addCompetitionModal = document.getElementById('add-competition-modal');
        
        attachShowButtonListeners();
        attachAddCompetitionListeners();
    }

    // Function to attach event listeners to the "View" buttons for competitions.
    function attachShowButtonListeners() {
        document.querySelectorAll('.show-competition').forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.dataset.competitionId;
                loadCompetitionForView(competitionId);
            });
        });
        
        document.getElementById('close-show-modal')?.addEventListener('click', function() {
            window.showCompetitionModal.classList.add('hidden');
        });
        
        document.getElementById('close-show-competition')?.addEventListener('click', function() {
            window.showCompetitionModal.classList.add('hidden');
        });
    }

    // Function to attach event listeners for the add competition modal
    function attachAddCompetitionListeners() {
        document.getElementById('open-add-competition-modal')?.addEventListener('click', function() {
            if (window.addCompetitionModal) {
                window.addCompetitionModal.classList.remove('hidden');
                resetAddCompetitionForm();
            }
        });
        
        document.getElementById('close-add-modal')?.addEventListener('click', function() {
            window.addCompetitionModal.classList.add('hidden');
        });
        
        document.getElementById('cancel-add-competition')?.addEventListener('click', function() {
            window.addCompetitionModal.classList.add('hidden');
        });
        
        document.getElementById('next-step')?.addEventListener('click', function() {
            const step1 = document.getElementById('step-1-content');
            const step2 = document.getElementById('step-2-content');
            const nextBtn = document.getElementById('next-step');
            const prevBtn = document.getElementById('prev-step');
            const submitBtn = document.getElementById('submit-add-competition');
            
            if (!validateStep1()) {
                return;
            }
            
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            document.querySelectorAll('.step-item')[1].classList.add('active');
            document.querySelectorAll('.step-item')[1].querySelector('div').classList.remove('bg-gray-200', 'text-gray-600');
            document.querySelectorAll('.step-item')[1].querySelector('div').classList.add('bg-blue-600', 'text-white');
            document.querySelector('.step-line').classList.add('bg-blue-600');
            
            nextBtn.classList.add('hidden');
            prevBtn.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
        });
        
        document.getElementById('prev-step')?.addEventListener('click', function() {
            const step1 = document.getElementById('step-1-content');
            const step2 = document.getElementById('step-2-content');
            const nextBtn = document.getElementById('next-step');
            const prevBtn = document.getElementById('prev-step');
            const submitBtn = document.getElementById('submit-add-competition');
            
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
            
            document.querySelectorAll('.step-item')[1].classList.remove('active');
            document.querySelectorAll('.step-item')[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
            document.querySelectorAll('.step-item')[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
            document.querySelector('.step-line').classList.remove('bg-blue-600');
            
            nextBtn.classList.remove('hidden');
            prevBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');
        });
        
        document.getElementById('add-competition-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAddCompetitionForm();
        });
        
        document.getElementById('submit-add-competition')?.addEventListener('click', function() {
            document.getElementById('add-competition-form').dispatchEvent(new Event('submit'));
        });
    }

    // Function to validate step 1 of the add competition form
    function validateStep1() {
        const name = document.getElementById('add-name').value;
        const organizer = document.getElementById('add-organizer').value;
        const period = document.getElementById('add-period').value;
        const level = document.getElementById('add-level').value;
        
        let isValid = true;
        
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        if (!name.trim()) {
            document.getElementById('name-error').textContent = 'Nama kompetisi wajib diisi';
            document.getElementById('name-error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!organizer.trim()) {
            document.getElementById('organizer-error').textContent = 'Penyelenggara wajib diisi';
            document.getElementById('organizer-error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!period) {
            document.getElementById('period-id-error').textContent = 'Periode wajib dipilih';
            document.getElementById('period-id-error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!level) {
            document.getElementById('level-error').textContent = 'Level kompetisi wajib dipilih';
            document.getElementById('level-error').classList.remove('hidden');
            isValid = false;
        }
        
        return isValid;
    }

    // Function to reset the add competition form
    function resetAddCompetitionForm() {
        document.getElementById('add-competition-form').reset();
        
        document.querySelectorAll('.step-item')[1].classList.remove('active');
        document.querySelectorAll('.step-item')[1].querySelector('div').classList.add('bg-gray-200', 'text-gray-600');
        document.querySelectorAll('.step-item')[1].querySelector('div').classList.remove('bg-blue-600', 'text-white');
        document.querySelector('.step-line').classList.remove('bg-blue-600');
        
        document.getElementById('step-1-content').classList.remove('hidden');
        document.getElementById('step-2-content').classList.add('hidden');
        
        document.getElementById('next-step').classList.remove('hidden');
        document.getElementById('prev-step').classList.add('hidden');
        document.getElementById('submit-add-competition').classList.add('hidden');
        
        document.getElementById('add-competition-error').classList.add('hidden');
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    // Function to submit the add competition form
    function submitAddCompetitionForm() {
        const form = document.getElementById('add-competition-form');
        const formData = new FormData(form);
        
        const submitBtn = document.getElementById('submit-add-competition');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        document.getElementById('add-competition-error').classList.add('hidden');
        document.getElementById('add-competition-error-list').innerHTML = '';
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        fetch(competitionRoutes.store, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            
            if (data.success) {
                showNotification('Kompetisi berhasil ditambahkan', 'success');
                
                window.addCompetitionModal.classList.add('hidden');
                
                window.location.reload();
            } else {
                showNotification('Gagal menambahkan kompetisi', 'error');
                
                if (data.errors) {
                    const errorList = document.getElementById('add-competition-error-list');
                    let errorCount = 0;
                    
                    for (const field in data.errors) {
                        data.errors[field].forEach(message => {
                            const li = document.createElement('li');
                            li.textContent = message;
                            errorList.appendChild(li);
                            errorCount++;
                        });
                        
                        const fieldId = field.replace('_', '-') + '-error';
                        const fieldError = document.getElementById(fieldId);
                        if (fieldError) {
                            fieldError.textContent = data.errors[field][0];
                            fieldError.classList.remove('hidden');
                        }
                    }
                    
                    document.getElementById('add-competition-error').classList.remove('hidden');
                    document.getElementById('add-competition-error-count').textContent = errorCount;
                    
                    const step1Fields = ['name', 'organizer', 'period_id', 'level'];
                    const hasStep1Errors = step1Fields.some(field => data.errors && data.errors[field]);
                    
                    if (hasStep1Errors) {
                        document.getElementById('prev-step').click();
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error adding competition:', error);
            showNotification('Terjadi kesalahan saat menambahkan kompetisi', 'error');
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    }

    // Function to load competition data for viewing in the modal.
    function loadCompetitionForView(competitionId) {
        if (!window.showCompetitionModal) return;
        
        window.showCompetitionModal.classList.remove('hidden');
        document.querySelector('.competition-detail-skeleton').classList.remove('hidden');
        document.querySelector('.competition-detail-content').classList.add('hidden');
        
        const url = competitionRoutes.show.replace('__id__', competitionId);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.competition) {
                document.querySelector('.competition-detail-skeleton').classList.add('hidden');
                document.querySelector('.competition-detail-content').classList.remove('hidden');
                
                const competition = data.competition;
                
                document.getElementById('competition-name').textContent = competition.name || '';
                document.getElementById('competition-level').textContent = competition.level_formatted || '';
                
                updateLevelIcon(competition.level);
                
                document.getElementById('competition-id').textContent = competition.id || '';
                document.getElementById('competition-organizer').textContent = competition.organizer || '';
                
                if (competition.period) {
                    document.getElementById('competition-period').textContent = competition.period.name || '';
                } else {
                    document.getElementById('competition-period').textContent = '-';
                }
                
                const statusEl = document.getElementById('competition-status');
                if (statusEl) {
                    statusEl.textContent = getStatusText(competition.status);
                    statusEl.className = `px-3 py-1 text-sm font-semibold rounded-full ${getStatusClass(competition.status)}`;
                }
                
                const startDate = competition.start_date ? new Date(competition.start_date) : null;
                const endDate = competition.end_date ? new Date(competition.end_date) : null;
                
                if (startDate && endDate) {
                    const formatDate = (date) => {
                        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    };
                    
                    document.getElementById('competition-dates').textContent = `${formatDate(startDate)} - ${formatDate(endDate)}`;
                } else {
                    document.getElementById('competition-dates').textContent = '-';
                }
                
                const regStartDate = competition.registration_start_date ? new Date(competition.registration_start_date) : null;
                const regEndDate = competition.registration_end_date ? new Date(competition.registration_end_date) : null;
                
                if (regStartDate && regEndDate) {
                    const formatDate = (date) => {
                        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    };
                    
                    document.getElementById('competition-registration').textContent = `${formatDate(regStartDate)} - ${formatDate(regEndDate)}`;
                } else {
                    document.getElementById('competition-registration').textContent = '-';
                }
                
                if (competition.competition_date) {
                    const compDate = new Date(competition.competition_date);
                    document.getElementById('competition-date').textContent = compDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                } else {
                    document.getElementById('competition-date').textContent = '-';
                }
                
                document.getElementById('competition-description').innerHTML = competition.description || '-';
                
                if (competition.updated_at) {
                    const updatedAt = new Date(competition.updated_at);
                    document.getElementById('show-competition-updated-at').textContent = updatedAt.toLocaleString('id-ID');
                } else {
                    document.getElementById('show-competition-updated-at').textContent = '-';
                }
            } else {
                console.error('Failed to load competition data:', data);
                showNotification('Gagal memuat data kompetisi', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading competition:', error);
            showNotification('Terjadi kesalahan saat memuat data kompetisi', 'error');
        });
    }

    // Function to update the level icon based on competition level.
    function updateLevelIcon(level) {
        const iconContainer = document.getElementById('level-icon-container');
        const icon = document.getElementById('level-icon');
        
        if (!iconContainer || !icon) return;
        
        iconContainer.className = 'h-24 w-24 rounded-full overflow-hidden flex items-center justify-center shadow-md';
        
        let bgClass = 'bg-indigo-100';
        let textClass = 'text-indigo-500';
        let svgPath = '';
        
        switch (level) {
            case 'international':
                bgClass = 'bg-purple-100';
                textClass = 'text-purple-500';
                svgPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                break;
            case 'national':
                bgClass = 'bg-indigo-100';
                textClass = 'text-indigo-500';
                svgPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />';
                break;
            case 'regional':
                bgClass = 'bg-blue-100';
                textClass = 'text-blue-500';
                svgPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />';
                break;
            case 'provincial':
                bgClass = 'bg-teal-100';
                textClass = 'text-teal-500';
                svgPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
                break;
            case 'university':
                bgClass = 'bg-orange-100';
                textClass = 'text-orange-500';
                svgPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />';
                break;
            default:
                bgClass = 'bg-gray-100';
                textClass = 'text-gray-500';
                svgPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />';
        }
        
        iconContainer.classList.add(bgClass);
        icon.className = `h-12 w-12 ${textClass}`;
        icon.innerHTML = svgPath;
    }

    // Function to get the human-readable status text.
    function getStatusText(status) {
        const statusMap = {
            'upcoming': 'Akan Datang',
            'active': 'Aktif',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan'
        };
        
        return statusMap[status] || status;
    }

    // Function to get the CSS class for a status.
    function getStatusClass(status) {
        const statusClassMap = {
            'upcoming': 'bg-yellow-100 text-yellow-800',
            'active': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        
        return statusClassMap[status] || 'bg-gray-100 text-gray-800';
    }

    // Function to handle pagination.
    function attachPaginationHandlers() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                if (url) {
                    window.location.href = url;
                }
            });
        });
    }

    // Function to show a notification.
    function showNotification(message, type = 'success') {
        const notificationContainer = document.getElementById('notification-container');
        
        if (!notificationContainer) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 flex flex-col items-end space-y-2';
            document.body.appendChild(container);
        }
        
        const notification = document.createElement('div');
        notification.className = `notification transform transition-all duration-300 ease-out translate-x-full opacity-0 flex items-center p-4 rounded-lg shadow-lg max-w-md`;
        
        let bgColor, iconPath;
        
        switch (type) {
            case 'success':
                bgColor = 'bg-green-50 border-l-4 border-green-500';
                iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                break;
            case 'error':
                bgColor = 'bg-red-50 border-l-4 border-red-500';
                iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                break;
            case 'warning':
                bgColor = 'bg-yellow-50 border-l-4 border-yellow-500';
                iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
                break;
            case 'info':
                bgColor = 'bg-blue-50 border-l-4 border-blue-500';
                iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                break;
            default:
                bgColor = 'bg-gray-50 border-l-4 border-gray-500';
                iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
        }
        
        notification.classList.add(...bgColor.split(' '));
        
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 ${type === 'success' ? 'text-green-500' : type === 'error' ? 'text-red-500' : type === 'warning' ? 'text-yellow-500' : 'text-blue-500'}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        ${iconPath}
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium ${type === 'success' ? 'text-green-800' : type === 'error' ? 'text-red-800' : type === 'warning' ? 'text-yellow-800' : 'text-blue-800'}">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button type="button" class="close-notification inline-flex text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('notification-container').appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 10);
        
        notification.querySelector('.close-notification').addEventListener('click', function() {
            closeNotification(notification);
        });
        
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
        
        function closeNotification(notif) {
            notif.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                notif.remove();
            }, 300);
        }
    }
}); 