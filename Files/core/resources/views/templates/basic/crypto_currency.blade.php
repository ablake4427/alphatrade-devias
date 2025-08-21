@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="market-overview py-50 section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-between">
                    <div class="section-heading mb-0 style-left">
                        <h4 class="section-heading__title mb-2">
                         {{ __($pageTitle) }}
                        </h4>
                        <p class=" market-overview-subtitle fs-16">
                             @lang('Explore available Cryptocurrency on ') {{ __(gs('site_name')) }}
                        </p>
                     </div>
                    <form class="market-list-search">
                        <input type="search" name="search" class="form-control form--control "  placeholder="@lang('Search here ')...">
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-4 justify-content-center gy-4">
            <div class="col-lg-4 col-md-6">
                <x-flexible-view :view="$activeTemplate.'coin.top_exchange_coin'"/>
            </div>
            <div class="col-lg-4 col-md-6">
                <x-flexible-view :view="$activeTemplate.'coin.highlight_coin'"/>
            </div>
            <div class="col-lg-4 col-md-6">
                <x-flexible-view :view="$activeTemplate.'coin.new_coin'"/>
            </div>
        </div>
    </div>
</div>
<div class="py-60 table-section">
    <div class="table-section__shape light-mood">
        <img src="{{ asset($activeTemplateTrue.'images/shapes/table-1.png') }}">
    </div>
    <div class="table-section__shape dark-mood style">
        <img src="{{ asset($activeTemplateTrue.'images/shapes/table-12.png') }}">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-wrapper">
                    <table class="table coin-pair-list-table table--responsive--lg coin-pair-list">
                        <thead>
                            <tr class="">
                                <th>@lang('Crypto')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('1h Change')</th>
                                <th>@lang('24h Change')</th>
                                <th>@lang('7d Change')</th>
                                <th>@lang('Marketcap')</th>
                            </tr>
                        </thead>
                        <tbody id="market-list-body"></tbody>
                    </table>
                    <div class="text-center mt-5">
                        <button type="button" class="btn btn--base outline btn--sm load-more-market-list">
                            <i class="fa fa-spinner"></i> @lang('Load More')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($sections && $sections->secs != null)
    @foreach (json_decode($sections->secs) as $sec)
        @include($activeTemplate . 'sections.' . $sec)
    @endforeach
@endif

@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            @if (!app()->offsetExists('lisiten_market_data_event'))
                pusherConnection('market-data', marketChangeHtml);
                @php app()->offsetSet('lisiten_market_data_event',true) @endphp
            @endif

            let skip     = 0;
            let limit    = 10;
            let search   = "";
            let loadMore = false;

            $('.load-more-market-list').on('click',function (e) {
                loadMore = true;
                getCryptoCurrencyList();
            });

            $('.market-list-search').on('submit',function(e){
                e.preventDefault();
                search=$(this).find('input[name=search]').val()
                loadMore=false;
                skip=0;
                limit=10;
                getCryptoCurrencyList();
            });

            function getCryptoCurrencyList() {
                let action = "{{ route('crypto_currency.list') }}";
                $.ajax({
                    url: action,
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    data: {
                        skip,
                        limit,
                        search
                    },
                    beforeSend:function(){
                        if(loadMore){
                            $('.load-more-market-list').html(`<i class="fa fa-spinner fa-spin"></i>`)
                        }
                    },
                    complete:function(){
                        if(loadMore){
                            $('.load-more-market-list').html(`<i class="fa fa-spinner"></i> @lang('Load More')`)
                        }else{
                            setTimeout(() => {
                                $('.coin-pair-list tr').removeClass('skeleton');
                            }, 1000);
                        }
                    },
                    success: function(resp) {

                        if (!resp.success) {
                            notify('error', resp.message);
                            return false;
                        }
                        let html = '';
                        if (resp.currencies.length <= 0) {
                            html += `<tr class="text-center">
                                <td colspan="100%">
                                    <div class="empty-thumb">
                                        <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                        <p class="empty-sell">${loadMore ? 'No more crypto currency found' : 'No crypto currency found'}</p>
                                    </div>
                                </td>
                            </tr>`;
                            $('.load-more-market-list').addClass('d-none');
                            loadMore ? $('#market-list-body').append(html) : $('#market-list-body').html(html);
                            return;
                        }
                        $.each(resp.currencies || [], function(i, currency) {
                            let marketData = currency.market_data;
                            let htmlClass  = marketData.html_classes || {};

                            html += `
                            <tr class="${!loadMore ? 'skeleton' : ''}">
                                <td>
                                    <div class="customer d-flex align-items-center">
                                        <div class="pair-thumb">
                                            <div class="coin-img-one">
                                                <img src="${currency.image_url}">
                                            </div>
                                        </div>
                                        <div class="customer__content">
                                            <h6 class="customer__name">${currency.name}</h6>
                                            <h6 class="customer__name crypto-symbol">${currency.symbol}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                   <span class="market-price-${marketData.id} ${htmlClass.price_change != undefined ? htmlClass.price_change : '' }">
                                        ${showAmount(marketData.price)}
                                    </span>
                                </td>
                                <td>
                                    <span class="market-percent-change-1h-${marketData.id} ${htmlClass.percent_change_1h || ''}">
                                        ${showAmount(marketData.percent_change_1h,2)}%
                                    </span>
                                </td>
                                <td>
                                     <span class="market-percent-change-24h-${marketData.id} ${htmlClass.percent_change_24h || ''}">
                                        ${showAmount(marketData.percent_change_24h,2)}%
                                    </span>
                                </td>
                                <td>
                                     <span class="market-percent-change-24h-${marketData.id} ${htmlClass.percent_change_7d || ''}">
                                        ${showAmount(marketData.percent_change_7d,2)}%
                                    </span>
                                </td>
                                <td>
                                    <span class="market-market_cap-${marketData.id}"> ${showAmount(marketData.market_cap)}</span>
                                </td>
                            </tr>
                            `
                        });

                        $('.load-more-market-list').removeClass('d-none');
                        loadMore ? $('#market-list-body').append(html) : $('#market-list-body').html(html);
                        if(skip ==0){
                            tableDataLabel();
                        }
                        skip+=parseInt(limit);
                        if(parseInt(skip) >= parseInt(resp.total) ){
                                $('.load-more-market-list').addClass('d-none')
                        }else{
                            $('.load-more-market-list').removeClass('d-none')
                        }
                    }
                });
            }
            getCryptoCurrencyList();


        })(jQuery);
    </script>
@endpush

@if (!app()->offsetExists('pusher_script'))
@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
@endpush
    @php app()->offsetSet('pusher_script',true) @endphp
@endif


@push('style')
    <style>
        .crypto-symbol{
            font-size: 12px;
        }
    </style>
@endpush

