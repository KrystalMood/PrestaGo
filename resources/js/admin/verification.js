document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const filterSelect = document.getElementById('filter-select');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateVerificationTable();
        });
    }
    
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            updateVerificationTable();
        });
    }
    
    // Function to update the verification table based on search and filter
    function updateVerificationTable() {
        const searchQuery = document.getElementById('search-input')?.value || '';
        const filterValue = filterSelect?.value || 'all';
        
        const url = new URL(window.verificationRoutes.index);
        url.searchParams.append('search', searchQuery);
        url.searchParams.append('status', filterValue);
        url.searchParams.append('ajax', 1);
        
        window.history.pushState({}, '', `${window.verificationRoutes.index}?search=${searchQuery}&status=${filterValue}`);
        
        const tableContainer = document.getElementById('verifications-table-container');
        const paginationContainer = document.getElementById('pagination-container');
        
        if (tableContainer) {
            tableContainer.innerHTML = `
                <div class="flex justify-center items-center py-20">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            `;
        }
        
        fetch(url.toString())
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (tableContainer) tableContainer.innerHTML = data.tableHtml;
                if (paginationContainer) paginationContainer.innerHTML = data.paginationHtml;
                
                attachEventListeners();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                if (tableContainer) {
                    tableContainer.innerHTML = `
                        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">An error occurred!</strong>
                            <span class="block sm:inline"> Failed to load data. Please try again later.</span>
                        </div>
                    `;
                }
            });
    }
    
    // Function to attach event listeners to dynamically added elements
    function attachEventListeners() {
        document.querySelectorAll('.show-verification').forEach(button => {
            button.addEventListener('click', function() {
                const verificationId = this.getAttribute('data-verification-id');
                showVerificationModal(verificationId);
            });
        });
        
        document.querySelectorAll('.approve-verification').forEach(button => {
            button.addEventListener('click', function() {
                const verificationId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to approve this verification?')) {
                    updateVerificationStatus(verificationId, 'approved');
                }
            });
        });
        
        // Reject verification
        document.querySelectorAll('.reject-verification').forEach(button => {
            button.addEventListener('click', function() {
                const verificationId = this.getAttribute('data-id');
                const reason = prompt('Enter reason for rejection:');
                if (reason) {
                    updateVerificationStatus(verificationId, 'rejected', reason);
                }
            });
        });
    }
    
    // Function to update verification status
    function updateVerificationStatus(id, status, reason = null) {
        const url = window.verificationRoutes.update.replace('__ID__', id);
        
        const data = {
            status: status
        };
        
        if (reason) {
            data.reason = reason;
        }
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                
                updateVerificationTable();
            } else {
                alert(data.message || 'An error occurred. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status. Please try again.');
        });
    }
    
    attachEventListeners();
}); 