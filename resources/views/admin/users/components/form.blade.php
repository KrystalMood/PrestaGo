@props(['user' => null, 'roles' => [], 'isEdit' => false])

<form action="{{ $isEdit ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
        <x-ui.form-input
            name="name"
            label="Nama Lengkap"
            :value="old('name', $user?->name)"
            required
            placeholder="Masukkan nama lengkap"
            :hasError="$errors->has('name')"
            :errorMessage="$errors->first('name')"
        />

        <x-ui.form-input
            type="email"
            name="email"
            label="Email"
            :value="old('email', $user?->email)"
            required
            placeholder="Masukkan alamat email"
            :hasError="$errors->has('email')"
            :errorMessage="$errors->first('email')"
        />

        <x-ui.form-input
            type="password"
            name="password"
            label="{{ $isEdit ? 'Password Baru' : 'Password' }}"
            :required="!$isEdit"
            placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 6 karakter' }}"
            :helperText="$isEdit ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter'"
            :hasError="$errors->has('password')"
            :errorMessage="$errors->first('password')"
        />

        <x-ui.form-input
            type="password"
            name="password_confirmation"
            label="{{ $isEdit ? 'Konfirmasi Password Baru' : 'Konfirmasi Password' }}"
            :required="!$isEdit"
            placeholder="Masukkan ulang password"
        />

        @php
            $roleOptions = [];
            foreach($roles ?? [] as $role) {
                $roleOptions[$role->id] = $role->level_nama;
            }
        @endphp

        <x-ui.form-select
            name="level_id"
            label="Peran"
            :options="$roleOptions"
            :selected="old('level_id', $user?->level_id)"
            required
            placeholder="Pilih Peran"
            :hasError="$errors->has('level_id')"
            :errorMessage="$errors->first('level_id')"
        />

        <x-ui.form-file
            name="photo"
            label="Foto Profil"
            accept="image/*"
            :helperText="'Format yang didukung: JPG, PNG, GIF. Maks: 2MB.'"
            :hasError="$errors->has('photo')"
            :errorMessage="$errors->first('photo')"
            :preview="$isEdit && $user?->photo ? asset('storage/' . $user->photo) : null"
            :previewAlt="$user?->name ?? ''"
        />
    </div>

    <div class="mt-8 border-t border-gray-200 pt-5 flex justify-end gap-3">
        <x-ui.button 
            variant="secondary" 
            tag="a" 
            href="{{ route('admin.users.index') }}"
        >
            Batal
        </x-ui.button>
        
        <x-ui.button 
            type="submit" 
            variant="primary"
        >
            {{ $isEdit ? 'Perbarui Data Pengguna' : 'Simpan Data Pengguna' }}
        </x-ui.button>
    </div>
</form> 