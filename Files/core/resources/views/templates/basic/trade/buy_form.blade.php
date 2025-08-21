@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
    $marketCurrencyWallet = @$meta->marketCurrencyWallet;
    $screen = @$meta->screen;
@endphp

<form class="buy-sell-form buy-sell @if (@$meta->screen == 'small') buy-sell-one @endif buy--form" method="POST">
    @csrf
    @if ($meta->screen == 'small')
        <span class="sidebar__close"><i class="fas fa-times"></i></span>
    @endif
    <input type="hidden" name="order_side" value="{{ Status::BUY_SIDE_ORDER }}">
    <input type="hidden" name="order_type" value="{{ Status::ORDER_TYPE_LIMIT }}">
    <div class="flex-between buy-sell__wrapper">
        <h6 class="buy-sell__title">@lang('Available')</h6>
        <span class="fs-12">
            <span
                class="avl-market-cur-wallet">{{ showAmount(@$marketCurrencyWallet->balance, currencyFormat: false) }}</span>
            {{ @$pair->market->currency->symbol }}
            <span class="cursor-pointer new--deposit" data-currency="{{ @$pair->market->currency->symbol }}">
                <i class="las la-plus-circle"></i>
            </span>
        </span>
    </div>

    <div class="buy-sell__price stop-limit-order d-none">
        <div class="input--group group-two">
            <span class="buy-sell__price-title fs-12">@lang('Stop') </span>
            <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
            <input type="number" step="any" class="form--control style-three" name="stop_rate"
                placeholder="{{ getAmount($pair->marketData->price) }}">
        </div>
    </div>
    <div class="buy-sell__price">
        <div class="input--group group-two">
            <span class="buy-sell__price-title fs-12">
                <span class="market-and-limit-order">
                    @lang('Price')
                </span>
                <span class="stop-limit-order d-none">
                    @lang('Limit')
                </span>
            </span>
            <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
            <input type="number" step="any" class="form--control style-three buy-rate" name="rate"
                value="{{ getAmount($pair->marketData->price) }}">
        </div>
    </div>
    <div class="buy-sell__price">
        <div class="input--group group-two">
            <span class="buy-sell__price-title fs-12">@lang('Amount') </span>
            <span class="buy-sell__price-btc fs-12"> {{ @$pair->coin->symbol }} </span>
            <input type="number" step="any" class="form--control style-three buy-amount"
                placeholder="{{ @$pair->buyPlaceHolder }}" name="amount">
        </div>
    </div>
    <div class="custom--range mt-2">
        <div class="buy-amount-slider custom--range__range slider-range"></div>
        <ul class="range-list buy-amount-range">
            <li class="range-list__number" data-percent="0">@lang('0')%<span></span></li>
            <li class="range-list__number" data-percent="25">@lang('25')%<span></span></li>
            <li class="range-list__number" data-percent="50">@lang('50')%<span></span></li>
            <li class="range-list__number" data-percent="75">@lang('75')%<span></span></li>
            <li class="range-list__number" data-percent="100">@lang('100')%<span></span></li>
        </ul>
    </div>
    <div class="buy-sell__price pt-0 mb-2">
        <div class="input--group group-two">
            <span class="buy-sell__price-title fs-12">@lang('Total') </span>
            <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
            <input type="number" step="any" class="form--control style-three total-buy-amount"
                placeholder="@lang('0.00')">
            <span class="fs-10 float-end mt-1 mb-2">
                @lang('Fee') {{ getAmount($pair->percent_charge_for_buy) }}%
                <span class="buy-charge d-none"></span>
            </span>
        </div>
    </div>
    <div class="trading-bottom__button">
        @auth
            <button class="btn btn--base-two w-100 btn--sm  buy-btn " type="submit">
                @lang('BUY') {{ __(@$pair->coin->symbol) }}
            </button>
        @else
            <div class="btn login-btn w-100 btn--sm">
                <a href="{{ route('user.login') }}">@lang('Login')</a>
                <span>@lang('or')</span>
                <a href="{{ route('user.register') }}">@lang('Register')</a>
            </div>
        @endauth
    </div>
</form>
