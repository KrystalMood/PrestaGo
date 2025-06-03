document.addEventListener('DOMContentLoaded', function() {
    initProgramComparisonChart();
});

function initProgramComparisonChart() {
    const chartContainer = document.getElementById('program-comparison-chart');
    if (!chartContainer) return;

    const programData = [];
    const programTable = document.getElementById('program-details-table');
    
    if (programTable) {
        const rows = programTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(1)').textContent.trim();
            const students = parseInt(row.querySelector('td:nth-child(2)').textContent.trim());
            const achievements = parseInt(row.querySelector('td:nth-child(3)').textContent.trim());
            const participation = parseFloat(row.querySelector('td:nth-child(4)').textContent.trim());
            const successRate = parseFloat(row.querySelector('td:nth-child(5)').textContent.trim());
            
            programData.push({
                name,
                students,
                achievements,
                participation,
                successRate
            });
        });
    }

    programData.sort((a, b) => b.achievements - a.achievements);

    const labels = programData.map(program => program.name);
    const studentsData = programData.map(program => program.students);
    const achievementsData = programData.map(program => program.achievements);
    const participationData = programData.map(program => program.participation);
    const successRateData = programData.map(program => program.successRate);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Jumlah Mahasiswa',
                    data: studentsData,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    type: 'bar',
                    label: 'Jumlah Prestasi',
                    data: achievementsData,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(5, 150, 105, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'Tingkat Partisipasi (%)',
                    data: participationData,
                    borderColor: 'rgba(245, 158, 11, 1)',
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false,
                    tension: 0.1,
                    yAxisID: 'y1'
                },
                {
                    type: 'line',
                    label: 'Tingkat Keberhasilan (%)',
                    data: successRateData,
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false,
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah',
                        font: {
                            size: 12
                        }
                    },
                    beginAtZero: true
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Persentase (%)',
                        font: {
                            size: 12
                        }
                    },
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        padding: 10,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.raw || 0;
                            if (context.dataset.label.includes('%')) {
                                return `${label}: ${value}%`;
                            }
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
} 