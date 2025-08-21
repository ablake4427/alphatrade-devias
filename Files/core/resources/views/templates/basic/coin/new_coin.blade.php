@php
    $newCoins = App\Models\Currency::active()->crypto()->rankOrdering()->with('marketData')->take(4)->get();
@endphp

<div class="market-overview-card">
    <div class="market-overview-card__header">
        <h6 class="mb-2">@lang('New Coin')</h6>
    </div>
    <div class="market-overview-card__list">
        @forelse ($newCoins as $newCoin)
            <div class="market-overview-card__item">
                <span class="coin-name">
                    <img src="{{ @$newCoin->image_url }}">
                    {{ @$newCoin->symbol }}
                </span>
                <span class="coin-text">
                    <span class="market-price-symbol-{{ @$newCoin->marketData->id }} {{ @$newCoin->marketData->html_classes->price_change }}">
                        {{ gs("cur_sym") }}
                    </span><span class="market-price-{{ @$newCoin->marketData->id }} {{ @$newCoin->marketData->html_classes->price_change }}">
                        {{ showAmount(@$newCoin->marketData->price,currencyFormat:false) }}
                    </span>
                </span>
                <span class="coin-percentage">
                    <span class="market-percent-change-1h-{{ @$newCoin->marketData->id }} {{ @$newCoin->marketData->html_classes->percent_change_1h }}">
                        {{ getAmount(@$newCoin->marketData->percent_change_1h) }}%
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
