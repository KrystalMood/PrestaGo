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
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Ekspor Laporan Prestasi</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.reports.generate-report') }}" method="POST" id="export-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Jenis Laporan</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="report_type_comprehensive" name="report_type" value="comprehensive" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="report_type_comprehensive" class="font-medium text-gray-700">Laporan Komprehensif</label>
                                            <p class="text-gray-500">Laporan lengkap prestasi mahasiswa</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="report_type_summary" name="report_type" value="summary" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="report_type_summary" class="font-medium text-gray-700">Ringkasan Eksekutif</label>
                                            <p class="text-gray-500">Ringkasan untuk keperluan manajemen</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Format & Periode</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="report_format" class="block text-sm font-medium text-gray-700">Format Laporan</label>
                                        <select id="report_format" name="report_format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="pdf">PDF Document</option>
                                            <option value="excel">Excel Spreadsheet</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="date_range" class="block text-sm font-medium text-gray-700">Periode Waktu</label>
                                        <select id="date_range" name="date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="current_year">Tahun Berjalan</option>
                                            <option value="current_semester">Semester Berjalan</option>
                                            <option value="last_year">Tahun Lalu</option>
                                            <option value="all_time">Semua Waktu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ekspor Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Report Templates -->
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Template Laporan</h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 hover:shadow-md transition cursor-pointer">
                            <div class="flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 text-center">Laporan Komprehensif</h3>
                            <p class="mt-1 text-sm text-gray-500 text-center">Laporan lengkap tentang semua prestasi dan partisipasi mahasiswa</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 hover:shadow-md transition cursor-pointer">
                            <div class="flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 text-center">Ringkasan Eksekutif</h3>
                            <p class="mt-1 text-sm text-gray-500 text-center">Ringkasan eksekutif untuk keperluan manajemen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcomponent 