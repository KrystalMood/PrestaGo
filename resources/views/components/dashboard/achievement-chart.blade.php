@props(['chartId' => 'achievementsChart'])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Statistik Prestasi</h3>
        <div class="flex items-center space-x-2">
            <select id="chart-year-filter" class="select select-sm select-bordered">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
            </select>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-sm btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="#" id="download-chart-png">Unduh PNG</a></li>
                    <li><a href="#" id="view-detailed-stats">Lihat Statistik Detail</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="h-80">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
            
            const achievementData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Prestasi Diajukan',
                        data: [42, 49, 65, 74, 55, 58, 63, 60, 66, 70, 78, 83],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Prestasi Terverifikasi',
                        data: [36, 42, 60, 66, 49, 52, 58, 53, 59, 65, 70, 74],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            };
            
            const achievementsChart = new Chart(ctx, {
                type: 'line',
                data: achievementData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6,
                                font: {
                                    family: "'Figtree', sans-serif",
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: 'rgba(0, 0, 0, 0.05)',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 5,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Figtree', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    family: "'Figtree', sans-serif",
                                    size: 11
                                }
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 3,
                            hoverRadius: 5
                        }
                    }
                }
            });
            
            document.getElementById('download-chart-png').addEventListener('click', function() {
                const link = document.createElement('a');
                link.download = 'statistik-prestasi.png';
                link.href = achievementsChart.toBase64Image();
                link.click();
            });
            
            document.getElementById('chart-year-filter').addEventListener('change', function() {
                const year = this.value;
                
                if (year === '2024') {
                    achievementsChart.data.datasets[0].data = [28, 32, 45, 51, 38, 42, 46, 39, 47, 52, 58, 63];
                    achievementsChart.data.datasets[1].data = [24, 28, 40, 45, 33, 37, 41, 36, 42, 48, 52, 56];
                } else {
                    achievementsChart.data.datasets[0].data = [42, 49, 65, 74, 55, 58, 63, 60, 66, 70, 78, 83];
                    achievementsChart.data.datasets[1].data = [36, 42, 60, 66, 49, 52, 58, 53, 59, 65, 70, 74];
                }
                
                achievementsChart.update();
            });
        });
    </script>
    @endpush
@endonce