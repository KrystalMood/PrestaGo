@component('layouts.app', ['title' => 'Profil Saya'])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Profil Saya') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Informasi Profil</h4>
                    
                    <form class="mt-4">
                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nama Lengkap</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <label for="nim" class="col-md-3 col-form-label text-md-right">NIM</label>
                            <div class="col-md-6">
                                <input id="nim" type="text" class="form-control" name="nim" value="" required>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-3 col-form-label text-md-right">Password Baru</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcomponent 