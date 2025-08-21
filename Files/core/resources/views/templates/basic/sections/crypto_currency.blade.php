@php
$content          = getContent('crypto_currency.content',true);
$cryptoCurrencies = App\Models\Currency::whereHas('marketData')->with('marketData:id,currency_id,html_classes,percent_change_1h,price,last_price')->crypto()->rankOrdering()->take(8)->get();
@endphp

<section  class="currency-section py-120 section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2 class="section-heading__title"> {{ __(@$content->data_values->heading) }} </h2>
                    <p class="section-heading__desc">{{ __(@$content->data_values->subheading) }} </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4" id="crypto-wrapper">
            @forelse($cryptoCurrencies as $cryptoCurrency)
            @php
                $marketData=@$cryptoCurrency->marketData;
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xsm-6">
                <div class="currency-item crypto-{{$cryptoCurrency->symbol}}">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-between">
                            <span class="currency-item__icon">
                                <img src="{{ $cryptoCurrency->imageUrl}}">
                            </span>
                            <div class="currency-item__coin">
                                <h6 class="currency-item__coin-title">{{ __($cryptoCurrency->symbol) }}</h6>
                                <span class="currency-item__coin-name"> {{ __($cryptoCurrency->name) }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="currency-item__content">
                        <h4 class="currency-item__content-number fs-18">
                            <span>
                                <span class="market-price-symbol-{{@$marketData->id}} {{ @$marketData->html_classes->price_change }}">
                                    {{ gs('cur_sym') }}
                                </span><span class="market-price-{{@$marketData->id}} {{ @$marketData->html_classes->price_change }}">
                                    {{ showAmount($cryptoCurrency->marketData->price,currencyFormat:false) }}
                                </span>
                            </span>
                            <span> - </span>
                            <span class="market-percent-change-1h-{{@$marketData->id}} {{ @$marketData->html_classes->percent_change_1h }}">
                                {{ showAmount($marketData->percent_change_1h,2,currencyFormat:false) }}%
                            </span>
                        </h4>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-thumb text-center">
                <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                <p class="fs-14">@lang('No crypto currency found')</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@if (!app()->offsetExists('pusher_script'))
@push('script-lib')
<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
@endpush
@php app()->offsetSet('pusher_script',true) @endphp
@endif


@push('script')
    <script>
        "use strict";
        (function ($) {
            @if (!app()->offsetExists('lisiten_market_data_event'))
                pusherConnection('market-data', marketChangeHtml);
                @php app()->offsetSet('lisiten_market_data_event',true) @endphp
            @endif
        })(jQuery);
    </script>
@endpush




