@props(['competition' => null, 'periods' => collect(), 'skills' => collect(), 'submitButtonText' => 'Simpan'])

<div class="space-y-6">
    <!-- Informasi Dasar -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
            <span class="text-blue-600 mr-2">●</span>
            Informasi Dasar
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <!-- Nama Kompetisi -->
            <div class="form-group mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kompetisi
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                    value="{{ old('name', $competition?->name) }}" 
                    required
                    placeholder="Masukkan nama kompetisi"
                >
                @error('name')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penyelenggara -->
            <div class="form-group mb-4">
                <label for="organizer" class="block text-sm font-medium text-gray-700 mb-2">
                    Penyelenggara
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="organizer" 
                    id="organizer" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('organizer') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                    value="{{ old('organizer', $competition?->organizer) }}" 
                    required
                    placeholder="Masukkan nama penyelenggara"
                >
                @error('organizer')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
            <span class="text-blue-600 mr-2">●</span>
            Deskripsi
        </h3>
        
        <div class="form-group mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
                <span class="text-red-500">*</span>
            </label>
            <textarea 
                name="description" 
                id="description" 
                rows="5" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('description') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                placeholder="Masukkan deskripsi kompetisi"
                required
            >{{ old('description', $competition?->description) }}</textarea>
            @error('description')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Tanggal -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
            <span class="text-blue-600 mr-2">●</span>
            Tanggal
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <!-- Tanggal Mulai -->
            <div class="form-group mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Mulai
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="start_date" 
                    id="start_date" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('start_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                    value="{{ old('start_date', $competition?->start_date ? $competition->start_date->format('Y-m-d') : '') }}" 
                    required
                >
                @error('start_date')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Selesai -->
            <div class="form-group mb-4">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Selesai
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="end_date" 
                    id="end_date" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('end_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                    value="{{ old('end_date', $competition?->end_date ? $competition->end_date->format('Y-m-d') : '') }}" 
                    required
                >
                @error('end_date')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Kategori -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
            <span class="text-blue-600 mr-2">●</span>
            Periode
        </h3>
        
        <div class="form-group mb-4">
            <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">
                Periode
                <span class="text-red-500">*</span>
            </label>
            <select 
                name="period_id" 
                id="period_id" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('period_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                required
            >
                <option value="">Pilih Periode</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ old('period_id', $competition?->period_id) == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
            @error('period_id')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <!-- Skill -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
            <span class="text-blue-600 mr-2">●</span>
            Skill (Opsional)
        </h3>
        
        <div class="form-group mb-4">
            <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">
                Skill yang Dibutuhkan
            </label>
            <select 
                name="skills[]" 
                id="skills" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('skills') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                multiple
            >
                @php
                    $selectedSkills = old('skills', $competition?->skills->pluck('id')->toArray() ?? []);
                @endphp
                
                @foreach ($skills as $skill)
                    <option value="{{ $skill->id }}" {{ in_array($skill->id, $selectedSkills) ? 'selected' : '' }}>
                        {{ $skill->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1.5 text-sm text-gray-500">Tahan tombol Ctrl/Cmd untuk memilih beberapa skill</p>
            @error('skills')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Gambar -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
            <span class="text-blue-600 mr-2">●</span>
            Gambar Kompetisi
        </h3>
        
        <div class="form-group mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                Gambar
                {{ $competition ? '' : '<span class="text-red-500">*</span>' }}
            </label>
            
            <input 
                type="file" 
                name="image" 
                id="image" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('image') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                accept="image/*"
                {{ $competition ? '' : 'required' }}
            >
            <p class="mt-1.5 text-sm text-gray-500">Format: JPG, PNG, GIF (Max: 2MB)</p>
            
            @if($competition && $competition->image)
                <div class="mt-3">
                    <p class="text-sm text-gray-700 mb-2">Gambar Saat Ini</p>
                    <img src="{{ asset('storage/' . $competition->image) }}" alt="{{ $competition->name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                </div>
            @endif
            
            @error('image')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <!-- Form Actions -->
    <div class="pt-5 border-t border-gray-200">
        <div class="flex justify-end">
            <a href="{{ route('admin.competitions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $submitButtonText }}
            </button>
        </div>
    </div>
</div>