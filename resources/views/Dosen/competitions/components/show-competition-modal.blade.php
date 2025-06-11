<!-- Show Competition Modal -->
<div id="show-competition-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 my-6 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Kompetisi</h3>
                <button type="button" id="close-show-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Skeleton Loading -->
            <div class="competition-detail-skeleton">
                <div class="flex items-center justify-center mb-6">
                    <div class="h-24 w-24 rounded-full bg-gray-200 animate-pulse"></div>
                </div>
                
                <!-- Competition Name Skeleton -->
                <div class="text-center mb-6">
                    <div class="h-8 bg-gray-200 rounded w-3/4 mx-auto mb-2 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mx-auto animate-pulse"></div>
                </div>
                
                <!-- Competition Details Skeleton -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Kompetisi</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="h-8 bg-gray-200 rounded w-24 animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penyelenggara</label>
                        <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
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
                
                <!-- Description Skeleton -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <div class="h-32 bg-gray-200 rounded animate-pulse"></div>
                </div>
                
                <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                    <div class="h-4 bg-gray-200 rounded w-48 animate-pulse"></div>
                    <div class="flex space-x-3">
                        <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
            
            <!-- Actual Content -->
            <div class="competition-detail-content hidden">
                <div class="flex items-center justify-center mb-6">
                    <div id="level-icon-container" class="h-24 w-24 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center shadow-md">
                        <svg id="level-icon" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Competition Name -->
                <div class="text-center mb-6">
                    <h2 id="competition-name" class="text-2xl font-bold text-gray-900"></h2>
                    <p id="competition-level" class="text-gray-500"></p>
                </div>
                
                <!-- Competition Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Kompetisi</label>
                        <div id="competition-id" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="flex items-center">
                            <span id="competition-status" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penyelenggara</label>
                        <div id="competition-organizer" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <div id="competition-period" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai - Selesai</label>
                        <div id="competition-dates" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendaftaran</label>
                        <div id="competition-registration" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                    
                    <div class="form-group md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                        <div id="competition-date" class="bg-gray-50 px-3 py-2 rounded-md border border-gray-300 text-gray-700"></div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <div id="competition-description" class="bg-gray-50 px-3 py-3 rounded-md border border-gray-300 text-gray-700 min-h-[100px]"></div>
                </div>
                
                <div class="flex items-center justify-between pt-5 border-t border-gray-200 mt-6">
                    <div class="flex items-center text-gray-500 text-sm">
                        <span>Terakhir diperbarui: <span id="show-competition-updated-at">-</span></span>
                    </div>
                    <div class="flex space-x-3">
                        <x-ui.button 
                            variant="secondary" 
                            id="close-show-competition"
                            type="button"
                        >
                            Tutup
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Competition Details Template -->
<template id="competition-details-template">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Informasi Kompetisi</h4>
                <div class="border-t border-gray-200 pt-2">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="text-sm text-gray-900 col-span-2 competition-data" data-field="name">-</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Penyelenggara</dt>
                            <dd class="text-sm text-gray-900 col-span-2 competition-data" data-field="organizer">-</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Tingkat</dt>
                            <dd class="text-sm text-gray-900 col-span-2 competition-data" data-field="level">-</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm col-span-2">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full competition-status">-</span>
                            </dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Periode</dt>
                            <dd class="text-sm text-gray-900 col-span-2 competition-data" data-field="period_name">-</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Keterampilan yang Dibutuhkan</h4>
                <div class="border-t border-gray-200 pt-2">
                    <div class="flex flex-wrap gap-2 mt-2" id="competition-skills">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                            Tidak ada data
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <div class="mb-4">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Jadwal</h4>
                <div class="border-t border-gray-200 pt-2">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Pendaftaran</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                <span class="competition-data" data-field="registration_start_date">-</span> s/d 
                                <span class="competition-data" data-field="registration_end_date">-</span>
                            </dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Pelaksanaan</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                <span class="competition-data" data-field="start_date">-</span> s/d 
                                <span class="competition-data" data-field="end_date">-</span>
                            </dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Pengumuman</dt>
                            <dd class="text-sm text-gray-900 col-span-2 competition-data" data-field="announcement_date">-</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Kontak</h4>
                <div class="border-t border-gray-200 pt-2">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Website</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                <a href="#" class="competition-data text-blue-600 hover:underline" data-field="website" target="_blank">-</a>
                            </dd>
                        </div>
                        <div class="py-2 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                <a href="#" class="competition-data text-blue-600 hover:underline" data-field="email">-</a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</h4>
        <div class="border-t border-gray-200 pt-2">
            <div class="prose max-w-none competition-data" data-field="description">-</div>
        </div>
    </div>
    
    <div class="mt-4">
        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Sub-Kompetisi</h4>
        <div class="border-t border-gray-200 pt-2">
            <div class="mt-2" id="subcompetitions-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="subcompetitions-list">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada sub-kompetisi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<!-- Subcompetition Row Template -->
<template id="subcompetition-row-template">
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 subcompetition-name">-</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 subcompetition-category">-</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 subcompetition-max-participants">-</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 subcompetition-current-participants">-</td>
    </tr>
</template> 