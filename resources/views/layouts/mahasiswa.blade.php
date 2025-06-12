@extends('components.shared.content')

@section('content')
    @if(isset($slot))
        {{ $slot }}
    @else
        @yield('content')
    @endif
@endsection


