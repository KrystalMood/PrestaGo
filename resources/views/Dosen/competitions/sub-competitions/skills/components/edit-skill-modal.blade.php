<!-- Edit Skill Modal -->
<div id="edit-skill-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Skill</h3>
                    <button type="button" id="close-edit-skill-modal" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Skeleton Loading -->
            <div id="edit-skill-skeleton" class="hidden p-6 space-y-5">
                <!-- Skill Name Skeleton -->
                <div>
                    <div class="block text-sm font-medium text-gray-700 mb-1.5">Skill</div>
                    <div class="h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="mt-1.5 h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                </div>
                
                <!-- Importance Level Skeleton -->
                <div>
                    <div class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Kepentingan (1-10)</div>
                    <div class="h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="mt-1.5 h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                </div>
                
                <!-- Weight Value Skeleton -->
                <div>
                    <div class="block text-sm font-medium text-gray-700 mb-1.5">Nilai Bobot</div>
                    <div class="h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="mt-1.5 h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                </div>
                
                <!-- Criterion Type Skeleton -->
                <div>
                    <div class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Kriteria</div>
                    <div class="h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="mt-1.5 h-4 bg-gray-200 rounded w-full animate-pulse"></div>
                </div>
            </div>

            <form id="edit-skill-form">
                @csrf
                <div class="p-6 space-y-5">
                    <input type="hidden" id="edit_skill_pivot_id" name="skill_pivot_id">
                    <input type="hidden" id="edit_skill_id" name="skill_id">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Skill</label>
                        <div class="text-sm font-medium py-2.5 px-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                            <span id="edit_skill_name"></span> (<span id="edit_skill_category"></span>)
                        </div>
                        <p class="mt-1.5 text-sm text-gray-500">
                            Skill yang dipilih untuk diedit
                        </p>
                    </div>
                    
                    <div>
                        <label for="edit_importance_level" class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Kepentingan (1-10)</label>
                        <div class="relative">
                            <input type="number" class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="edit_importance_level" name="importance_level" min="1" max="10" required>
                        </div>
                        <p class="mt-1.5 text-sm text-gray-500">
                            1 = Paling tidak penting, 10 = Paling penting
                        </p>
                    </div>
                    
                    <div>
                        <label for="edit_weight_value" class="block text-sm font-medium text-gray-700 mb-1.5">Nilai Bobot</label>
                        <div class="relative">
                            <input type="number" class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="edit_weight_value" name="weight_value" step="0.01" min="0">
                        </div>
                        <p class="mt-1.5 text-sm text-gray-500">
                            Nilai bobot yang digunakan untuk perhitungan
                        </p>
                    </div>
                    
                    <div>
                        <label for="edit_criterion_type" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Kriteria</label>
                        <div class="relative">
                            <select id="edit_criterion_type" name="criterion_type" class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                        <p class="mt-1.5 text-sm text-gray-500">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800 ring-1 ring-green-200 mr-1">Benefit</span>: Nilai lebih tinggi lebih baik | 
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-800 ring-1 ring-red-200 ml-1">Cost</span>: Nilai lebih rendah lebih baik
                        </p>
                    </div>

                    <div id="edit-skill-errors" class="hidden bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul id="edit-skill-error-list" class="list-disc pl-5 space-y-1"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200 rounded-b-xl">
                    <button type="button" id="cancel-edit-skill" class="inline-flex justify-center py-2.5 px-5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">Batal</button>
                    <button type="submit" id="submit-edit-skill" class="inline-flex justify-center py-2.5 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div> 