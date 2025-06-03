document.addEventListener('DOMContentLoaded', function() {
    initAchievementsByLevelChart();
    initStudyProgramPerformanceChart();
});

function initAchievementsByLevelChart() {
    const chartContainer = document.getElementById('achievements-by-level-chart');
    if (!chartContainer) return;

    const localCount = parseInt(chartContainer.dataset.local || 0);
    const regionalCount = parseInt(chartContainer.dataset.regional || 0);
    const nationalCount = parseInt(chartContainer.dataset.national || 0);
    const internationalCount = parseInt(chartContainer.dataset.international || 0);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'pie',
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
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        padding: 15,
                        font: {
                            size: 12
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

function initStudyProgramPerformanceChart() {
    const chartContainer = document.getElementById('study-program-performance-chart');
    if (!chartContainer) return;

    const programStats = [];
    const programTable = document.getElementById('program-stats-table');
    
    if (programTable) {
        const rows = programTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(1)').textContent.trim();
            const total = parseInt(row.querySelector('td:nth-child(2)').textContent.trim());
            const international = parseInt(row.querySelector('td:nth-child(3)').textContent.trim());
            const national = parseInt(row.querySelector('td:nth-child(4)').textContent.trim());
            const regional = parseInt(row.querySelector('td:nth-child(5)').textContent.trim());
            const local = parseInt(row.querySelector('td:nth-child(6)').textContent.trim());
            
            programStats.push({
                name,
                total,
                international,
                national,
                regional,
                local
            });
        });
    }

    programStats.sort((a, b) => b.total - a.total);

    const labels = programStats.map(program => program.name);
    const internationalData = programStats.map(program => program.international);
    const nationalData = programStats.map(program => program.national);
    const regionalData = programStats.map(program => program.regional);
    const localData = programStats.map(program => program.local);

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Internasional',
                    data: internationalData,
                    backgroundColor: '#34d399',
                    borderColor: '#10b981',
                    borderWidth: 1
                },
                {
                    label: 'Nasional',
                    data: nationalData,
                    backgroundColor: '#818cf8',
                    borderColor: '#6366f1',
                    borderWidth: 1
                },
                {
                    label: 'Regional',
                    data: regionalData,
                    backgroundColor: '#60a5fa',
                    borderColor: '#3b82f6',
                    borderWidth: 1
                },
                {
                    label: 'Lokal',
                    data: localData,
                    backgroundColor: '#94a3b8',
                    borderColor: '#64748b',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true
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
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
} 