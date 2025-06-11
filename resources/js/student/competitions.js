document.addEventListener('DOMContentLoaded', function() {
    initCompetitions();
});

function initCompetitions() {
    initSearchAndFilter();
    autoUpdateCompetitionStatuses();
}

function initSearchAndFilter() {
    const searchForm = document.getElementById('competition-search-form');
    const categoryFilter = document.getElementById('category-filter');
    const statusFilter = document.getElementById('status-filter');
    const sortFilter = document.getElementById('sort-filter');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchFilteredCompetitions();
        });
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            fetchFilteredCompetitions();
        });
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            fetchFilteredCompetitions();
        });
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            fetchFilteredCompetitions();
        });
    }
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').getAttribute('href');
            fetchCompetitionsFromUrl(url);
        }
    });
}

function fetchFilteredCompetitions() {
    const searchForm = document.getElementById('competition-search-form');
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const statusFilter = document.getElementById('status-filter');
    const sortFilter = document.getElementById('sort-filter');
    
    let url = new URL(window.competitionRoutes.index);
    
    if (searchInput && searchInput.value) {
        url.searchParams.append('search', searchInput.value);
    }
    
    if (categoryFilter && categoryFilter.value) {
        url.searchParams.append('category', categoryFilter.value);
    }
    
    if (statusFilter && statusFilter.value) {
        url.searchParams.append('status', statusFilter.value);
    }
    
    if (sortFilter && sortFilter.value) {
        url.searchParams.append('sort', sortFilter.value);
    }
    
    fetchCompetitionsFromUrl(url.toString());
}

function fetchCompetitionsFromUrl(url) {
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('competitions-table-container').innerHTML = data.table;
            
            document.getElementById('pagination-container').innerHTML = data.pagination;
            
            updateStats(data.stats);
            
            autoUpdateCompetitionStatuses();
            
            document.getElementById('competitions-table-container').scrollIntoView({
                behavior: 'smooth'
            });
        }
    })
    .catch(error => {
        console.error('Error fetching competitions:', error);
    });
}

function updateStats(stats) {
    if (!stats) return;
    
    Object.keys(stats).forEach(key => {
        const statElement = document.querySelector(`[data-key="${key}"] .text-2xl`);
        if (statElement) {
            statElement.textContent = stats[key];
        }
    });
    
    if (window.studentRecommendations && window.studentRecommendations.updateStats) {
        window.studentRecommendations.updateStats(stats);
    }
}

// Function to automatically update competition statuses based on dates
function autoUpdateCompetitionStatuses() {
    const competitionRows = document.querySelectorAll('[data-competition-id]');
    
    competitionRows.forEach(row => {
        const startDate = row.dataset.startDate ? new Date(row.dataset.startDate) : null;
        const endDate = row.dataset.endDate ? new Date(row.dataset.endDate) : null;
        
        if (!startDate || !endDate) return;
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        let status = 'upcoming';
        
        if (today < startDate) {
            status = 'upcoming';
        } else if (today >= startDate && today <= endDate) {
            status = 'active';
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

// Function to get the text representation of a status
function getStatusText(status) {
    switch (status) {
        case 'upcoming':
            return 'Akan Datang';
        case 'active':
            return 'Aktif';
        case 'completed':
            return 'Selesai';
        default:
            return 'Unknown';
    }
}

// Function to get the CSS class for a status
function getStatusClass(status) {
    switch (status) {
        case 'upcoming':
            return 'bg-blue-100 text-blue-800';
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'completed':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

window.studentCompetitions = {
    init: initCompetitions,
    fetchFiltered: fetchFilteredCompetitions
}; 