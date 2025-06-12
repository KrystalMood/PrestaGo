document.addEventListener('DOMContentLoaded', function() {
    initRecommendations();
});

function initRecommendations() {
    const recommendationsContainer = document.getElementById('recommendations-container');
    
    if (!recommendationsContainer) {
        return;
    }
    
    const recommendationCards = recommendationsContainer.querySelectorAll('.recommendation-card');
    recommendationCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName !== 'A') {
                const detailLink = this.querySelector('a[href]');
                if (detailLink) {
                    window.location.href = detailLink.getAttribute('href');
                }
            }
        });
    });
}

function updateRecommendationsStats(stats) {
    const recommendationsStatsElement = document.querySelector('[data-key="recommendedCompetitions"] .text-2xl');
    
    if (recommendationsStatsElement && stats.recommendedCompetitions !== undefined) {
        recommendationsStatsElement.textContent = stats.recommendedCompetitions;
    }
}

window.studentRecommendations = {
    init: initRecommendations,
    updateStats: updateRecommendationsStats
}; 