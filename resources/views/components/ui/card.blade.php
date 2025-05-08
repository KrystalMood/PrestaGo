{{-- Card Component --}}

@props([
    'title' => 'Card Title',
    'tambah' => null,
    'export' => null,
])

{{-- Card Structure --}}
<div>
    <div class="card bg-base-100 card-xs rounded-lg border border-gray-200">
        <!-- header -->
<div class="card-header grid grid-cols-2 items-center my-4 mx-4">

    <div class="text-center">
        <h4 class="font-semibold text-lg text-gray-800 card-title leading-tight">
            {{ $title }}
        </h4>
    </div>

    <div class="text-right">
        @if ($tambah)
            <a href="{{ $tambah }}" class="btn btn-primary">Tambah Prestasi</a>
        @endif
        @if ($export)
            <a href="{{ $export }}" class="btn btn-outline-primary">Export Prestasi</a>
        @endif
    </div>
</div>

        <hr class="border-gray-200">
        <div class="card-body">
            {{ $slot }}
        </div>
</div>
