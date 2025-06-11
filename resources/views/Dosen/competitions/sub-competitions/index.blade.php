@component('layouts.dosen', ['title' => 'Sub-Kompetisi: ' . $competition->name])
<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Sub-Kompetisi: {{ $competition->name }}</h1>
                <p class="text-gray-600 mt-2">Halaman ini menampilkan daftar sub-kompetisi yang tersedia untuk kompetisi "{{ $competition->name }}".</p>
            </div>
            <a href="{{ route('lecturer.competitions.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
    
    <div class="mb-6 flex justify-end">
        <button type="button" id="open-add-sub-competition-modal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Sub-Kompetisi
        </button>
    </div>
    
    <div id="sub-competitions-table-container">
        @include('Dosen.competitions.sub-competitions.table')
    </div>
</div>

<!-- Include modals -->
@include('Dosen.competitions.sub-competitions.components.add-sub-competition-modal')
@include('Dosen.competitions.sub-competitions.components.edit-sub-competition-modal')
@include('Dosen.competitions.sub-competitions.components.show-sub-competition-modal')

<!-- JavaScript Variables and Setup -->
<script>
    window.subCompetitionRoutes = {
        index: "{{ route('lecturer.competitions.sub-competitions.index', $competition->id) }}",
        store: "{{ route('lecturer.competitions.sub-competitions.store', $competition->id) }}",
        show: "{{ route('lecturer.competitions.sub-competitions.show', ['id' => $competition->id, 'sub_id' => '__id__']) }}",
        edit: "{{ route('lecturer.competitions.sub-competitions.show', ['id' => $competition->id, 'sub_id' => '__id__']) }}/edit",
        skills: "{{ route('lecturer.competitions.sub-competitions.skills', ['competition' => $competition->id, 'sub_competition' => '__id__']) }}",
        update: "{{ route('lecturer.competitions.sub-competitions.update', ['competition' => $competition->id, 'sub_competition' => '__id__']) }}"
    };
    window.csrfToken = "{{ csrf_token() }}";
    window.competitionId = "{{ $competition->id }}";
</script>

<!-- Load External JS -->
@vite(['resources/js/dosen/sub-competitions.js'])
@endcomponent 