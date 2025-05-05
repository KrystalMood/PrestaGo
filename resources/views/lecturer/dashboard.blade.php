@component('layouts.app', ['title' => 'Dashboard Dosen'])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Dosen') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p>Ini adalah dashboard khusus untuk dosen.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">Daftar Mahasiswa</div>
                                <div class="card-body">
                                    <p>Lihat daftar mahasiswa yang Anda bimbing.</p>
                                    <a href="{{ route('lecturer.students.index') }}" class="btn btn-primary">Lihat Mahasiswa</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">Rekomendasi</div>
                                <div class="card-body">
                                    <p>Kelola rekomendasi untuk mahasiswa.</p>
                                    <a href="{{ route('lecturer.recommendations.index') }}" class="btn btn-primary">Kelola Rekomendasi</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">Profil Saya</div>
                                <div class="card-body">
                                    <p>Kelola informasi profil Anda.</p>
                                    <a href="{{ route('lecturer.profile.index') }}" class="btn btn-primary">Kelola Profil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcomponent 