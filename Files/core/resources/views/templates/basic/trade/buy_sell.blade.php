@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
    $marketCurrencyWallet = @$meta->marketCurrencyWallet;
    $coinWallet = @$meta->coinWallet;
    $screen = @$meta->screen;
@endphp

@if (@$meta->screen != 'small')
    <div class="trading-bottom @if (@$meta->screen == 'medium') two @else d-xl-block d-none @endif">
        <div class="trading-bottom__header flex-between mb-2">
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
                    <x-flexible-view :view="$activeTemplate . 'trade.buy_form'" :meta="['pair' => $pair, 'marketCurrencyWallet' => $marketCurrencyWallet, 'screen' => 'big']" />
                    <x-flexible-view :view="$activeTemplate . 'trade.sell_form'" :meta="['pair' => $pair, 'coinWallet' => $coinWallet, 'screen' => 'big']" />
                </div>
            </div>
        </div>
    </div>
@else
    <div class="trading-bottom__fixed">
        <div class="trading-bottom__footer d-flex">
            <div class="trading-bottom__button buy-btn">
                <button class="btn btn--base-two w-100 btn--sm buy-btn-sm"> @lang('BUY')
                    {{ __(@$pair->coin->symbol) }} </button>
            </div>
            <div class="trading-bottom__button sell-btn">
                <button class="btn btn--danger w-100 btn--sm sell-btn-sm"> @lang('SELL')
                    {{ __(@$pair->coin->symbol) }} </button>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="d-sm-block d-md-none">
            <x-flexible-view :view="$activeTemplate . 'trade.buy_form'" :meta="['pair' => $pair, 'marketCurrencyWallet' => $marketCurrencyWallet, 'screen' => 'small']" />
            <x-flexible-view :view="$activeTemplate . 'trade.sell_form'" :meta="['pair' => $pair, 'coinWallet' => $coinWallet, 'screen' => 'small']" />
        </div>
    </div>
@endif

@if (!app()->offsetExists('buy_sell_script'))
    @php app()->offsetSet('buy_sell_script',true) @endphp
    @push('script')
        <script>
            "use strict";
            (function($) {

                    let marketPrice = parseFloat("{{ @$pair->marketData->price }}");
                    let coinSymbol = "{{ @$pair->coin->symbol }}";
                    let marketCurrencySymbol = "{{ @$pair->market->currency->symbol }}";

                    function buyCalculation() {
                        let amount = parseFloat($('.buy-amount').val());
                        if (!amount) {
                            $('.buy-charge').addClass('d-none');
                            return false;
                        }
                        let rate = buyRate();
                        let totalBuyAmount = amount * rate;
                        $('.total-buy-amount').val(getAmount(totalBuyAmount));
                        buyCharge()
                    };

                    $('.buy-amount, .buy-rate').on('input change', function(e) {
                        let allSameElement = $(this).attr('name');
                        $(`form.buy--form`).find(`input[name=${allSameElement}]`).not(this).val($(this).val())
                        buyCalculation();
                    });

                    function buyCharge() {
                        let buyPercentCharge = parseFloat("{{ $pair->percent_charge_for_buy }}");
                        let amount = parseFloat($('.total-buy-amount').val());
                        if (amount && amount > 0) {
                            let charge = (amount / 100) * buyPercentCharge;
                            $('.buy-charge').text(', ' + getAmount(charge) + ' ' + marketCurrencySymbol).removeClass('d-none')
                        } else {
                            $('.buy-charge').addClass('d-none');
                        }
                    }

                    function buyRate() {
                        return parseFloat($('.buy-rate').val() || marketPrice);
                    }

                    $('.total-buy-amount').on('keyup input change', function(e) {
                        let amount = parseFloat($(this).val());
                        if (!amount) return false;
                        let charge = buyCharge(amount);
                        let rate = buyRate();
                        let coinAmount = amount / rate;
                        $('.buy-amount').val(getAmount(coinAmount));
                        buyCharge();
                    });

                    $('.buy-amount-range').on('click', '.range-list__number', function(e) {
                            @guest
                            return false;
                        @endguest

                        let percent = parseInt($(this).data('percent')); changeBuyAmountRange(percent);

                        $(".buy-amount-slider").find('.ui-widget-header').css({
                            'width': `${percent}%`
                        });

                        $(".buy-amount-slider").find('.ui-state-default').css({
                            'left': `${percent ==100 ? 97 : percent}%`
                        });
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

                let availableBalance = parseFloat("{{ @$marketCurrencyWallet->balance }}");
                if (availableBalance <= 0) return false;

                let percentAmount = (availableBalance / 100) * percent; $('.total-buy-amount').val(getAmount(percentAmount))
                .trigger('change');
            }

            $(".buy-amount-slider").slider({
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

            $('.sell-rate, .sell-amount').on('input change', function(e) {
                let allSameElement = $(this).attr('name');
                $(`form.sel--form`).find(`input[name=${allSameElement}]`).not(this).val($(this).val())
                sellCalculation();
            });

            // sell calculation
            function sellCharge() {
                let sellPercentCharge = parseFloat("{{ $pair->percent_charge_for_sell }}");
                let amount = parseFloat($('.total-sell-amount').val());
                if (amount && amount > 0) {
                    let charge = (amount / 100) * sellPercentCharge;
                    $('.sell-charge').text(', ' + getAmount(charge) + ' ' + marketCurrencySymbol).removeClass('d-none')
                } else {
                    $('.sell-charge').addClass('d-none');
                }
            }

            function sellRate() {
                return parseFloat($('.sell-rate').val() || marketPrice);
            }

            function sellCalculation() {
                let amount = parseFloat($('.sell-amount').val());
                if (!amount) {
                    $('.sell-charge').addClass('d-none');
                    return false;
                }
                let rate = sellRate();
                let totalSellAmount = amount * rate;
                $('.total-sell-amount').val(getAmount(totalSellAmount));
                sellCharge();
            };

            $('.total-sell-amount').on('keyup input change', function(e) {
                let amount = parseFloat($(this).val());
                if (!amount) return false;
                let charge = sellCharge(amount);
                let rate = sellRate();
                let marketAmount = amount / rate;
                $('.sell-amount').val(getAmount(marketAmount));
                sellCharge();
            });

            function changeSellAmountRange(percent) {
                @guest
                return false;
            @endguest

            percent = parseFloat(percent);

            if (percent > 100) {
                notify('error', "@lang('Invalid amount range selected')");
                return false;
            }

            let availableBalance = parseFloat("{{ @$coinWallet->balance }}");
            if (availableBalance <= 0) return false;

            let percentAmount = (availableBalance / 100) * percent;
            $('.sell-amount').val(getAmount(percentAmount)).trigger('change');
            }

            $('.sell-amount-range').on('click', '.range-list__number', function(e) {

                @guest
                return false;
            @endguest
            let percent = parseInt($(this).data('percent')); changeSellAmountRange(percent);

            $(".sell-amount-slider").find('.ui-widget-header').css({
                'width': `${percent}%`
            });

            $(".sell-amount-slider").find('.ui-state-default').css({
                'left': `${percent == 100 ? 97 : percent}%`
            });
            });

            $(".sell-amount-slider").slider({
                range: 'min',
                min: 0,
                max: 100,
                slide: function(event, ui) {
                    changeSellAmountRange(ui.value);
                },
                change: function(event, ui) {
                    changeSellAmountRange(ui.value);
                }
            });

            $('.buy-sell-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                let action = "{{ route('user.order.save', ':symbol') }}";
                let symbol = "{{ @$pair->symbol }}";
                let token = $(this).find('input[name=_token]');
                let orderSide = $(this).find(`input[name=order_side]`).val();
                let cancelMessage = "@lang('Are you sure to cancel this order?')";
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
                        if (resp.success) {
                            if (orderSide == 1) {
                                $('.avl-market-cur-wallet').text(resp.data.wallet_balance);
                                $('.buy-charge').addClass('d-none');
                            } else {
                                $('.avl-coin-wallet').text(resp.data.coin_wallet_balance);
                                $('.sell-charge').addClass('d-none');
                            }
                            let order = resp.data.order;
                            let updateData = {
                                id: order.id,
                                amount: order.amount,
                                rate: order.rate
                            }
                            let ordrHtml = `<tr class="skeleton">
                                    <td>${order.formatted_date}</td>
                                    <td>${resp.data.pair_symbol.replace('_','/')} </td>
                                    <td>${order.order_side_badge}</td>
                                    <td>
                                        <div class="order--amount-rate-wrapper">
                                            <span class="order-amount d-block">
                                                ${getAmount(order.amount)}
                                                <span class="amount-rate-update" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="amount">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                            <span class="order-amount d-block">
                                                ${getAmount(order.rate)}
                                                <span class="amount-rate-update" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="rate">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                             <span class="order-amount ${(order.status == 2 && order.is_draft == 1) ? 'd-lock' : 'd-none'}">
                                               <span class="order--rate-value"> 
                                                ${order.order_side == 2 ? "<=" : ">=" } ${getAmount(order.stop_rate)}
                                                </span>
                                            </span>
                                        </div>
                                    </td>
                                    <td> ${getAmount(order.total)}</td>
                                    <td>${getAmount(0)}</td>
                                    <td> ${order.status_badge.replaceAll('badge','text')} </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="delete-icon p-0 m-0 confirmationBtn" data-question="${cancelMessage}" data-action="${actionCancel.replace(':id',order.id)}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                            notify('success', resp.message);
                            $('.order-list-body').prepend(ordrHtml);
                            $('.order-list-body').find('.empty-thumb').closest('tr').remove();
                            setTimeout(() => {
                                $('.order-list-body tr').removeClass('skeleton');
                            }, 500);

                        } else {
                            notify('error', resp.message);
                        }
                    },
                    error: function(e) {
                        notify("@lang('Something went to wrong')")
                    }
                });
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
                $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_LIMIT }}`);
            }

            if (orderType == 'market') {
                $('.buy-rate').attr('readonly', true);
                $('.sell-rate').attr('readonly', true);
                $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_MARKET }}`);
            }

            if (orderType == "stop_limit") {
                $('.stop-limit-order').removeClass("d-none");
                $('.market-and-limit-order').addClass("d-none");
                $('.buy-rate').attr('readonly', false);
                $('.sell-rate').attr('readonly', false);
                $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_STOP_LIMIT }}`);
            } else {
                $('.stop-limit-order').addClass("d-none");
                $('.market-and-limit-order').removeClass("d-none");
            }
            });

            })(jQuery);
        </script>
    @endpush

    @push('style')
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/range-ui.css') }}">
    @endpush

    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.js') }}"></script>
    @endpush
@endif
