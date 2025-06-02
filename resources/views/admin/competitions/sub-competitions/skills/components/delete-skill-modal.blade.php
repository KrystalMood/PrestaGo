<!-- Delete Skill Modal -->
<div id="delete-skill-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
                    <button type="button" id="close-delete-skill-modal" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form id="delete-skill-form">
                @csrf
                <input type="hidden" id="delete_skill_id" name="skill_id">
                <div class="p-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-5 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Apakah Anda yakin ingin menghapus skill <strong><span id="delete_skill_name" class="font-semibold"></span></strong> dari sub-kompetisi ini?
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Tindakan ini hanya akan menghapus skill dari sub-kompetisi ini. Skill itu sendiri akan tetap ada dalam sistem dan dapat ditambahkan kembali nanti jika diperlukan.
                    </p>

                    <div id="delete-skill-errors" class="hidden mt-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul id="delete-skill-error-list" class="list-disc pl-5 space-y-1"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200 rounded-b-xl">
                    <button type="button" id="cancel-delete-skill" class="inline-flex justify-center py-2.5 px-5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">Batal</button>
                    <button type="submit" id="submit-delete-skill" class="inline-flex justify-center py-2.5 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
