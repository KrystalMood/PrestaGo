document.addEventListener('DOMContentLoaded', function() {
    initPeriodsComparisonChart();
    initCategoriesComparisonChart();
});

function initPeriodsComparisonChart() {
    const chartContainer = document.getElementById('periods-comparison-chart');
    if (!chartContainer) return;

    const period1Name = document.querySelector('.period-1-name')?.textContent.trim() || '2024/2025 - Semester 1';
    const period2Name = document.querySelector('.period-2-name')?.textContent.trim() || '2023/2024 - Semester 2';
    
    const period1Achievements = parseInt(document.querySelector('.period-1-achievements')?.textContent.trim() || '156');
    const period2Achievements = parseInt(document.querySelector('.period-2-achievements')?.textContent.trim() || '134');
    
    const period1Participation = parseInt(document.querySelector('.period-1-participation')?.textContent.trim() || '348');
    const period2Participation = parseInt(document.querySelector('.period-2-participation')?.textContent.trim() || '302');
    
    const period1International = parseInt(document.querySelector('.period-1-international')?.textContent.trim() || '28');
    const period2International = parseInt(document.querySelector('.period-2-international')?.textContent.trim() || '22');
    
    const period1National = parseInt(document.querySelector('.period-1-national')?.textContent.trim() || '75');
    const period2National = parseInt(document.querySelector('.period-2-national')?.textContent.trim() || '65');

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Prestasi', 'Partisipasi', 'Internasional', 'Nasional'],
            datasets: [
                {
                    label: period1Name,
                    data: [period1Achievements, period1Participation, period1International, period1National],
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(67, 56, 202, 1)',
                    borderWidth: 1
                },
                {
                    label: period2Name,
                    data: [period2Achievements, period2Participation, period2International, period2National],
                    backgroundColor: 'rgba(99, 102, 241, 0.5)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
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

function initCategoriesComparisonChart() {
    const chartContainer = document.getElementById('categories-comparison-chart');
    if (!chartContainer) return;

    const categoryData = [];
    const categoryTable = document.getElementById('categories-comparison-table');
    
    if (categoryTable) {
        const rows = categoryTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(1)').textContent.trim();
            const period1 = parseInt(row.querySelector('td:nth-child(2)').textContent.trim());
            const period2 = parseInt(row.querySelector('td:nth-child(3)').textContent.trim());
            const change = row.querySelector('td:nth-child(4)').textContent.trim();
            const isPositive = !change.includes('-');
            
            categoryData.push({
                name,
                period1,
                period2,
                change,
                isPositive
            });
        });
    } else {
        categoryData.push(
            { name: 'Pemrograman', period1: 42, period2: 35, change: '+20.0%', isPositive: true },
            { name: 'Data Science & AI', period1: 34, period2: 25, change: '+36.0%', isPositive: true },
            { name: 'UI/UX Design', period1: 28, period2: 22, change: '+27.3%', isPositive: true },
            { name: 'IoT & Embedded Systems', period1: 15, period2: 18, change: '-16.7%', isPositive: false },
            { name: 'Mobile Development', period1: 25, period2: 20, change: '+25.0%', isPositive: true },
            { name: 'Cyber Security', period1: 12, period2: 14, change: '-14.3%', isPositive: false }
        );
    }

    categoryData.sort((a, b) => {
        const aChange = parseFloat(a.change.replace('%', '').replace('+', ''));
        const bChange = parseFloat(b.change.replace('%', '').replace('+', ''));
        return a.isPositive === b.isPositive ? bChange - aChange : (a.isPositive ? -1 : 1);
    });

    const labels = categoryData.map(data => data.name);
    const period1Data = categoryData.map(data => data.period1);
    const period2Data = categoryData.map(data => data.period2);
    const changeData = categoryData.map(data => {
        const value = parseFloat(data.change.replace('%', '').replace('+', ''));
        return data.isPositive ? value : -value;
    });

    const period1Name = document.querySelector('.period-1-name')?.textContent.trim() || '2024/2025 - Semester 1';
    const period2Name = document.querySelector('.period-2-name')?.textContent.trim() || '2023/2024 - Semester 2';

    const ctx = chartContainer.getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: period1Name,
                    data: period1Data,
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(67, 56, 202, 1)',
                    borderWidth: 1,
                    order: 2
                },
                {
                    label: period2Name,
                    data: period2Data,
                    backgroundColor: 'rgba(99, 102, 241, 0.5)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    order: 3
                },
                {
                    label: 'Perubahan (%)',
                    data: changeData,
                    backgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        return value >= 0 ? 'rgba(16, 185, 129, 0.7)' : 'rgba(239, 68, 68, 0.7)';
                    },
                    borderColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        return value >= 0 ? 'rgba(5, 150, 105, 1)' : 'rgba(220, 38, 38, 1)';
                    },
                    borderWidth: 1,
                    type: 'line',
                    yAxisID: 'y1',
                    order: 1
                }
            ]
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
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Perubahan (%)',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
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