@component('layouts.admin', ['title' => 'Ekspor Laporan'])
    @push('scripts')
    <script src="{{ asset('js/admin/reports/export.js') }}"></script>
    @endpush

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ekspor Laporan</h1>
        <p class="text-gray-600 mt-1">Buat dan unduh laporan prestasi mahasiswa</p>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            @include('admin.reports.components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Export Form -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
                <div class="p-4 bg-indigo-50 border-b border-indigo-100">
                    <h2 class="font-semibold text-indigo-800">Ekspor Laporan Prestasi</h2>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    Laporan ini akan mencakup semua data prestasi terverifikasi, termasuk statistik program studi, kategori lomba, dan tren tahunan.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.reports.generate-report') }}" method="POST" id="export-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Format Laporan</h3>
                                <div class="bg-white p-4 border border-gray-200 rounded-lg">
                                    <div class="space-y-4">
                                        <div class="flex items-center p-3 rounded-md hover:bg-gray-50 transition">
                                            <input id="format_excel" name="report_format" type="radio" value="excel" class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" checked>
                                            <label for="format_excel" class="ml-3 flex flex-col">
                                                <span class="text-sm font-medium text-gray-900">Excel (.xlsx)</span>
                                                <span class="text-xs text-gray-500">Multiple sheets dengan format yang dapat diedit</span>
                                            </label>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex items-center p-3 rounded-md hover:bg-gray-50 transition">
                                            <input id="format_pdf" name="report_format" type="radio" value="pdf" class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                            <label for="format_pdf" class="ml-3 flex flex-col">
                                                <span class="text-sm font-medium text-gray-900">PDF (.pdf)</span>
                                                <span class="text-xs text-gray-500">Format dokumen yang siap cetak</span>
                                            </label>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ekspor Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
@endcomponent 