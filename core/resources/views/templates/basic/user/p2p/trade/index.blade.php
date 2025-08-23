@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
    @if ($trades->count())
        @include($activeTemplate . 'user.p2p.trade.table', ['trades' => $trades, 'user' => $user])
    @else
        @include($activeTemplate . 'user.p2p.empty_message', [
            'text'    => 'Trade Now',
            'message' => 'No trade found. Click the below button to explore our P2P trade.',
            'url'     => route('p2p'),
            'icon'    => 'far fa-chart-bar',
        ])
    @endif
@endsection
