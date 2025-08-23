@php
    $meta = (object) $meta;
    $pair = $meta->pair;
@endphp

    <div class="trading-left trading-list-empty  @if($meta->screen == 'big') d-xl-block d-none @elseif($meta->screen == 'medium')  two @endif">
        @if ($meta->screen == 'big')
            <span class="sidebar__close d-xl-none d-block"><i class="fas fa-times"></i></span>
        @endif
        <div class="trading-left__top">
            <h5 class="trading-left__top-title"> @lang('Order Book') </h5>
            <div>
                <ul class="nav nav-pills mb-0 custom--tab">
                    <li class="nav-item change-order-type ps-1 pe-1" data-order-type="all">
                        <button type="button" class="nav-link active">
                            @lang('All')
                        </button>
                    </li>
                    <li class="nav-item change-order-type ps-1 pe-1" data-order-type="sell">
                        <button type="button" class="nav-link">
                            @lang('Sell')
                        </button>
                    </li>
                    <li class="nav-item change-order-type ps-1 pe-1" data-order-type="buy">
                        <button type="button" class="nav-link">
                            @lang('Buy')
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active d-flex d-xl-block flex-wrap gap-2">
                <div class="trading-left__buy flex-fill">
                    <div class="trading-left__header flex-between sell-side-order-list-header">
                        <span class="price__title">@lang('Price')({{ @$pair->market->currency->symbol }})</span>
                        <span class="amount__title">@lang('Amount') ({{ @$pair->coin->symbol }}) </span>
                        <span class="total__title"> @lang('Total')</span>
                    </div>
                    <div class="sell-side-order-list"></div>
                </div>
                <div class="trading-left__bottom sell-list-wrapper flex-fill">
                    <h5 class="trading-left__bottom-title d-block d-none d-xl-block order-book-price-all">
                        <span class="market-price-{{@$pair->marketData->id}} {{ @$pair->marketData->html_classes->price_change }}">
                            {{ showAmount(@$pair->marketData->price,currencyFormat:false) }}
                        </span>
                        <span class="price-icon-{{@$pair->marketData->id}} {{ @$pair->marketData->html_classes->price_change }}">
                            @if (@$pair->marketData->html_classes->price_change == 'up')
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                        </span>
                    </h5>
                    <div class="trading-left__buy left-two ">
                        <div class="d-flex trading-left__header flex-between  buy-side-order-list-header d-xl-none">
                            <span class="price__title">@lang('Price')({{ @$pair->market->currency->symbol }})</span>
                            <span class="amount__title">@lang('Amount') ({{ @$pair->coin->symbol }}) </span>
                            <span class="total__title"> @lang('Total')</span>
                        </div>
                        <div class="order-buy buy-side-order-list"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@if (!app()->offsetExists('order_book_script'))
@php app()->offsetSet('order_book_script',true) @endphp
@push('script')
    <script>
        "use strict";
        (function ($) {

            let orderType="all";

            $('.change-order-type').on('click',function (e) {
                orderType=$(this).data('order-type');

                $('.change-order-type button').removeClass('active');
                $(this).find('button').addClass('active');

                if(orderType == 'all'){
                    $('.trading-left__buy').removeClass('w-100');
                    $('.trading-left__bottom').removeClass('w-100');
                    $('.buy-side-order-list-header').removeClass('d-none');
                    $('.sell-side-order-list-header').removeClass('d-none');
                    $('.sell-side-order-list').removeClass('d-none');
                    $('.buy-side-order-list').removeClass('d-none');
                }

                if(orderType == 'sell'){
                    $('.trading-left__buy').addClass('w-100');
                    $('.trading-left__bottom').removeClass('w-100');
                    $('.buy-side-order-list-header').addClass('d-none');
                    $('.sell-side-order-list-header').removeClass('d-none');
                    $('.buy-side-order-list').addClass('d-none');
                    $('.sell-side-order-list').removeClass('d-none');
                }

                if(orderType == 'buy'){
                    $('.trading-left__buy').removeClass('w-100');
                    $('.trading-left__bottom').addClass('w-100');
                    $('.buy-side-order-list-header').removeClass('d-none');
                    $('.sell-side-order-list-header').addClass('d-none');
                    $('.sell-side-order-list').addClass('d-none');
                    $('.buy-side-order-list').removeClass('d-none');
                }

                getOrderList();
            });

            $('body').on('click','.order-list',function (e) {
                let rate=$(this).data('rate');
                $('.buy-rate').val(getAmount(rate)).trigger('change');
                $('.sell-rate').val(getAmount(rate)).trigger('change');
            });

            function getOrderList(){

                let action="{{ route('trade.order.book',':curSym') }}";
                let sellSideHtml="",buySideHtml="";

                $.ajax({
                    url: action.replace(':curSym',"{{ $pair->symbol }}")+"?order_type="+orderType,
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {
                        $('.order-list').addClass('skeleton');
                    },
                    complete:function(){
                        setTimeout(() => {
                            $('.order-list').removeClass('skeleton');
                        }, 500);
                    },
                    success: function (resp) {
                        if(!resp.success){
                            return false;
                        }
                        if(orderType == 'sell' || orderType == 'all'){
                            if(resp.sell_side_orders.length <=0){
                                $('.sell-side-order-list').html(sellSideHtml || `
                                    <div class="empty-thumb">
                                        <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                        <p class="empty-sell">@lang('No order found')</p>
                                    </div>
                                `);
                            }else{
                                $.each(resp.sell_side_orders || [], function (i,sellSideOrder ) {

                                    sellSideHtml += generateOrderHtml(sellSideOrder);
                                });
                                $('.sell-side-order-list').html(sellSideHtml);
                            }

                        }
                        if(orderType == 'buy' || orderType=='all'){
                            if(resp.buy_side_orders.length <=0){
                                $('.buy-side-order-list').html(buySideHtml || `
                                    <div class="empty-thumb">
                                        <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                        <p class="empty-sell">@lang('No order found')</p>
                                    </div>
                                `);
                            }else{
                                $.each(resp.buy_side_orders || [], function (i,buy_side_order ) {
                                    buySideHtml += generateOrderHtml(buy_side_order);
                                });
                                $('.buy-side-order-list').html(buySideHtml);
                            }
                        }
                    }
                });
            }

            getOrderList();

            function orderPlace(data) {

                let order         = data.order;
                let orderSideSell = "{{ Status::SELL_SIDE_ORDER }}";
                let parentElement = "";

                if(parseInt(data.order.order_side) == parseInt(orderSideSell)){
                     parentElement='.sell-side-order-list';
                    $('.empty-sell').remove();
                }else{
                     parentElement='.buy-side-order-list';
                    $('.empty-buy').remove();
                }

                let existsElement=$(parentElement).find(`.order-rate-${order.rate.replace('.','_')}`);
                if(existsElement.length){

                    let oldTotalAmount = parseFloat($(existsElement).data('total-amount'));
                    let newTotalAmount = oldTotalAmount+parseFloat(order.amount);

                    $(existsElement).data('total-amount',newTotalAmount);
                    $(existsElement).find('.amount__item').text(showAmount(newTotalAmount));

                    let oldTotal = parseFloat($(existsElement).data('total'));
                    let newTotal = parseFloat(oldTotal)+(parseFloat(order.total) * parseFloat(order.rate));

                    $(existsElement).data('total',newTotal);
                    $(existsElement).find('.total__item').text(showAmount(newTotal));
                    $(existsElement).addClass('has-my-order');

                }else{
                    $(parentElement).prepend(`
                    <ul class="trading-left__list flex-between skeleton order-list order-rate-${order.rate.replace('.','_')} has-my-order" data-rate="${order.rate}" data-total-amount='${order.amount}' data-total="${order.total}">
                        <li class="price__item">${showAmount(order.rate)}</li>
                        <li class="amount__item">${showAmount(order.amount)}</li>
                        <li class="total__item"> ${showAmount(order.total)}</li>
                    </ul>
                 `);
                }
                setTimeout(() => {
                    $('.trading-left__list').removeClass('skeleton');
                }, 1500);
            }

            pusherConnection('order-placed-to-{{$pair->symbol}}', orderPlace);

            function generateOrderHtml(order) {
                let tradePercentage = parseInt(order.total_trade) / parseInt(order.total_order);
                let total           = getAmount(order.total_amount)*getAmount(order.rate);
                let colorCode       = parseInt(order.order_side) == 1 ? '#06a55c45' : '#891e1e57';
                return `
                    <ul style="background: linear-gradient(to left, ${colorCode} ${ tradePercentage}%, transparent ${tradePercentage}%)" class="trading-left__list flex-between  order-list skeleton mb-2 ps-0 order-rate-${order.rate.replace('.','_')} mb-2 ${order.has_my_order ? 'has-my-order' : ''}" data-rate="${order.rate}" data-total-amount='${order.total_amount}' data-total="${total}" >
                        <li class="price__item">${showAmount(order.rate)}</li>
                        <li class="amount__item">${showAmount(order.total_amount)}</li>
                        <li class="total__item"> ${showAmount(total)}</li>
                    </ul>
                `;
            };

        })(jQuery);
    </script>
@endpush
@endif

