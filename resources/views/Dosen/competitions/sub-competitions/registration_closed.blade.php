@component('layouts.dosen', ['title' => 'Pendaftaran Ditutup - ' . $subCompetition->name])

<div class="bg-white rounded-lg shadow-custom p-6">
    <!-- Breadcrumbs -->
    <div class="mb-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('lecturer.competitions.index') }}" class="text-gray-500 hover:text-blue-600 text-sm">
                        Kompetisi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('lecturer.competitions.show', $competition->id) }}" class="text-gray-500 hover:text-blue-600 ml-1 md:ml-2 text-sm">
                            {{ $competition->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('lecturer.competitions.sub-competitions.index', $competition->id) }}" class="text-gray-500 hover:text-blue-600 ml-1 md:ml-2 text-sm">
                            Sub-Kompetisi
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 ml-1 md:ml-2 text-sm font-medium">Pendaftaran Ditutup</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="text-center py-12">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Pendaftaran Telah Ditutup</h2>
        <p class="text-gray-600 mb-6 max-w-lg mx-auto">
            Maaf, pendaftaran untuk kompetisi <span class="font-medium">{{ $subCompetition->name }}</span> telah ditutup pada tanggal <span class="font-medium">{{ $endDate->format('d M Y') }}</span>.
        </p>
        
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 max-w-lg mx-auto text-left">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Periode pendaftaran untuk kompetisi ini telah berakhir. Jika Anda memiliki pertanyaan atau memerlukan informasi lebih lanjut, silakan hubungi penyelenggara kompetisi.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mt-8">
            <a href="{{ route('lecturer.competitions.sub-competitions.index', $competition->id) }}" class="btn btn-primary">
                Kembali ke Daftar Sub-Kompetisi
            </a>
        </div>
    </div>
</div>

@endcomponent 