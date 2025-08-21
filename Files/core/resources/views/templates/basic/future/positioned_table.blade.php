<table class="table table-two my-order-list-table order-table prep-table">
    <thead>
        <tr>
            <th>@lang('Symbol')</th>
            <th>@lang('Size')</th>
            <th>@lang('TP/SL')</th>
            <th>@lang('Entry Price')</th>
            <th>@lang('Current Price')</th>
            <th>@lang('Liq. Price')</th>
            <th>@lang('Margin')</th>
            <th>@lang('PNL (ROI)')</th>
            <th class="text--base cursor-pointer {{ $positionedOrders->count() ? 'closeAllPosition' : '' }} ">@lang('Close All Positions')</th>
        </tr>
    </thead>
    <tbody class="order-list-body">
        @php
            $unrealizedPnl = 0;
        @endphp
        @forelse ($positionedOrders as $positionedOrder)
            <tr>
                <td>
                    <div class="future-symbol {{ $positionedOrder->order_side == Status::BUY_SIDE_ORDER ? 'future-symbol--buy' : 'future-symbol--sell' }}">
                        <div class="future-symbol__inner">
                            <h4 class="title">{{ __(@$positionedOrder->futureTradeConfig->coinPair->symbol) }}</h4>
                            <span class="sub-title">
                                @lang('Perpetual')
                            </span>
                        </div>
                        <span class="future-symbol__badge">
                            {{ @$positionedOrder->leverage }}@lang('X')
                        </span>
                    </div>
                </td>
                <td>
                    <span class="up">
                        <span class="positionSize">
                            {{ showAmount(($positionedOrder->coin_amount - $positionedOrder->pendingCoinAmount) * $positionedOrder->futureTradeConfig->coinPair->marketData->price, currencyFormat: false) }}
                        </span>
                    </span>
                </td>
                <td>
                    <button class="tp-add-btn btn btn--sm addProfitLoseBtn" data-future_order_id="{{ $positionedOrder->id }}" data-coin_amount="{{ getAmount($positionedOrder->coin_amount) }}" data-available_coin_amount="{{ getAmount($positionedOrder->coin_amount - $positionedOrder->pendingCoinAmount) }}" data-entry_price="{{ getAmount($positionedOrder->rate) }}" data-pending_coin_amount="{{ getAmount($positionedOrder->pendingCoinAmount) }}" data-order_side="{{ $positionedOrder->order_side }}"
                        data-bs-toggle="modal" data-bs-target="#takeProfitLossModal"><i class="fa-solid fa-plus"></i> @lang('ADD')</button>
                </td>
                <td>{{ showAmount($positionedOrder->rate, currencyFormat: false) }}</td>
                <td><span class="thisSymCurrentPrice">{{ $positionedOrder->futureTradeConfig->coinPair->marketData->price }}</span></td>
                <td>
                    @if ($positionedOrder->liquidation_rate > 0)
                        <span class="text--danger">{{ showAmount($positionedOrder->liquidation_rate, currencyFormat: false) }}</span>
                    @else
                        <span> --</span>
                    @endif
                </td>
                <td>
                    <div class="trade-margin-wrapper d-flex gap-2 align-items-center">
                        <div class="trade-margin-left">
                            <span class="amount">{{ showAmount($positionedOrder->margin, currencyFormat: false) }}</span> <br>
                            <span>({{ $positionedOrder->margin_mode == Status::MARGIN_MODE_CROSS ? __('Cross') : __('Isolated') }})</span>
                        </div>
                    </div>
                </td>
                <td>
                    @php
                        $currentPrice = $positionedOrder->futureTradeConfig->coinPair->marketData->price;
                        $currentPrice = $currentPrice > 0 ? $currentPrice : 1;
                        $pnl = ($currentPrice - $positionedOrder->rate) * ($positionedOrder->size / $currentPrice);
                        if ($positionedOrder->order_side == Status::SELL_SIDE_ORDER) {
                            $pnl = $pnl * -1;
                        }
                        $roi = $positionedOrder->margin > 0 ? ($pnl / $positionedOrder->margin) * 100 : 0;
                        $unrealizedPnl += $pnl;
                    @endphp

                    <div class="trade-margin-wrapper d-flex gap-2 align-items-center">
                        @php
                            $currentPrice = $positionedOrder->futureTradeConfig->coinPair->marketData->price;
                            $rate = $positionedOrder->rate;
                            $size = $positionedOrder->size;
                            $margin = $positionedOrder->margin;
                            $orderSide = $positionedOrder->order_side;
                            $symbol = strtolower(str_replace('_', '', $positionedOrder->futureTradeConfig->coinPair->symbol));
                            $currencySymbol = @$positionedOrder->futureTradeConfig->coinPair->market->currency->symbol;
                        @endphp

                        <div class="trade-margin-left {{ $pnl > 0 ? 'text--success' : 'text--danger' }} pnlAndRoi" data-rate="{{ $rate }}" data-size="{{ $size }}" data-margin="{{ $margin }}" data-side="{{ $orderSide }}" data-symbol="{{ $symbol }}" data-currency="{{ $currencySymbol }}">
                            <span class="amount text--default">{{ showAmount(abs($pnl), currencyFormat: false) }} {{ $currencySymbol }}</span><br>
                            <span class="roi text--default">({{ showAmount($roi, 2, currencyFormat: false) }})%</span>
                        </div>
                    </div>
                </td>
                <td>
                    <form action="{{ route('user.future.trade.profit.loss.take') }}" class="closePositionForm">
                        @csrf
                        <input type="hidden" class="futureOrderId" value="{{ $positionedOrder->id }}">
                        <div class="close-position">
                            <div class="close-position__label">
                                <button class="text--base closePositionBtn">@lang('Close')</button>
                            </div>
                            <input type="number" step="any" class="form-control form--control close-position-input closePositionAmount" value="{{ getAmount($positionedOrder->coin_amount - $positionedOrder->pendingCoinAmount) }}">
                            <input type="number" step="any" class="form-control form--control close-position-input closePositionTriggerPrice" value="{{ getAmount($positionedOrder->futureTradeConfig->coinPair->marketData->price) }}">
                        </div>
                    </form>
                </td>
            </tr>
        @empty
            @php echo userTableEmptyMessage('positioned') @endphp
        @endforelse
    </tbody>
</table>

<script>
    (function($) {
        "use strict";

        let totalUnrealizedPnl = parseFloat(`{{ $unrealizedPnl }}`);
        let className = totalUnrealizedPnl > 0 ? 'text--success' : 'text--danger';
        $('.totalUnrealizedPnl').text(getAmount(totalUnrealizedPnl)).addClass(className).parent('span').addClass(className);

        $(document).on('click', '.closeAllPosition', function() {
            console.log('cliic');
            
            $.get(`{{ route('user.future.trade.position.close.all') }}`,
                function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                        getPositionedOrder();
                        getPendingQueue();
                    }else{
                        notify('error', response.message);
                    }
                }
            );

        })


    })(jQuery);
</script>
