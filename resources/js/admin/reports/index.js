document.addEventListener('DOMContentLoaded', function() {
    initAchievementTrendChart();
    initAchievementsByLevelChart();
    initProgramComparisonChart();
});

function initAchievementTrendChart() {
    const chartContainer = document.getElementById('achievement-trend-chart');
    if (!chartContainer) return;

    const dataContainer = document.getElementById('achievement-trends-container');
    let trendData = [];
    
    if (dataContainer) {
        const trendItems = dataContainer.querySelectorAll('.trend-item');
        trendItems.forEach(item => {
            trendData.push({
                month: item.dataset.month,
                count: parseInt(item.dataset.count)
            });
        });
    }
    
    if (trendData.length === 0) {
        trendData = [
            { month: 'January', count: 12 },
            { month: 'February', count: 19 },
            { month: 'March', count: 15 },
            { month: 'April', count: 22 },
            { month: 'May', count: 28 },
            { month: 'June', count: 25 },
            { month: 'July', count: 30 },
            { month: 'August', count: 24 },
            { month: 'September', count: 18 },
            { month: 'October', count: 15 },
            { month: 'November', count: 12 },
            { month: 'December', count: 8 }
        ];
    }

    const labels = trendData.map(item => item.month);
    const data = trendData.map(item => item.count);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Prestasi',
                data: data,
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1f2937',
                    bodyColor: '#1f2937',
                    borderColor: 'rgba(79, 70, 229, 0.5)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        title: function(tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function(context) {
                            return `${context.raw} Prestasi`;
                        }
                    }
                }
            }
        }
    });
}

function initAchievementsByLevelChart() {
    const chartContainer = document.getElementById('achievements-by-level-chart');
    if (!chartContainer) return;

    const localCount = parseInt(chartContainer.dataset.local || 0);
    const regionalCount = parseInt(chartContainer.dataset.regional || 0);
    const nationalCount = parseInt(chartContainer.dataset.national || 0);
    const internationalCount = parseInt(chartContainer.dataset.international || 0);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Lokal', 'Regional', 'Nasional', 'Internasional'],
            datasets: [{
                data: [localCount, regionalCount, nationalCount, internationalCount],
                backgroundColor: [
                    '#94a3b8',
                    '#60a5fa',
                    '#818cf8',
                    '#34d399',
                ],
                borderWidth: 1,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

function initProgramComparisonChart() {
    const chartContainer = document.getElementById('program-comparison-chart');
    if (!chartContainer) return;

    const programData = [];
    const programDataContainer = document.getElementById('program-data-container');
    
    if (programDataContainer) {
        const programs = programDataContainer.querySelectorAll('.program-data');
        programs.forEach(program => {
            const name = program.dataset.name;
            const achievements = parseInt(program.dataset.achievements || 0);
            
            programData.push({
                name,
                achievements
            });
        });
    } else {
        programData.push(
            { name: 'Teknik Informatika', achievements: 124 },
            { name: 'Sistem Informasi', achievements: 87 },
            { name: 'Manajemen Informatika', achievements: 37 }
        );
    }

    programData.sort((a, b) => b.achievements - a.achievements);

    const labels = programData.map(program => program.name);
    const achievementsData = programData.map(program => program.achievements);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Prestasi',
                data: achievementsData,
                backgroundColor: [
                    'rgba(79, 70, 229, 0.8)',
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(129, 140, 248, 0.8)',
                ],
                borderColor: [
                    'rgba(67, 56, 202, 1)',
                    'rgba(79, 70, 229, 1)',
                    'rgba(99, 102, 241, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Prestasi',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
} 