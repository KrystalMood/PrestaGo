<!-- Show student Modal -->
<div id="show-student-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Detail Mahasiswa</h3>
                <button type="button" id="close-show-modal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Skeleton Loading -->
            <div id="show-student-skeleton" class="hidden space-y-6">
                <!-- student Header Skeleton -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-16 h-16 rounded-full bg-gray-200 animate-pulse"></div>
                    </div>
                    <div class="flex-1">
                        <div class="h-6 bg-gray-200 rounded w-3/4 animate-pulse mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                    </div>
                </div>

                <!-- student Status Skeleton -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-5 w-20 bg-gray-200 rounded-full animate-pulse"></div>
                            <div class="ml-2 h-4 w-32 bg-gray-200 rounded animate-pulse"></div>
                        </div>
                        <div>
                            <div class="h-5 w-20 bg-gray-200 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- student Details Skeleton -->
                <div class="space-y-4">
                    <div>
                        <div class="h-4 bg-gray-200 rounded w-32 animate-pulse mb-2"></div>
                        <div class="h-5 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                    </div>
                    
                    <div>
                        <div class="h-4 bg-gray-200 rounded w-32 animate-pulse mb-2"></div>
                        <div class="h-24 bg-gray-200 rounded animate-pulse"></div>
                    </div>

                    <!-- Attachments Skeleton -->
                    <div>
                        <div class="h-4 bg-gray-200 rounded w-32 animate-pulse mb-2"></div>
                        <div class="mt-2 grid grid-cols-1 gap-3">
                            <div class="h-12 bg-gray-200 rounded animate-pulse"></div>
                            <div class="h-12 bg-gray-200 rounded animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Skeleton -->
                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end">
                        <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Actual Content -->
            <div id="show-student-content" class="space-y-6">
                <!-- Student Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900" id="show-student-name-header">-</h4>
                            <p class="text-sm text-gray-500" id="show-student-competition-header">-</p>
                        </div>
                    </div>
                </div>

                <!-- Student Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-500">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" id="show-student-status">-</span>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" id="show-student-level">-</span>
                        </div>
                    </div>
                </div>

                <!-- Student Details -->
                <div class="space-y-4">
                    <div id="show-student-nim-container">
                        <h5 class="text-sm font-medium text-gray-500">NIM Mahasiswa</h5>
                        <p class="mt-1 text-sm text-gray-900" id="show-student-nim"></p>
                    </div>
                    
                    <div id="show-student-team-name-container" class="hidden">
                        <h5 class="text-sm font-medium text-gray-500">Nama Tim</h5>
                        <p class="mt-1 text-sm text-gray-900" id="show-student-team-name">-</p>
                    </div>
                    
                    <div id="show-student-members-container">
                        <h5 class="text-sm font-medium text-gray-500">Nama Anggota</h5>
                        <ul id="show-leader-members" class="mt-1 text-sm text-gray-600 font-medium"></ul>
                        <ul id="show-student-members" class="mt-1 text-sm text-gray-900"></ul>
                    </div>
                    
                    <div id="show-student-competition-container">
                        <h5 class="text-sm font-medium text-gray-500">Lomba yang Diikuti</h5>
                        <p class="mt-1 text-sm text-gray-900" id="show-student-competition">-</p>
                    </div>

                    <div id="show-student-advisor-container">
                        <h5 class="text-sm font-medium text-gray-500">Nama Advisor</h5>
                        <p class="mt-1 text-sm text-gray-900" id="show-student-advisor">-</p>
                    </div>

                    <div id="show-student-notes-container">
                        <h5 class="text-sm font-medium text-gray-500">Catatan</h5>
                        <p class="mt-1 text-sm text-gray-600 font-medium" id="show-student-notes">-</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end">
                        <button type="button" id="close-show-student-btn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
