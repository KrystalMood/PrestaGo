@component('layouts.mahasiswa', ['title' => 'Profil Saya'])

<div class="container py-6">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-lg shadow-lg">
                <div class="card-body p-6">
                    @if (session('status'))
                        <div class="alert alert-success bg-green-50 border-l-4 border-green-500 p-4 mb-6" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Profile Photo -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 shadow-md">
                                 @if(Auth::user()->photo)
                                <img id="profile-photo-preview" src="{{ asset('storage/'. Auth::user()->photo) }}" alt="{{ Auth::user()->name }}" loading="lazy" 
                                    alt="Profile photo" class="w-full h-full object-cover">
                                @else
                                <img id="profile-photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4338ca&color=fff" alt="{{ $user->name }}"  loading="lazy"
                                    alt="Profile photo" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <label for="profile-photo" class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 rounded-full p-2 text-white cursor-pointer shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </label>
                        </div>
                        <h4 class="text-lg font-medium mt-3">{{ Auth::user()->name }}</h4>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    </div>

                    <form method="POST" action="#" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <input type="file" id="profile-photo" name="profile_photo" class="hidden" accept="image/*" onchange="previewProfilePhoto(this)">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                            <!-- Name -->
                            <div class="form-group">
                                <x-ui.form-input 
                                    name="name" 
                                    id="name"
                                    label="Nama Lengkap" 
                                    placeholder="Masukkan nama lengkap" 
                                    required="true"
                                    value="{{ Auth::user()->name }}"
                                />
                                @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- NIM -->
                            <div class="form-group">
                                <x-ui.form-input 
                                    name="nim" 
                                    id="nim"
                                    label="Nomor Induk Mahasiswa (NIM)" 
                                    placeholder="Masukkan NIM" 
                                    required="true"
                                    value="{{ Auth::user()->nim ?? '' }}"
                                />
                                @error('nim')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="form-group">
                                <x-ui.form-input 
                                    type="email"
                                    name="email" 
                                    id="email"
                                    label="Email" 
                                    placeholder="Masukkan email" 
                                    required="true"
                                    value="{{ Auth::user()->email }}"
                                />
                                @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Password -->
                            <div class="form-group md:col-span-2">
                                <x-ui.form-input 
                                    type="password"
                                    name="password" 
                                    id="password"
                                    label="Password Baru (Opsional)" 
                                    placeholder="Masukkan password baru"
                                    helperText="Biarkan kosong jika tidak ingin mengubah password"
                                />
                                @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Password Confirmation -->
                            <div class="form-group md:col-span-2">
                                <x-ui.form-input 
                                    type="password"
                                    name="password_confirmation" 
                                    id="password_confirmation"
                                    label="Konfirmasi Password Baru" 
                                    placeholder="Konfirmasi password baru"
                                />
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex items-center justify-end pt-5 border-t border-gray-200 mt-6">
                            <x-ui.button 
                                type="submit" 
                                variant="primary"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewProfilePhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('profile-photo-preview').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endcomponent