<div class="trading-bottom__header flex-between">
    <div class="trading-bottom__header-top">
        <div>
            <button type="button" class="future-trade-btn marginModeBtnText" data-bs-toggle="modal" data-bs-target="#crossModal">@lang('Isolated')</button>
            <button type="button" class="future-trade-btn leverageBtnText" data-bs-toggle="modal" data-bs-target="#exampleModalLeverage">{{ $futurePair->leverage }}@lang('X')</button>
        </div>
        <div class="d-flex gap-2 align-items-center">

            @if (@$onlyBuy || @$onlySell)
                <span class="sidebar__close"><i class="fas fa-times"></i></span>
            @endif

        </div>
    </div>
    <ul class="nav nav-pills mb-0 custom--tab tab-three" id="pills-tab" role="tablist">
        <li class="nav-item order-type" role="presentation" data-order-type="limit">
            <button class="nav-link active" type="button"> @lang('Limit') </button>
        </li>
        <li class="nav-item order-type" role="presentation" data-order-type="market">
            <button class="nav-link" type="button"> @lang('Market')</button>
        </li>
        <li class="nav-item order-type" role="presentation" data-order-type="stop_limit">
            <button class="nav-link" type="button"> @lang('Stop Limit')</button>
        </li>
    </ul>
</div>
<div class="tab-content">
    <div class="tab-pane fade show active" role="tabpanel">
        <div class="trading-bottom__wrapper order-wrapper">
            <div class="d-flex buy-sell__wrapper mt-3 gap-2">
                <h6 class="buy-sell__title">@lang('Available')</h6>
                <span class="fs-12">
                    <span class="avl-market-cur-wallet"></span> {{ @$pair->market->currency->symbol }}
                </span>
            </div>
            <form class="buy-sell-form  buy--form orderForm" method="POST">
                @csrf
                <input type="hidden" name="pair_id" value="{{ $pair->id }}">
                <input type="hidden" name="order_side">
                <input type="hidden" name="leverage" value="{{ $futurePair->leverage }}">
                <input type="hidden" name="margin_mode" value="{{ Status::MARGIN_MODE_ISOLATED }}">
                <input type="hidden" name="order_type" value="{{ Status::QUEUE_LIMIT_ORDER }}">
                <input type="hidden" name="size_coin" value="1">
                <div class="buy-sell__price stop-limit-order d-none">
                    <div class="input--group group-two">
                        <span class="buy-sell__price-btc fs-12"> {{ @$pair->market->currency->symbol }} </span>
                        <input type="number" step="any" class="form--control style-three" name="stop_rate" placeholder="@lang('Stop')">
                    </div>
                </div>
                <div class="buy-sell__price">
                    <div class="input--group group-two">
                        <span class="buy-sell__price-btc fs-12 cursor-pointer"> {{ @$pair->market->currency->symbol }}
                        </span>
                        <input type="number" step="any" class="form--control style-three buy-rate" name="rate" placeholder="@lang('Rate')" value="{{ getAmount($futurePair->coinPair->marketData->price) }}" id="rate">
                    </div>
                </div>
                <div class="common-input-group z-9 buy-sell__price">
                    <input type="text" class="form--control style-three" placeholder="Size" name="size" autocomplete="off">
                    <div class="future-dropdown-wrapper">
                        <div class="custom--dropdown future-order-book">
                            <div class="custom--dropdown__selected dropdown-list__item">
                                <span class="text">{{ __(@$pair->market->currency->symbol) }}</span>
                            </div>
                            <ul class="dropdown-list">
                                <li class="dropdown-list__item sizeCoin" data-size_coin="1">
                                    <span class="text">{{ __(@$pair->market->currency->symbol) }}</span>
                                </li>
                                <li class="dropdown-list__item sizeCoin" data-size_coin="2">
                                    <span class="text">{{ __(@$pair->coin->symbol) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="custom--range mt-2">
                    <div class="place-order-slider custom--range__range slider-range"></div>
                    <ul class="range-list place-order-range">
                        <li class="range-list__number" data-percent="0">
                            @lang('0')%<span></span></li>
                        <li class="range-list__number" data-percent="25">
                            @lang('25')%<span></span></li>
                        <li class="range-list__number" data-percent="50">
                            @lang('50')%<span></span></li>
                        <li class="range-list__number" data-percent="75">
                            @lang('75')%<span></span></li>
                        <li class="range-list__number" data-percent="100">
                            @lang('100')%<span></span></li>
                    </ul>
                </div>
                @auth
                    <div class="trading-bottom__button d-flex gap-2">
                        @if (!@$onlySell)
                            <button class="btn btn--base-two w-100 btn--sm  buy-btn h-45 longBtn" type="submit">
                                @lang('BUY/LONG')
                            </button>
                        @endif
                        @if (!@$onlyBuy)
                            <button class="btn btn--danger btn--sm w-100 sell-btn h-45 shortBtn" type="submit">
                                @lang('SELL/SHORT')
                            </button>
                        @endif
                    </div>
                @endauth

                <ul class="future-trade-data">
                    <li class="future-trade-data-item">
                        <span class="d-blocks future-trade-data-item__single">
                            <span>@lang('Size'):</span><span class="sizeAmount">0.00</span>
                        </span>
                        <span class="future-trade-data-item__single">
                            <span>@lang('Cost'):</span><span class="costAmount">0.00</span>
                        </span>
                    </li>
                    <li class="future-trade-data-item">
                        <span class="future-trade-data-item__single">
                            <span>@lang('Min'):</span><span>{{ showAmount(@$futurePair->min_buy_amount, exceptZeros:true, currencyFormat: false) }} {{ @$pair->coin->symbol }}</span>
                        </span>
                        <span class="future-trade-data-item__single">
                            <span>@lang('Max'):</span><span>{{ showAmount(@$futurePair->max_buy_amount, exceptZeros:true, currencyFormat: false) }} {{ @$pair->coin->symbol }}</span>
                        </span>
                    </li>
                    <li class="future-trade-data-item">
                        <span class="d-blocks future-trade-data-item__single">
                            <span>@lang('Buy Charge'):</span><span>{{ showAmount($futurePair->buy_charge, 2, currencyFormat: false) }}%</span>
                        </span>
                        <span class="future-trade-data-item__single">
                            <span>@lang('Sell Charge'):</span><span>{{ showAmount($futurePair->sell_charge, 2, currencyFormat: false) }}%</span>
                        </span>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>

@once
    @push('script')
        <script>
            (function($) {
                "use strict";

                let coinSymbol = "{{ @$pair->coin->symbol }}";
                let marketCurrencySymbol = "{{ @$pair->market->currency->symbol }}";

                $('.longBtn').on('click', function() {
                    $('[name=order_side]').val({{ Status::BUY_SIDE_ORDER }});
                });

                $('.shortBtn').on('click', function() {
                    $('[name=order_side]').val({{ Status::SELL_SIDE_ORDER }});
                });

                $('.order-type').on('click', function(e) {
                    let orderType = $(this).data('order-type');

                    $('.order-type').find('button').removeClass('active');
                    $(this).find('button').addClass('active');
                    $(this).closest('.trading-bottom').find('.order-wrapper');
                    $('.stop-limit-order').addClass("d-none");
                    $(".buy-sell-form").trigger("reset");

                    if (orderType == 'limit') {
                        $('.buy-rate').attr('readonly', false);
                        $('.sell-rate').attr('readonly', false);
                        $(`input[name=order_type]`).val(`{{ Status::QUEUE_LIMIT_ORDER }}`);
                    }

                    if (orderType == 'market') {
                        $('.buy-rate').attr('readonly', true);
                        $('.sell-rate').attr('readonly', true);
                        $(`input[name=order_type]`).val(`{{ Status::QUEUE_MARKET_ORDER }}`);
                    }

                    if (orderType == "stop_limit") {
                        $('.stop-limit-order').removeClass("d-none");
                        $('.market-and-limit-order').addClass("d-none");
                        $('.buy-rate').attr('readonly', false);
                        $('.sell-rate').attr('readonly', false);
                        $(`input[name=order_type]`).val(`{{ Status::QUEUE_STOP_LIMIT }}`);
                    } else {
                        $('.stop-limit-order').addClass("d-none");
                        $('.market-and-limit-order').removeClass("d-none");
                    }
                });

                $('.buy-sell-form').on('submit', function(e) {
                    e.preventDefault();

                    let formData = new FormData($(this)[0]);

                    let action = "{{ route('user.future.trade.order.save', ':symbol') }}";
                    let symbol = "{{ @$pair->symbol }}";
                    let token = $(this).find('input[name=_token]');
                    let orderSide = $(this).find(`input[name=order_side]`).val();
                    let cancelMessage = "@lang('Cross')";
                    let actionCancel = "{{ route('user.order.cancel', ':id') }}";

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        url: action.replace(':symbol', symbol),
                        method: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.buy-btn').attr('disabled', true);
                            $('.sell-btn').attr('disabled', true);
                            if (orderSide == 1) {
                                $('.buy-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                            } else {
                                $('.sell-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                            }
                        },
                        complete: function() {
                            $('.buy-btn').attr('disabled', false);
                            $('.sell-btn').attr('disabled', false);
                            if (orderSide == 1) {
                                $('.buy-btn').find(`.fa-spin`).remove();
                            } else {
                                $('.sell-btn').find(`.fa-spin`).remove();
                            }
                        },
                        success: function(resp) {
                            if (resp.status == 'success') {
                                getAvailableBalance();
                                let order = resp.data.order;
                                let updateData = {
                                    id: order.id,
                                    amount: order.size,
                                    rate: order.rate
                                }

                                getPendingQueue();
                                $('[name=size]').val('');

                                notify('success', resp.message);
                                $('.openOrderBody').find('.empty-thumb').closest('tr').remove();
                                setTimeout(() => {
                                    $('.order-list-body tr').removeClass('skeleton');
                                }, 500);

                            } else {
                                notify('error', resp.message);
                            }
                        },
                        error: function(e) {
                            notify("@lang('X')")
                        }
                    });
                });

                {{-- blade-formatter-disable --}}

                @auth

                    window.getPositionedOrder = function () {
                        let action = "{{ route('user.future.trade.positioned') }}";
                        $.ajax({
                            url: action,
                            method: "GET",
                            success: function(resp) {
                                $('.positionedTable').html(resp.data.html);
                                $('.positioned-count').text(resp.data.totalPositionedCount);

                                if(resp.data.runningMarginMode == `{{ Status::MARGIN_MODE_CROSS }}`) {
                                    $('.crossActiveBtn').click();
                                } else {
                                    $('.isolatedActiveBtn').click();
                                }

                                if(resp.data.leverage > 0){                                    
                                    $('[name=adjust_leverage]').val(resp.data.leverage);
                                    $('.adjustLeverageBtn').click();
                                }
                                
                                runRealtimePnl();
                            }
                        });
                    }

                    getPositionedOrder();

                    function runRealtimePnl()
                    {
                        $('.pnlAndRoi').each(function () {
                            const container = $(this);
                            const rate = parseFloat(container.data('rate'));
                            const size = parseFloat(container.data('size'));
                            const margin = parseFloat(container.data('margin'));
                            const side = container.data('side');
                            const symbol = container.data('symbol');
                            const currency = container.data('currency');
                            const coinAmount  = container.closest('tr').find('.closePositionAmount').val();

                            const socket = new WebSocket(`wss://stream.binance.com:9443/ws/${symbol}@ticker`);

                            socket.onmessage = function(event) {
                                const data = JSON.parse(event.data);
                                
                                const currentPrice = parseFloat(data.c);
                                let pnl = (currentPrice - rate) * (size / currentPrice);
                                if (side == `{{ Status::SELL_SIDE_ORDER }}`) {                                    
                                    pnl = pnl * -1;
                                }
                                let roi = margin > 0 ? (pnl / margin) * 100 : 0;

                                 // Update DOM
                                const pnlFormatted = Math.abs(pnl).toFixed(4);
                                const roiFormatted = roi.toFixed(2);

                                container.find('.amount')
                                    .text(`${pnlFormatted} ${currency}`)
                                    .removeClass('text--success text--danger text--default')
                                    .addClass(pnl > 0 ? 'text--success' : 'text--danger');

                                container.find('.roi')
                                    .text(`(${roiFormatted}%)`)
                                    .removeClass('text--success text--danger text--default')
                                    .addClass(pnl > 0 ? 'text--success' : 'text--danger');

                                container.closest('tr').find('.thisSymCurrentPrice').text(currentPrice.toFixed(4));

                                container.closest('tr').find('.positionSize').text(getAmount(coinAmount*currentPrice));
                            };                            
                        });

                    }

                    getPendingQueue();

                    let availableBalance = parseFloat($('.avl-market-cur-wallet').text());

                    // get available balance
                    function getAvailableBalance() {
                        let action = "{{ route('user.future.trade.available.balance', @$pair->market->currency->id) }}";
                        $.ajax({
                            url: action,
                            method: "GET",
                            success: function(resp) {
                                $('.avl-market-cur-wallet').text(resp.data.available_balance);
                                availableBalance = parseFloat(resp.data.available_balance);
                            }
                        });
                    }

                    getAvailableBalance();

                @endauth


                let sizeCoin = 1; 
                $('.sizeCoin').on('click', function() {
                    sizeCoin = $(this).data('size_coin');
                    $('[name=size_coin]').val(sizeCoin);
                    calculateCost();
                    updateSliderPositionAndData(0);
                });

                let size = 0; 
                $('[name=size]').on('input', function() {
                    size = $(this).val();
                    calculateCost();

                    if (availableBalance <= 0) return false;
                    let maxBalance;

                    if(sizeCoin == 1){
                        maxBalance = leverage * availableBalance;
                    }else{
                        maxBalance = leverage * availableBalance / parseFloat($('[name=rate]').val());
                    }

                    let percent =  size / maxBalance * 100;
                    updateSliderPositionAndData(percent, false);
                });

                let leverage;

                $('[name=leverage]').on('change', function() {
                    leverage = $(this).val();
                    $('[name=leverage]').val(leverage);
                    calculateCost();
                    updateSliderPositionAndData(0);
                }).change();

                function calculateCost() {
                    let leverage = $('[name=leverage]').val();
                    let symbol = sizeCoin == 1 ? marketCurrencySymbol : coinSymbol;
                    $('.sizeAmount').text(getAmount(size) + ' ' + symbol);
                    $('.costAmount').text(getAmount(size / leverage) + ' ' + symbol);
                }

                $('.place-order-range').on('click', '.range-list__number', function(e) {
                    @guest
                        return false;
                    @endguest

                    let percent = parseInt($(this).data('percent')); 
                    updateSliderPositionAndData(percent);
                });

                function updateSliderPositionAndData(percent, updateSizeInput = true)
                {
                    changeBuyAmountRange(percent, updateSizeInput);
                    percent = percent >= 100 ? 100 : percent;

                    $(".place-order-slider").find('.ui-widget-header').css({
                        'width': `${percent}%`
                    });

                    $(".place-order-slider").find('.ui-state-default').css({
                        'left': `${percent ==100 ? 97 : percent}%`
                    });
                }


                function changeBuyAmountRange(percent, updateSizeInput = true) {                    
                    @guest
                        return false;
                    @endguest

                    percent = parseFloat(percent);

                    if (percent > 100) {
                        notify('error', "@lang('Insufficient balance')");
                        return false;
                    }

                    if (availableBalance <= 0) return false;
                    let maxBalance;

                    if(sizeCoin == 1){
                        maxBalance = leverage * availableBalance;
                    }else{
                        maxBalance = leverage * availableBalance / parseFloat($('[name=rate]').val());
                    }

                    let percentAmount = (maxBalance / 100) * percent;
                    
                    if(updateSizeInput && percentAmount){
                        $('[name=size]').val(getAmount(percentAmount)).trigger('change');
                    }
                }

                $(".place-order-slider").slider({
                    range: 'min',
                    min: 0,
                    max: 100,
                    slide: function(event, ui) {
                        changeBuyAmountRange(ui.value);
                    },
                    change: function(event, ui) {
                        changeBuyAmountRange(ui.value);
                    }
                });

                {{-- blade-formatter-enable --}}

            })
            (jQuery);


            function getPendingQueue() {
                let action = "{{ route('user.future.trade.pending.queue') }}";
                $.ajax({
                    url: action,
                    method: "GET",
                    success: function(resp) {
                        $('.openOrderTable').html(resp.data.html);
                        $('.open-count').text(resp.data.pendingQueueCount);
                    }
                });
            }
        </script>
    @endpush
@endonce
