@extends('components.shared.content')

@section('content')

@component('layouts.app', ['title' => 'Daftar Kompetisi - ' . $subCompetition->name])

<div class="bg-white rounded-lg shadow-custom p-6">
    <!-- Breadcrumbs -->
    <div class="mb-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('student.competitions.index') }}" class="text-gray-500 hover:text-blue-600 text-sm">
                        Kompetisi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('student.competitions.show', $competition->id) }}" class="text-gray-500 hover:text-blue-600 ml-1 md:ml-2 text-sm">
                            {{ $competition->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('student.competitions.sub.show', [$competition->id, $subCompetition->id]) }}" class="text-gray-500 hover:text-blue-600 ml-1 md:ml-2 text-sm">
                            {{ $subCompetition->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 ml-1 md:ml-2 text-sm font-medium">Pendaftaran</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Formulir Pendaftaran</h1>
        <p class="text-gray-600 mt-1">
            Silakan lengkapi data pendaftaran untuk kategori lomba <span class="font-medium">{{ $subCompetition->name }}</span> pada kompetisi <span class="font-medium">{{ $competition->name }}</span>.
        </p>
    </div>

    <!-- Registration Period Warning -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Perhatian: Batas Waktu Pendaftaran</h3>
                <div class="mt-1 text-sm text-yellow-700">
                    <p>Pendaftaran hanya tersedia selama periode berikut:</p>
                    <p class="font-medium mt-1">
                        {{ $subCompetition->registration_start ? \Carbon\Carbon::parse($subCompetition->registration_start)->format('d M Y') : 'Tidak ditentukan' }} 
                        sampai 
                        {{ $subCompetition->registration_end ? \Carbon\Carbon::parse($subCompetition->registration_end)->format('d M Y') : 'Tidak ditentukan' }}
                    </p>
                    <p class="mt-1">Setelah periode pendaftaran berakhir, Anda tidak akan dapat mendaftar untuk kompetisi ini.</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Application Form -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form action="{{ route('student.competitions.sub.apply', [$competition->id, $subCompetition->id]) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Team Information -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Informasi Tim</h2>
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="team_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Tim <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="team_name" id="team_name" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required value="{{ old('team_name') }}">
                        @error('team_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Masukkan nama tim yang akan digunakan dalam kompetisi.</p>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Anggota Tim (Opsional)</h2>
                
                <div class="space-y-4" id="team-members-container">
                    <p class="text-sm text-gray-600">Tambahkan anggota tim lainnya dari mahasiswa yang telah terdaftar. Anda dapat menambahkan hingga 3 anggota.</p>
                    
                    <div id="team-members-list">
                        <!-- Team members will be added here -->
                        <div class="mb-4 p-4 border border-gray-200 rounded-md bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-md font-medium text-gray-700">Anggota Tim 1</h3>
                            </div>
                            <div class="form-group">
                                <label for="team_members_0" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilih Mahasiswa
                                </label>
                                <select name="team_members[]" id="team_members_0" class="team-member-select w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Mahasiswa (Kosongkan jika tidak perlu) --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('team_members.0') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} - {{ $student->nim ?? 'NIM tidak tersedia' }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4 p-4 border border-gray-200 rounded-md bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-md font-medium text-gray-700">Anggota Tim 2</h3>
                            </div>
                            <div class="form-group">
                                <label for="team_members_1" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilih Mahasiswa
                                </label>
                                <select name="team_members[]" id="team_members_1" class="team-member-select w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Mahasiswa (Kosongkan jika tidak perlu) --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('team_members.1') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} - {{ $student->nim ?? 'NIM tidak tersedia' }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4 p-4 border border-gray-200 rounded-md bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-md font-medium text-gray-700">Anggota Tim 3</h3>
                            </div>
                            <div class="form-group">
                                <label for="team_members_2" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilih Mahasiswa
                                </label>
                                <select name="team_members[]" id="team_members_2" class="team-member-select w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Mahasiswa (Kosongkan jika tidak perlu) --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('team_members.2') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} - {{ $student->nim ?? 'NIM tidak tersedia' }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-sm text-gray-600">
                        <p>Anggota tim harus sudah terdaftar sebagai pengguna sistem. Jika anggota tim belum terdaftar, mereka perlu mendaftar terlebih dahulu.</p>
                        <p class="mt-1">Anda tidak perlu mengisi semua slot anggota tim jika tim Anda memiliki kurang dari 3 anggota tambahan.</p>
                        <p class="mt-1 text-yellow-600">Catatan: Satu mahasiswa hanya dapat dipilih sekali sebagai anggota tim.</p>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const teamMemberSelects = document.querySelectorAll('.team-member-select');
                            
                            // Update disabled options based on selected values
                            function updateDisabledOptions() {
                                // Get all currently selected values
                                const selectedValues = Array.from(teamMemberSelects)
                                    .map(select => select.value)
                                    .filter(value => value !== ''); // Filter out empty values
                                
                                // For each select element
                                teamMemberSelects.forEach(select => {
                                    const currentValue = select.value;
                                    
                                    // For each option in this select
                                    Array.from(select.options).forEach(option => {
                                        const optionValue = option.value;
                                        
                                        // Skip the empty option
                                        if (optionValue === '') return;
                                        
                                        // If this option is selected in another dropdown and not in current one
                                        if (selectedValues.includes(optionValue) && currentValue !== optionValue) {
                                            option.disabled = true;
                                            option.classList.add('text-gray-400');
                                        } else {
                                            option.disabled = false;
                                            option.classList.remove('text-gray-400');
                                        }
                                    });
                                });
                            }
                            
                            // Add change event listeners to all selects
                            teamMemberSelects.forEach(select => {
                                select.addEventListener('change', updateDisabledOptions);
                            });
                            
                            // Initialize on page load (for when old values are preserved)
                            updateDisabledOptions();
                        });
                    </script>
                </div>
            </div>

            <!-- Mentor Information -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Dosen Pendamping</h2>
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="mentor_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Dosen Pendamping <span class="text-red-500">*</span>
                        </label>
                        <select name="mentor_id" id="mentor_id" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Dosen Pendamping --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ old('mentor_id') == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mentor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Pilih dosen yang akan mendampingi tim Anda dalam kompetisi ini.</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Informasi Tambahan</h2>
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Tambahkan catatan atau informasi tambahan jika diperlukan.</p>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Dengan mendaftar, Anda menyetujui ketentuan dan persyaratan yang berlaku untuk kompetisi ini. Pastikan data yang dimasukkan sudah benar dan sesuai dengan identitas Anda.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded" required>
                        </div>
                        <div class="ml-3">
                            <label for="terms" class="text-sm text-gray-700">
                                Saya telah membaca dan menyetujui <a href="#" class="text-blue-600 hover:text-blue-800">ketentuan dan persyaratan</a> yang berlaku untuk kompetisi ini.
                            </label>
                        </div>
                    </div>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('student.competitions.sub.show', [$competition->id, $subCompetition->id]) }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Daftar Kompetisi
                </button>
            </div>
        </form>
    </div>
</div>

@endcomponent
@endsection 