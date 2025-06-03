document.addEventListener('DOMContentLoaded', function() {
    initAnnualTrendChart();
    initQuarterlyTrendChart();
});

function initAnnualTrendChart() {
    const chartContainer = document.getElementById('annual-trend-chart');
    if (!chartContainer) return;

    const yearlyData = [];
    const yearlyTable = document.getElementById('yearly-comparison-table');
    
    if (yearlyTable) {
        const rows = yearlyTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const year = row.querySelector('td:nth-child(1)').textContent.trim();
            const participation = parseInt(row.querySelector('td:nth-child(2)').textContent.trim());
            const achievements = parseInt(row.querySelector('td:nth-child(3)').textContent.trim());
            const successRate = parseFloat(row.querySelector('td:nth-child(4)').textContent.trim());
            
            yearlyData.push({
                year,
                participation,
                achievements,
                successRate
            });
        });
    }

    yearlyData.sort((a, b) => a.year - b.year);

    const labels = yearlyData.map(data => data.year);
    const participationData = yearlyData.map(data => data.participation);
    const achievementsData = yearlyData.map(data => data.achievements);
    const successRateData = yearlyData.map(data => data.successRate);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Partisipasi',
                    data: participationData,
                    borderColor: 'rgba(99, 102, 241, 1)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Prestasi',
                    data: achievementsData,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Tingkat Keberhasilan (%)',
                    data: successRateData,
                    borderColor: 'rgba(245, 158, 11, 1)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tahun',
                        font: {
                            size: 12
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
                            if (label.includes('%')) {
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

function initQuarterlyTrendChart() {
    const chartContainer = document.getElementById('quarterly-trend-chart');
    if (!chartContainer) return;

    const quarterlyData = [
        { period: '2023 Q1', participation: 58, achievements: 32 },
        { period: '2023 Q2', participation: 65, achievements: 38 },
        { period: '2023 Q3', participation: 72, achievements: 42 },
        { period: '2023 Q4', participation: 80, achievements: 45 },
        { period: '2024 Q1', participation: 85, achievements: 48 },
        { period: '2024 Q2', participation: 92, achievements: 53 },
        { period: '2024 Q3', participation: 98, achievements: 57 },
        { period: '2024 Q4', participation: 105, achievements: 62 },
        { period: '2025 Q1', participation: 112, achievements: 67 },
        { period: '2025 Q2', participation: 120, achievements: 72 },
    ];

    const labels = quarterlyData.map(data => data.period);
    const participationData = quarterlyData.map(data => data.participation);
    const achievementsData = quarterlyData.map(data => data.achievements);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Partisipasi',
                    data: participationData,
                    borderColor: 'rgba(99, 102, 241, 1)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Prestasi',
                    data: achievementsData,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Periode',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah',
                        font: {
                            size: 12
                        }
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
                }
            }
        }
    });
} 