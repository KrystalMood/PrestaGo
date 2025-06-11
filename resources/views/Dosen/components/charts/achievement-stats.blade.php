@props(['achievementStats'])

<!-- Achievement Stat Cards -->
@include('admin.components.charts.achievement-stat-cards', ['achievementStats' => $achievementStats])

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <h4 class="text-md font-medium text-gray-700 mb-4">Prestasi Berdasarkan Jenis</h4>
        <div class="relative" style="height: 250px;">
            <canvas id="achievementsByTypeChart"></canvas>
            <div id="noTypeDataMessage" class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 text-gray-500">
                Belum ada data prestasi berdasarkan jenis
            </div>
        </div>
    </div>
    <div>
        <h4 class="text-md font-medium text-gray-700 mb-4">Prestasi Berdasarkan Bulan</h4>
        <div class="relative" style="height: 250px;">
            <canvas id="achievementsByMonthChart"></canvas>
            <div id="noMonthDataMessage" class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 text-gray-500">
                Belum ada data prestasi berdasarkan bulan
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for achievements by type chart
        const typeData = @json($achievementStats['byType']);
        const typeLabels = typeData.map(item => item.type);
        const typeCounts = typeData.map(item => item.total);
        
        // Check if we have any data
        const hasTypeData = typeCounts.some(count => count > 0);
        
        // Colors for the doughnut chart
        const backgroundColors = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(199, 199, 199, 0.7)',
        ];
        
        // Create achievements by type chart (doughnut)
        const typeCtx = document.getElementById('achievementsByTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: typeLabels,
                datasets: [{
                    data: typeCounts,
                    backgroundColor: backgroundColors.slice(0, typeLabels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
        
        // Show message if no data
        if (!hasTypeData) {
            document.getElementById('noTypeDataMessage').classList.remove('hidden');
        }
        
        // Data for achievements by month chart
        const monthData = @json($achievementStats['byMonth']);
        const monthLabels = monthData.map(item => item.month);
        const monthCounts = monthData.map(item => item.total);
        
        // Check if we have any month data
        const hasMonthData = monthCounts.some(count => count > 0);
        
        // Create achievements by month chart (line)
        const monthCtx = document.getElementById('achievementsByMonthChart').getContext('2d');
        const monthChart = new Chart(monthCtx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Jumlah Prestasi',
                    data: monthCounts,
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
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
                }
            }
        });
        
        // Show message if no data
        if (!hasMonthData) {
            document.getElementById('noMonthDataMessage').classList.remove('hidden');
        }
        
        // Log data to console for debugging
        console.log('Achievement Stats - Type Data:', typeData);
        console.log('Achievement Stats - Month Data:', monthData);
    });
</script>
@endpush
