document.addEventListener('DOMContentLoaded', function() {
    initProgramComparisonChart();
});

function initProgramComparisonChart() {
    const ctx = document.getElementById('program-comparison-chart');
    
    if (!ctx) return;
    
    const programs = [];
    const achievements = [];
    const students = [];
    const participationRates = [];
    
    const rows = document.querySelectorAll('#program-details-table tbody tr');
    
    if (rows.length === 0 || rows[0].querySelector('td[colspan]')) {
        console.log('No program data available');
        return;
    }
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 5) {
            programs.push(cells[0].textContent.trim());
            students.push(parseInt(cells[1].textContent.trim(), 10));
            achievements.push(parseInt(cells[2].textContent.trim(), 10));
            
            const participationText = cells[3].textContent.trim();
            const participationValue = parseFloat(participationText.replace('%', ''));
            participationRates.push(participationValue);
        }
    });
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: programs,
            datasets: [
                {
                    label: 'Total Prestasi',
                    data: achievements,
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Tingkat Partisipasi (%)',
                    data: participationRates,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Perbandingan Prestasi dan Partisipasi per Program Studi'
                }
            }
        }
    });
} 