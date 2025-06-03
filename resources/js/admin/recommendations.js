document.addEventListener('DOMContentLoaded', function() {
    const thresholdSlider = document.getElementById('threshold');
    const thresholdValue = document.getElementById('threshold-value');
    
    if (thresholdSlider && thresholdValue) {
        // Function to update the threshold value display when the slider changes
        thresholdSlider.addEventListener('input', function() {
            thresholdValue.textContent = this.value + '%';
        });
    }
    
    const ahpConsistencySlider = document.getElementById('ahp_consistency_threshold');
    const ahpConsistencyValue = document.getElementById('ahp_consistency_threshold_value');
    
    if (ahpConsistencySlider && ahpConsistencyValue) {
        // Function to update the AHP consistency threshold value display when the slider changes
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
                // Function to update the AHP priority value display when its corresponding slider changes
                slider.addEventListener('input', function() {
                    ahpPriorityValues[index].textContent = this.value;
                });
            }
        });
    }
    
    const competitionSelect = document.getElementById('competition');
    const subCompetitionContainer = document.getElementById('sub-competition-container');
    const subCompetitionSelect = document.getElementById('sub_competition_id');
    
    if (competitionSelect && subCompetitionContainer && subCompetitionSelect) {
        // Function to handle the response from a fetch request
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
}); 