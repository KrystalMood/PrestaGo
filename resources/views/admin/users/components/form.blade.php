@props(['user' => null, 'roles' => [], 'isEdit' => false])

<form action="{{ $isEdit ? route('admin.users.update', $user->users_id) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user?->name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand focus:border-brand" required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user?->email) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand focus:border-brand" required>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                @if($isEdit)
                    Password Baru <span class="text-gray-500 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                @else
                    Password
                @endif
            </label>
            <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand focus:border-brand" {{ !$isEdit ? 'required' : '' }}>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                @if($isEdit)
                    Konfirmasi Password Baru
                @else
                    Konfirmasi Password
                @endif
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand focus:border-brand" {{ !$isEdit ? 'required' : '' }}>
        </div>

        <div>
            <label for="level_id" class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
            <select name="level_id" id="level_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand focus:border-brand" required>
                <option value="">Pilih Peran</option>
                @foreach($roles ?? [] as $role)
                    <option value="{{ $role->level_id }}" {{ old('level_id', $user?->level_id) == $role->level_id ? 'selected' : '' }}>
                        {{ $role->level_nama }}
                    </option>
                @endforeach
            </select>
            @error('level_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
            
            @if($isEdit && $user?->photo)
            <div class="flex items-center mb-2">
                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full object-cover mr-2">
                <span class="text-sm text-gray-600">Foto profil saat ini</span>
            </div>
            @endif
            
            <input type="file" name="photo" id="photo" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand focus:border-brand" accept="image/*">
            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, PNG, GIF. Maks: 2MB.</p>
            @error('photo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-6 flex justify-end gap-3">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-lg">
            {{ $isEdit ? 'Perbarui Data Pengguna' : 'Simpan Data Pengguna' }}
        </button>
    </div>
</form> 