<div class="bg-white rounded-lg shadow-custom overflow-hidden mt-6">
    <div class="p-4 bg-blue-50">
        <h2 class="font-semibold text-blue-800">Filter Tren</h2>
    </div>
    <div class="p-4">
        <form>
            <div class="space-y-4">
                <div>
                    <label for="time_range" class="block text-sm font-medium text-gray-700">Rentang Waktu</label>
                    <select id="time_range" name="time_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="5y">5 Tahun Terakhir</option>
                        <option value="3y">3 Tahun Terakhir</option>
                        <option value="1y">1 Tahun Terakhir</option>
                        <option value="custom">Kustom</option>
                    </select>
                </div>
                <div>
                    <label for="program" class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <select id="program" name="program" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all">Semua Program Studi</option>
                        <option value="ti">Teknik Informatika</option>
                        <option value="si">Sistem Informasi</option>
                        <option value="mi">Manajemen Informatika</option>
                    </select>
                </div>
                <div>
                    <label for="trend_type" class="block text-sm font-medium text-gray-700">Jenis Tren</label>
                    <select id="trend_type" name="trend_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="participation">Partisipasi</option>
                        <option value="achievement">Prestasi</option>
                        <option value="level">Tingkat Lomba</option>
                    </select>
                </div>
                <div class="hidden" id="custom_date_range">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeRangeSelect = document.getElementById('time_range');
        const customDateRange = document.getElementById('custom_date_range');
        
        timeRangeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        });
    });
</script> 