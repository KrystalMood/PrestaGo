document.addEventListener('DOMContentLoaded', function() {
    initCompetitions();
});

function initCompetitions() {
    initSearchAndFilter();
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

window.studentCompetitions = {
    init: initCompetitions,
    fetchFiltered: fetchFilteredCompetitions
}; 