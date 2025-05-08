@extends('components.shared.content')

@section('content')


@component('layouts.app', ['title' => 'Prestasi Saya'])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <x-ui.card title="Prestasi Saya" tambah="#">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Daftar Prestasi</h4>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Prestasi</th>
                                <th>Jenis</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data prestasi</td>
                            </tr>
                        </tbody>
                    </table>
            </x-ui.card>
        </div>
    </div>
</div>
@endcomponent

@endsection
