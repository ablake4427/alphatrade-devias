@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
@if (!session()->has('success'))
    <div class="p2p-form">
        <div class="p2p-form__wrapper">
            <div class="p2p-form__tab">
                @if (@$ad)
                    <span class="p2p-form__tab-button ad-type side-buy @if (@$ad->type == Status::P2P_AD_TYPE_BUY) active @endif"
                        data-type="{{ Status::P2P_AD_TYPE_BUY }}">@lang('Buy')</span>
                    <span class="p2p-form__tab-button ad-type side-sell @if (@$ad->type == Status::P2P_AD_TYPE_SELL) active @endif"
                        data-type="{{ Status::P2P_AD_TYPE_SELL }}">@lang('Sell')</span>
                @else
                    <span class="p2p-form__tab-button ad-type side-buy active" data-type="{{ Status::P2P_AD_TYPE_BUY }}">@lang('Buy')</span>
                    <span class="p2p-form__tab-button ad-type side-sell" data-type="{{ Status::P2P_AD_TYPE_SELL }}">@lang('Sell')</span>
                @endif
            </div>
        </div>
        <div class="p2p-form__content">
            <form class="p2p-form-box" method="POST" action="{{ route('user.p2p.advertisement.save', @$ad->id) }}">
                @csrf
                <input type="hidden" name="type" value="{{ Status::P2P_TRADE_SIDE_BUY }}">
                <input type="hidden" name="step" value="{{ $step }}">
                @include($activeTemplate . "user.p2p.advertisement.step.$step")
            </form>
        </div>
    </div>
@else
@include($activeTemplate . 'user.p2p.empty_message', [
    'text'    => 'New Ad',
    'message' => session('success'),
    'url'     => route("user.p2p.advertisement.create"),
    'img' => getImage('assets/images/extra_images/new.png')
])
@endif

@endsection
