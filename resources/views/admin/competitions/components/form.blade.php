@props(['competition' => null, 'categories' => collect(), 'submitButtonText' => 'Simpan'])

<div class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1 required">Nama Kompetisi</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $competition?->name) }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-300 @enderror" 
                    placeholder="Masukkan nama kompetisi" 
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Organizer -->
            <div>
                <label for="organizer" class="block text-sm font-medium text-gray-700 mb-1 required">Penyelenggara</label>
                <input 
                    type="text" 
                    id="organizer" 
                    name="organizer" 
                    value="{{ old('organizer', $competition?->organizer) }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('organizer') border-red-300 @enderror" 
                    placeholder="Masukkan nama penyelenggara" 
                    required
                >
                @error('organizer')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1 required">Deskripsi</label>
            <textarea 
                id="description" 
                name="description" 
                rows="5" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('description') border-red-300 @enderror" 
                placeholder="Masukkan deskripsi kompetisi" 
                required
            >{{ old('description', $competition?->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Dates and Category -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal & Kategori</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1 required">Tanggal Mulai</label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    value="{{ old('start_date', $competition?->start_date?->format('Y-m-d')) }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('start_date') border-red-300 @enderror" 
                    required
                >
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1 required">Tanggal Selesai</label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    value="{{ old('end_date', $competition?->end_date?->format('Y-m-d')) }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('end_date') border-red-300 @enderror" 
                    required
                >
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1 required">Kategori</label>
                <select 
                    id="category_id" 
                    name="category_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('category_id') border-red-300 @enderror" 
                    required
                >
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $competition?->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div class="mt-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1 required">Status</label>
            <select 
                id="status" 
                name="status" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('status') border-red-300 @enderror" 
                required
            >
                <option value="">Pilih Status</option>
                <option value="upcoming" {{ old('status', $competition?->status) == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                <option value="active" {{ old('status', $competition?->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ old('status', $competition?->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ old('status', $competition?->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Additional Information -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tambahan</h3>
        
        <!-- Requirements -->
        <div>
            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">Persyaratan & Ketentuan</label>
            <textarea 
                id="requirements" 
                name="requirements" 
                rows="5" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('requirements') border-red-300 @enderror" 
                placeholder="Masukkan persyaratan dan ketentuan kompetisi"
            >{{ old('requirements', $competition?->requirements) }}</textarea>
            @error('requirements')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- URL -->
        <div class="mt-6">
            <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL Kompetisi</label>
            <input 
                type="url" 
                id="url" 
                name="url" 
                value="{{ old('url', $competition?->url) }}" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('url') border-red-300 @enderror" 
                placeholder="https://example.com"
            >
            @error('url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Image Upload -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Kompetisi</h3>
        
        <div class="space-y-4">
            <!-- Current Image (if editing) -->
            @if($competition?->image)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                    <div class="relative w-48 h-48">
                        <img src="{{ asset('storage/' . $competition->image) }}" alt="{{ $competition->name }}" class="object-cover rounded-lg border border-gray-200 w-full h-full">
                    </div>
                </div>
            @endif
            
            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">{{ $competition ? 'Ganti Gambar' : 'Unggah Gambar' }}</label>
                <input 
                    type="file" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-300 @enderror"
                >
                <p class="mt-1 text-sm text-gray-500">
                    Unggah gambar dalam format JPG, PNG, atau GIF. Ukuran maksimum 2MB.
                </p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.competitions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Batal
        </a>
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ $submitButtonText }}
        </button>
    </div>
</div>

<style>
    .required:after {
        content: " *";
        color: #EF4444;
    }
</style> 