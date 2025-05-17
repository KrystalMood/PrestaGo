<div class="bg-white rounded-lg shadow-custom overflow-hidden mt-6">
    <div class="p-4 bg-emerald-50">
        <h2 class="font-semibold text-emerald-800">Periode Perbandingan</h2>
    </div>
    <div class="p-4">
        <form>
            <div class="space-y-4">
                <div>
                    <label for="period1" class="block text-sm font-medium text-gray-700">Periode 1</label>
                    <select id="period1" name="period1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="2024-2">2024/2025 - Semester 2</option>
                        <option value="2024-1">2024/2025 - Semester 1</option>
                        <option value="2023-2">2023/2024 - Semester 2</option>
                        <option value="2023-1">2023/2024 - Semester 1</option>
                    </select>
                </div>
                <div>
                    <label for="period2" class="block text-sm font-medium text-gray-700">Periode 2</label>
                    <select id="period2" name="period2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="2024-1">2024/2025 - Semester 1</option>
                        <option value="2023-2">2023/2024 - Semester 2</option>
                        <option value="2023-1">2023/2024 - Semester 1</option>
                        <option value="2022-2">2022/2023 - Semester 2</option>
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
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Bandingkan Periode
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 