document.addEventListener('DOMContentLoaded', function() {
    const competitionRoutes = window.competitionRoutes || {};
    const csrfToken = window.csrfToken || '';

    setupCompetitionModals();
    
    autoUpdateCompetitionStatuses();

    attachPaginationHandlers();

    // Function to automatically determine and update the competition status based on its start and end dates, and update the UI.
    function updateCompetitionStatus(formPrefix) {
            const prefix = formPrefix || 'add'; 
            console.log(`Updating competition status for ${prefix} form`);
            
            const startDateEl = document.getElementById(`${prefix}-start-date`);
            const endDateEl = document.getElementById(`${prefix}-end-date`);
            const regStartEl = document.getElementById(`${prefix}-registration-start`);
            const regEndEl = document.getElementById(`${prefix}-registration-end`);
            const compDateEl = document.getElementById(`${prefix}-competition-date`);
            const statusEl = document.getElementById(`${prefix}-status`);
            
            if (!statusEl) {
                console.error(`Status element not found for ${prefix} form`);
                return;
            }
            
            if (!startDateEl || !endDateEl) {
                console.error(`Required date fields not found for ${prefix} form`);
                return;
            }
            
            console.log(`Found all required fields for ${prefix} form`);
            
            const startDate = startDateEl.value ? new Date(startDateEl.value) : null;
            const endDate = endDateEl.value ? new Date(endDateEl.value) : null;
            const regStart = regStartEl?.value ? new Date(regStartEl.value) : null;
            const regEnd = regEndEl?.value ? new Date(regEndEl.value) : null;
            const compDate = compDateEl?.value ? new Date(compDateEl.value) : null;
            
            console.log(`Date values for ${prefix} form:`, {
                startDate: startDateEl.value,
                endDate: endDateEl.value,
                regStart: regStartEl?.value,
                regEnd: regEndEl?.value,
                compDate: compDateEl?.value
            });
            
            const today = new Date();
            today.setHours(0, 0, 0, 0); 
            
            let status = 'upcoming'; 
            let statusText = 'Akan Datang'; 
            
            if (startDate && endDate) {
                if (today < startDate) {
                    status = 'upcoming';
                    statusText = 'Akan Datang';
                } else if (today >= startDate && today <= endDate) {
                    status = 'active';
                    statusText = 'Aktif';
                } else if (today > endDate) {
                    status = 'completed';
                    statusText = 'Selesai';
                }
            }
            
            console.log(`Determined status for ${prefix} form:`, status);
            
            statusEl.value = status;
            
            statusEl.disabled = true;
            
            statusEl.classList.add('bg-gray-200', 'cursor-not-allowed', 'opacity-75');
            
            const statusWrapper = statusEl.closest('.form-group');
            if (statusWrapper) {
                const statusLabel = statusEl.previousElementSibling;
                if (statusLabel && statusLabel.tagName === 'LABEL') {
                    if (!statusLabel.querySelector('.auto-label')) {
                        const autoLabel = document.createElement('span');
                        autoLabel.className = 'auto-label ml-2 text-xs font-normal text-gray-600 bg-gray-100 px-2 py-1 rounded';
                        autoLabel.textContent = '(Otomatis)';
                        statusLabel.appendChild(autoLabel);
                    }
                }
                
                let noteEl = statusWrapper.querySelector('.status-auto-note');
                if (!noteEl) {
                    noteEl = document.createElement('div');
                    noteEl.className = 'status-auto-note mt-2 p-2 bg-gray-50 border-l-4 border-blue-400 rounded text-sm';
                    statusWrapper.appendChild(noteEl);
                }
                
                noteEl.innerHTML = `
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-700">Status: <span class="text-blue-600">${statusText}</span></p>
                            <p class="text-gray-600">Status ditentukan otomatis berdasarkan tanggal yang diinput.</p>
                        </div>
                    </div>
                `;
                
                console.log(`Status visual indicators updated for ${prefix} form`);
            } else {
                console.warn(`Status wrapper not found for ${prefix} form`);
            }
    }

    // Function to initialize and set up event listeners for competition modals.
    function setupCompetitionModals() {
        window.addCompetitionModal = document.getElementById('add-competition-modal');
        window.editCompetitionModal = document.getElementById('edit-competition-modal');
        window.showCompetitionModal = document.getElementById('show-competition-modal');
        window.deleteCompetitionModal = document.getElementById('delete-competition-modal');

        // Function to set default dates to today for the add competition form and initialize period/status.
        function setDefaultDates() {
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0]; 
            
            document.getElementById('add-start-date').value = formattedDate;
            document.getElementById('add-end-date').value = formattedDate;
            document.getElementById('add-registration-start').value = formattedDate;
            document.getElementById('add-registration-end').value = formattedDate;
            document.getElementById('add-competition-date').value = formattedDate;
            
            const statusEl = document.getElementById('add-status');
            if (statusEl && !statusEl.value) {
                statusEl.value = 'upcoming'; 
            }
            
            findAndSetDefaultPeriod();
            updateCompetitionStatus('add');
        }

        
        // Function to load period options from the server via AJAX.
        async function loadPeriodOptions() {
            try {
                const periodSelect = document.getElementById('add-period');
                if (!periodSelect) return;
                
                const response = await fetch('/admin/periods', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                const periods = [];
                
                if (data.table) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.table;
                    
                    const rows = tempDiv.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const idCell = row.querySelector('td:first-child');
                        const nameCell = row.querySelector('td:nth-child(2)');
                        const dateCell = row.querySelector('td:nth-child(3)');
                        
                        if (idCell && dateCell) {
                            const id = idCell.textContent.trim();
                            const name = nameCell ? nameCell.textContent.trim() : `Period ${id}`;
                            const dateRange = dateCell.textContent.trim();
                            let startDateStr, endDateStr;

                            if (!dateRange) {
                                console.warn(`Period ID ${id} has an empty date range in loadPeriodOptions. Skipping.`);
                                return;
                            }

                            if (dateRange.includes(' - ')) {
                                [startDateStr, endDateStr] = dateRange.split(' - ');
                            } else {
                                startDateStr = dateRange;
                                endDateStr = dateRange;
                            }
                            
                            const startDateParts = startDateStr.split(' ');
                            const endDateParts = endDateStr.split(' ');

                            if (startDateParts.length < 3 || endDateParts.length < 3) {
                                console.warn(`Period ID ${id} has malformed date parts after attempting to parse "${startDateStr}" or "${endDateStr}" in loadPeriodOptions. Skipping.`);
                                return;
                            }
                            
                            const monthMap = {
                                'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06',
                                'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
                            };
                            
                            const startDateRaw = `${startDateParts[2]}-${monthMap[startDateParts[1]]}-${startDateParts[0].padStart(2, '0')}`;
                            const endDateRaw = `${endDateParts[2]}-${monthMap[endDateParts[1]]}-${endDateParts[0].padStart(2, '0')}`;
                            
                            periods.push({
                                id,
                                name,
                                start_date_raw: startDateRaw,
                                end_date_raw: endDateRaw
                            });
                        }
                    });
                }
                
                return periods;
            } catch (error) {
                console.error('Error loading periods:', error);
                return [];
            }
        }
        
        // Function to find and set the appropriate default period for the "add competition" form based on competition dates and current date.
        async function findAndSetDefaultPeriod() {
            const startDate = document.getElementById('add-start-date').value;
            const endDate = document.getElementById('add-end-date').value;
            
            if (!startDate || !endDate) return;
            
            const periodSelect = document.getElementById('add-period');
            if (!periodSelect) return;
            
            const periodOptions = Array.from(periodSelect.options).filter(option => option.value !== '');
            
            if (periodOptions.length === 0) return;
            
            const periods = [];
            periodOptions.forEach(option => {
                periods.push({
                    id: option.value,
                    name: option.textContent
                });
            });
            
            if (periods.length === 0) return;
            
            let bestMatchPeriod = null;
            let fallbackPeriod = null;
            
            const compStartDate = new Date(startDate);
            const compEndDate = new Date(endDate);
            const today = new Date();
            
            if (periods.length > 0) {
                bestMatchPeriod = periods[0].id;
            }
            
            try {
                const response = await fetch('/admin/periods', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.table) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.table;
                    
                    const rows = tempDiv.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const idCell = row.querySelector('td:first-child');
                        const nameCell = row.querySelector('td:nth-child(2)');
                        const dateCell = row.querySelector('td:nth-child(3)');
                        
                        if (idCell && dateCell) {
                            const id = idCell.textContent.trim();
                            const name = nameCell ? nameCell.textContent.trim() : `Period ${id}`;
                            const dateRange = dateCell.textContent.trim();
                            let startDateStr, endDateStr;

                            if (!dateRange) {
                                console.warn(`Period ID ${id} has an empty date range in findAndSetDefaultPeriod. Skipping.`);
                                return;
                            }

                            if (dateRange.includes(' - ')) {
                                [startDateStr, endDateStr] = dateRange.split(' - ');
                            } else {
                                // Assume single date, start and end are the same
                                startDateStr = dateRange;
                                endDateStr = dateRange;
                                // console.log(`Period ID ${id} is a single-day period: "${dateRange}" in findAndSetDefaultPeriod.`); // Optional: for debugging
                            }
                            
                            // Parse dates (assuming format is DD MMM YYYY)
                            const startDateParts = startDateStr.split(' ');
                            const endDateParts = endDateStr.split(' ');

                            if (startDateParts.length < 3 || endDateParts.length < 3) {
                                console.warn(`Period ID ${id} has malformed date parts after attempting to parse "${startDateStr}" or "${endDateStr}" in findAndSetDefaultPeriod. Skipping.`);
                                return;
                            }
                            
                            // Convert to YYYY-MM-DD format for comparison
                            const monthMap = {
                                'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06',
                                'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
                            };
                            
                            const startDateRaw = `${startDateParts[2]}-${monthMap[startDateParts[1]]}-${startDateParts[0].padStart(2, '0')}`;
                            const endDateRaw = `${endDateParts[2]}-${monthMap[endDateParts[1]]}-${endDateParts[0].padStart(2, '0')}`;
                            
                            // Find the corresponding period in our array and add date information
                            const periodIndex = periods.findIndex(p => p.id === id);
                            if (periodIndex !== -1) {
                                periods[periodIndex].start_date_raw = startDateRaw;
                                periods[periodIndex].end_date_raw = endDateRaw;
                            }
                        }
                    });
                    
                    // Reset best match since we now have date information
                    bestMatchPeriod = null;
                    fallbackPeriod = null;
                    let activePeriod = null;
                    let upcomingPeriod = null;
                    let recentlyEndedPeriod = null;
                    
                    console.log('Finding the best period match for today:', today.toISOString().split('T')[0]);
                    
                    // First, categorize periods based on their relation to today's date
                    for (const period of periods) {
                        // Skip periods without date information
                        if (!period.start_date_raw || !period.end_date_raw) {
                            console.log(`Period ${period.id} (${period.name}) skipped - missing date information`);
                            continue;
                        }
                        
                        const periodStartDate = new Date(period.start_date_raw);
                        const periodEndDate = new Date(period.end_date_raw);
                        
                        console.log(`Evaluating period ${period.id} (${period.name}): ${period.start_date_raw} to ${period.end_date_raw}`);
                        
                        // Check if the period is currently active (contains today's date)
                        if (periodStartDate <= today && periodEndDate >= today) {
                            console.log(`✅ Period ${period.id} is ACTIVE - contains today's date`);
                            activePeriod = period.id;
                            // No need to check other periods if we found an active one
                            break;
                        }
                        
                        // Check if the period is upcoming (starts in the future)
                        if (periodStartDate > today) {
                            console.log(`Period ${period.id} is UPCOMING - starts in the future`);
                            // If we haven't found an upcoming period yet, or this one starts sooner
                            if (!upcomingPeriod || periodStartDate < new Date(periods.find(p => p.id === upcomingPeriod).start_date_raw)) {
                                upcomingPeriod = period.id;
                            }
                        }
                        
                        // Check if the period recently ended (within the last 30 days)
                        if (periodEndDate < today && ((today - periodEndDate) / (1000 * 60 * 60 * 24)) <= 30) {
                            console.log(`Period ${period.id} RECENTLY ENDED - ended within the last 30 days`);
                            // If we haven't found a recently ended period yet, or this one ended more recently
                            if (!recentlyEndedPeriod || periodEndDate > new Date(periods.find(p => p.id === recentlyEndedPeriod).end_date_raw)) {
                                recentlyEndedPeriod = period.id;
                            }
                        }
                        
                        // As a fallback, check if the period encompasses the competition dates
                        if (!fallbackPeriod && periodStartDate <= compStartDate && periodEndDate >= compEndDate) {
                            console.log(`Period ${period.id} is a FALLBACK - encompasses competition dates`);
                            fallbackPeriod = period.id;
                        }
                    }
                    
                    // Determine the best match based on priority:
                    // 1. Active period (current date falls within period)
                    // 2. Upcoming period (closest future period)
                    // 3. Recently ended period (ended within last 30 days)
                    // 4. Fallback (period that encompasses competition dates)
                    // 5. Closest period by date
                    
                    if (activePeriod) {
                        console.log('✅ Selected ACTIVE period:', activePeriod);
                        bestMatchPeriod = activePeriod;
                    } else if (upcomingPeriod) {
                        console.log('✅ Selected UPCOMING period:', upcomingPeriod);
                        bestMatchPeriod = upcomingPeriod;
                    } else if (recentlyEndedPeriod) {
                        console.log('✅ Selected RECENTLY ENDED period:', recentlyEndedPeriod);
                        bestMatchPeriod = recentlyEndedPeriod;
                    } else if (fallbackPeriod) {
                        console.log('✅ Selected FALLBACK period:', fallbackPeriod);
                        bestMatchPeriod = fallbackPeriod;
                    } else if (periods.length > 0) {
                        // If still no match found, find the closest period
                        console.log('No suitable period found, finding closest by date...');
                        
                        // Sort periods by how close they are to today
                        const periodsWithDates = periods.filter(p => p.start_date_raw && p.end_date_raw);
                        
                        if (periodsWithDates.length > 0) {
                            const sortedPeriods = [...periodsWithDates].sort((a, b) => {
                                const aStartDate = new Date(a.start_date_raw);
                                const aEndDate = new Date(a.end_date_raw);
                                const bStartDate = new Date(b.start_date_raw);
                                const bEndDate = new Date(b.end_date_raw);
                                
                                // Calculate the distance from today to the period (in days)
                                const aDistance = Math.min(
                                    Math.abs(today - aStartDate) / (1000 * 60 * 60 * 24),
                                    Math.abs(today - aEndDate) / (1000 * 60 * 60 * 24)
                                );
                                
                                const bDistance = Math.min(
                                    Math.abs(today - bStartDate) / (1000 * 60 * 60 * 24),
                                    Math.abs(today - bEndDate) / (1000 * 60 * 60 * 24)
                                );
                                
                                return aDistance - bDistance; // Sort by closest to today
                            });
                            
                            // Use the closest period as default
                            bestMatchPeriod = sortedPeriods[0].id;
                            console.log('✅ Selected CLOSEST period:', bestMatchPeriod);
                        } else {
                            // If no periods have date information, use the first period
                            bestMatchPeriod = periods[0].id;
                            console.log('✅ Selected FIRST period (no date info available):', bestMatchPeriod);
                        }
                    }
                }
            } catch (error) {
                console.error('Error fetching period dates:', error);
                // If we can't get date information, just select the first period
                if (periods.length > 0 && !bestMatchPeriod) {
                    bestMatchPeriod = periods[0].id;
                }
            }
            
            // Set the selected period if found
            if (bestMatchPeriod) {
                console.log('Setting period to:', bestMatchPeriod);
                periodSelect.value = bestMatchPeriod;
                // Trigger a change event to ensure any listeners are notified
                const event = new Event('change', { bubbles: true });
                periodSelect.dispatchEvent(event);
            }
        }
        
        // Function to find and set the appropriate period for edit form based on competition dates
        async function findAndSetDefaultPeriodForEdit() {
            const startDate = document.getElementById('edit-start-date').value;
            const endDate = document.getElementById('edit-end-date').value;
            
            if (!startDate || !endDate) return;
            
            // Get all periods from the dropdown
            const periodSelect = document.getElementById('edit-period');
            if (!periodSelect) return;
            
            // Get all period options (excluding the placeholder)
            const periodOptions = Array.from(periodSelect.options).filter(option => option.value !== '');
            
            // If there are no periods, we can't set a default
            if (periodOptions.length === 0) return;
            
            // Extract period data from the existing options
            const periods = [];
            periodOptions.forEach(option => {
                periods.push({
                    id: option.value,
                    name: option.textContent
                });
            });
            
            // If there are no periods, we can't set a default
            if (periods.length === 0) return;
            
            // Initialize variables for period selection
            let bestMatchPeriod = null;
            let fallbackPeriod = null;
            let activePeriod = null;
            let upcomingPeriod = null;
            let recentlyEndedPeriod = null;
            
            // Convert competition dates to Date objects for comparison
            const compStartDate = new Date(startDate);
            const compEndDate = new Date(endDate);
            const today = new Date();
            
            console.log('Finding the best period match for edit form - today:', today.toISOString().split('T')[0]);
            console.log('Competition dates:', startDate, 'to', endDate);
            
            try {
                // Get period date ranges via AJAX to help with selection
                const response = await fetch('/admin/periods', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                // Process the data to extract period date information
                if (data.table) {
                    // Create a temporary div to parse the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.table;
                    
                    // Extract period data from the table rows
                    const rows = tempDiv.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const idCell = row.querySelector('td:first-child');
                        const nameCell = row.querySelector('td:nth-child(2)');
                        const dateCell = row.querySelector('td:nth-child(3)');
                        
                        if (idCell && dateCell) {
                            const id = idCell.textContent.trim();
                            const dateRange = dateCell.textContent.trim();
                            let startDateStr, endDateStr;

                            if (!dateRange) {
                                console.warn(`Period ID ${id} has an empty date range in findAndSetDefaultPeriodForEdit. Skipping.`);
                                return;
                            }

                            if (dateRange.includes(' - ')) {
                                [startDateStr, endDateStr] = dateRange.split(' - ');
                            } else {
                                // Assume single date, start and end are the same
                                startDateStr = dateRange;
                                endDateStr = dateRange;
                            }
                            
                            // Parse dates (assuming format is DD MMM YYYY)
                            const startDateParts = startDateStr.split(' ');
                            const endDateParts = endDateStr.split(' ');

                            if (startDateParts.length < 3 || endDateParts.length < 3) {
                                console.warn(`Period ID ${id} has malformed date parts after attempting to parse "${startDateStr}" or "${endDateStr}" in findAndSetDefaultPeriodForEdit. Skipping.`);
                                return;
                            }
                            
                            // Convert to YYYY-MM-DD format for comparison
                            const monthMap = {
                                'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06',
                                'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
                            };
                            
                            const startDateRaw = `${startDateParts[2]}-${monthMap[startDateParts[1]]}-${startDateParts[0].padStart(2, '0')}`;
                            const endDateRaw = `${endDateParts[2]}-${monthMap[endDateParts[1]]}-${endDateParts[0].padStart(2, '0')}`;
                            
                            // Find the corresponding period in our array and add date information
                            const periodIndex = periods.findIndex(p => p.id === id);
                            if (periodIndex !== -1) {
                                periods[periodIndex].start_date_raw = startDateRaw;
                                periods[periodIndex].end_date_raw = endDateRaw;
                            }
                        }
                    });
                    
                    // First, categorize periods based on their relation to today's date
                    for (const period of periods) {
                        // Skip periods without date information
                        if (!period.start_date_raw || !period.end_date_raw) {
                            console.log(`Period ${period.id} (${period.name}) skipped - missing date information`);
                            continue;
                        }
                        
                        const periodStartDate = new Date(period.start_date_raw);
                        const periodEndDate = new Date(period.end_date_raw);
                        
                        console.log(`Evaluating period ${period.id} (${period.name}): ${period.start_date_raw} to ${period.end_date_raw}`);
                        
                        // Check if the period is currently active (contains today's date)
                        if (periodStartDate <= today && periodEndDate >= today) {
                            console.log(`✅ Period ${period.id} is ACTIVE - contains today's date`);
                            activePeriod = period.id;
                            // No need to check other periods if we found an active one
                            break;
                        }
                        
                        // Check if the period is upcoming (starts in the future)
                        if (periodStartDate > today) {
                            console.log(`Period ${period.id} is UPCOMING - starts in the future`);
                            // If we haven't found an upcoming period yet, or this one starts sooner
                            if (!upcomingPeriod || periodStartDate < new Date(periods.find(p => p.id === upcomingPeriod).start_date_raw)) {
                                upcomingPeriod = period.id;
                            }
                        }
                        
                        // Check if the period recently ended (within the last 30 days)
                        if (periodEndDate < today && ((today - periodEndDate) / (1000 * 60 * 60 * 24)) <= 30) {
                            console.log(`Period ${period.id} RECENTLY ENDED - ended within the last 30 days`);
                            // If we haven't found a recently ended period yet, or this one ended more recently
                            if (!recentlyEndedPeriod || periodEndDate > new Date(periods.find(p => p.id === recentlyEndedPeriod).end_date_raw)) {
                                recentlyEndedPeriod = period.id;
                            }
                        }
                        
                        // As a fallback, check if the period encompasses the competition dates
                        if (!fallbackPeriod && periodStartDate <= compStartDate && periodEndDate >= compEndDate) {
                            console.log(`Period ${period.id} is a FALLBACK - encompasses competition dates`);
                            fallbackPeriod = period.id;
                        }
                    }
                    
                    // Determine the best match based on priority:
                    // 1. Active period (current date falls within period)
                    // 2. Upcoming period (closest future period)
                    // 3. Recently ended period (ended within last 30 days)
                    // 4. Fallback (period that encompasses competition dates)
                    // 5. Closest period by date
                    
                    if (activePeriod) {
                        console.log('✅ Selected ACTIVE period for edit form:', activePeriod);
                        bestMatchPeriod = activePeriod;
                    } else if (upcomingPeriod) {
                        console.log('✅ Selected UPCOMING period for edit form:', upcomingPeriod);
                        bestMatchPeriod = upcomingPeriod;
                    } else if (recentlyEndedPeriod) {
                        console.log('✅ Selected RECENTLY ENDED period for edit form:', recentlyEndedPeriod);
                        bestMatchPeriod = recentlyEndedPeriod;
                    } else if (fallbackPeriod) {
                        console.log('✅ Selected FALLBACK period for edit form:', fallbackPeriod);
                        bestMatchPeriod = fallbackPeriod;
                    } else if (periods.length > 0) {
                        // If still no match found, find the closest period
                        console.log('No suitable period found for edit form, finding closest by date...');
                        
                        // Sort periods by how close they are to today
                        const periodsWithDates = periods.filter(p => p.start_date_raw && p.end_date_raw);
                        
                        if (periodsWithDates.length > 0) {
                            const sortedPeriods = [...periodsWithDates].sort((a, b) => {
                                const aStartDate = new Date(a.start_date_raw);
                                const aEndDate = new Date(a.end_date_raw);
                                const bStartDate = new Date(b.start_date_raw);
                                const bEndDate = new Date(b.end_date_raw);
                                
                                // Calculate the distance from today to the period (in days)
                                const aDistance = Math.min(
                                    Math.abs(today - aStartDate) / (1000 * 60 * 60 * 24),
                                    Math.abs(today - aEndDate) / (1000 * 60 * 60 * 24)
                                );
                                
                                const bDistance = Math.min(
                                    Math.abs(today - bStartDate) / (1000 * 60 * 60 * 24),
                                    Math.abs(today - bEndDate) / (1000 * 60 * 60 * 24)
                                );
                                
                                return aDistance - bDistance; // Sort by closest to today
                            });
                            
                            // Use the closest period as default
                            bestMatchPeriod = sortedPeriods[0].id;
                            console.log('✅ Selected CLOSEST period for edit form:', bestMatchPeriod);
                        } else {
                            // If no periods have date information, use the first period
                            bestMatchPeriod = periods[0].id;
                            console.log('✅ Selected FIRST period for edit form (no date info available):', bestMatchPeriod);
                        }
                    }
                }
            } catch (error) {
                console.error('Error fetching period dates for edit form:', error);
                // If we can't get date information, just select the first period
                if (periods.length > 0 && !bestMatchPeriod) {
                    bestMatchPeriod = periods[0].id;
                }
            }
            
            // Set the selected period if found
            if (bestMatchPeriod) {
                console.log('Setting period for edit form to:', bestMatchPeriod);
                periodSelect.value = bestMatchPeriod;
                // Trigger a change event to ensure any listeners are notified
                const event = new Event('change', { bubbles: true });
                periodSelect.dispatchEvent(event);
            }
        }
        
        // Add event listeners to update period and status when dates change
        function setupDateChangeListeners() {
            // Get all date inputs from add form
            const addDateInputs = [
                document.getElementById('add-start-date'),
                document.getElementById('add-end-date'),
                document.getElementById('add-registration-start'),
                document.getElementById('add-registration-end'),
                document.getElementById('add-competition-date')
            ].filter(el => el); // Filter out null elements
            
            // Add event listeners to update period and status when dates change
            addDateInputs.forEach(input => {
                input.addEventListener('change', () => {
                    if (input.id === 'add-start-date' || input.id === 'add-end-date') {
                        findAndSetDefaultPeriod();
                    }
                    updateCompetitionStatus('add');
                });
            });
            
            // Get all date inputs from edit form
            const editDateInputs = [
                document.getElementById('edit-start-date'),
                document.getElementById('edit-end-date'),
                document.getElementById('edit-registration-start'),
                document.getElementById('edit-registration-end'),
                document.getElementById('edit-competition-date')
            ].filter(el => el); // Filter out null elements
            
            // Add event listeners to update status and period when dates change in edit form
            editDateInputs.forEach(input => {
                input.addEventListener('change', () => {
                    updateCompetitionStatus('edit');
                    // Also update period if start or end date changes
                    if (input.id === 'edit-start-date' || input.id === 'edit-end-date') {
                        findAndSetDefaultPeriodForEdit();
                    }
                });
            });
        }
        
        const openAddModalBtn = document.getElementById('open-add-competition-modal');
        if (openAddModalBtn) {
            openAddModalBtn.addEventListener('click', function() {
                if (window.addCompetitionModal) {
                    window.addCompetitionModal.classList.remove('hidden');
                    resetFormErrors('add-competition-form');
                    resetMultiStepForm();
                    
                    // First ensure the period dropdown is loaded
                    loadPeriodOptions().then(() => {
                        // Then set default dates and period
                        setDefaultDates(); // Set default dates when opening the modal
                        setupDateChangeListeners(); // Setup listeners for date changes
                    });
                }
            });
        }

        const closeAddModalBtn = document.getElementById('close-add-modal');
        const cancelAddBtn = document.getElementById('cancel-add-competition');
        [closeAddModalBtn, cancelAddBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.addCompetitionModal) {
                        window.addCompetitionModal.classList.add('hidden');
                        document.getElementById('add-competition-form').reset();
                        resetFormErrors('add-competition-form');
                        resetMultiStepForm();
                    }
                });
            }
        });

        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-competition');
        [closeEditModalBtn, cancelEditBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.editCompetitionModal) {
                        window.editCompetitionModal.classList.add('hidden');
                        resetFormErrors('edit-competition-form');
                    }
                });
            }
        });
        
        const closeShowModalBtn = document.getElementById('close-show-modal');
        const closeShowBtn = document.getElementById('close-show-competition');
        const editFromShowBtn = document.getElementById('edit-from-show');
        
        [closeShowModalBtn, closeShowBtn].forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    if (window.showCompetitionModal) {
                        window.showCompetitionModal.classList.add('hidden');
                    }
                });
            }
        });
        
        if (editFromShowBtn) {
            editFromShowBtn.addEventListener('click', function() {
                const competitionId = editFromShowBtn.getAttribute('data-competition-id');
                if (window.showCompetitionModal) {
                    window.showCompetitionModal.classList.add('hidden');
                }
                loadCompetitionForEdit(competitionId);
            });
        }
        
        const cancelDeleteBtn = document.getElementById('cancel-delete-competition');
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                if (window.deleteCompetitionModal) {
                    window.deleteCompetitionModal.classList.add('hidden');
                }
            });
        }
        
        setupAddCompetitionForm();
        setupMultiStepFormNavigation();
        attachEditButtonListeners();
        attachShowButtonListeners();
        attachDeleteButtonListeners();
    }
    
    // Resets the multi-step form to its initial (first) step.
    function resetMultiStepForm() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-competition');
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
    
    // Set up the multi-step form navigation.
    function setupMultiStepFormNavigation() {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-competition');
        
        if (!step1 || !step2 || !nextBtn || !prevBtn || !submitBtn) return;
        
        const stepItems = document.querySelectorAll('.step-item');
        
        nextBtn.addEventListener('click', function() {
            const name = document.getElementById('add-name');
            const organizer = document.getElementById('add-organizer');
            const category = document.getElementById('add-category');
            const type = document.getElementById('add-type');
            const period = document.getElementById('add-period');
            const status = document.getElementById('add-status');
            
            let isValid = true;
            let errorMessage = '';
            
            resetFieldErrors();
            
            // Set a default status value to avoid validation error
            if (status && !status.value) {
                status.value = 'upcoming';
            }
            
            if (name && !name.value.trim()) {
                isValid = false;
                errorMessage += '<li>Nama Kompetisi wajib diisi</li>';
                showFieldError(name, 'Nama Kompetisi wajib diisi');
            }
            
            if (organizer && !organizer.value.trim()) {
                isValid = false;
                errorMessage += '<li>Penyelenggara wajib diisi</li>';
                showFieldError(organizer, 'Penyelenggara wajib diisi');
            }
            
            if (category && !category.value) {
                isValid = false;
                errorMessage += '<li>Kategori wajib dipilih</li>';
                showFieldError(category, 'Kategori wajib dipilih');
            }
            
            if (period && !period.value) {
                isValid = false;
                errorMessage += '<li>Periode wajib dipilih</li>';
                showFieldError(period, 'Periode wajib dipilih');
            }
            
            if (!isValid) {
                const errorContainer = document.getElementById('add-competition-error');
                const errorList = document.getElementById('add-competition-error-list');
                const errorCount = document.getElementById('add-competition-error-count');
                
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessage;
                errorCount.textContent = errorMessage.split('<li>').length - 1;
                
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            document.getElementById('add-competition-error').classList.add('hidden');
            
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            // Automatically set the status based on dates when moving to step 2
            updateCompetitionStatus('add');
            
            // Disable and gray out the status dropdown
            const statusField = document.getElementById('add-status');
            if (statusField) {
                statusField.disabled = true;
                statusField.classList.add('bg-gray-200', 'text-gray-600');
            }
            
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
        
        // Function to display an error message for a specific form field.
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
        
        // Function to reset error messages and styling for form fields in the add competition form.
        function resetFieldErrors() {
            const inputFields = document.getElementById('add-competition-form').querySelectorAll('input, select, textarea');
            inputFields.forEach(field => {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            });
            
            const errorMessages = document.getElementById('add-competition-modal').querySelectorAll('.error-message');
            errorMessages.forEach(error => {
                error.textContent = '';
                error.classList.add('hidden');
            });
        }
    }
    
    // Function to handle the submission of the add competition form via AJAX.
    function submitAddCompetitionForm() {
        const form = document.getElementById('add-competition-form');
        
        const startDate = document.getElementById('add-start-date').value;
        const endDate = document.getElementById('add-end-date').value;
        const regStart = document.getElementById('add-registration-start').value;
        const regEnd = document.getElementById('add-registration-end').value;
        
        let isValid = true;
        let errorMessages = [];
        
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                const fieldName = field.previousElementSibling?.textContent?.replace('*', '').trim() || 'Field';
                errorMessages.push(`<li>${fieldName} wajib diisi</li>`);
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            }
        });
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errorMessages.push('<li>Tanggal Selesai tidak boleh kurang dari Tanggal Mulai</li>');
            document.getElementById('add-end-date').classList.add('border-red-500');
        }
        
        if (regStart && regEnd && new Date(regEnd) < new Date(regStart)) {
            isValid = false;
            errorMessages.push('<li>Tanggal Selesai Pendaftaran tidak boleh kurang dari Tanggal Mulai Pendaftaran</li>');
            document.getElementById('add-registration-end').classList.add('border-red-500');
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('add-competition-error');
            const errorList = document.getElementById('add-competition-error-list');
            const errorCount = document.getElementById('add-competition-error-count');
            
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = errorMessages.join('');
            errorCount.textContent = errorMessages.length;
            
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        document.getElementById('add-competition-error')?.classList.add('hidden');
        
        const submitBtnElement = document.getElementById('submit-add-competition');
        const originalButtonText = submitBtnElement.innerHTML;
        submitBtnElement.disabled = true;
        submitBtnElement.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        // Ensure status is set and field is temporarily enabled for FormData
        const statusField = document.getElementById('add-status');
        if (statusField) {
            updateCompetitionStatus('add'); // Sets value and disables field as per its logic
            statusField.disabled = false;   // Re-enable just before FormData creation
        }

        const formData = new FormData(form);

        // Optionally, re-disable the status field after FormData is created to maintain UI state
        if (statusField) {
            statusField.disabled = true;
        }
        
        fetch(competitionRoutes.store, {
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
            submitBtnElement.disabled = false;
            submitBtnElement.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to add competition');
            }
            
            form.reset();
            window.addCompetitionModal.classList.add('hidden');
            
            showNotification(data.message || 'Kompetisi berhasil ditambahkan', 'success');
            
            refreshCompetitionsTable();
        })
        .catch(error => {
            submitBtnElement.disabled = false;
            submitBtnElement.innerHTML = originalButtonText;
            
            console.error('Error adding competition:', error);
            
            if (error.response && error.response.status === 422) {
                const errorData = error.response.data;
                displayErrors(errorData.errors, form, 'add-competition-error', 'add-competition-error-list');
            } else {
                showNotification(error.message || 'Gagal menambahkan kompetisi. Silakan coba lagi.', 'error');
            }
        });
    }

    // Set up the add competition form with AJAX submission.
    function setupAddCompetitionForm() {
        const addForm = document.getElementById('add-competition-form');
        if (!addForm) return;
        
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAddCompetitionForm();
        });
        
        const submitBtn = document.getElementById('submit-add-competition');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                submitAddCompetitionForm();
            });
        }
    }
    
    // Attach event listeners to edit buttons.
    function attachEditButtonListeners() {
        const editButtons = document.querySelectorAll('.edit-competition');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.getAttribute('data-competition-id');
                loadCompetitionForEdit(competitionId);
            });
        });
    }
    
    // Attach event listeners to show buttons.
    function attachShowButtonListeners() {
        const showButtons = document.querySelectorAll('.show-competition');
        showButtons.forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.getAttribute('data-competition-id');
                loadCompetitionForView(competitionId);
            });
        });
    }
    
    // Load competition data for editing.
    function loadCompetitionForEdit(competitionId) {
        console.log('Loading competition for edit:', competitionId);
        
        // Ensure the edit modal is available in the DOM
        const editModal = document.getElementById('edit-competition-modal');
        if (!editModal) {
            console.error('Edit competition modal not found in DOM');
            return;
        }
        
        // Show the modal with skeleton loading
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
        
        // Show skeleton loading
        const contentElements = editModal.querySelectorAll('.competition-edit-content');
        const skeletonElements = editModal.querySelectorAll('.competition-edit-skeleton');
        
        contentElements.forEach(el => el.classList.add('hidden'));
        skeletonElements.forEach(el => el.classList.remove('hidden'));
        
        // Fetch competition data
        fetch(competitionRoutes.show.replace('__id__', competitionId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load competition');
            }
            
            const competition = data.data;
            console.log('Competition data loaded:', competition);
            
            // Helper function to safely set element value with detailed logging
            const safeSetValue = (elementId, value) => {
                const element = document.getElementById(elementId);
                if (element) {
                    console.log(`Setting ${elementId} to:`, value);
                    element.value = value || '';
                } else {
                    console.warn(`Element with ID '${elementId}' not found in DOM`);
                }
            };
            
            // Check if required form elements exist
            const requiredElements = [
                'edit-competition-id', 'edit-name', 'edit-organizer', 'edit-period',
                'edit-level', 'edit-status', 'edit-start-date', 'edit-end-date',
                'edit-registration-start', 'edit-registration-end', 'edit-competition-date',
                'edit-description'
            ];
            
            const missingElements = requiredElements.filter(id => !document.getElementById(id));
            if (missingElements.length > 0) {
                console.error('Missing required form elements:', missingElements);
            }
            
            // Set form field values from competition data
            safeSetValue('edit-competition-id', competition.id);
            safeSetValue('edit-name', competition.name);
            safeSetValue('edit-organizer', competition.organizer);
            safeSetValue('edit-period', competition.period_id);
            safeSetValue('edit-level', competition.level);
            safeSetValue('edit-status', competition.status);
            
            // Set date values with additional checks
            if (competition.start_date) {
                const startDate = competition.start_date.split('T')[0];
                safeSetValue('edit-start-date', startDate);
            }
            
            if (competition.end_date) {
                const endDate = competition.end_date.split('T')[0];
                safeSetValue('edit-end-date', endDate);
            }
            
            if (competition.registration_start) {
                const regStartDate = competition.registration_start.split('T')[0];
                safeSetValue('edit-registration-start', regStartDate);
            }
            
            if (competition.registration_end) {
                const regEndDate = competition.registration_end.split('T')[0];
                safeSetValue('edit-registration-end', regEndDate);
            }
            
            if (competition.competition_date) {
                const compDate = competition.competition_date.split('T')[0];
                safeSetValue('edit-competition-date', compDate);
            }
            
            safeSetValue('edit-description', competition.description);
            
            console.log('Form values set successfully');
            
            const submitEditBtn = document.getElementById('submit-edit-competition');
            if (submitEditBtn) {
                submitEditBtn.onclick = function() {
                    updateCompetition(competition.id);
                };
            }
            
            resetFormErrors('edit-competition-form');
            
            // Set up date change listeners explicitly for the edit form
            const editDateInputs = [
                document.getElementById('edit-start-date'),
                document.getElementById('edit-end-date'),
                document.getElementById('edit-registration-start'),
                document.getElementById('edit-registration-end'),
                document.getElementById('edit-competition-date')
            ].filter(el => el); // Filter out null elements
            
            // Remove any existing listeners (clone and replace elements)
            editDateInputs.forEach(input => {
                const newInput = input.cloneNode(true);
                input.parentNode.replaceChild(newInput, input);
                
                // Add fresh event listeners
                newInput.addEventListener('change', () => {
                    console.log(`Date changed: ${newInput.id}`);
                    updateCompetitionStatus('edit');
                    
                    // Also update period if start or end date changes
                    if (newInput.id === 'edit-start-date' || newInput.id === 'edit-end-date') {
                        findAndSetDefaultPeriodForEdit();
                    }
                });
            });
            
            // Force update status based on dates
            try {
                console.log('Forcing status update for edit form');
                updateCompetitionStatus('edit');
                
                // Also find and set the appropriate period based on competition dates
                findAndSetDefaultPeriodForEdit();
            } catch (e) {
                console.warn('Could not update competition status or period:', e);
            }
            
            // Hide skeleton and show content
            contentElements.forEach(el => el.classList.remove('hidden'));
            skeletonElements.forEach(el => el.classList.add('hidden'));
        })
        .catch(error => {
            console.error('Error loading competition:', error);
            showNotification('Gagal memuat data kompetisi. Silakan coba lagi.', 'error');
            
            // Hide skeleton on error
            contentElements.forEach(el => el.classList.remove('hidden'));
            skeletonElements.forEach(el => el.classList.add('hidden'));
        });
    }
    
    // Load competition data for viewing.
    function loadCompetitionForView(competitionId) {
        const showModal = window.showCompetitionModal;
        if (!showModal) return;
        
        // Show the modal with skeleton loading
        showModal.classList.remove('hidden');
        
        // Show skeleton loading
        const contentElements = showModal.querySelectorAll('.competition-detail-content');
        const skeletonElements = showModal.querySelectorAll('.competition-detail-skeleton');
        
        contentElements.forEach(el => el.classList.add('hidden'));
        skeletonElements.forEach(el => el.classList.remove('hidden'));
        
        fetch(competitionRoutes.show.replace('__id__', competitionId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load competition');
            }
            
            const competition = data.data;
            
            document.getElementById('competition-id').textContent = competition.id;
            document.getElementById('competition-name').textContent = competition.name;
            document.getElementById('competition-level').textContent = competition.level_formatted || 'Umum';
            document.getElementById('competition-organizer').textContent = competition.organizer;
            document.getElementById('competition-period').textContent = competition.period?.name || 'N/A';
            
            let startDate = new Date(competition.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            let endDate = new Date(competition.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('competition-dates').textContent = `${startDate} - ${endDate}`;
            
            if (competition.registration_start && competition.registration_end) {
                let regStartDate = new Date(competition.registration_start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                let regEndDate = new Date(competition.registration_end).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                document.getElementById('competition-registration').textContent = `${regStartDate} - ${regEndDate}`;
            } else {
                document.getElementById('competition-registration').textContent = 'Tidak Ada Informasi';
            }
            
            if (competition.competition_date) {
                let competitionDate = new Date(competition.competition_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                document.getElementById('competition-date').textContent = competitionDate;
            } else {
                document.getElementById('competition-date').textContent = 'Tidak Ada Informasi';
            }
            
            const statusElement = document.getElementById('competition-status');
            statusElement.textContent = getStatusText(competition.status);
            statusElement.className = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' + getStatusClass(competition.status);
            
            document.getElementById('competition-description').innerHTML = competition.description || '<p class="text-gray-500">Tidak ada deskripsi.</p>';
            document.getElementById('show-competition-updated-at').textContent = competition.updated_at ? new Date(competition.updated_at).toLocaleString() : '-';

            const editButton = document.getElementById('edit-from-show');
            if (editButton) {
                editButton.setAttribute('data-competition-id', competition.id);
            }
            
            updateLevelIcon(competition.level);
            
            // Hide skeleton and show content
            contentElements.forEach(el => el.classList.remove('hidden'));
            skeletonElements.forEach(el => el.classList.add('hidden'));
        })
        .catch(error => {
            console.error('Error loading competition:', error);
            showNotification('Gagal memuat data kompetisi. Silakan coba lagi.', 'error');
            
            // Hide skeleton on error
            contentElements.forEach(el => el.classList.remove('hidden'));
            skeletonElements.forEach(el => el.classList.add('hidden'));
        });
    }
    
    // Function to update the level icon based on competition level
    function updateLevelIcon(level) {
        const levelText = level ? level.toLowerCase() : '';
        const iconContainer = document.getElementById('level-icon-container');
        const icon = document.getElementById('level-icon');
        
        if (!iconContainer || !icon) return;
        
        let bgClass = 'bg-indigo-100';
        let iconClass = 'text-indigo-500';
        let iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />';
        
        if (levelText === 'international' || levelText.includes('internasional')) {
            bgClass = 'bg-blue-100';
            iconClass = 'text-blue-600';
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
        } else if (levelText === 'national' || levelText.includes('nasional')) {
            bgClass = 'bg-red-100';
            iconClass = 'text-red-600';
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />';
        } else if (levelText === 'regional' || levelText.includes('regional')) {
            bgClass = 'bg-green-100';
            iconClass = 'text-green-600';
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />';
        } else if (levelText === 'provincial' || levelText.includes('provinsi')) {
            bgClass = 'bg-yellow-100';
            iconClass = 'text-yellow-600';
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
        } else if (levelText === 'university' || levelText.includes('universitas')) {
            bgClass = 'bg-purple-100';
            iconClass = 'text-purple-600';
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />';
        }
        
        const existingContainerClasses = iconContainer.getAttribute('class') || '';
        const newContainerClasses = existingContainerClasses
            .replace(/bg-\w+-\d+/g, '')
            .trim() + ' h-24 w-24 rounded-full overflow-hidden ' + bgClass + ' flex items-center justify-center shadow-md';
        iconContainer.setAttribute('class', newContainerClasses);
        
        const existingIconClasses = icon.getAttribute('class') || '';
        const newIconClasses = existingIconClasses
            .replace(/text-\w+-\d+/g, '')
            .trim() + ' h-12 w-12 ' + iconClass;
        icon.setAttribute('class', newIconClasses);
        
        icon.innerHTML = iconPath;
    }
    
    // Update competition data.
    function updateCompetition(competitionId) {
        const form = document.getElementById('edit-competition-form');
        
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        let errorMessages = [];
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                const fieldName = field.previousElementSibling.textContent.replace('*', '').trim();
                errorMessages.push(`<li>${fieldName} wajib diisi</li>`);
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            }
        });
        
        const startDate = document.getElementById('edit-start-date').value;
        const endDate = document.getElementById('edit-end-date').value;
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errorMessages.push('<li>Tanggal Selesai tidak boleh kurang dari Tanggal Mulai</li>');
        }
        
        const period = document.getElementById('edit-period').value;
        if (!period) {
            isValid = false;
            errorMessages.push('<li>Periode wajib dipilih</li>');
            document.getElementById('edit-period').classList.add('border-red-500');
        }
        
        if (!isValid) {
            const errorContainer = document.getElementById('edit-competition-error');
            const errorList = document.getElementById('edit-competition-error-list');
            const errorCount = document.getElementById('edit-competition-error-count');
            
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = errorMessages.join('');
            errorCount.textContent = errorMessages.length;
            
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        document.getElementById('edit-competition-error')?.classList.add('hidden');
        
        const submitBtn = document.getElementById('submit-edit-competition');
        const originalButtonText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        // Ensure status is set and field is temporarily enabled for FormData
        const statusField = document.getElementById('edit-status');
        if (statusField) {
            updateCompetitionStatus('edit'); // Sets value and disables field as per its logic
            statusField.disabled = false;    // Re-enable just before FormData creation
        }

        const formData = new FormData(form);

        // Optionally, re-disable the status field after FormData is created to maintain UI state
        if (statusField) {
            statusField.disabled = true;
        }
    
    fetch(competitionRoutes.update.replace('__id__', competitionId), {
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
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to update competition');
            }
            
            form.reset();
            window.editCompetitionModal.classList.add('hidden');
            
            showNotification(data.message || 'Kompetisi berhasil diperbarui', 'success');
            
            refreshCompetitionsTable();
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
            
            console.error('Error updating competition:', error);
            
            if (error.response && error.response.status === 422) {
                const errorData = error.response.data;
                displayErrors(errorData.errors, form, 'edit-competition-error', 'edit-competition-error-list');
            } else {
                showNotification(error.message || 'Gagal memperbarui kompetisi. Silakan coba lagi.', 'error');
            }
        });
    }
    
    // Attach event listeners to delete buttons.
    function attachDeleteButtonListeners() {
        const deleteButtons = document.querySelectorAll('.delete-competition');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const competitionId = this.getAttribute('data-competition-id');
                const competitionName = this.getAttribute('data-competition-name');
                
                document.getElementById('competition-name-to-delete').textContent = competitionName;
                const confirmDeleteBtn = document.getElementById('confirm-delete-competition');
                
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.setAttribute('data-competition-id', competitionId);
                    confirmDeleteBtn.onclick = function() {
                        deleteCompetition(competitionId);
                    };
                }
                
                document.dispatchEvent(new CustomEvent('delete-modal:show'));
            });
        });
    }
    
    // Delete competition.
    function deleteCompetition(competitionId) {
        const confirmBtn = document.getElementById('confirm-delete-competition');
        const originalButtonText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        fetch(competitionRoutes.destroy.replace('__id__', competitionId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to delete competition');
            }
            
            const closeButtons = document.querySelectorAll('[data-modal-hide="delete-competition-modal"]');
            if (closeButtons.length > 0) {
                closeButtons[0].click();
            } else {
                window.deleteCompetitionModal.classList.add('hidden');
            }
            
            showNotification(data.message || 'Kompetisi berhasil dihapus', 'success');
            
            refreshCompetitionsTable();
        })
        .catch(error => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalButtonText;
            
            console.error('Error deleting competition:', error);
            const closeButtons = document.querySelectorAll('[data-modal-hide="delete-competition-modal"]');
            if (closeButtons.length > 0) {
                closeButtons[0].click();
            } else {
                window.deleteCompetitionModal.classList.add('hidden');
            }
            
            showNotification(error.message || 'Gagal menghapus kompetisi. Silakan coba lagi.', 'error');
        });
    }
    
    // Refresh the competitions table.
    async function refreshCompetitionsTable() {
        const tableContainer = document.getElementById('competitions-table-container');
        const paginationContainer = document.getElementById('pagination-container');
        
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('ajax', 'true');
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to refresh competitions');
            }
            
            tableContainer.innerHTML = data.table;
            paginationContainer.innerHTML = data.pagination;
            
            if (data.stats) {
                updateStats(data.stats);
            }
            
            attachEditButtonListeners();
            attachShowButtonListeners();
            attachDeleteButtonListeners();
            attachPaginationHandlers();
            
        } catch (error) {
            console.error('Error refreshing competitions table:', error);
            showNotification('Gagal memuat tabel kompetisi. Silakan muat ulang halaman.', 'error');
        }
    }
    
    // Attach pagination handlers.
    function attachPaginationHandlers() {
        const pagButtons = document.querySelectorAll('.pagination-button');
        pagButtons.forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const pageUrl = this.getAttribute('href');
                if (!pageUrl) return;
                
                try {
                    const url = new URL(pageUrl);
                    url.searchParams.set('ajax', 'true');
                    
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to refresh competitions');
                    }
                    
                    document.getElementById('competitions-table-container').innerHTML = data.table;
                    document.getElementById('pagination-container').innerHTML = data.pagination;
                    
                    window.history.pushState({}, '', pageUrl);
                    
                    attachEditButtonListeners();
                    attachShowButtonListeners();
                    attachDeleteButtonListeners();
                    attachPaginationHandlers();
                    
                } catch (error) {
                    console.error('Error loading page:', error);
                    showNotification('Gagal memuat halaman. Silakan coba lagi.', 'error');
                }
            });
        });
    }
    
    // Update stats cards.
    function updateStats(stats) {
        if (!stats) return;
        
        for (const key in stats) {
            if (stats.hasOwnProperty(key)) {
                const statElement = document.querySelector(`[data-stat-key="${key}"]`);
                if (statElement) {
                    statElement.textContent = stats[key];
                }
            }
        }
    }
    
    // Helper function to get status text.
    function getStatusText(status) {
        const statusMap = {
            'upcoming': 'Akan Datang',
            'active': 'Aktif',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan'
        };
        
        return statusMap[status] || status;
    }
    
    // Helper function to get status class.
    function getStatusClass(status) {
        const classMap = {
            'upcoming': 'bg-yellow-100 text-yellow-800',
            'active': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        
        return classMap[status] || 'bg-gray-100 text-gray-800';
    }
    
    // Display validation errors.
    function displayErrors(errors, form, errorContainer, errorList) {
        const container = document.getElementById(errorContainer);
        const list = document.getElementById(errorList);
        const countElement = document.getElementById(`${errorContainer}-count`);
        
        if (!container || !list) return;
        
        list.innerHTML = '';
        
        const inputFields = form.querySelectorAll('input, select, textarea');
        inputFields.forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
            
            const fieldName = field.getAttribute('name');
            const errorElement = document.getElementById(`${field.id.replace('add-', '')}-error`) || 
                                document.getElementById(`${field.id.replace('edit-', '')}-error`);
            
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
        });
        
        let errorCount = 0;
        let errorMessages = [];
        
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                const messages = errors[field];
                errorCount += messages.length;
                
                messages.forEach(message => {
                    errorMessages.push(`<li>${message}</li>`);
                });
                
                const inputField = form.querySelector(`[name="${field}"]`);
                if (inputField) {
                    inputField.classList.add('border-red-500');
                    inputField.classList.remove('border-gray-300');
                    
                    const fieldId = inputField.id;
                    const errorElement = document.getElementById(`${fieldId.replace('add-', '')}-error`) || 
                                        document.getElementById(`${fieldId.replace('edit-', '')}-error`);
                    
                    if (errorElement) {
                        errorElement.textContent = messages[0]; 
                        errorElement.classList.remove('hidden');
                    }
                }
            }
        }
        
        list.innerHTML = errorMessages.join('');
        if (countElement) countElement.textContent = errorCount;
        container.classList.remove('hidden');
        
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        
        if (step1 && step2) {
            const step1Fields = ['name', 'organizer', 'category_id', 'status', 'level', 'type'];
            const hasStep1Errors = step1Fields.some(field => errors[field]);
            
            const step2Fields = ['start_date', 'end_date', 'registration_start', 'registration_end', 'description'];
            const hasStep2Errors = step2Fields.some(field => errors[field]);
            
            if (hasStep1Errors) {
                showStep(1);
            } else if (hasStep2Errors) {
                showStep(2);
            }
        }
        
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Function to show a specific step in the multi-step form.
    function showStep(stepNumber) {
        const step1 = document.getElementById('step-1-content');
        const step2 = document.getElementById('step-2-content');
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-add-competition');
        const stepItems = document.querySelectorAll('.step-item');
        
        if (stepNumber === 1) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            
            stepItems[0].classList.add('active');
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
        } else if (stepNumber === 2) {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            stepItems[0].classList.add('completed');
            stepItems[1].classList.add('active');
            document.querySelector('.step-line')?.classList.add('bg-blue-600');
            
            const step2Indicator = stepItems[1].querySelector('div');
            if (step2Indicator) {
                step2Indicator.classList.add('bg-blue-600', 'text-white');
                step2Indicator.classList.remove('bg-gray-200', 'text-gray-600');
            }
            
            if (nextBtn) nextBtn.classList.add('hidden');
            if (prevBtn) prevBtn.classList.remove('hidden');
            if (submitBtn) submitBtn.classList.remove('hidden');
        }
    }
    
    // Reset form errors.
    function resetFormErrors(formOrId) {
        const form = typeof formOrId === 'string' ? document.getElementById(formOrId) : formOrId;
        
        if (!form) return;
        
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
        
        const errorElements = form.closest('.fixed')?.querySelectorAll('.error-message') || [];
        errorElements.forEach(error => {
            error.textContent = '';
            error.classList.add('hidden');
        });
        
        const errorContainers = form.closest('.fixed')?.querySelectorAll('[id$="-error"]') || [];
        errorContainers.forEach(container => {
            if (container.id.endsWith('-error') && !container.classList.contains('error-message')) {
                container.classList.add('hidden');
            }
        });
    }
    
    // Function to automatically check and update competition statuses based on current date
    function autoUpdateCompetitionStatuses() {
        // Check if we're on the admin page
        if (!window.location.pathname.includes('/admin')) {
            console.log('Not on admin page, skipping auto status update');
            return;
        }
        
        console.log('Checking for competition status updates...');
        
        // Use the dedicated endpoint for status updates
        fetch('/admin/update-competition-statuses', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update competition statuses');
                return;
            }
            
            const updatedCount = data.updated_count;
            const updatedCompetitions = data.updated_competitions;
            
            if (updatedCount > 0) {
                console.log(`Successfully updated ${updatedCount} competition statuses:`, updatedCompetitions);
                
                // Refresh the competitions table to show updated statuses
                if (typeof refreshCompetitionsTable === 'function') {
                    refreshCompetitionsTable();
                }
                
                // Show notification to user
                showNotification(data.message, 'info');
            } else {
                console.log('All competition statuses are up to date');
            }
        })
        .catch(error => {
            console.error('Error updating competition statuses:', error);
        });
    }
    
    // Show notification.
    function showNotification(message, type = 'success') {
        const existingNotification = document.getElementById('notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.id = 'notification';
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg flex items-center transition-all duration-300 transform translate-x-full`;
        notification.style.minWidth = '320px';
        notification.style.maxWidth = '420px';
        
        if (type === 'success') {
            notification.classList.add('bg-green-50', 'border-l-4', 'border-green-500');
        } else if (type === 'error') {
            notification.classList.add('bg-red-50', 'border-l-4', 'border-red-500');
        } else {
            notification.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
        }
        
        let iconSvg = '';
        if (type === 'success') {
            iconSvg = `
                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
        } else if (type === 'error') {
            iconSvg = `
                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
        } else {
            iconSvg = `
                <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
        }
        
        notification.innerHTML = `
            <div class="flex-shrink-0">
                ${iconSvg}
            </div>
            <div class="ml-3 flex-1 mr-2">
                <p class="${type === 'success' ? 'text-green-700' : type === 'error' ? 'text-red-700' : 'text-blue-700'} text-sm font-medium">
                    ${message}
                </p>
            </div>
            <div class="pl-3 ml-auto">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="close-notification inline-flex text-gray-500 hover:text-gray-700 focus:outline-none">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        // Function to close the displayed notification.
        function closeNotification(notif) {
            notif.classList.remove('translate-x-0');
            notif.classList.add('translate-x-full');
            
            setTimeout(() => {
                notif.remove();
            }, 300);
        }
        
        const closeBtn = notification.querySelector('.close-notification');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeNotification(notification));
        }
        
        setTimeout(() => {
            if (document.body.contains(notification)) {
                closeNotification(notification);
            }
        }, 5000);
        
        return notification;
    }
}); 