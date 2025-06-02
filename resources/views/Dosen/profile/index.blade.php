@component('layouts.dosen', ['title' => 'Profil Dosen'])

<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Dosen</h1>
        </div>

        <!-- Tabs -->
        <div class="mb-6">
            <ul class="flex border-b border-gray-200">
                <li class="mr-1">
                    <a href="#personal-info-content" class="tab-link inline-block px-4 py-2 text-sm font-medium border-b-2 border-blue-600 text-blue-600" data-target="personal-info-content">
                        Informasi Pribadi
                    </a>
                </li>
                <li class="mr-1">
                    <a href="#skills-content" class="tab-link inline-block px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-target="skills-content">
                        Keterampilan
                    </a>
                </li>
                <li class="mr-1">
                    <a href="#interests-content" class="tab-link inline-block px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-target="interests-content">
                        Bidang Minat Penelitian
                    </a>
                </li>
            </ul>
        </div>

        <!-- Lecture Profile Info Tab -->
        <div id="personal-info-content" class="tab-content block">
            <form id="profile-form" method="POST" action="{{ route('lecturer.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Profile Photo -->
                    <div class="md:w-1/4">
                        <div class="flex flex-col items-center">
                            <div class="mb-4 relative">
                                <div class="h-36 w-36 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                    <img id="preview-photo" src="{{ $user->photo ? asset('storage/photos' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4338ca&color=fff&size=150' }}" 
                                         class="h-full w-full object-cover" alt="{{ $user->name }}">
                                </div>
                                <button type="button" id="upload-photo-btn" class="absolute bottom-0 right-0 bg-blue-600 text-white p-1.5 rounded-full shadow-md hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                                <input type="file" id="photo" name="photo" class="hidden" accept="image/*">
                            </div>
                            <p class="text-sm text-gray-500">Klik untuk mengubah foto</p>
                        </div>
                    </div>

                    <!-- Lecture Profile Information -->
                    <div class="md:w-3/4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ $user->name }}">
                            </div>
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Dosen Nasional (NIDN)</label>
                                <input type="text" id="nip" name="nip" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-50" value="{{ $user->nip }}" readonly disabled>
                                <input type="hidden" name="nip" value="{{ $user->nip }}">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-50" value="{{ $user->email }}" readonly>
                            </div>
                            <div>
                                <label for="program_studi_id" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                <select id="program_studi_id" name="program_studi_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-50" disabled>
                                    @foreach($studyPrograms as $program)
                                        <option value="{{ $program->id }}" {{ $user->program_studi_id == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="program_studi_id" value="{{ $user->program_studi_id }}">
                            </div>
                            <div>
                                <label for="jumlah_jurnal" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Jurnal</label>
                                <input type="text" id="jumlah_jurnal" name="jumlah_jurnal" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ $user->jumlah_jurnal }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="pt-4">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Skills Tab -->
        <div id="skills-content" class="tab-content hidden">
            <form id="skills-form" method="POST" action="{{ route('lecturer.profile.skills.update') }}" class="space-y-6">
                @csrf
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Daftar Keterampilan</h3>
                        <p class="text-sm text-gray-600 mt-1">Keterampilan yang Anda kuasai dan tingkat keahlian Anda</p>
                    </div>
                    <button type="button" id="open-add-skill-modal-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Keterampilan
                    </button>
                </div>

                <div id="selected-skills-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php 
                        $userSkillsMap = $userSkills->keyBy('id'); // Easier lookup
                    @endphp
                    
                    @if($userSkills->isNotEmpty())
                        @foreach($userSkills as $userSkill)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden" data-skill-id="{{ $userSkill->id }}">
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $userSkill->name }}</h4>
                                            @if($userSkill->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                    {{ $userSkill->category }}
                                                </span>
                                            @endif
                                        </div>
                                        <button type="button" class="remove-skill-btn text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-gray-100 transition-colors" title="Hapus Keterampilan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="skill-level-{{ $userSkill->id }}" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Keahlian</label>
                                        <select id="skill-level-{{ $userSkill->id }}" name="skill_level[{{ $userSkill->id }}]" class="skill-level-select w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="1" {{ $userSkill->pivot->proficiency_level == 1 ? 'selected' : '' }}>Pemula</option>
                                            <option value="2" {{ $userSkill->pivot->proficiency_level == 2 ? 'selected' : '' }}>Dasar</option>
                                            <option value="3" {{ $userSkill->pivot->proficiency_level == 3 ? 'selected' : '' }}>Menengah</option>
                                            <option value="4" {{ $userSkill->pivot->proficiency_level == 4 ? 'selected' : '' }}>Mahir</option>
                                            <option value="5" {{ $userSkill->pivot->proficiency_level == 5 ? 'selected' : '' }}>Ahli</option>
                                        </select>
                                        
                                        <div class="flex items-center mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full skill-progress-bar" style="width: {{ ($userSkill->pivot->proficiency_level / 5) * 100 }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500 ml-2">{{ $userSkill->pivot->proficiency_level }}/5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div id="no-skills-message" class="text-center py-10 px-4 col-span-2 bg-gray-50 rounded-lg border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                <circle cx="12" cy="9" r="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 3.5V2m4 1.5V2m-4.5 15H6m12.5 0H15" />
                            </svg>
                            <h3 class="mt-3 text-base font-medium text-gray-900">Tambahkan Keterampilan Anda</h3>
                            <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan keterampilan apapun.</p>
                        </div>
                    @endif
                </div>
                
                @if($userSkills->isNotEmpty())
                <div class="pt-6 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Simpan Keterampilan
                    </button>
                </div>
                @endif
            </form>
        </div>

        <!-- Add Skill Modal -->
        <div id="add-skill-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
            <div class="relative top-20 mx-auto p-0 border w-full max-w-2xl shadow-lg rounded-lg bg-white transition-transform duration-300 transform">
                <div class="p-5 border-b">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-semibold text-gray-900">Tambah Keterampilan</p>
                        <button id="close-add-skill-modal-btn" class="modal-close cursor-pointer z-50 text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="modal-skill-search" placeholder="Cari keterampilan..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div id="modal-skills-list" class="max-h-72 overflow-y-auto overflow-x-hidden space-y-1 pr-1">
                        <!-- Available skills will be populated here by JavaScript -->
                        <p class="text-gray-500 text-sm py-2 px-1">Ketik untuk mencari keterampilan yang tersedia.</p>
                    </div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex justify-between items-center">
                    <span class="text-xs text-gray-500">Pilih satu atau lebih keterampilan</span>
                    <button type="button" id="add-selected-skills-btn" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        Tambahkan Terpilih
                    </button>
                </div>
            </div>
        </div>

        <!-- Interests Tab -->
        <div id="interests-content" class="tab-content hidden">
            <form id="interests-form" method="POST" action="{{ route('lecturer.profile.interests.update') }}" class="space-y-6">
                @csrf
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Daftar Bidang Minat penelitian</h3>
                        <p class="text-sm text-gray-600 mt-1">Bidang penelitian yang Anda minati dan tingkat ketertarikan Anda</p>
                    </div>
                    <button type="button" id="open-add-interest-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Bidang Minat
                    </button>
                </div>
                    
                <div id="user-interests-container" class="space-y-4">
                    @if($userInterests->isNotEmpty())
                        <div id="user-interests-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($userInterests as $interest)
                                <div class="interest-item border border-gray-200 rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden" data-interest-id="{{ $interest->id }}">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $interest->name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $interest->description }}</p>
                                        </div>
                                        <button type="button" class="remove-interest-btn text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-gray-100 transition-colors" title="Hapus Bidang Minat">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="interest-level-{{ $interest->id }}" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Ketertarikan</label>
                                        <select id="interest-level-{{ $interest->id }}" name="interests[{{ $interest->id }}][interest_level]" class="interest-level-select w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="1" {{ $interest->pivot->interest_level == 1 ? 'selected' : '' }}>Sedikit</option>
                                            <option value="2" {{ $interest->pivot->interest_level == 2 ? 'selected' : '' }}>Rendah</option>
                                            <option value="3" {{ $interest->pivot->interest_level == 3 ? 'selected' : '' }}>Sedang</option>
                                            <option value="4" {{ $interest->pivot->interest_level == 4 ? 'selected' : '' }}>Tinggi</option>
                                            <option value="5" {{ $interest->pivot->interest_level == 5 ? 'selected' : '' }}>Sangat Tinggi</option>
                                        </select>
                                        <input type="hidden" name="interests[{{ $interest->id }}][interest_area_id]" value="{{ $interest->id }}">
                                        
                                        <div class="flex items-center mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full interest-progress-bar" style="width: {{ ($interest->pivot->interest_level / 5) * 100 }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500 ml-2">{{ $interest->pivot->interest_level }}/5</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div id="no-interests-message" class="text-center py-10 px-4 col-span-2 bg-gray-50 rounded-lg border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <h3 class="mt-3 text-base font-medium text-gray-900">Tambahkan Bidang Minat Anda</h3>
                            <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan bidang minat apapun.</p>
                        </div>
                        <!-- We'll create this dynamically with JavaScript when needed -->
                    @endif
                </div>
                
                @if($userInterests->isNotEmpty())
                <div id="interests-form-buttons" class="pt-6 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Simpan Bidang Minat
                    </button>
                </div>
                @else
                <div id="interests-form-buttons" class="pt-6 flex justify-end hidden">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Simpan Bidang Minat
                    </button>
                </div>
                @endif
            </form>
        </div>

        <!-- Add Interest Modal -->
        <div id="add-interest-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
            <div class="relative top-20 mx-auto p-0 border w-full max-w-2xl shadow-lg rounded-lg bg-white transition-transform duration-300 transform">
                <div class="p-5 border-b">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-semibold text-gray-900">Tambah Bidang Minat</p>
                        <button id="close-interest-modal-btn" class="modal-close cursor-pointer z-50 text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="modal-interest-search" placeholder="Cari bidang minat..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div id="modal-interests-list" class="max-h-72 overflow-y-auto overflow-x-hidden space-y-1 pr-1">
                        <!-- Available interests will be populated here by JavaScript -->
                        <p class="text-gray-500 text-sm py-2 px-1">Ketik untuk mencari bidang minat yang tersedia.</p>
                    </div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex justify-between items-center">
                    <span class="text-xs text-gray-500">Pilih satu atau lebih bidang minat</span>
                    <button type="button" id="add-selected-interests-btn" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        Tambahkan Terpilih
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Try to load skills directly, or else fetch them via AJAX
    window.allSkillsMasterList = @json($skills ?? []);
    
    // Debug skills data
    console.log('Skills data loaded in page:');
    console.log('Total skills:', window.allSkillsMasterList.length);
    
    // If we don't have skills data, load it via AJAX before the main JS initializes
    if (!window.allSkillsMasterList || window.allSkillsMasterList.length === 0) {
        console.log('No skills loaded from server. Fetching via AJAX before initializing JS...');
        
        // Create a promise for loading the JS
        window.skillsLoaded = new Promise((resolve, reject) => {
            // Fetch skills data
            fetch('/debug/skills', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Skills fetched via AJAX:', data.count);
                    window.allSkillsMasterList = data.skills;
                    console.log('First 5 skills:', window.allSkillsMasterList.slice(0, 5));
                    if (window.allSkillsMasterList.length > 0) {
                        console.log('First skill properties:', Object.keys(window.allSkillsMasterList[0]));
                    }
                    resolve(data.skills);
                })
                .catch(error => {
                    console.error('Error fetching skills:', error);
                    reject(error);
                });
        });
    } else {
        // Skills already loaded, create a resolved promise
        window.skillsLoaded = Promise.resolve(window.allSkillsMasterList);
        console.log('First 5 skills:', window.allSkillsMasterList.slice(0, 5));
        if (window.allSkillsMasterList.length > 0) {
            console.log('First skill properties:', Object.keys(window.allSkillsMasterList[0]));
        }
    }
    
    // Load interest areas
    window.allInterestsMasterList = @json($interests ?? []);
    
    // Debug interest areas data
    console.log('Interest areas data loaded in page:');
    console.log('Total interest areas:', window.allInterestsMasterList.length);
    
    // If we don't have interest areas data, we'll load via AJAX in the JS file
</script>
@vite(['resources/js/dosen/profile.js'])
@endcomponent