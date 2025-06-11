@component('layouts.dosen', ['title' => 'Daftar Kompetisi - ' . $subCompetition->name])

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        height: 42px;
        padding: 6px;
        border-color: #d1d5db;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5;
    }
</style>
@endpush

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
        <form action="{{ route('lecturer.competitions.sub-competitions.apply', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}" method="POST" class="space-y-6">
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
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Anggota Tim</h2>
                <div class="space-y-4" id="team-members-container">
                    <p class="text-sm text-gray-600">Pilih mahasiswa yang akan menjadi anggota tim. Anggota pertama dianggap sebagai ketua tim.</p>

                    @for($i = 0; $i < 3; $i++)
                    <div class="mb-4 p-4 border border-gray-200 rounded-md bg-gray-50">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-md font-medium text-gray-700">{{ $i === 0 ? 'Ketua Tim' : 'Anggota Tim ' . $i }}</h3>
                        </div>
                        <div class="form-group">
                            <label for="team_members_{{ $i }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Pilih Mahasiswa {{ $i === 0 ? '(Wajib)' : '(Opsional)' }}
                            </label>
                            <select name="team_members[]" id="team_members_{{ $i }}" class="team-member-select w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" {{ $i === 0 ? 'required' : '' }}>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('team_members.' . $i) == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} - {{ $student->nim ?? 'NIM tidak tersedia' }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor

                    <div class="mt-3 text-sm text-gray-600">
                        <p>Pastikan setiap mahasiswa hanya dipilih sekali. Sistem akan mencegah duplikasi otomatis.</p>
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
                                Dengan mendaftar, Anda menyetujui ketentuan dan persyaratan yang berlaku untuk kompetisi ini. Pastikan data yang dimasukkan sudah benar.
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
                                Saya telah membaca dan menyetujui <a href="#" class="text-blue-600 hover:text-blue-800">ketentuan dan persyaratan</a> yang berlaku.
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
                <a href="{{ route('lecturer.competitions.sub-competitions.index', $competition->id) }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Daftar Kompetisi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.team-member-select').select2({
            placeholder: "Cari dan pilih mahasiswa...",
            allowClear: true,
            width: '100%'
        });

        // Prevent duplicate selections
        function updateDisabledOptions() {
            const selectedValues = $('.team-member-select').map(function() { return $(this).val(); }).get().filter(v => v !== '');
            $('.team-member-select').each(function() {
                const currentSelect = $(this);
                const currentValue = currentSelect.val();
                currentSelect.find('option').each(function() {
                    const option = $(this);
                    const optionValue = option.val();
                    if(optionValue === '') { return; }
                    if(selectedValues.includes(optionValue) && optionValue !== currentValue) {
                        option.prop('disabled', true);
                    } else {
                        option.prop('disabled', false);
                    }
                });
                // Refresh Select2 to reflect disabled state
                currentSelect.select2('destroy');
                currentSelect.select2({
                    placeholder: "Cari dan pilih mahasiswa...",
                    allowClear: true,
                    width: '100%'
                });
            });
        }

        $('.team-member-select').on('change', function() {
            updateDisabledOptions();
        });

        // Initialize at load (for old values)
        updateDisabledOptions();
    });
</script>
@endpush

@endcomponent 