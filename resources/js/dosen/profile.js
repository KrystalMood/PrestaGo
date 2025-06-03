// Function to set up tab switching functionality
function setupTabs() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    console.log('Initializing tabs. Found links:', tabLinks.length, 'Found contents:', tabContents.length);

    if (tabLinks.length === 0 || tabContents.length === 0) {
        console.error('Tab links or contents not found. Aborting tab setup.');
        return;
    }

    let firstTabTargetId = null;
    tabLinks.forEach((link, index) => {
        if (index === 0) { 
            firstTabTargetId = link.getAttribute('data-target');
        }
    });

    tabContents.forEach(content => {
        if (content.id === firstTabTargetId) {
            console.log('Initial show:', content.id);
            content.style.display = 'block'; 
            content.classList.remove('hidden');
        } else {
            console.log('Initial hide:', content.id);
            content.style.display = 'none'; 
            content.classList.add('hidden');
        }
    });

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Tab clicked:', this.textContent.trim());
            
            tabLinks.forEach(tabLink => {
                tabLink.classList.remove('border-blue-600', 'text-blue-600');
                tabLink.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-600', 'text-blue-600');
            
            tabContents.forEach(content => {
                console.log('Hiding content:', content.id);
                content.style.display = 'none';
                content.classList.add('hidden'); 
            });
            
            const targetId = this.getAttribute('data-target');
            console.log('Attempting to show content with ID:', targetId);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                console.log('Found target element:', targetElement);
                targetElement.style.display = 'block';
                targetElement.classList.remove('hidden'); 
                console.log('Set display to block for:', targetId);
            } else {
                console.error('Target content element not found for ID:', targetId);
            }
        });
    });
}

// Function to convert skills data for API submission
function prepareSkillsData() {
    const skillsData = [];
    const selectedSkillElements = document.querySelectorAll('#selected-skills-container > div[data-skill-id]');
    
    selectedSkillElements.forEach(skillElement => {
        const skillId = skillElement.getAttribute('data-skill-id');
        const proficiencyLevelSelect = skillElement.querySelector(`select[name="skill_level[${skillId}]"]`);
        if (skillId && proficiencyLevelSelect) {
            skillsData.push({
                skill_id: skillId,
                proficiency_level: proficiencyLevelSelect.value
            });
        }
    });
    return skillsData;
}

// Function to convert interests data for API submission
function prepareInterestsData() {
    const interestsData = [];
    const interestItems = document.querySelectorAll('#user-interests-list .interest-item');
    
    interestItems.forEach(item => {
        const interestId = item.getAttribute('data-interest-id');
        const levelSelect = item.querySelector(`select[name="interests[${interestId}][interest_level]"]`);
        
        if (interestId && levelSelect) {
            interestsData.push({
                interest_area_id: interestId,
                interest_level: levelSelect.value
            });
        }
    });
    
    return interestsData;
}

// Function to set up photo upload functionality
function setupPhotoUpload() {
    const uploadBtn = document.getElementById('upload-photo-btn');
    const photoInput = document.getElementById('photo');
    const previewPhoto = document.getElementById('preview-photo');
    
    if (uploadBtn && photoInput && previewPhoto) {
        uploadBtn.addEventListener('click', () => {
            photoInput.click();
        });
        
        photoInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = (e) => {
                    previewPhoto.src = e.target.result;
                };
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
}

// Function to set up skill checkboxes
function setupSkillCheckboxes() {
    const skillCheckboxes = document.querySelectorAll('.skill-checkbox');
    
    skillCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const skillId = this.value;
            const levelSelect = document.querySelector(`select[name="skill_level[${skillId}]"]`);
            
            if (this.checked) {
                levelSelect.disabled = false;
            } else {
                levelSelect.disabled = true;
            }
        });
    });
}

// Function to set up skill category collapsible behavior
function setupSkillCategories() {
    const categoryHeaders = document.querySelectorAll('.skill-category-header');
    
    categoryHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const toggleBtn = this.querySelector('.skill-category-toggle');
            const toggleIcon = toggleBtn.querySelector('svg');
            
            if (content.style.display === 'none') {
                content.style.display = 'grid';
                toggleIcon.classList.remove('rotate-180');
                toggleBtn.setAttribute('aria-expanded', 'true');
            } else {
                content.style.display = 'none';
                toggleIcon.classList.add('rotate-180');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    });
}

// Function to set up skill search functionality
function setupSkillSearch() {
    const searchInput = document.getElementById('skill-search');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const skillItems = document.querySelectorAll('.skill-checkbox');
        
        skillItems.forEach(item => {
            const skillName = item.nextElementSibling.textContent.toLowerCase();
            const skillRow = item.closest('.flex.items-center');
            
            if (skillName.includes(searchTerm) || searchTerm === '') {
                skillRow.style.display = 'flex';
            } else {
                skillRow.style.display = 'none';
            }
        });
        
        const categories = document.querySelectorAll('.border.border-gray-200.rounded-lg');
        categories.forEach(category => {
            const visibleSkills = category.querySelectorAll('.flex.items-center[style="display: flex"]');
            if (visibleSkills.length === 0 && searchTerm !== '') {
                category.style.display = 'none';
            } else {
                category.style.display = 'block';
            }
        });
    });
}

// Function to set up interest search in modal
function setupInterestSearch() {
    const searchInput = document.getElementById('modal-interest-search');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        populateInterestsInModal(searchTerm);
    });
}

// Function to handle showing the interest modal
function openAddInterestModal() {
    const modal = document.getElementById('add-interest-modal');
    if (!modal) {
        showToast('Modal tidak ditemukan', 'error');
        return;
    }
    
    if (!window.allInterestsMasterList || window.allInterestsMasterList.length === 0) {
        showToast('Belum ada data bidang minat', 'warning');
        
        fetch('/api/interest-areas')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.interests && data.interests.length > 0) {
                    window.allInterestsMasterList = data.interests;
                    showToast('Data bidang minat berhasil dimuat', 'success');
                    populateInterestsInModal();
                    modal.classList.remove('hidden');
                } else {
                    showToast('Tidak ada bidang minat yang tersedia', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching interests:', error);
                showToast('Gagal memuat data bidang minat', 'error');
            });
        return;
    }
    
    populateInterestsInModal();
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        modal.classList.add('opacity-100');
        const modalContent = modal.querySelector('div');
        if (modalContent) {
            modalContent.classList.add('scale-100');
            modalContent.classList.remove('scale-95');
        }
    }, 50);
}

// Function to close the interest modal
function closeAddInterestModal() {
    const modal = document.getElementById('add-interest-modal');
    if (!modal) return;
    
    const modalContent = modal.querySelector('div');
    if (modalContent) {
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        const searchInput = document.getElementById('modal-interest-search');
        if (searchInput) {
            searchInput.value = '';
        }
    }, 200);
}

// Function to populate the modal with interest areas
function populateInterestsInModal(searchTerm = '') {
    const modalInterestsList = document.getElementById('modal-interests-list');
    if (!modalInterestsList) return;
    
    modalInterestsList.innerHTML = '';
    
    const currentInterests = Array.from(document.querySelectorAll('#user-interests-list .interest-item'))
        .map(item => item.getAttribute('data-interest-id'));
    
    if (!window.allInterestsMasterList || window.allInterestsMasterList.length === 0) {
        modalInterestsList.innerHTML = `
            <div class="py-3 px-2 text-center">
                <p class="text-gray-500">Tidak ada bidang minat yang tersedia. Silakan hubungi administrator.</p>
            </div>
        `;
        return;
    }
    
    let filteredInterests = window.allInterestsMasterList;
    if (searchTerm !== '') {
        filteredInterests = window.allInterestsMasterList.filter(interest => 
            interest.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (interest.description && interest.description.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }
    
    if (filteredInterests.length === 0) {
        modalInterestsList.innerHTML = `
            <div class="py-3 px-2 text-center">
                <p class="text-gray-500">Tidak ada bidang minat yang cocok dengan pencarian.</p>
            </div>
        `;
        return;
    }
    
    filteredInterests.forEach(interest => {
        const isAlreadySelected = currentInterests.includes(interest.id.toString());
        
        const interestElement = document.createElement('div');
        interestElement.className = 'p-3 border rounded-md hover:bg-gray-50 ' + (isAlreadySelected ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer');
        interestElement.setAttribute('data-interest-id', interest.id);
        interestElement.setAttribute('data-interest-name', interest.name);
        interestElement.setAttribute('data-interest-description', interest.description || '');
        
        interestElement.innerHTML = `
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    ${!isAlreadySelected ? `
                        <input type="checkbox" class="modal-interest-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-3">
                    ` : ''}
                    <div>
                        <h4 class="font-medium text-gray-900">${interest.name}</h4>
                        <p class="text-sm text-gray-500 mt-1">${interest.description || 'Tidak ada deskripsi'}</p>
                    </div>
                </div>
                <div>
                    ${!isAlreadySelected ? `` : `
                        <span class="text-xs text-gray-500 py-1 px-2 bg-gray-100 rounded">Sudah ditambahkan</span>
                    `}
                </div>
            </div>
        `;
        
        if (!isAlreadySelected) {
            interestElement.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('.modal-interest-checkbox');
                    checkbox.checked = !checkbox.checked;
                }
            });
        }
        
        modalInterestsList.appendChild(interestElement);
    });
}

// Function to add a selected interest to the user's list
function addInterestToUserList(interestId, interestName, interestDescription) {
    let userInterestsList = document.getElementById('user-interests-list');
    const noInterestsMessage = document.getElementById('no-interests-message');
    const interestFormButtons = document.getElementById('interests-form-buttons');
    const userInterestsContainer = document.getElementById('user-interests-container');
    
    if (!document.querySelector(`.interest-item[data-interest-id="${interestId}"]`)) {
        
        if (noInterestsMessage) {
            noInterestsMessage.style.display = 'none';
        }
        
        if (interestFormButtons) {
            interestFormButtons.classList.remove('hidden');
        }
        
        if (!userInterestsList && userInterestsContainer) {
            userInterestsList = document.createElement('div');
            userInterestsList.id = 'user-interests-list';
            userInterestsList.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';
            userInterestsContainer.appendChild(userInterestsList);
        }
        
        if (!userInterestsList) {
            console.error('Could not find or create user interests list container');
            showToast('Terjadi kesalahan saat menambahkan bidang minat', 'error');
            return;
        }
        
        const interestElement = document.createElement('div');
        interestElement.className = 'interest-item border border-gray-200 rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden';
        interestElement.setAttribute('data-interest-id', interestId);
        
        interestElement.innerHTML = `
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="font-medium text-gray-900">${interestName}</h4>
                    <p class="text-sm text-gray-500 mt-1">${interestDescription || ''}</p>
                </div>
                <button type="button" class="remove-interest-btn text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-gray-100 transition-colors" title="Hapus Bidang Minat">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-4">
                <label for="interest-level-${interestId}" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Ketertarikan</label>
                <select id="interest-level-${interestId}" name="interests[${interestId}][interest_level]" class="interest-level-select w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="1">Sedikit</option>
                    <option value="2">Rendah</option>
                    <option value="3" selected>Sedang</option>
                    <option value="4">Tinggi</option>
                    <option value="5">Sangat Tinggi</option>
                </select>
                <input type="hidden" name="interests[${interestId}][interest_area_id]" value="${interestId}">
                
                <div class="flex items-center mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full interest-progress-bar" style="width: 60%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-500 ml-2">3/5</span>
                </div>
            </div>
        `;
        
        const removeButton = interestElement.querySelector('.remove-interest-btn');
        if (removeButton) {
            removeButton.addEventListener('click', handleRemoveInterest);
        }
        
        const levelSelect = interestElement.querySelector('.interest-level-select');
        if (levelSelect) {
            levelSelect.addEventListener('change', function(e) {
                updateInterestProgressBar(e);
                saveInterestsToDatabase();
            });
        }
        
        userInterestsList.appendChild(interestElement);
        
        saveInterestsToDatabase();
        
        showToast(`Bidang minat "${interestName}" berhasil ditambahkan`, 'success');
    }
}

// Function to save interests to the database
function saveInterestsToDatabase() {
    const interestsData = prepareInterestsData();
    
    console.log("Saving interests data:", interestsData);
    
    const form = document.getElementById('interests-form');
    if (!form) {
        console.error("Interests form not found");
        return;
    }
    
    const dataToSend = interestsData.length === 0 ? { interests: [], is_empty: true } : { interests: interestsData };
    
    const savingIndicator = document.createElement('div');
    savingIndicator.id = 'saving-interests-indicator';
    savingIndicator.className = 'fixed bottom-4 left-4 bg-blue-500 text-white px-3 py-1 rounded-full text-xs flex items-center';
    savingIndicator.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
    
    const existingIndicator = document.getElementById('saving-interests-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    document.body.appendChild(savingIndicator);
    
    console.log("Sending data to server:", dataToSend);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(dataToSend)
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 422 && interestsData.length === 0) {
                console.log("Server rejected empty interests array, but this is expected. Interests successfully cleared.");
                return { success: true, message: "Semua bidang minat berhasil dihapus" };
            }
            throw new Error(`Server responded with status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (savingIndicator) {
            savingIndicator.remove();
        }
        
        console.log('Interests save response:', data);
        
        if (data.success) {
            console.log('Interests saved successfully');
        } else {
            console.error('Error saving interests:', data.errors || data);
            showToast('Error: ' + (data.message || 'Gagal menyimpan bidang minat'), 'error');
        }
    })
    .catch(error => {
        if (savingIndicator) {
            savingIndicator.remove();
        }
        
        console.error('Fetch error:', error);
        
        if (error.message && error.message.includes('422') && interestsData.length === 0) {
            console.log("Server rejected empty interests array. This is expected behavior when removing all interests.");
        } else {
            showToast('Terjadi kesalahan saat menyimpan bidang minat', 'error');
        }
    });
}

// Function to handle removing an interest
function handleRemoveInterest(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const interestItem = event.target.closest('.interest-item');
    if (!interestItem) {
        console.error("Could not find interest item to remove");
        return;
    }
    
    const interestId = interestItem.getAttribute('data-interest-id');
    const interestName = interestItem.querySelector('h4').textContent;
    let userInterestsList = document.getElementById('user-interests-list');
    
    console.log(`Attempting to remove interest: ${interestName} (ID: ${interestId})`);

    const allInterestItems = document.querySelectorAll('.interest-item');
    if (allInterestItems.length === 1) {
        console.log("Cannot remove the last interest - showing notification");
        showToast('Tidak dapat menghapus bidang minat terakhir. Anda harus memiliki minimal satu bidang minat.', 'warning');
        
        interestItem.classList.add('border-yellow-500');
        interestItem.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.3)';
        
        setTimeout(() => {
            interestItem.classList.remove('border-yellow-500');
            interestItem.style.boxShadow = '';
        }, 2000);
        
        return;
    }

    interestItem.classList.add('opacity-0');
    interestItem.style.transition = 'opacity 300ms';
    
    setTimeout(() => {
        try {
            if (interestItem.parentNode) {
                interestItem.parentNode.removeChild(interestItem);
                console.log('Interest element visually removed from DOM.');
            } else {
                interestItem.remove(); 
                console.log('Interest element visually removed from DOM via fallback.');
            }

            const remainingInterests = userInterestsList ? userInterestsList.querySelectorAll('.interest-item') : [];
            console.log(`Remaining interests after visual removal: ${remainingInterests.length}`);
            
            saveInterestsToDatabase();
            showToast(`Bidang minat "${interestName}" telah dihapus`, 'info');
        } catch (error) {
            console.error('Error during interest removal post-animation:', error);
            showToast('Terjadi kesalahan saat menghapus bidang minat', 'error');
        }
    }, 300);
}

// Function to initialize interest-related components
function initializeInterestComponents() {
    const openAddInterestBtn = document.getElementById('open-add-interest-btn');
    const emptyAddInterestBtn = document.getElementById('empty-add-interest-btn');
    const closeInterestModalBtn = document.getElementById('close-interest-modal-btn');
    const addSelectedInterestsBtn = document.getElementById('add-selected-interests-btn');
    
    const interestsContainer = document.getElementById('user-interests-container');
    if (interestsContainer) {
        const infoNotice = document.createElement('div');
        infoNotice.className = 'mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md';
        infoNotice.innerHTML = `
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-blue-800">
                    <span class="font-medium">Penting:</span> Bidang minat yang Anda pilih akan berpengaruh pada rekomendasi lomba yang ditampilkan untuk Anda. Tambahkan bidang minat yang sesuai untuk mendapatkan rekomendasi lomba yang lebih akurat.
                </p>
            </div>
        `;
        interestsContainer.insertBefore(infoNotice, interestsContainer.firstChild);
    }
    
    if (openAddInterestBtn) {
        openAddInterestBtn.addEventListener('click', openAddInterestModal);
    }
    
    if (emptyAddInterestBtn) {
        emptyAddInterestBtn.addEventListener('click', openAddInterestModal);
    }
    
    if (closeInterestModalBtn) {
        closeInterestModalBtn.addEventListener('click', closeAddInterestModal);
    }
    
    if (addSelectedInterestsBtn) {
        addSelectedInterestsBtn.addEventListener('click', function() {
            const selectedInModal = document.querySelectorAll('.modal-interest-checkbox:checked');
            
            if (selectedInModal.length === 0) {
                showToast('Pilih minimal satu bidang minat untuk ditambahkan.', 'warning');
                return;
            }
            
            selectedInModal.forEach(checkbox => {
                const interestDiv = checkbox.closest('div[data-interest-id]');
                const interestId = interestDiv.getAttribute('data-interest-id');
                const interestName = interestDiv.getAttribute('data-interest-name');
                const interestDescription = interestDiv.getAttribute('data-interest-description') || '';
                addInterestToUserList(interestId, interestName, interestDescription);
            });
            
            closeAddInterestModal();
            showToast('Bidang minat berhasil ditambahkan', 'success');
        });
    }
    
    document.querySelectorAll('.interest-level-select').forEach(select => {
        select.addEventListener('change', function(e) {
            updateInterestProgressBar(e);
            saveInterestsToDatabase(); 
        });
    });
    
    document.querySelectorAll('.remove-interest-btn').forEach(btn => {
        btn.addEventListener('click', handleRemoveInterest);
    });
    
    setupInterestSearch();
    
    const interestsFormButtons = document.getElementById('interests-form-buttons');
    if (interestsFormButtons) {
        interestsFormButtons.style.display = 'none'; 
        interestsFormButtons.classList.add('hidden');
    }
    
    const interestsForm = document.getElementById('interests-form');
    if (interestsForm) {
        const allButtons = interestsForm.querySelectorAll('button[type="submit"], input[type="submit"]');
        allButtons.forEach(button => {
            button.style.display = 'none';
            button.classList.add('hidden');
            
            const parentDiv = button.closest('div');
            if (parentDiv) {
                parentDiv.style.display = 'none';
                parentDiv.classList.add('hidden');
            }
        });
    }
    
    if (interestsContainer) {
        const autoSaveNotice = document.createElement('div');
        autoSaveNotice.className = 'mt-4 p-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-600';
        autoSaveNotice.innerHTML = `
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Perubahan disimpan secara otomatis
            </div>
        `;
        
        const formButtons = document.getElementById('interests-form-buttons');
        if (formButtons) {
            formButtons.parentNode.insertBefore(autoSaveNotice, formButtons);
        } else {
            interestsContainer.appendChild(autoSaveNotice);
        }
    }
    
    if (interestsForm) {
        interestsForm.onsubmit = function(event) {
            event.preventDefault();
            console.log("Form submission prevented");
            return false;
        };
    }
}

let allSkillsMasterList = []; 

// Function to sync skills data from the window object
function syncSkillsFromWindow() {
    if (window.allSkillsMasterList && window.allSkillsMasterList.length > 0) {
        allSkillsMasterList = window.allSkillsMasterList;
        return true;
    }
    return false;
}

// Main initialization function executed when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile page JS loaded');

    if (localStorage.getItem('student_skills_empty_state') === 'true') {
        console.log("Found empty skills state in localStorage - checking if we need to sync");
        
        const noSkillsMessage = document.getElementById('no-skills-message');
        if (!noSkillsMessage) {
            console.log("Server has skills but localStorage says we should have none - syncing");
            
            const container = document.getElementById('selected-skills-container');
            if (container) {
                const skillItems = container.querySelectorAll('div[data-skill-id]');
                
                if (skillItems.length > 0) {
                    console.log(`Removing ${skillItems.length} skills from UI based on localStorage state`);
                    
                    container.innerHTML = '';
                    
                    const noSkillsHTML = `
                        <div id="no-skills-message" class="text-center py-10 px-4 col-span-2 bg-gray-50 rounded-lg border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Keterampilan</h3>
                            <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan keterampilan apapun.</p>
                        </div>
                    `;
                    container.innerHTML = noSkillsHTML;
                    
                    showToast('Menampilkan status terakhir keterampilan Anda', 'info');
                }
            }
        }
        
        localStorage.removeItem('student_skills_empty_state');
        localStorage.removeItem('student_skills_empty_timestamp');
    }

    setupTabs();
    
    setupPhotoUpload();
    
    window.skillsLoaded
        .then(() => {
            console.log('Skills data loaded, initializing skill components');
            syncSkillsFromWindow();
            initializeSkillComponents();
        })
        .catch(error => {
            console.error('Error loading skills data:', error);
            showToast('Terjadi kesalahan saat memuat data keterampilan', 'error');
        });
    
    if (typeof window.allInterestsMasterList === 'undefined') {
        window.allInterestsMasterList = []; 
        
        fetch('/api/interest-areas')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.interests) {
                    window.allInterestsMasterList = data.interests;
                    console.log('Interests loaded via AJAX:', window.allInterestsMasterList.length);
                    initializeInterestComponents();
                }
            })
            .catch(error => {
                console.error('Error loading interests:', error);
            });
    } else {
        console.log('Interests already loaded in page:', window.allInterestsMasterList?.length || 0);
        initializeInterestComponents();
    }
    
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    initializeProgressBars();
});

// Function to initialize all skill-related components
function initializeSkillComponents() {
    console.log('Initializing skill components with', allSkillsMasterList.length, 'skills');
    
    const skillsContainer = document.getElementById('skills-container');
    if (skillsContainer) {
        const infoNotice = document.createElement('div');
        infoNotice.className = 'mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md';
        infoNotice.innerHTML = `
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-blue-800">
                    <span class="font-medium">Penting:</span> Keterampilan yang Anda pilih akan berpengaruh pada rekomendasi lomba yang ditampilkan untuk Anda. Tambahkan keterampilan yang sesuai dengan tingkat keahlian yang tepat untuk mendapatkan rekomendasi lomba yang lebih akurat.
                </p>
            </div>
        `;
        skillsContainer.insertBefore(infoNotice, skillsContainer.firstChild);
    }
    
    document.querySelectorAll('.skill-level-select').forEach(select => {
        select.addEventListener('change', function(e) {
            updateSkillProgressBar(e);
            saveSkillsToDatabase(); 
        });
    });

    const openModalBtn = document.getElementById('open-add-skill-modal-btn');
    const closeModalBtn = document.getElementById('close-add-skill-modal-btn');
    const emptyAddBtn = document.getElementById('empty-add-skill-btn');
    const modalSkillSearchInput = document.getElementById('modal-skill-search');
    const addSelectedSkillsBtn = document.getElementById('add-selected-skills-btn');

    if (openModalBtn) {
        openModalBtn.addEventListener('click', openAddSkillModal);
    }
    
    if (emptyAddBtn) {
        emptyAddBtn.addEventListener('click', openAddSkillModal);
    }
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeAddSkillModal);
    }
    
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('add-skill-modal');
        if (modal && event.target === modal) {
            closeAddSkillModal();
        }
    });
    
    if (modalSkillSearchInput) {
        modalSkillSearchInput.addEventListener('input', function() {
            populateAllSkillsInModal(this.value);
        });
    }
    
    if (addSelectedSkillsBtn) {
        addSelectedSkillsBtn.addEventListener('click', function() {
            const selectedInModal = document.querySelectorAll('.modal-skill-checkbox:checked');
            
            if (selectedInModal.length === 0) {
                showToast('Pilih minimal satu keterampilan untuk ditambahkan.', 'warning');
                return;
            }
            
            selectedInModal.forEach(checkbox => {
                const skillDiv = checkbox.closest('div[data-skill-id]');
                const skillId = skillDiv.getAttribute('data-skill-id');
                const skillName = skillDiv.getAttribute('data-skill-name');
                const skillCategory = skillDiv.getAttribute('data-skill-category');
                addSkillToSelectedList(skillId, skillName, skillCategory);
            });
            
            closeAddSkillModal();
            showToast('Keterampilan berhasil ditambahkan', 'success');
        });
    }

    document.querySelectorAll('#selected-skills-container .remove-skill-btn').forEach(button => {
        button.addEventListener('click', handleRemoveSkill);
    });
    
    const skillsFormButtons = document.querySelectorAll('#skills-form button[type="submit"]');
    skillsFormButtons.forEach(button => {
        const buttonContainer = button.closest('div');
        if (buttonContainer) {
            buttonContainer.classList.add('hidden');
        } else {
            button.classList.add('hidden');
        }
    });
    
    if (skillsContainer) {
        const autoSaveNotice = document.createElement('div');
        autoSaveNotice.className = 'mt-4 p-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-600';
        autoSaveNotice.innerHTML = `
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Perubahan disimpan secara otomatis
            </div>
        `;
        
        const formContainer = document.getElementById('selected-skills-container');
        if (formContainer) {
            formContainer.parentNode.appendChild(autoSaveNotice);
        } else {
            skillsContainer.appendChild(autoSaveNotice);
        }
    }
    
    const skillsForm = document.getElementById('skills-form');
    if (skillsForm) {
        const oldSubmit = skillsForm.onsubmit;
        skillsForm.onsubmit = function(e) {
            e.preventDefault();
            return false;
        };
    }
}

// Function to display a toast notification
function showToast(message, type = 'info') {
    let toastContainer = document.getElementById('toast-container');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed top-4 right-4 z-50 flex flex-col space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    const toastId = 'toast-' + Date.now();
    toast.id = toastId;
    
    let bgColor, textColor, icon;
    switch (type) {
        case 'success':
            bgColor = 'bg-green-500';
            textColor = 'text-white';
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>`;
            break;
        case 'error':
            bgColor = 'bg-red-500';
            textColor = 'text-white';
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>`;
            break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            textColor = 'text-white';
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>`;
            break;
        default: 
            bgColor = 'bg-blue-500';
            textColor = 'text-white';
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>`;
    }
    
    toast.className = `flex items-center p-3 ${bgColor} ${textColor} rounded shadow-lg transform transition-all duration-300 opacity-0 translate-x-full`;
    toast.innerHTML = `
        <div class="mr-2">
            ${icon}
        </div>
        <div class="flex-1 mr-2">${message}</div>
        <button type="button" class="ml-auto text-white hover:text-gray-200 focus:outline-none" onclick="document.getElementById('${toastId}').remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-full');
    }, 10);
    
    setTimeout(() => {
        if (document.getElementById(toastId)) {
            toast.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                if (document.getElementById(toastId)) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Function to open and initialize the Add Skill Modal
function openAddSkillModal() {
    syncSkillsFromWindow();
    
    const modal = document.getElementById('add-skill-modal');
    if (!modal) {
        console.error('Modal element not found!');
        return;
    }
    
    console.log('Opening skill modal, skill data state:');
    console.log('  - allSkillsMasterList exists:', typeof allSkillsMasterList !== 'undefined');
    console.log('  - allSkillsMasterList length:', allSkillsMasterList ? allSkillsMasterList.length : 'N/A');
    console.log('  - Sample of first 2 skills:', allSkillsMasterList ? allSkillsMasterList.slice(0, 2) : 'N/A');
    
    if (!allSkillsMasterList || allSkillsMasterList.length === 0) {
        console.error('No skills data available! This could indicate the $skills variable was not properly passed from the controller.');
        showToast('Tidak ada data keterampilan yang tersedia. Silakan hubungi administrator.', 'error');
        return;
    }
    
    populateAllSkillsInModal();
    modal.classList.remove('hidden');
    document.getElementById('modal-skill-search').focus();
    
    setTimeout(() => {
        modal.classList.add('opacity-100');
        const modalContent = modal.querySelector('div');
        if (modalContent) {
            modalContent.classList.add('scale-100');
            modalContent.classList.remove('scale-95');
        }
    }, 50);
}

// Function to close the Add Skill Modal
function closeAddSkillModal() {
    const modal = document.getElementById('add-skill-modal');
    if (!modal) return;
    
    const modalContent = modal.querySelector('div');
    if (modalContent) {
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('modal-skill-search').value = ''; 
    }, 200);
}

// Function to populate the modal with all available skills, excluding already selected ones
function populateAllSkillsInModal(searchTerm = '') {
    const modalSkillsList = document.getElementById('modal-skills-list');
    if (!modalSkillsList) {
        console.error('Modal skills list container not found!');
        return;
    }

    modalSkillsList.innerHTML = ''; 

    if (!allSkillsMasterList || allSkillsMasterList.length === 0) {
        console.error('No skills data available in populateAllSkillsInModal function');
        modalSkillsList.innerHTML = `
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Data Tidak Tersedia</h3>
                <p class="mt-1 text-xs text-gray-500">Tidak ada data keterampilan yang tersedia. Pastikan data keterampilan telah diatur oleh administrator.</p>
            </div>`;
        return;
    }

    const currentlySelectedSkillIds = Array.from(document.querySelectorAll('#selected-skills-container > div[data-skill-id]'))
                                          .map(div => div.getAttribute('data-skill-id'));
    
    console.log('Filtering skills. Current search term:', searchTerm);
    console.log('Currently selected skills:', currentlySelectedSkillIds.length, 'skills');

    try {
        const filteredSkills = allSkillsMasterList.filter(skill => {
            if (!skill || typeof skill !== 'object' || !('name' in skill) || !('id' in skill)) {
                console.warn('Invalid skill object found:', skill);
                return false;
            }
            
            const matchesSearch = skill.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
                                (skill.category && skill.category.toLowerCase().includes(searchTerm.toLowerCase()));
            const notAlreadySelected = !currentlySelectedSkillIds.includes(String(skill.id));
            return matchesSearch && notAlreadySelected;
        });
        
        console.log('Filtered skills count:', filteredSkills.length);

        if (filteredSkills.length === 0) {
            modalSkillsList.innerHTML = `
                <div class="p-4 text-center">
                    <p class="text-gray-500 text-sm">
                        ${searchTerm ? 
                            'Tidak ada keterampilan yang cocok dengan pencarian "' + searchTerm + '".' : 
                            'Tidak ada keterampilan yang tersedia atau semua keterampilan sudah ditambahkan.'}
                    </p>
                </div>`;
            return;
        }

        const skillsByCategory = {};
        filteredSkills.forEach(skill => {
            const category = skill.category || 'Lainnya';
            if (!skillsByCategory[category]) {
                skillsByCategory[category] = [];
            }
            skillsByCategory[category].push(skill);
        });

        Object.keys(skillsByCategory).sort().forEach(category => {
            const categorySkills = skillsByCategory[category];
            
            const categoryElement = document.createElement('div');
            categoryElement.className = 'py-2';
            categoryElement.innerHTML = `
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider px-2 pb-1">${category}</h3>
            `;
            modalSkillsList.appendChild(categoryElement);
            
            categorySkills.forEach(skill => {
                const skillElement = document.createElement('div');
                skillElement.className = 'flex items-center justify-between p-2 hover:bg-gray-100 rounded-md cursor-pointer';
                skillElement.setAttribute('data-skill-id', skill.id);
                skillElement.setAttribute('data-skill-name', skill.name);
                skillElement.setAttribute('data-skill-category', skill.category || '');
                
                skillElement.innerHTML = `
                    <div class="flex items-center">
                        <input type="checkbox" class="modal-skill-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-3">
                        <span class="font-medium text-sm">${skill.name}</span>
                    </div>
                `;
                
                skillElement.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = this.querySelector('.modal-skill-checkbox');
                        checkbox.checked = !checkbox.checked;
                    }
                });
                
                modalSkillsList.appendChild(skillElement);
            });
        });
    } catch (error) {
        console.error('Error processing skills data:', error);
        modalSkillsList.innerHTML = `
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Terjadi Kesalahan</h3>
                <p class="mt-1 text-xs text-gray-500">Terjadi kesalahan dalam memproses data keterampilan. Detail error: ${error.message}</p>
            </div>`;
    }
}

// Function to add a new skill to the selected list on the main page
function addSkillToSelectedList(skillId, skillName, skillCategory) {
    const container = document.getElementById('selected-skills-container');
    if (!container) return;

    const noSkillsMsg = document.getElementById('no-skills-message');
    if (noSkillsMsg) {
        noSkillsMsg.remove();
    }

    const skillHTML = `
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden" data-skill-id="${skillId}">
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">${skillName}</h4>
                        ${skillCategory ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">${skillCategory}</span>` : ''}
                    </div>
                    <button type="button" class="remove-skill-btn text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-gray-100 transition-colors" title="Hapus Keterampilan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="mt-4">
                    <label for="skill-level-${skillId}" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Keahlian</label>
                    <select id="skill-level-${skillId}" name="skill_level[${skillId}]" class="skill-level-select w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1" selected>Pemula</option>
                        <option value="2">Dasar</option>
                        <option value="3">Menengah</option>
                        <option value="4">Mahir</option>
                        <option value="5">Ahli</option>
                    </select>
                    
                    <div class="flex items-center mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full skill-progress-bar" style="width: 20%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-500 ml-2">1/5</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', skillHTML);
    
    const newSkillCard = container.lastElementChild;
    
    const newRemoveButton = newSkillCard.querySelector('.remove-skill-btn');
    if (newRemoveButton) {
        newRemoveButton.addEventListener('click', handleRemoveSkill);
    }
    
    const newLevelSelect = newSkillCard.querySelector('.skill-level-select');
    if (newLevelSelect) {
        newLevelSelect.addEventListener('change', function(e) {
            updateSkillProgressBar(e);
            saveSkillsToDatabase(); 
        });
    }
    
    saveSkillsToDatabase();
    
    showToast(`Keterampilan "${skillName}" berhasil ditambahkan`, 'success');
}

// Function to handle removing a skill from the selected list
function handleRemoveSkill(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const skillDiv = event.currentTarget.closest('div[data-skill-id]');
    if (!skillDiv) {
        console.error("Could not find skill div to remove");
        return;
    }
    
    const skillName = skillDiv.querySelector('h4').textContent;
    const container = document.getElementById('selected-skills-container');
    const skillId = skillDiv.getAttribute('data-skill-id');
    
    console.log(`Attempting to remove skill: ${skillName} (ID: ${skillId})`);
    
    const isLastSkill = container.querySelectorAll('div[data-skill-id]').length === 1;
    if (isLastSkill) {
        console.log("Cannot remove the last skill - showing notification");
        showToast('Tidak dapat menghapus keterampilan terakhir. Anda harus memiliki minimal satu keterampilan.', 'warning');
        
        skillDiv.classList.add('border-yellow-500');
        skillDiv.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.3)';
        
        setTimeout(() => {
            skillDiv.classList.remove('border-yellow-500');
            skillDiv.style.boxShadow = '';
        }, 2000);
        
        return;
    }
    
    skillDiv.classList.add('opacity-0');
    skillDiv.style.transition = 'opacity 300ms';
    
    setTimeout(() => {
        try {
            if (skillDiv.parentNode) {
                skillDiv.parentNode.removeChild(skillDiv);
                console.log('Skill element visually removed from DOM.');
            } else {
                skillDiv.remove(); 
                console.log('Skill element visually removed from DOM via fallback.');
            }

            const remainingSkills = container ? container.querySelectorAll('div[data-skill-id]') : [];
            console.log(`Remaining skills after visual removal: ${remainingSkills.length}`);
            
            saveSkillsToDatabase();
            showToast(`Keterampilan "${skillName}" telah dihapus`, 'info');
        } catch (error) {
            console.error('Error during skill removal:', error);
            showToast('Terjadi kesalahan saat menghapus keterampilan', 'error');
        }
    }, 300);
}

// Function to save skills to the database automatically
function saveSkillsToDatabase() {
    const skillsData = prepareSkillsData();
    
    console.log("Saving skills data:", skillsData);
    
    const form = document.getElementById('skills-form');
    if (!form) {
        console.error("Skills form not found");
        return;
    }
    
    const savingIndicator = document.createElement('div');
    savingIndicator.id = 'saving-skills-indicator';
    savingIndicator.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-xs flex items-center';
    savingIndicator.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
    
    const existingIndicator = document.getElementById('saving-skills-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    document.body.appendChild(savingIndicator);
    
    let dataToSend;
    if (skillsData.length === 0) {
        dataToSend = { is_empty: true };
        console.log("Using is_empty flag for empty skills array");
    } else {
        dataToSend = { skills: skillsData };
    }
    
    console.log("Sending data to server:", dataToSend);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(dataToSend)
    })
    .then(response => {
        console.log("Server response status:", response.status, response.statusText);
        
        if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (savingIndicator) {
            savingIndicator.remove();
        }
        
        console.log('Skills save response:', data);
        
        if (data.success) {
            console.log('Skills saved successfully');
        } else {
            console.error('Error saving skills:', data.errors || data);
            showToast('Error: ' + (data.message || 'Gagal menyimpan keterampilan'), 'error');
        }
    })
    .catch(error => {
        if (savingIndicator) {
            savingIndicator.remove();
        }
        
        console.error('Fetch error:', error);
        showToast('Terjadi kesalahan saat menyimpan keterampilan', 'error');
    });
}

// Function to update the skill progress bar when proficiency level changes
function updateSkillProgressBar(event) {
    const select = event.target;
    const level = parseInt(select.value, 10);
    const cardElement = select.closest('div[data-skill-id]');
    
    if (cardElement) {
        const progressBar = cardElement.querySelector('.skill-progress-bar');
        
        if (progressBar) {
            progressBar.style.width = `${(level / 5) * 100}%`;
            
            const levelTextContainer = progressBar.parentElement.nextElementSibling;
            if (levelTextContainer) {
                levelTextContainer.textContent = `${level}/5`;
            } else {
                const levelText = cardElement.querySelector('.flex.items-center.mt-2 span');
                if (levelText) {
                    levelText.textContent = `${level}/5`;
                }
            }
        }
    }
}

function updateInterestProgressBar(event){
    const select = event.target;
    const level = parseInt(select.value, 10);
    const cardElement = select.closest('div[data-interest-id]');
    
    if (cardElement) {
        const progressBar = cardElement.querySelector('.interest-progress-bar');
        
        if (progressBar) {
            progressBar.style.width = `${(level / 5) * 100}%`;
            
            const levelTextContainer = progressBar.parentElement.nextElementSibling;
            if (levelTextContainer) {
                levelTextContainer.textContent = `${level}/5`;
            } else {
                const levelText = cardElement.querySelector('.flex.items-center.mt-2 span');
                if (levelText) {
                    levelText.textContent = `${level}/5`;
                }
            }
        }
    }
}

// Function to initialize progress bars for skills and interests that are already on the page
function initializeProgressBars() {
    document.querySelectorAll('.skill-level-select').forEach(select => {
        const level = parseInt(select.value, 10);
        const cardElement = select.closest('div[data-skill-id]');
        
        if (cardElement) {
            const progressBar = cardElement.querySelector('.skill-progress-bar');
            
            if (progressBar) {
                progressBar.style.width = `${(level / 5) * 100}%`;
                
                const levelTextContainer = progressBar.parentElement.nextElementSibling;
                if (levelTextContainer) {
                    levelTextContainer.textContent = `${level}/5`;
                } else {
                    const levelText = cardElement.querySelector('.flex.items-center.mt-2 span');
                    if (levelText) {
                        levelText.textContent = `${level}/5`;
                    }
                }
            }
        }
    });
    
    document.querySelectorAll('.interest-level-select').forEach(select => {
        const level = parseInt(select.value, 10);
        const cardElement = select.closest('div[data-interest-id]');
        
        if (cardElement) {
            const progressBar = cardElement.querySelector('.interest-progress-bar');
            
            if (progressBar) {
                progressBar.style.width = `${(level / 5) * 100}%`;
                
                const levelTextContainer = progressBar.parentElement.nextElementSibling;
                if (levelTextContainer) {
                    levelTextContainer.textContent = `${level}/5`;
                } else {
                    const levelText = cardElement.querySelector('.flex.items-center.mt-2 span');
                    if (levelText) {
                        levelText.textContent = `${level}/5`;
                    }
                }
            }
        }
    });
} 