@php
    $topExchangesCoins = App\Models\Order::whereHas('coin', function ($q) {
        $q->active()->crypto();
        })->where('status', '!=', Status::ORDER_CANCELED)
        ->selectRaw('*,SUM(filled_amount) as total_exchange_amount')
        ->groupBy('coin_id')
        ->orderBy('total_exchange_amount', 'desc')
        ->take(4)
        ->with('coin', 'coin.marketData')
        ->get();
@endphp

<div class="market-overview-card">
    <div class="market-overview-card__header">
        <h6 class="mb-2">@lang('Top Exchanges Coin')</h6>
    </div>
    <div class="market-overview-card__list">
        @forelse ($topExchangesCoins as $topExchangesCoin)
            @php
                $marketData = @$topExchangesCoin->coin->marketData;
                $htmlClass = $marketData->html_classes;
            @endphp
            <div class="market-overview-card__item">
                <span class="coin-name">
                    <img src="{{ @$topExchangesCoin->coin->image_url }}">
                    {{ @$topExchangesCoin->coin->symbol }}
                </span>
                <span class="coin-text">
                    <span class="market-price-symbol-{{@$marketData->id}} {{ @$marketData->html_classes->price_change }}">
                        {{ gs("cur_sym") }}
                    </span><span class="market-price-{{@$marketData->id}} {{ @$marketData->html_classes->price_change }}">
                        {{ showAmount($marketData->price,currencyFormat:false) }}
                    </span>
                </span>
                <span class="coin-percentage">
                    <span class="market-percent-change-1h-{{ @$marketData->id }} {{ @$htmlClass->percent_change_1h }}">
                        {{ getAmount(@$marketData->percent_change_1h) }}%
                    </span>
                </span>
            </div>
        @empty
            <div class="empty-thumb text-center">
                <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                <p class="fs-14">@lang('No coin found')</p>
            </div>
        @endforelse
    </div>
</div>
