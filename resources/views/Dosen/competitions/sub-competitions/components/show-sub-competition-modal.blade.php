<!-- Show Sub-Competition Modal -->
<div id="show-sub-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 my-6 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Sub-Kompetisi</h3>
                <button type="button" id="close-show-sub-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Skeleton Loading -->
            <div class="sub-competition-detail-skeleton">
                <div class="flex items-center justify-center mb-6">
                    <div class="h-24 w-24 rounded-full bg-gray-200 animate-pulse"></div>
                </div>
                
                <!-- Sub-Competition Name Skeleton -->
                <div class="text-center mb-6">
                    <div class="h-8 bg-gray-200 rounded w-3/4 mx-auto mb-2 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mx-auto animate-pulse"></div>
                </div>
                
                <!-- Sub-Competition Details Skeleton -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Sub-Kompetisi</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="h-8 bg-gray-200 rounded w-24 animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Peserta</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai - Selesai</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendaftaran</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
                
                <!-- Skills Skeleton -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Skill yang Dibutuhkan</label>
                    <div class="h-16 bg-gray-200 rounded animate-pulse"></div>
                </div>
                
                <!-- Description Skeleton -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <div class="h-32 bg-gray-200 rounded animate-pulse"></div>
                </div>
                
                <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                    <div class="h-4 bg-gray-200 rounded w-48 animate-pulse"></div>
                    <div class="flex space-x-3">
                        <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
            
            <!-- Student View Preview -->
            <div class="mb-6 border-b border-gray-200 pb-4 hidden sub-competition-detail-content">
                <h4 class="text-sm font-medium text-gray-500 mb-2">PREVIEW TAMPILAN MAHASISWA</h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Left Column: Details -->
                        <div class="md:w-2/3">
                            <h2 id="sub-competition-name-preview" class="text-xl font-bold text-gray-900 mb-2"></h2>
                            
                            <div class="flex items-center mb-4">
                                <span id="sub-competition-status-preview" class="px-2 py-1 text-xs font-medium rounded-full"></span>
                                <span id="sub-competition-category-preview" class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800"></span>
                            </div>
                            
                            <div class="prose prose-sm max-w-none text-gray-600 mb-4">
                                <p id="sub-competition-description-preview"></p>
                            </div>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span id="sub-competition-dates-preview" class="text-gray-700"></span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="sub-competition-registration-preview" class="text-gray-700"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Skills -->
                        <div class="md:w-1/3 bg-white rounded-lg border border-gray-200 p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Skill yang Dibutuhkan</h3>
                            <div id="sub-competition-skills-preview" class="flex flex-wrap gap-2">
                                <!-- Skills will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actual Content -->
            <div class="sub-competition-detail-content hidden">
                <div class="flex items-center justify-center mb-6">
                    <div class="h-24 w-24 rounded-full overflow-hidden bg-blue-100 flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                
                <!-- Sub-Competition Name -->
                <div class="text-center mb-6">
                    <h2 id="sub-competition-name" class="text-2xl font-bold text-gray-900"></h2>
                    <p id="sub-competition-category" class="text-gray-500"></p>
                </div>
                
                <!-- Sub-Competition Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Sub-Kompetisi</label>
                        <div id="sub-competition-id" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="flex items-center">
                            <span id="sub-competition-status" class="px-3 py-1 text-xs leading-5 font-semibold rounded-full"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <div id="sub-competition-category-detail" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Peserta</label>
                        <div id="sub-competition-participants" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai - Selesai</label>
                        <div id="sub-competition-dates" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendaftaran</label>
                        <div id="sub-competition-registration" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                        <div id="sub-competition-date" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                </div>
                
                <!-- Skills -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Skill yang Dibutuhkan</label>
                    <div id="sub-competition-skills" class="bg-gray-50 px-3 py-3 rounded-md border border-gray-300 text-gray-700 min-h-[60px]">
                        <div class="flex flex-wrap gap-2">
                            <!-- Skills will be populated here -->
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <div id="sub-competition-description" class="bg-gray-50 px-3 py-3 rounded-md border border-gray-300 text-gray-700 min-h-[100px]"></div>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-between pt-5 border-t border-gray-200">
                    <div id="show-sub-competition-updated-at" class="text-sm text-gray-500"></div>
                    <div class="flex space-x-3">
                        <button id="close-show-sub-competition" type="button" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Tutup
                        </button>
                        <button id="edit-show-sub-competition" type="button" class="edit-sub-competition px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 