<div class="modal favorites-modal trade-profit-modal custom--modal fade zoomIn" id="takeProfitLossModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 p-0 mb-3">
                <div class="margin-mode buy-Sell-trade-modal tpsl-form">
                    <ul class="nav nav-pills" id="pills-tab-buy-sell" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link buy-btn active profitLossTabBtn" id="takeprofit-tab" data-bs-toggle="pill" data-bs-target="#pills-BuyLong2" type="button" role="tab" aria-controls="pills-BuyLong2" aria-selected="true">@lang('Take Profit')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link sell-btn profitLossTabBtn" id="sellShort-tab2" data-bs-toggle="pill" data-bs-target="#sellShort2" type="button" role="tab" aria-controls="sellShort2" aria-selected="false" tabindex="-1">@lang('Stop Loss')</button>
                        </li>
                    </ul>
                </div>
                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            </div>
            <div class="modal-body p-0">
                <div class="" id="pills-BuyLong2" role="tabpanel" aria-labelledby="takeprofit-tab" tabindex="0">
                    <form action="{{ route('user.future.trade.profit.loss.take') }}" method="POST" class="common-form-design takeProfitForm">
                        @csrf
                        <input type="hidden" name="future_order_id">
                        <div class="tpsl-inner d-flex gap-3">
                            <div class="container-fluid">
                                <div class="row gy-4">
                                    <div class="col-md-7">
                                        <div class="tpsl-inner__left w-100">
                                            <div class="convert-asset__transection w-100">
                                                <ul>
                                                    <li>
                                                        <span>@lang('Symbol')</span>
                                                        <span class="text-white">{{ __($pair->symbol) }}<span class="badge badge--gray badge--sm">@lang('Prep')</span></span>
                                                    </li>
                                                    <li>
                                                        <span>@lang('Entry Price')</span>
                                                        <span class="fw-600 text-white entryPrice"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="common-input-group z-2">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form--control form-control style-three" placeholder="Trigger Price" name="trigger_price">
                                                        <span class="input-group-text bg--base border--base text-white">{{ __(@$pair->market->currency->symbol) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-7">
                                                    <div class="input--group group-two convert-asset mb-2 group-two-one">
                                                        <button type="button" class="buy-sell__price-btc fs-12 text-end setMarketPrice"><span class="text-white fw-600">@lang('Market Price') </span>
                                                        </button>
                                                        <input type="number" class="form--control style-three buy-amount" name="market_price" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="common-input-group z-1">
                                                        <input type="text" class="form--control style-three profitPnlVal" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-2 mt-4">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-2">
                                                        <label for="" class="mb-2 text-white">@lang('Size')</label>
                                                        <div class="input--group group-two convert-asset">
                                                            <span class="buy-sell__price-btc fs-12 text-end"><span class="text-white fw-600">{{ __(@$pair->coin->symbol) }}</span>
                                                            </span>
                                                            <input type="number" step="any" class="form--control style-three buy-amount" name="coin_amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="custom--range">
                                                        <div class="profit-loss-slider custom--range__range slider-range"></div>
                                                        <ul class="range-list profit-loss-range">
                                                            <li class="range-list__number" data-percent="0">
                                                                @lang('1x')<span></span></li>
                                                            <li class="range-list__number" data-percent="25">
                                                                @lang('25x')<span></span></li>
                                                            <li class="range-list__number" data-percent="50">
                                                                @lang('50x')<span></span></li>
                                                            <li class="range-list__number" data-percent="75">
                                                                @lang('75x')<span></span></li>
                                                            <li class="range-list__number" data-percent="100">
                                                                @lang('100x')<span></span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input--group group-two convert-asset mb-2">
                                                        <span class="buy-sell__price-btc fs-12 text-end"><span class="text-white fw-600">%</span>
                                                        </span>
                                                        <input type="number" step="any" placeholder="0" class="form--control style-three buy-amount coinAmountPercent">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="tpsl-inner__right">
                                            <div id="timer-container">
                                                <svg width="200" height="200" viewBox="0 0 200 200" class="circular-progress circularProgress">
                                                    <defs>
                                                        <linearGradient id="gradientStroke1" x1="0%" y1="0%" x2="100%" y2="0%">
                                                            <stop offset="0%" stop-color="hsl(var(--base))">
                                                            </stop>
                                                            <stop offset="100%" stop-color="hsl(var(--base-two))"></stop>
                                                        </linearGradient>
                                                    </defs>
                                                    <circle class="bg" cx="100" cy="100" r="90" fill="none" stroke="#ddd" stroke-width="15"></circle>
                                                    <circle class="fg" cx="100" cy="100" r="90" fill="none" stroke="url(#gradientStroke1)" stroke-width="15" stroke-dasharray="565.48" circumference:="" 2="" *="" π="" 90="" --="">
                                                    </circle>
                                                </svg>
                                                <div class="progress-text">
                                                    <p class="fw-bold text-white title progressTitle"></p>
                                                    <p>@lang('Available')</p>
                                                </div>
                                            </div>
                                            <div class="tpsl-inner__left w-100">
                                                <div class="convert-asset__transection w-100">
                                                    <ul>
                                                        <li>
                                                            <span>@lang('Available')</span>
                                                            <span class="text-white availableCoinAmount"></span>
                                                        </li>
                                                        <li>
                                                            <span>@lang('Trigger Price')</span>
                                                            <span class="text-white triggerPrice"></span>
                                                        </li>
                                                        <li>
                                                            <span>@lang('Estimated PNL')</span>
                                                            <span class="fw-600 text-white profitPnl"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-group mt-4 text-end">
                                    <button class="btn btn--base">@lang('Confirm')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        (function($) {
            "use strict";

            let availableCoinAmount = 0;
            let inputSize = 0;
            let coinAmountPercent = 0;
            let orderSide = '';
            let triggerPrice = 0;

            $(document).on('click', '.addProfitLoseBtn', function() {
                availableCoinAmount = $(this).data('available_coin_amount') * 1;
                let coinAmount = $(this).data('coin_amount') * 1;
                let futureOrderId = $(this).data('future_order_id');
                let entryPrice = $(this).data('entry_price');
                let pendingCoinAMount = $(this).data('pending_coin_amount');
                orderSide = $(this).data('order_side');
                triggerPrice = $(this).closest('tr').find('.thisSymCurrentPrice').text();
                $('[name="market_price"]').val(triggerPrice);
                $('[name="trigger_price"]').val(triggerPrice);
                $('.triggerPrice').text(triggerPrice);

                let availableCoinAmountPercent = (availableCoinAmount / coinAmount) * 100;
                $('[name="future_order_id"]').val(futureOrderId);
                $('.entryPrice').text(entryPrice);
                $('.progressTitle').text(availableCoinAmountPercent.toFixed(2) + '%');
                $('.circularProgress').attr('style', '--progress: ' + availableCoinAmountPercent + ';');
                $('.availableCoinAmount').text(availableCoinAmount.toFixed(2) + ' {{ __(@$pair->coin->symbol) }}');
            });


            $(document).on('input', '[name="trigger_price"]', function() {
                $('.triggerPrice').text($(this).val());
                calculateProfitPnl();
            });

            $(document).on('click', '.setMarketPrice', function() {
                let marketPrice = $('[name="market_price"]').val();
                $('[name="trigger_price"]').val(marketPrice);
                calculateProfitPnl();
            });

            $(document).on('input', '.coinAmountPercent', function() {
                coinAmountPercent = $(this).val();
                inputSize = (coinAmountPercent / 100) * availableCoinAmount;
                $('[name=coin_amount]').val(inputSize.toFixed(2));
                calculateProfitPnl();
            });

            $(document).on('input', '[name=coin_amount]', function() {
                inputSize = $(this).val();
                coinAmountPercent = (inputSize / availableCoinAmount) * 100;
                $('.coinAmountPercent').val(coinAmountPercent.toFixed(2));

                $(".profit-loss-slider").find('.ui-widget-header').css({
                    'width': `${coinAmountPercent}%`
                });

                $(".profit-loss-slider").find('.ui-state-default').css({
                    'left': `${coinAmountPercent >= 97 ? 97 : coinAmountPercent}%`
                });

                calculateProfitPnl();
            });


            function calculateProfitPnl() {
                let sliderPercent = coinAmountPercent > 100 ? 100 : coinAmountPercent;

                $(".profit-loss-slider").find('.ui-widget-header').css({
                    'width': `${sliderPercent}%`
                });

                $(".profit-loss-slider").find('.ui-state-default').css({
                    'left': `${sliderPercent > 97 ? 97 : sliderPercent}%`
                });

                let entryPrice = $('.entryPrice').text();
                let price = $('[name="trigger_price"]').val();

                if (!price) {
                    price = $('[name="market_price"]').val();
                }

                let profitPnl = (price - entryPrice) * (inputSize / price);

                if (orderSide == `{{ Status::SELL_SIDE_ORDER }}`) {
                    profitPnl = profitPnl * -1;
                }

                $('.profitPnl').text(getAmount(profitPnl));
                $('.profitPnlVal').val(getAmount(profitPnl));
            }


            $(document).on('submit', '.takeProfitForm', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#takeProfitLossModal').modal('hide');
                            notify('success', response.message);
                            getPendingQueue();
                            getPositionedOrder();
                        } else {
                            notify('error', response.message);
                        }
                    },
                    error: function(response) {
                        notify('error', response.message);
                    }
                });
            });

            $('.profitLossTabBtn').on('click', function() {
                $('.coinAmountPercent').val(0).trigger('input');
                $('[name=trigger_price]').val($('[name=market_price]').val());
            });

            $('#takeProfitLossModal').on('hidden.bs.modal', function() {
                $('[name=coin_amount], .coinAmountPercent').val('');
            });

            {{-- blade-formatter-disable --}}

                $('.profit-loss-range').on('click', '.range-list__number', function(e) {
                    @guest
                        return false;
                    @endguest

                    let percent = parseInt($(this).data('percent'));
                    changeBuyAmountRange(percent);

                   
                });

                function changeBuyAmountRange(percent) {
                    @guest
                        return false;
                    @endguest

                    percent = parseFloat(percent);

                    if (percent > 100) {
                        notify('error', "@lang('Invalid amount range selected')");
                        return false;
                    }

                    if (availableCoinAmount <= 0) return false;

                    let percentAmount = (availableCoinAmount / 100) * percent;
                    $('[name=coin_amount]').val(getAmount(percentAmount)).trigger('change');
                    $('.coinAmountPercent').val(percent).trigger('input');
                }

                $(".profit-loss-slider").slider({
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



        })(jQuery);
    </script>
@endpush
