@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
@endphp
<div class="trading-right__bottom">
    <div class="trading-history">
        <h5 class="trading-history__title"> @lang('Trade History') </h5>
    </div>
    <div class="d-flex trading-market__header justify-content-between text-center">
        <div class="trading-market__header-two">
            @lang('Price')({{ $pair->market->currency->symbol }})
        </div>
        <div class="trading-market__header-one">
            @lang('Amount') ({{ $pair->coin->symbol }})
        </div>
        <div class="trading-market__header-three">
            @lang('Date/Time')
        </div>
    </div>
    <div class="tab-content" id="pills-tabContentfortyfour">
        <div class="tab-pane fade show active" id="pills-marketnineteen" role="tabpanel"
            aria-labelledby="pills-marketnineteen-tab" tabindex="0">
            <div class="market-wrapper">
                <div class="history  trade-history"></div>
            </div>
        </div>
    </div>
</div>

@if (!app()->offsetExists('trade_script'))
@php app()->offsetSet('trade_script',true) @endphp
@push('script')
    <script>
        "use strict";
        (function ($) {
            let pairSymbol    = "{{ $pair->symbol }}";
            let sellSideTrade = parseInt("{{ Status::SELL_SIDE_TRADE }}");


            function newTradeHmtl (data) {
               let trades=data.trade;

               let newHtml=``;
               $.each(trades, function (symbol, trade) {
                    if(pairSymbol != symbol){
                        return;
                    }
                    $.each(trade, function (i, element) {
                        newHtml+=`<ul class="history__list flex-between trade-history-item" data-rate="${element.rate}">
                        <li class="history__amount-item text-start ${ sellSideTrade == parseInt(element.trade_side) ? 'text-danger' : '' }">
                            ${showAmount(element.rate)}
                        </li>
                        <li class="history__price-item text-center"> ${showAmount(element.amount)} </li>
                        <li class="history__date-item"> ${new Date().toLocaleString()} </li>
                    </ul>`
                    });
               });
               $('.trade-history').prepend(newHtml);
            }
            pusherConnection('trade', newTradeHmtl);

            $('.trade-history').on('click','.trade-history-item',function (e) {
                let rate=$(this).data('rate');
                $('.buy-rate').val(getAmount(rate)).trigger('change');
                $('.buy-amount').val(1);
                $('.sell-amount').val(1);
                $('.sell-rate').val(getAmount(rate)).trigger('change');
            });

            function tradeHistory(){
                let action        = "{{ route('trade.history',':curSym') }}";

                $.ajax({
                    url: action.replace(':curSym',"{{@$pair->symbol}}"),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function (resp) {
                        let html=``;
                        if(resp.success){
                            if(resp.trades.length > 0){
                                $.each(resp.trades, function (i, trade) {
                                    html+=`<ul class="history__list flex-between trade-history-item" data-rate="${trade.rate}">
                                        <li class="history__amount-item text-start ${ sellSideTrade == parseInt(trade.trade_side) ? 'text-danger' : '' }">
                                            ${showAmount(trade.rate)}
                                        </li>
                                        <li class="history__price-item text-center"> ${showAmount(trade.amount)} </li>
                                        <li class="history__date-item"> ${trade.formatted_date} </li>
                                    </ul>`
                                    ;
                                });
                                $('.trade-history').removeClass('justify-content-center');
                            }else{
                                html+=`
                                <div class="empty-thumb">
                                    <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                    <p class="empty-sell">@lang('No trade found')</p>
                                </div>
                                `;
                                $('.trade-history').addClass('justify-content-center');
                            }
                        }
                        $('.trade-history').html(html);
                    }
                });
            }
            tradeHistory();
        })(jQuery);
    </script>
@endpush
@endif
