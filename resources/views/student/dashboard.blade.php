@component('layouts.app', ['title' => 'Dashboard Mahasiswa'])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Mahasiswa') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p>Ini adalah dashboard khusus untuk mahasiswa.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">Prestasi Saya</div>
                                <div class="card-body">
                                    <p>Lihat dan kelola prestasi yang telah Anda raih.</p>
                                    <a href="{{ route('student.achievements.index') }}" class="btn btn-primary">Lihat Prestasi</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">Kompetisi</div>
                                <div class="card-body">
                                    <p>Lihat informasi kompetisi yang tersedia.</p>
                                    <a href="{{ route('student.competitions.index') }}" class="btn btn-primary">Lihat Kompetisi</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">Profil Saya</div>
                                <div class="card-body">
                                    <p>Kelola informasi profil Anda.</p>
                                    <a href="{{ route('student.profile.index') }}" class="btn btn-primary">Kelola Profil</a>
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