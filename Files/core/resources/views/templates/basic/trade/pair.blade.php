@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
@endphp
<div class=" @if (@$meta->screen == 'small') col-sm-12  d-xl-none d-block @else d-xl-block d-none @endif ">
    <div class="trading-header skeleton selected-pair">
        <h4 class="trading-header__title"> {{ str_replace('_', '/', $pair->symbol) }} </h4>
        <div>
            <span class="text--base fs-12">@lang('Price')</span>
            <p class="trading-header-number">
                <span
                    class="market-price-{{ @$pair->marketData->id }} {{ @$pair->marketData->html_classes->price_change }}">
                    {{ showAmount(@$pair->marketData->price,currencyFormat:false) }}
                </span>
            </p>
        </div>
        <div>
            <span class="text--base fs-12">@lang('Last Price')</span>
            <p class="trading-header-number market-last-price-{{ @$pair->marketData->id }} ">
                {{ showAmount(@$pair->marketData->last_price,currencyFormat:false) }}</p>
        </div>
        <div>
            <span class="text--base fs-12"> @lang('1H Change') </span>
            <p class="trading-header__number ">
                <span
                    class="market-percent-change-1h-{{ @$pair->marketData->id }} {{ @$pair->marketData->html_classes->percent_change_1h }}">
                    {{ getAmount(@$pair->marketData->percent_change_1h, 2) }}%
                </span>
            </p>
        </div>
        <div>
            <span class="text--base fs-12"> @lang('24H Change') </span>
            <p class="trading-header__number {{ @$pair->marketData->html_classes->percent_change_24h }}">
                {{ getAmount(@$pair->marketData->percent_change_24h, 2) }}%
            </p>
        </div>
        <div>
            <span class="text--base fs-12">@lang('Marketcap')</span>
            <p class="trading-header__number"> {{ showAmount(@$pair->marketData->market_cap,currencyFormat:false) }} </p>
        </div>
    </div>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            setTimeout(() => {
                $('.selected-pair').removeClass('skeleton');
            }, 1500);
        })(jQuery);
    </script>
@endpush
