document.addEventListener('DOMContentLoaded', function() {
    const thresholdSlider = document.getElementById('threshold');
    const thresholdValue = document.getElementById('threshold-value');
    
    if (thresholdSlider && thresholdValue) {
        thresholdValue.textContent = thresholdSlider.value + '%';
        
        thresholdSlider.addEventListener('input', function() {
            thresholdValue.textContent = this.value + '%';
        });
    }
    
    const wpWeightSkills = document.getElementById('wp_weight_skills');
    const wpWeightInterests = document.getElementById('wp_weight_interests');
    const wpWeightCompetitionLevel = document.getElementById('wp_weight_competition_level');
    const wpWeightDeadline = document.getElementById('wp_weight_deadline');
    const wpWeightActivityRating = document.getElementById('wp_weight_activity_rating');
    
    const wpWeightSkillsValue = document.getElementById('wp_weight_skills_value');
    const wpWeightInterestsValue = document.getElementById('wp_weight_interests_value');
    const wpWeightCompetitionLevelValue = document.getElementById('wp_weight_competition_level_value');
    const wpWeightDeadlineValue = document.getElementById('wp_weight_deadline_value');
    const wpWeightActivityRatingValue = document.getElementById('wp_weight_activity_rating_value');
    
    const wpTotalWeight = document.getElementById('wp_total_weight');
    
    if (wpWeightSkills && wpWeightInterests && wpWeightCompetitionLevel && wpWeightDeadline && wpWeightActivityRating) {
        wpWeightSkills.addEventListener('input', updateWPWeights);
        wpWeightInterests.addEventListener('input', updateWPWeights);
        wpWeightCompetitionLevel.addEventListener('input', updateWPWeights);
        wpWeightDeadline.addEventListener('input', updateWPWeights);
        wpWeightActivityRating.addEventListener('input', updateWPWeights);
        
        updateWPWeights();
    }
    
    function updateWPWeights() {
        const skillsWeight = parseFloat(wpWeightSkills.value);
        const interestsWeight = parseFloat(wpWeightInterests.value);
        const competitionLevelWeight = parseFloat(wpWeightCompetitionLevel.value);
        const deadlineWeight = parseFloat(wpWeightDeadline.value);
        const activityRatingWeight = parseFloat(wpWeightActivityRating.value);
        
        wpWeightSkillsValue.textContent = skillsWeight.toFixed(1);
        wpWeightInterestsValue.textContent = interestsWeight.toFixed(1);
        wpWeightCompetitionLevelValue.textContent = competitionLevelWeight.toFixed(1);
        wpWeightDeadlineValue.textContent = deadlineWeight.toFixed(1);
        wpWeightActivityRatingValue.textContent = activityRatingWeight.toFixed(1);
        
        const total = skillsWeight + interestsWeight + competitionLevelWeight + deadlineWeight + activityRatingWeight;
        wpTotalWeight.textContent = total.toFixed(1);
        
        if (Math.abs(total - 1.0) < 0.01) {
            wpTotalWeight.classList.remove('bg-red-100', 'text-red-800');
            wpTotalWeight.classList.add('bg-green-100', 'text-green-800');
        } else {
            wpTotalWeight.classList.remove('bg-green-100', 'text-green-800');
            wpTotalWeight.classList.add('bg-red-100', 'text-red-800');
        }
        
        if (Math.abs(total - 1.0) > 0.01) {
            const diff = 1.0 - total;
            const source = this;
            
            if (source === wpWeightSkills) {
                adjustOtherSliders([wpWeightInterests, wpWeightCompetitionLevel, wpWeightDeadline, wpWeightActivityRating], diff);
            } else if (source === wpWeightInterests) {
                adjustOtherSliders([wpWeightSkills, wpWeightCompetitionLevel, wpWeightDeadline, wpWeightActivityRating], diff);
            } else if (source === wpWeightCompetitionLevel) {
                adjustOtherSliders([wpWeightSkills, wpWeightInterests, wpWeightDeadline, wpWeightActivityRating], diff);
            } else if (source === wpWeightDeadline) {
                adjustOtherSliders([wpWeightSkills, wpWeightInterests, wpWeightCompetitionLevel, wpWeightActivityRating], diff);
            } else if (source === wpWeightActivityRating) {
                adjustOtherSliders([wpWeightSkills, wpWeightInterests, wpWeightCompetitionLevel, wpWeightDeadline], diff);
            }
        }
    }
    
    function adjustOtherSliders(sliders, diff) {
        if (Math.abs(diff) <= 0.3) {
            const perSlider = diff / sliders.length;
            
            sliders.forEach(slider => {
                const currentValue = parseFloat(slider.value);
                const newValue = Math.max(0.1, Math.min(0.9, currentValue + perSlider));
                slider.value = newValue.toFixed(1);
                
                if (slider === wpWeightSkills) {
                    wpWeightSkillsValue.textContent = newValue.toFixed(1);
                } else if (slider === wpWeightInterests) {
                    wpWeightInterestsValue.textContent = newValue.toFixed(1);
                } else if (slider === wpWeightCompetitionLevel) {
                    wpWeightCompetitionLevelValue.textContent = newValue.toFixed(1);
                } else if (slider === wpWeightDeadline) {
                    wpWeightDeadlineValue.textContent = newValue.toFixed(1);
                } else if (slider === wpWeightActivityRating) {
                    wpWeightActivityRatingValue.textContent = newValue.toFixed(1);
                }
            });
        }
    }
    
    const ahpConsistencySlider = document.getElementById('ahp_consistency_threshold');
    const ahpConsistencyValue = document.getElementById('ahp_consistency_threshold_value');
    
    if (ahpConsistencySlider && ahpConsistencyValue) {
        ahpConsistencySlider.addEventListener('input', function() {
            ahpConsistencyValue.textContent = this.value;
        });
    }
    
    const ahpPrioritySliders = [
        document.getElementById('ahp_priority_skills'),
        document.getElementById('ahp_priority_achievements'),
        document.getElementById('ahp_priority_interests'),
        document.getElementById('ahp_priority_deadline'),
        document.getElementById('ahp_priority_competition_level')
    ];
    
    const ahpPriorityValues = [
        document.getElementById('ahp_priority_skills_value'),
        document.getElementById('ahp_priority_achievements_value'),
        document.getElementById('ahp_priority_interests_value'),
        document.getElementById('ahp_priority_deadline_value'),
        document.getElementById('ahp_priority_competition_level_value')
    ];
    
    if (ahpPrioritySliders.length > 0 && ahpPrioritySliders[0] && ahpPriorityValues.length > 0 && ahpPriorityValues[0]) {
        ahpPrioritySliders.forEach((slider, index) => {
            if (slider && ahpPriorityValues[index]) {
                slider.addEventListener('input', function() {
                    ahpPriorityValues[index].textContent = this.value;
                });
            }
        });
    }
    
    const methodSelect = document.getElementById('dss_method');
    const ahpConfig = document.getElementById('ahp-config');
    const wpConfig = document.getElementById('wp-config');
    
    if (methodSelect && ahpConfig && wpConfig) {
        methodSelect.addEventListener('change', function() {
            const selectedMethod = this.value;
            
            if (selectedMethod === 'ahp' || selectedMethod === 'hybrid') {
                ahpConfig.classList.remove('hidden');
            } else {
                ahpConfig.classList.add('hidden');
            }
            
            if (selectedMethod === 'wp' || selectedMethod === 'hybrid') {
                wpConfig.classList.remove('hidden');
            } else {
                wpConfig.classList.add('hidden');
            }
        });
        
        const initialMethod = methodSelect.value;
        if (initialMethod === 'ahp' || initialMethod === 'hybrid') {
            ahpConfig.classList.remove('hidden');
        } else {
            ahpConfig.classList.add('hidden');
        }
        
        if (initialMethod === 'wp' || initialMethod === 'hybrid') {
            wpConfig.classList.remove('hidden');
        } else {
            wpConfig.classList.add('hidden');
        }
    }
    
    const competitionSelect = document.getElementById('competition');
    const subCompetitionContainer = document.getElementById('sub-competition-container');
    const subCompetitionSelect = document.getElementById('sub_competition_id');
    
    if (competitionSelect && subCompetitionContainer && subCompetitionSelect) {
        function handleFetchResponse(response) {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            
            return response.text().then(text => {
                if (!text || text.trim() === '') {
                    return []; 
                }
                
                try {
                    return JSON.parse(text);
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.error('Response text:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        }
        
        // Function to fetch all sub-competitions and populate the sub-competition select dropdown
        function fetchAllSubCompetitions() {
            console.log('Fetching all sub-competitions...');
            subCompetitionSelect.innerHTML = '<option value="">Loading sub kompetisi...</option>';
            subCompetitionSelect.disabled = true;
            
            const baseUrl = window.location.origin;
            const apiUrl = `${baseUrl}/api/admin/competitions/all/sub-competitions`;
            console.log('API URL:', apiUrl);
            
            fetch(apiUrl)
                .then(handleFetchResponse)
                .then(data => {
                    console.log('Sub-competitions data:', data);
                    subCompetitionSelect.innerHTML = '<option value="">-- Semua Sub Kompetisi --</option>';
                    
                    if (data && data.length > 0) {
                        data.forEach(subCompetition => {
                            const option = document.createElement('option');
                            option.value = subCompetition.id;
                            option.textContent = `${subCompetition.name} (${subCompetition.competition_name})`;
                            subCompetitionSelect.appendChild(option);
                        });
                        console.log('Added', data.length, 'sub-competitions to dropdown');
                    } else {
                        console.log('No sub-competitions found');
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'Tidak ada sub kompetisi yang tersedia';
                        subCompetitionSelect.appendChild(option);
                    }
                    subCompetitionSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching all sub-competitions:', error);
                    subCompetitionSelect.innerHTML = '<option value="">-- Error loading sub kompetisi --</option>';
                    subCompetitionSelect.disabled = false;
                });
        }
        
        fetchAllSubCompetitions();
        
        // Function to handle changes in the competition select dropdown
        competitionSelect.addEventListener('change', function() {
            const competitionId = this.value;
            
            if (competitionId) {
                const baseUrl = window.location.origin;
                const apiUrl = `${baseUrl}/api/admin/competitions/${competitionId}/sub-competitions`;
                console.log('Fetching sub-competitions for competition ID:', competitionId);
                console.log('API URL:', apiUrl);
                
                subCompetitionSelect.innerHTML = '<option value="">Loading sub kompetisi...</option>';
                subCompetitionSelect.disabled = true;
                
                fetch(apiUrl)
                    .then(handleFetchResponse)
                    .then(data => {
                        subCompetitionSelect.innerHTML = '<option value="">-- Semua Sub Kompetisi --</option>';
                        
                        if (data && data.length > 0) {
                            data.forEach(subCompetition => {
                                const option = document.createElement('option');
                                option.value = subCompetition.id;
                                option.textContent = subCompetition.name;
                                subCompetitionSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.disabled = true;
                            option.textContent = 'Tidak ada sub kompetisi';
                            subCompetitionSelect.appendChild(option);
                        }
                        
                        subCompetitionSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching sub-competitions:', error);
                        subCompetitionSelect.innerHTML = '<option value="">-- Error loading sub kompetisi --</option>';
                        subCompetitionSelect.disabled = false;
                    });
            } else {
                fetchAllSubCompetitions();
            }
        });
    }

    const form = document.getElementById('recommendation-form');
    const generateBtn = document.getElementById('generate-btn');

    if (form && generateBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menghasilkan...';
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg> Hasilkan Rekomendasi';
                
                if (!data.success) {
                    if (typeof showToast === 'function') {
                        showToast(data.message, 'error');
                    } else {
                        console.error('Error:', data.message);
                    }
                    return;
                }
                
                if (typeof showToast === 'function') {
                    showToast('Berhasil menghasilkan ' + data.count + ' rekomendasi.', 'success');
                }
            })
            .catch(error => {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg> Hasilkan Rekomendasi';
                console.error('Error:', error);
                
                if (typeof showToast === 'function') {
                    showToast('Terjadi kesalahan saat menghasilkan rekomendasi. Silakan coba lagi.', 'error');
                }
            });
        });
    }
    
    // Delete Single Recommendation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-recommendation')) {
            const button = e.target.closest('.delete-recommendation');
            const userId = button.getAttribute('data-user-id');
            const competitionId = button.getAttribute('data-competition-id');
            const fromSession = button.getAttribute('data-from-session') === 'true';
            
            if (typeof showModal === 'function') {
                showModal(
                    'Konfirmasi Hapus',
                    'Apakah Anda yakin ingin menghapus rekomendasi ini?',
                    function() {
                        const row = button.closest('tr');
                        if (row) {
                            row.remove();
                        }
                        
                        let countElement;
                        if (fromSession) {
                            countElement = document.querySelector('#session-recommendations-count');
                            
                            const remainingRows = document.querySelectorAll('#recommendation-table-body-session tr').length;
                            if (remainingRows === 0) {
                                const tableBodySession = document.getElementById('recommendation-table-body-session');
                                if (tableBodySession) {
                                    let parentElement = tableBodySession.parentElement;
                                    while (parentElement && !parentElement.classList.contains('card')) {
                                        parentElement = parentElement.parentElement;
                                    }
                                    if (parentElement) {
                                        parentElement.classList.add('hidden');
                                    }
                                }
                            }
                        } else {
                            countElement = document.querySelector('#recommendation-results .text-sm.text-gray-600');
                            
                            const remainingRows = document.querySelectorAll('#recommendation-table-body tr').length;
                            if (remainingRows === 0) {
                                resultsContainer.classList.add('hidden');
                            }
                        }
                        
                        if (countElement) {
                            const count = parseInt(countElement.textContent.match(/\d+/)[0]) - 1;
                            countElement.textContent = `${count} rekomendasi dihasilkan`;
                        }
                        
                        removeFromGeneratedRecommendations(userId, competitionId);
                        
                        if (typeof showToast === 'function') {
                            showToast('Rekomendasi berhasil dihapus', 'success');
                        }
                    }
                );
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus rekomendasi ini?')) {
                    const row = button.closest('tr');
                    if (row) {
                        row.remove();
                    }
                    
                    let countElement;
                    if (fromSession) {
                        countElement = document.querySelector('#session-recommendations-count');
                        
                        const remainingRows = document.querySelectorAll('#recommendation-table-body-session tr').length;
                        if (remainingRows === 0) {
                            const tableBodySession = document.getElementById('recommendation-table-body-session');
                            if (tableBodySession) {
                                let parentElement = tableBodySession.parentElement;
                                while (parentElement && !parentElement.classList.contains('card')) {
                                    parentElement = parentElement.parentElement;
                                }
                                if (parentElement) {
                                    parentElement.classList.add('hidden');
                                }
                            }
                        }
                    } else {
                        countElement = document.querySelector('#recommendation-results .text-sm.text-gray-600');
                        
                        const remainingRows = document.querySelectorAll('#recommendation-table-body tr').length;
                        if (remainingRows === 0) {
                            resultsContainer.classList.add('hidden');
                        }
                    }
                    
                    if (countElement) {
                        const count = parseInt(countElement.textContent.match(/\d+/)[0]) - 1;
                        countElement.textContent = `${count} rekomendasi dihasilkan`;
                    }
                    
                    removeFromGeneratedRecommendations(userId, competitionId);
                    
                    if (typeof showToast === 'function') {
                        showToast('Rekomendasi berhasil dihapus', 'success');
                    }
                }
            }
        }
    });
    
    // Delete All Recommendations (AJAX results)
    const deleteAllAjaxBtn = document.getElementById('delete-all-recommendations-ajax');
    if (deleteAllAjaxBtn) {
        deleteAllAjaxBtn.addEventListener('click', function() {
            if (typeof showModal === 'function') {
                showModal(
                    'Konfirmasi Hapus Semua',
                    'Apakah Anda yakin ingin menghapus semua rekomendasi yang dihasilkan?',
                    function() {
                        tableBody.innerHTML = '';
                        
                        resultsContainer.classList.add('hidden');
                        
                        clearGeneratedRecommendations();
                        
                        if (typeof showToast === 'function') {
                            showToast('Semua rekomendasi berhasil dihapus.', 'success');
                        }
                    }
                );
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus semua rekomendasi yang dihasilkan?')) {
                    tableBody.innerHTML = '';
                    
                    resultsContainer.classList.add('hidden');
                    
                    clearGeneratedRecommendations();
                    
                    if (typeof showToast === 'function') {
                        showToast('Semua rekomendasi berhasil dihapus.', 'success');
                    }
                }
            }
        });
    }
    
    // Delete All Recommendations from Session
    const deleteAllSessionBtn = document.getElementById('delete-all-recommendations-session');
    if (deleteAllSessionBtn) {
        deleteAllSessionBtn.addEventListener('click', function() {
            const tableBody = document.getElementById('recommendation-table-body-session');
            const resultsContainer = deleteAllSessionBtn.closest('.card');
            
            if (typeof showModal === 'function') {
                showModal(
                    'Konfirmasi Hapus Semua',
                    'Apakah Anda yakin ingin menghapus semua rekomendasi yang dihasilkan?',
                    function() {
                        if (resultsContainer) {
                            resultsContainer.classList.add('hidden');
                        }
                        
                        clearGeneratedRecommendations();
                        
                        if (typeof showToast === 'function') {
                            showToast('Semua rekomendasi berhasil dihapus.', 'success');
                        }
                    }
                );
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus semua rekomendasi yang dihasilkan?')) {
                    if (tableBody) {
                        tableBody.innerHTML = '';
                    }
                    
                    if (resultsContainer) {
                        resultsContainer.classList.add('hidden');
                    }
                    
                    clearGeneratedRecommendations();
                    
                    if (typeof showToast === 'function') {
                        showToast('Semua rekomendasi berhasil dihapus.', 'success');
                    }
                }
            }
        });
    }
    
    // Function to remove a specific recommendation from the session
    function removeFromGeneratedRecommendations(userId, competitionId) {
        fetch('/admin/recommendations/remove-generated', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                user_id: userId,
                competition_id: competitionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success && typeof showToast === 'function') {
                showToast('Error removing recommendation: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast === 'function') {
                showToast('Terjadi kesalahan saat menghapus rekomendasi', 'error');
            }
        });
    }
    
    // Function to clear all generated recommendations from the session
    function clearGeneratedRecommendations() {
        fetch('/admin/recommendations/clear-generated', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success && typeof showToast === 'function') {
                showToast('Error clearing recommendations: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast === 'function') {
                showToast('Terjadi kesalahan saat menghapus semua rekomendasi', 'error');
            }
        });
    }
}); 