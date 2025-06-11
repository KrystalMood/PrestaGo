@component('layouts.dosen', ['title' => 'Kelola Skill untuk ' . $subCompetition->name])
<div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $subCompetition->name }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $competition->name }} - Kelola Skill</p>
            <div class="mt-3">
                <a href="{{ route('lecturer.competitions.sub-competitions.index', $competition->id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Sub-Kompetisi
                </a>
            </div>
        </div>
        <div>
            <button type="button" id="open-add-skill-modal" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Skill
            </button>
        </div>
    </div>
    <div class="mt-6">
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200" id="skillsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Skill</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tingkat Kepentingan</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nilai Bobot</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe Kriteria</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subCompetition->skills as $skill)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $skill->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $skill->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $skill->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ 
                                $skill->pivot->importance_level >= 8 ? 'bg-green-100 text-green-800 ring-1 ring-green-200' : 
                                ($skill->pivot->importance_level >= 5 ? 'bg-blue-100 text-blue-800 ring-1 ring-blue-200' : 
                                'bg-gray-100 text-gray-800 ring-1 ring-gray-200') 
                            }}">
                                {{ $skill->pivot->importance_level }}/10
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $skill->pivot->weight_value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ 
                                $skill->pivot->criterion_type === 'benefit' ? 'bg-green-100 text-green-800 ring-1 ring-green-200' : 
                                'bg-red-100 text-red-800 ring-1 ring-red-200' 
                            }}">
                                {{ ucfirst($skill->pivot->criterion_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                            <div class="flex items-center space-x-3">
                                <button type="button" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition-colors duration-200 edit-skill-btn" 
                                    data-skill-id="{{ $skill->id }}" 
                                    data-skill-name="{{ $skill->name }}"
                                    data-importance-level="{{ $skill->pivot->importance_level }}"
                                    data-weight-value="{{ $skill->pivot->weight_value }}"
                                    data-criterion-type="{{ $skill->pivot->criterion_type }}"
                                    title="Edit Skill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition-colors duration-200 delete-skill-btn" 
                                    data-skill-id="{{ $skill->id }}" 
                                    data-skill-name="{{ $skill->name }}"
                                    title="Hapus Skill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="bg-gray-50 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-gray-600 font-medium">Belum ada skill untuk sub-kompetisi ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('Dosen.competitions.sub-competitions.skills.components.add-skill-modal')
@include('Dosen.competitions.sub-competitions.skills.components.edit-skill-modal')
@include('Dosen.competitions.sub-competitions.skills.components.delete-skill-modal')

<!-- JavaScript Setup -->
<script>
    // Define routes for skills
    window.skillRoutes = {
        store: "{{ route('lecturer.competitions.sub-competitions.skills.store', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}",
        update: "{{ route('lecturer.competitions.sub-competitions.skills.update', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'skill' => '__id__']) }}",
        destroy: "{{ route('lecturer.competitions.sub-competitions.skills.destroy', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'skill' => '__id__']) }}"
    };
    
    // Define CSRF token
    window.csrfToken = "{{ csrf_token() }}";
</script>

<!-- Load External JS -->
@vite(['resources/js/dosen/skills.js'])
@endcomponent 