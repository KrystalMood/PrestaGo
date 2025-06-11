document.addEventListener('DOMContentLoaded', function() {

    initializeEventListeners();
    /**
     * Initializes all event listeners for achievement operations.
     */
    function initializeEventListeners() {
        initializeShowStudent();
        initializeDeleteActions();
    }
    
    /**
     * Initializes event listeners for showing student details.
     */
    function initializeShowStudent() {
        const showButtons = document.querySelectorAll('.show-student');
        const showModal = document.getElementById('show-student-modal');
        const closeButtons = document.querySelectorAll('#close-show-modal, #close-show-student-btn');

        showButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const studentId = this.getAttribute('data-student-id');
                const studentJenis = this.getAttribute('data-student-jenis');
                if (studentId) {
                    console.log('studentJenis:', studentJenis);
                    fetchStudentDetails(studentId, studentJenis);
                    showModal.classList.remove('hidden');
                }
                showModal.classList.remove('hidden');
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                showModal.classList.add('hidden');
            });
        });

        showModal.addEventListener('click', function(e) {
            if (e.target === showModal) {
                showModal.classList.add('hidden');
            }
        });
    }

    /**
     * Initializes AJAX delete actions for student records
     */
    function initializeDeleteActions() {
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('button[data-delete-id]');
            if (!deleteBtn) return;
            
            e.preventDefault();
            
            const studentId = deleteBtn.getAttribute('data-delete-id');
            const studentJenis = deleteBtn.getAttribute('data-jenis');
            
            // Create confirmation modal
            const confirmModal = createConfirmationModal(
                'Konfirmasi Hapus Data',
                'Apakah Anda yakin ingin menghapus data mahasiswa ini? Tindakan ini tidak dapat dibatalkan.',
                () => deleteStudent(studentId, studentJenis)
            );
            
            document.body.appendChild(confirmModal);
            confirmModal.classList.remove('hidden');
        });
    }
    
    /**
     * Creates a confirmation modal
     * @param {string} title - Modal title
     * @param {string} message - Modal message
     * @param {Function} confirmCallback - Function to execute on confirm
     * @returns {HTMLElement} - The modal element
     */
    function createConfirmationModal(title, message, confirmCallback) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">${title}</h3>
                        <button type="button" class="close-modal text-gray-400 hover:text-gray-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex justify-end space-x-3">
                        <button class="close-modal px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </button>
                        <button class="confirm-action px-4 py-2 border border-transparent rounded-md text-white bg-red-600 hover:bg-red-700">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add event listeners
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modal.classList.add('hidden');
                setTimeout(() => modal.remove(), 300);
            });
        });
        
        modal.querySelector('.confirm-action').addEventListener('click', () => {
            confirmCallback();
            modal.classList.add('hidden');
            setTimeout(() => modal.remove(), 300);
        });
        
        return modal;
    }
    
    /**
     * Deletes a student record via AJAX
     * @param {string} studentId - The ID of the student to delete
     * @param {string} studentJenis - The type of student (Kompetisi or Sub Kompetisi)
     */
    function deleteStudent(studentId, studentJenis) {
        const url = `/lecturer/students/${studentId}/delete?jenis=${encodeURIComponent(studentJenis)}`;
        
        // Get CSRF token from the hidden form
        const csrfToken = document.querySelector('#csrf-form input[name="_token"]').value;
        
        // Create a form data object
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', csrfToken);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Data mahasiswa tidak ditemukan');
                } else if (response.status === 403) {
                    throw new Error('Anda tidak memiliki akses untuk menghapus data ini');
                } else {
                    throw new Error(`Terjadi kesalahan: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            showNotification('Data mahasiswa berhasil dihapus', 'success');
            
            // Remove the row from the table
            const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
            if (row) {
                row.classList.add('fade-out');
                setTimeout(() => {
                    row.remove();
                    
                    // Check if there are no more rows and show empty message
                    const tbody = document.querySelector('#students-table tbody');
                    if (tbody && tbody.querySelectorAll('tr:not(.empty-row)').length === 0) {
                        const emptyRow = document.createElement('tr');
                        emptyRow.className = 'empty-row';
                        emptyRow.innerHTML = `
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-600 font-medium">Belum ada data mahasiswa yang dibimbing</p>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(emptyRow);
                    }
                }, 300);
            }
            
            // Update counters if they exist
            updateStudentCounters();
        })
        .catch(error => {
            console.error('Error deleting student:', error);
            showNotification(error.message || 'Terjadi kesalahan saat menghapus data mahasiswa', 'error');
        });
    }
    
    /**
     * Updates the student counters on the page
     */
    function updateStudentCounters() {
        // Count the number of rows in the table
        const rows = document.querySelectorAll('#students-table tbody tr:not(.empty-row)');
        const totalCount = rows.length;
        
        // Update the total counter if it exists
        const totalCounter = document.querySelector('[data-key="totalStudents"] .counter-value');
        if (totalCounter) {
            totalCounter.textContent = totalCount;
        }
        
        // Count and update other counters
        const statusCounts = {
            onGoingStudents: 0,
            rejectedStudents: 0,
            regristedStudents: 0
        };
        
        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(6) span');
            if (statusCell) {
                const statusText = statusCell.textContent.trim().toLowerCase();
                if (statusText.includes('menunggu')) {
                    statusCounts.onGoingStudents++;
                } else if (statusText.includes('ditolak')) {
                    statusCounts.rejectedStudents++;
                } else if (statusText.includes('terima')) {
                    statusCounts.regristedStudents++;
                }
            }
        });
        
        // Update each counter
        Object.keys(statusCounts).forEach(key => {
            const counter = document.querySelector(`[data-key="${key}"] .counter-value`);
            if (counter) {
                counter.textContent = statusCounts[key];
            }
        });
    }
    
    /**
     * Fetches student details from the server via AJAX.
     * @param {string} studentId - The ID of the student to fetch.
     */
    function fetchStudentDetails(studentId, studentJenis) {
        if (!studentId || studentId.trim() === '') {
            console.error('Invalid student ID');
            showNotification('ID mahasiswa tidak valid', 'error');
            return;
        }
        if (!studentJenis || !['Kompetisi', 'Sub Kompetisi'].includes(studentJenis)) {
            console.error('Invalid student jenis');
            showNotification('Jenis mahasiswa tidak valid', 'error');
            return;
        }

        const showModal = document.getElementById('show-student-modal');
        const showContent = document.getElementById('show-student-content');
        const showSkeleton = document.getElementById('show-student-skeleton');

        showModal.classList.remove('hidden');
        if (showContent) showContent.classList.add('hidden');
        if (showSkeleton) showSkeleton.classList.remove('hidden');

        // Get CSRF token from the hidden form
        const csrfToken = document.querySelector('#csrf-form input[name="_token"]').value;
        
        // Use the correct route for AJAX requests
        const url = `/lecturer/students/${studentId}?jenis=${encodeURIComponent(studentJenis)}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Mahasiswa tidak ditemukan');
                } else if (response.status === 403) {
                    throw new Error('Anda tidak memiliki akses ke data ini');
                } else {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success === false) {
                throw new Error(data.message || 'Terjadi kesalahan saat memuat data mahasiswa');
            }
            displayStudentDetails(data);
            if (showContent) showContent.classList.remove('hidden');
            if (showSkeleton) showSkeleton.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error fetching student details:', error);
            let errorMessage = 'Terjadi kesalahan saat mengambil data mahasiswa.';
            if (error.message) {
                errorMessage += ' ' + error.message;
            }
            showNotification(errorMessage, 'error');
            showModal.classList.add('hidden');
            if (showContent) showContent.classList.remove('hidden');
            if (showSkeleton) showSkeleton.classList.add('hidden');
        });
    }
    
    /**
     * Displays the fetched achievement details in the show modal.
     * @param {Object} data - The achievement data object from the server.
     */
    function displayStudentDetails(data) {
        if (!data || typeof data !== 'object') {
            showNotification('Terjadi kesalahan: Format data tidak valid', 'error');
            return;
        }

        const student = data.student;
        console.log('Student data:', student);

        if (!student || typeof student !== 'object') {
            showNotification('Format data mahasiswa tidak valid', 'error');
            return;
        }

        // Header
        document.getElementById('show-student-name-header').textContent = student.nama || '-';
        document.getElementById('show-student-competition-header').textContent = student.kompetisi || '-';

        // Team Name (if available)
        const teamNameContainer = document.getElementById('show-student-team-name-container');
        if (teamNameContainer) {
            if (student.team_name && student.team_name !== '-') {
                teamNameContainer.classList.remove('hidden');
                document.getElementById('show-student-team-name').textContent = student.team_name;
            } else {
                teamNameContainer.classList.add('hidden');
            }
        }

        // Status - simplified for lecturers (no approval functionality)
        const statusElement = document.getElementById('show-student-status');
        let statusText = '-';
        let statusClass = 'bg-gray-100 text-gray-800';
        
        if (student.status_mentor === 'accept') {
            statusText = 'Diterima';
            statusClass = 'bg-green-100 text-green-800';
        } else if (student.status_mentor === 'pending') {
            statusText = 'Menunggu';
            statusClass = 'bg-amber-100 text-amber-800';
        } else if (student.status_mentor === 'reject') {
            statusText = 'Ditolak';
            statusClass = 'bg-red-100 text-red-800';
        } else if (student.status) {
            statusText = student.status;
        }
        
        statusElement.textContent = statusText;
        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + statusClass;

        // Level
        const levelElement = document.getElementById('show-student-level');
        let levelText = '-';
        let levelClass = 'bg-gray-100 text-gray-800';
        if (student.level === 'international') {
            levelText = 'Internasional';
            levelClass = 'bg-red-100 text-red-800';
        } else if (student.level === 'national') {
            levelText = 'Nasional';
            levelClass = 'bg-blue-100 text-blue-800';
        } else if (student.level === 'regional') {
            levelText = 'Regional';
            levelClass = 'bg-green-100 text-green-800';
        } else if (student.level) {
            levelText = student.level;
        }
        levelElement.textContent = levelText;
        levelElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + levelClass;

        // NIM
        document.getElementById('show-student-nim').textContent = student.nim || '-';

        // Competition (di detail)
        document.getElementById('show-student-competition').textContent = student.kompetisi || '-';

        // Advisor: Tampilkan jika data ada, sembunyikan jika tidak
        const advisorContainer = document.getElementById('show-student-advisor-container');
        if (advisorContainer) {
            if (student.advisor && student.advisor !== '-') {
                advisorContainer.classList.remove('hidden');
                document.getElementById('show-student-advisor').textContent = student.advisor;
            } else {
                advisorContainer.classList.add('hidden');
            }
        }

        // Catatan: Tampilkan jika data ada, sembunyikan jika tidak
        const notesContainer = document.getElementById('show-student-notes-container');
        if (notesContainer) {
            if (student.notes && student.notes !== '-') {
                notesContainer.classList.remove('hidden');
                document.getElementById('show-student-notes').textContent = student.notes;
            } else {
                notesContainer.classList.add('hidden');
            }
        }

        // Team Members: Tampilkan jika data ada, sembunyikan jika tidak
        const membersContainer = document.getElementById('show-student-members-container');
        const membersList = document.getElementById('show-student-members');

        // Leader (Ketua Tim)
        const leaderList = document.getElementById('show-leader-members');
        if (leaderList) {
            leaderList.innerHTML = '';
            const li = document.createElement('li');
            li.className = 'flex flex-row space-x-6';
            li.innerHTML = `
                <span class="inline-block w-48 truncate font-semibold">${student.nama || '-'}</span>
                <span>${student.nim || '-'}</span>
            `;
            leaderList.appendChild(li);
        }

        if (Array.isArray(student.team_members) && student.team_members.length > 0) {
            membersContainer.classList.remove('hidden');
            membersList.innerHTML = '';
            student.team_members.forEach(member => {
                const li = document.createElement('li');
                li.className = 'flex flex-row space-x-6';
                li.innerHTML = `
                    <span class="inline-block w-48 truncate">${member.name || '-'}</span>
                    <span>${member.nim || '-'}</span>
                `;
                membersList.appendChild(li);
            });
        } else {
            membersContainer.classList.add('hidden');
            membersList.innerHTML = '';
        }

        // Tampilkan modal
        document.getElementById('show-student-modal').classList.remove('hidden');
    }

    function showNotification(message, type) {
        let container = document.getElementById('notification-container');
        
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-4 max-w-md';
            document.body.appendChild(container);
        }
        
        const notification = document.createElement('div');
        notification.className = `p-4 rounded-lg shadow-lg flex items-start gap-3 transform translate-x-full transition-transform duration-300 ease-out ${type === 'success' ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500'}`;
        
        notification.innerHTML = `
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    ${ '<!-- SVG Path Placeholder -->' }
                </svg>
            </div>
            <div class="ml-3 flex-1 break-words">
                <p class="text-sm ${type === 'success' ? 'text-green-700' : 'text-red-700'}">${message}</p>
            </div>
            <div class="flex-shrink-0 flex ml-2">
                <button class="inline-flex text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);
        
        const closeButton = notification.querySelector('button');
        closeButton.addEventListener('click', () => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
});
