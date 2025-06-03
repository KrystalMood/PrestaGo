document.addEventListener('DOMContentLoaded', function() {
    initFeedbackFunctionality();
});

/**
 * Initializes all core functionality for the student feedback page,
 * including star ratings, form submission, and loading previous feedback.
 */
function initFeedbackFunctionality() {
    const stars = document.querySelectorAll('.star-btn');
    const ratingText = document.getElementById('rating-text');
    const ratingInput = document.getElementById('overall_rating');
    const ratingTexts = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
    
    if (stars.length && ratingText && ratingInput) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;
                ratingText.textContent = ratingTexts[rating - 1];
                stars.forEach((s, index) => {
                    const starSvg = s.querySelector('svg');
                    if (index < rating) {
                        starSvg.classList.remove('text-gray-300');
                        starSvg.classList.add('text-yellow-400');
                        starSvg.setAttribute('fill', 'currentColor');
                    } else {
                        starSvg.classList.add('text-gray-300');
                        starSvg.classList.remove('text-yellow-400');
                        starSvg.setAttribute('fill', 'none');
                    }
                });
            });
            
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                stars.forEach((s, index) => {
                    const starSvg = s.querySelector('svg');
                    if (index < rating) {
                        starSvg.classList.add('text-yellow-400');
                        starSvg.classList.remove('text-gray-300');
                    }
                });
            });
            
            star.addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                stars.forEach((s, index) => {
                    const starSvg = s.querySelector('svg');
                    if (index < currentRating) {
                        starSvg.classList.add('text-yellow-400');
                        starSvg.classList.remove('text-gray-300');
                    } else {
                        starSvg.classList.remove('text-yellow-400');
                        starSvg.classList.add('text-gray-300');
                    }
                });
            });
        });
    }
    
    const form = document.getElementById('feedback-form');
    const competitionSelect = document.getElementById('competition_id');
    const feedbackContent = document.getElementById('feedback-form-content');
    const submitButton = document.getElementById('submit-button');
    const feedbackExistsWarning = document.getElementById('feedback-exists-warning');
    
    if (competitionSelect) {
        competitionSelect.addEventListener('change', function() {
            const competitionId = this.value;
            if (competitionId) {
                checkFeedbackEligibility(competitionId);
            } else {
                enableFeedbackForm(true);
            }
        });
    }
    
    if (form && ratingInput) {
        form.addEventListener('submit', function(e) {
            if (!ratingInput.value) {
                e.preventDefault();
                alert('Mohon berikan penilaian keseluruhan dengan memilih bintang.');
                return;
            }
        });
    }

    loadPreviousFeedback();
}

/**
 * Checks if the user is eligible to provide feedback for the selected competition
 * @param {number} competitionId - The ID of the selected competition
 */
function checkFeedbackEligibility(competitionId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const feedbackContent = document.getElementById('feedback-form-content');
    const submitButton = document.getElementById('submit-button');
    const feedbackExistsWarning = document.getElementById('feedback-exists-warning');
    
    // Show loading state
    if (feedbackContent) {
        feedbackContent.classList.add('opacity-50', 'pointer-events-none');
    }
    if (submitButton) {
        submitButton.disabled = true;
    }
    
    fetch('/student/feedback/check-eligibility', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            competition_id: competitionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.eligible) {
            // User is eligible to provide feedback
            enableFeedbackForm(true);
            if (feedbackExistsWarning) {
                feedbackExistsWarning.classList.add('hidden');
            }
        } else {
            // User is not eligible to provide feedback
            enableFeedbackForm(false);
            if (feedbackExistsWarning) {
                feedbackExistsWarning.textContent = data.message;
                feedbackExistsWarning.classList.remove('hidden');
            }
        }
    })
    .catch(error => {
        console.error('Error checking feedback eligibility:', error);
        // In case of error, enable the form to prevent blocking the user
        enableFeedbackForm(true);
    })
    .finally(() => {
        // Remove loading state
        if (feedbackContent) {
            feedbackContent.classList.remove('opacity-50', 'pointer-events-none');
        }
    });
}

/**
 * Enables or disables the feedback form based on eligibility
 * @param {boolean} enable - Whether to enable the form
 */
function enableFeedbackForm(enable) {
    const feedbackContent = document.getElementById('feedback-form-content');
    const submitButton = document.getElementById('submit-button');
    
    if (feedbackContent) {
        if (enable) {
            feedbackContent.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            feedbackContent.classList.add('opacity-50', 'pointer-events-none');
        }
    }
    
    if (submitButton) {
        submitButton.disabled = !enable;
    }
}

/**
 * Loads previously submitted feedback data.
 * (The actual data fetching logic, e.g., via AJAX, would be implemented here).
 */
function loadPreviousFeedback() {
    const container = document.getElementById('previous-feedback-container');
    
    if (container) {
        // Placeholder for AJAX call logic to fetch data from the server
    }
}

/**
 * Renders a list of previous feedback items into the designated container on the page.
 * @param {Array} feedbackItems - An array of feedback objects to be displayed.
 */
function renderPreviousFeedback(feedbackItems) {
    const container = document.getElementById('previous-feedback-container');
    
    if (container && feedbackItems && feedbackItems.length > 0) {
        let html = '<div class="divide-y divide-gray-200">';
        
        feedbackItems.forEach(item => {
            const ratingStars = generateRatingStars(item.overall_rating);
            const date = new Date(item.created_at).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            html += `
                <div class="py-6 px-4 sm:px-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900">${item.competition_name}</h4>
                        <div class="flex items-center">
                            ${ratingStars}
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Dikirim pada ${date}
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 mb-4">
                        <p class="font-medium">Kelebihan:</p>
                        <p class="mt-1">${item.strengths}</p>
                    </div>
                    <div class="text-sm text-gray-700 mb-4">
                        <p class="font-medium">Saran Perbaikan:</p>
                        <p class="mt-1">${item.improvements}</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="deleteFeedback(${item.id})" class="text-sm text-red-600 hover:text-red-800">
                            Hapus Feedback
                        </button>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
    }
}

/**
 * Generates the HTML string for displaying a star rating.
 * @param {number} rating - The numerical rating value (e.g., 1 to 5).
 * @returns {string} - The HTML string representing the stars.
 */
function generateRatingStars(rating) {
    let html = '<div class="flex items-center">';
    
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            html += `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            `;
        } else {
            html += `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            `;
        }
    }
    
    html += '</div>';
    return html;
}

/**
 * Handles the deletion of a specific feedback entry.
 * It will prompt the user for confirmation before proceeding with the deletion.
 * @param {number} id - The ID of the feedback entry to be deleted.
 */
function deleteFeedback(id) {
    if (confirm('Apakah Anda yakin ingin menghapus feedback ini?')) {
        console.log('Deleting feedback with ID:', id);
        // Placeholder for AJAX delete request logic
    }
}

window.deleteFeedback = deleteFeedback;