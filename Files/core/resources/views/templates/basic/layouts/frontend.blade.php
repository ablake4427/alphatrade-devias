@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @if (request()->routeIs('binary'))
        @include($activeTemplate . 'partials.binary_header')
    @else
        @include($activeTemplate . 'partials.header')
    @endif
    @yield('content')

    @if (!request()->routeIs('binary'))
        @include($activeTemplate . 'partials.footer')
    @endif
@endsection
