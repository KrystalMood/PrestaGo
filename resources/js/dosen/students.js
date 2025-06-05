document.addEventListener('DOMContentLoaded', function() {

    initializeEventListeners();
    /**
     * Initializes all event listeners for achievement operations.
     */
    function initializeEventListeners() {
        initializeShowStudent();
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

        const url = `/lecturer/students/${studentId}?jenis=${encodeURIComponent(studentJenis)}`;

        fetch(url)
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

        // Status
        const statusElement = document.getElementById('show-student-status');
        let statusText = '-';
        let statusClass = 'bg-gray-100 text-gray-800';
        if (student.status === 'registered') {
            statusText = 'Terverifikasi';
            statusClass = 'bg-green-100 text-green-800';
        } else if (student.status === 'on going') {
            statusText = 'Menunggu';
            statusClass = 'bg-amber-100 text-amber-800';
        } else if (student.status === 'rejected') {
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
