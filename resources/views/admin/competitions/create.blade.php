@component('layouts.admin', ['title' => 'Tambah Kompetisi Baru'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        @component('admin.users.components.page-header')
            @slot('subtitle', 'Lengkapi formulir di bawah ini untuk menambahkan kompetisi baru.')
            @slot('showBackButton', true)
            @slot('backRoute', route('admin.competitions.index'))
        @endcomponent

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.competitions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <x-ui.form-input
                        name="name"
                        label="Nama Kompetisi"
                        :value="old('name')"
                        required
                        placeholder="Masukkan nama kompetisi"
                        :hasError="$errors->has('name')"
                        :errorMessage="$errors->first('name')"
                    />
                    
                    <x-ui.form-input
                        name="organizer"
                        label="Penyelenggara"
                        :value="old('organizer')"
                        required
                        placeholder="Masukkan nama penyelenggara"
                        :hasError="$errors->has('organizer')"
                        :errorMessage="$errors->first('organizer')"
                    />
                    
                    @php
                        $levelOptions = [
                            'international' => 'Internasional',
                            'national' => 'Nasional',
                            'regional' => 'Regional',
                            'provincial' => 'Provinsi',
                            'university' => 'Universitas'
                        ];
                        
                        $typeOptions = [
                            'individual' => 'Individu',
                            'team' => 'Tim',
                            'both' => 'Keduanya'
                        ];
                        
                        $statusOptions = [
                            'upcoming' => 'Akan Datang',
                            'active' => 'Aktif',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ];
                        
                        $periodOptions = [];
                        foreach($periods as $period) {
                            $periodOptions[$period->id] = $period->name;
                        }
                    @endphp
                    
                    <x-ui.form-select
                        name="level"
                        label="Tingkat"
                        :options="$levelOptions"
                        :selected="old('level')"
                        required
                        placeholder="Pilih Tingkat"
                        :hasError="$errors->has('level')"
                        :errorMessage="$errors->first('level')"
                    />
                    
                    <x-ui.form-select
                        name="type"
                        label="Tipe"
                        :options="$typeOptions"
                        :selected="old('type')"
                        required
                        placeholder="Pilih Tipe"
                        :hasError="$errors->has('type')"
                        :errorMessage="$errors->first('type')"
                    />
                </div>
                
                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5"
                        class="w-full px-4 py-2.5 border rounded-lg shadow-sm focus:ring-2 focus:ring-brand focus:border-brand {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Masukkan deskripsi kompetisi"
                        required
                    >{{ old('description') }}</textarea>
                    <p class="mt-1.5 text-sm text-gray-500">Berikan informasi lengkap tentang kompetisi ini, termasuk tujuan dan manfaat bagi peserta.</p>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Dates and Details -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Jadwal & Detail
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                    <x-ui.form-input
                        type="date"
                        name="registration_start"
                        label="Tanggal Mulai Pendaftaran"
                        :value="old('registration_start')"
                        required
                        :hasError="$errors->has('registration_start')"
                        :errorMessage="$errors->first('registration_start')"
                    />
                    
                    <x-ui.form-input
                        type="date"
                        name="registration_end"
                        label="Tanggal Akhir Pendaftaran"
                        :value="old('registration_end')"
                        required
                        :hasError="$errors->has('registration_end')"
                        :errorMessage="$errors->first('registration_end')"
                    />
                    
                    <x-ui.form-input
                        type="date"
                        name="competition_date"
                        label="Tanggal Kompetisi"
                        :value="old('competition_date')"
                        required
                        :hasError="$errors->has('competition_date')"
                        :errorMessage="$errors->first('competition_date')"
                    />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <x-ui.form-select
                        name="status"
                        label="Status"
                        :options="$statusOptions"
                        :selected="old('status')"
                        required
                        placeholder="Pilih Status"
                        :hasError="$errors->has('status')"
                        :errorMessage="$errors->first('status')"
                    />
                    
                    <x-ui.form-select
                        name="period_id"
                        label="Periode"
                        :options="$periodOptions"
                        :selected="old('period_id')"
                        required
                        placeholder="Pilih Periode"
                        :hasError="$errors->has('period_id')"
                        :errorMessage="$errors->first('period_id')"
                    />
                    
                    <x-ui.form-input
                        type="url"
                        name="registration_link"
                        label="Link Pendaftaran"
                        :value="old('registration_link')"
                        placeholder="https://example.com"
                        helperText="Masukkan URL resmi kompetisi atau halaman pendaftaran jika ada"
                        :hasError="$errors->has('registration_link')"
                        :errorMessage="$errors->first('registration_link')"
                    />
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Informasi Tambahan
                </h3>
                
                <div>
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">
                        Persyaratan & Ketentuan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="requirements" 
                        name="requirements" 
                        rows="5"
                        class="w-full px-4 py-2.5 border rounded-lg shadow-sm focus:ring-2 focus:ring-brand focus:border-brand {{ $errors->has('requirements') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Masukkan persyaratan dan ketentuan kompetisi"
                        required
                    >{{ old('requirements') }}</textarea>
                    <p class="mt-1.5 text-sm text-gray-500">Jelaskan persyaratan dan ketentuan lengkap yang perlu dipenuhi oleh peserta kompetisi.</p>
                    @error('requirements')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Skills Section (if needed) -->
            @if(isset($skills) && $skills->count() > 0)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Keterampilan yang Dibutuhkan
                </h3>
                
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Pilih keterampilan yang dibutuhkan untuk kompetisi ini dan tingkat kepentingannya.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($skills as $skill)
                        <div class="p-4 border border-gray-200 rounded-lg bg-white">
                            <div class="flex items-start">
                                <input type="checkbox" id="skill_{{ $skill->id }}" name="skills[{{ $loop->index }}][skill_id]" value="{{ $skill->id }}" class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <label for="skill_{{ $skill->id }}" class="font-medium text-gray-700">{{ $skill->name }}</label>
                                    <div class="mt-2">
                                        <select name="skills[{{ $loop->index }}][importance_level]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="1">Rendah</option>
                                            <option value="2">Agak Rendah</option>
                                            <option value="3" selected>Sedang</option>
                                            <option value="4">Tinggi</option>
                                            <option value="5">Sangat Tinggi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200 mt-6">
                <x-ui.button 
                    variant="secondary" 
                    tag="a" 
                    href="{{ route('admin.competitions.index') }}"
                >
                    Batal
                </x-ui.button>
                
                <x-ui.button 
                    type="submit" 
                    variant="primary"
                >
                    Simpan Kompetisi
                </x-ui.button>
            </div>
        </form>
    </div>
@endcomponent 