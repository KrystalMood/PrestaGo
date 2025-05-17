<div class="bg-white rounded-lg shadow-custom overflow-hidden mt-6">
    <div class="p-4 bg-purple-50">
        <h2 class="font-semibold text-purple-800">Filter Program Studi</h2>
    </div>
    <div class="p-4">
        <form>
            <div class="space-y-4">
                <div>
                    <label for="period" class="block text-sm font-medium text-gray-700">Periode</label>
                    <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="current">Periode Saat Ini (2024/2025 - Semester 1)</option>
                        <option value="prev">Periode Sebelumnya (2023/2024 - Semester 2)</option>
                        <option value="yearly">Tahunan (2024)</option>
                        <option value="all">Semua Periode</option>
                    </select>
                </div>
                <div>
                    <label for="program" class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <select id="program" name="program" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all">Bandingkan Semua Program Studi</option>
                        <option value="ti">Teknik Informatika</option>
                        <option value="si">Sistem Informasi</option>
                        <option value="mi">Manajemen Informatika</option>
                    </select>
                </div>
                <div>
                    <label for="metric" class="block text-sm font-medium text-gray-700">Metrik Analisis</label>
                    <select id="metric" name="metric" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="participation">Tingkat Partisipasi</option>
                        <option value="achievement">Prestasi Diraih</option>
                        <option value="success_rate">Rasio Keberhasilan</option>
                        <option value="competition_diversity">Keragaman Lomba</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input id="include_demographics" name="include_demographics" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="include_demographics" class="ml-2 block text-sm text-gray-700">Sertakan data demografis</label>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Analisis Program Studi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 