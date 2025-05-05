@component('layouts.app', ['title' => 'Daftar Mahasiswa'])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Daftar Mahasiswa') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Mahasiswa Bimbingan</h4>
                    
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari mahasiswa...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Cari</button>
                            </div>
                        </div>
                    </div>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Program Studi</th>
                                <th>Jumlah Prestasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data mahasiswa</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endcomponent 