@component('layouts.mahasiswa', ['title' => 'Prestasi Saya'])



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <x-ui.card title="Prestasi Saya" tambah="{{ route('Mahasiswa.achievements.create') }}">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                            <div id="achievemnets-table-container">
                                @component('Mahasiswa.achievements.components.tables')
                                    @slot('achievements', $achievements ?? collect())
                                @endcomponent
                            </div>

        <div id="pagination-container">
            @component('Mahasiswa.components.tables.pagination', ['data' => $achievements ?? collect()])
            @endcomponent
        </div>
            </x-ui.card>
        </div>
    </div>
</div>

<dialog id="modal" class="modal">
<x-ui.popupcard>
</x-ui.popupcard>
</dialog>
@vite('resources/js/admin/users.js')
@endcomponent

