@php
    $meta = (object) $meta;
@endphp
<div class="table-wrapper">
    <div class=" table-wrapper__item">
        <div class="table-header-menu">
            <button type="button" class="table-header-menu__link market-type active" data-type="all">
                <i class="las la-border-all"></i> @lang('All')
            </button>
            <button type="button" class="table-header-menu__link market-type" data-type="crypto">
                <i class="las la-coins"></i> @lang('Crypto')
            </button>
            <button type="button" class="table-header-menu__link market-type" data-type="fiat"><i class="las la-hryvnia"></i>
                @lang('Fiat')
            </button>
        </div>
        <div class="market-list__left">
            @if (@$meta->from_section)
                <a href="{{ route('market') }}" class="btn btn--sm btn--base outline">
                    <i class="las la-coins"></i> @lang('All Pair')
                </a>
            @else
                <form class="market-list-search">
                    <input type="search" name="market_list_serach" class="market-list-search-field form--control" placeholder="@lang('Search here ')...">
                    <i class="las la-search"></i>
                </form>
            @endif
        </div>
    </div>
    <table class="table coin-pair-list-table table--responsive--lg coin-pair-list">
        <thead>
            <tr class="">
                <th>@lang('Pair')</th>
                <th>@lang('Price')</th>
                <th>@lang('1h Change')</th>
                <th>@lang('24h Change')</th>
                <th class="text-start">@lang('Marketcap')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody id="market-list-body"></tbody>
    </table>
    @if (!@$meta->from_section)
        <div class="text-center mt-5">
            <button type="button" class="btn btn--base outline btn--sm load-more-market-list d-none">
                <i class="fa fa-spinner"></i> @lang('Load More')
            </button>
        </div>
    @endif
</div>

@push('script')
    <script>
        "use strict";
        (function($) {

                @if (!app()->offsetExists('lisiten_market_data_event'))
                    pusherConnection('market-data', marketChangeHtml);
                    @php app()->offsetSet('lisiten_market_data_event',true) @endphp
                @endif

                let type = "all";
                let loadMore = false;
                let skip = 0;
                let limit = "{{ $meta->limit ?? 15 }}";
                let search = "";

                $('.market-type').on('click', function(e) {
                    $('.market-type').removeClass('active');
                    $(this).addClass('active');
                    type = $(this).data('type');
                    resetVariable()
                    getPairList();
                });

                $('.load-more-market-list').on('click', function(e) {
                    loadMore = true;
                    getPairList();
                });

                $('.market-list-search').on('submit', function(e) {
                    e.preventDefault();
                    search = $(this).find('.market-list-search-field').val()
                    resetVariable();
                    getPairList();
                });

                function getPairList() {
                    let action = "{{ route('market.list') }}";
                    $.ajax({
                                url: action,
                                type: "GET",
                                dataType: 'json',
                                cache: false,
                                data: {
                                    type,
                                    skip,
                                    limit,
                                    search
                                },
                                beforeSend: function() {
                                    if (loadMore) {
                                        $('.load-more-market-list').html(`<i class="fa fa-spinner fa-spin"></i>`)
                                    }
                                },
                                complete: function() {
                                    if (loadMore) {
                                        $('.load-more-market-list').html(`<i class="fa fa-spinner"></i> @lang('Load More')`)
                                    } else {
                                        removeSkeleton();
                                    }
                                },
                                success: function(resp) {

                                    if (!resp.success) {
                                        notify('error', resp.message);
                                        return false;
                                    }
                                    let html = '';
                                    if (resp.pairs.length <= 0) {
                                        html += `<tr class="text-center">
                                <td colspan="100%">
                                    <div class="empty-thumb">
                                        <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                        <p class="empty-sell">${loadMore ? 'No more pair found' : 'No pair found'}</p>
                                    </div>
                                </td>
                            </tr>`;
                                        $('.load-more-market-list').addClass('d-none');
                                        loadMore ? $('#market-list-body').append(html) : $('#market-list-body').html(html);
                                        return;
                                    }
                                    let spotTradeUrl = "{{ route('trade', ':symbol') }}";
                                    let binaryTradeUrl = "{{ route('binary', ':id') }}";
                                    let futureTradeUrl = "{{ route('future.trade', ':symbol') }}";
                                    $.each(resp.pairs || [], function(i, pair) {
                                            let marketData = pair.market_data;
                                            let htmlClass = marketData.html_classes || {};
                                            html += `
                            <tr class="${!loadMore ? 'skeleton' : ''}">
                                <td>
                                    <div class="customer d-flex align-items-center">
                                        <div class="pair-thumb">
                                            <div class="coin-img-one">
                                                <img src="${pair.coin.image_url}">
                                            </div>
                                            <div class="coin-img-two">
                                                <img src="${pair.market.currency.image_url}">
                                            </div>
                                        </div>
                                        <div class="customer__content">
                                            <h6 class="customer__name">${pair.symbol}</h6>
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
                                    <span class="market-market_cap-${marketData.id}"> ${showAmount(marketData.market_cap)}</span>
                                </td>
                               <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        ${(() => {
                                            return `
                                                <div class="trade-btn-wrapper">
                                                    <button class="btn allTradeBtn btn--sm outline">
                                                        @lang('Trade')<i class="ms-2 las la-ellipsis-v"></i>  
                                                    </button>
                                                    <ul class="trade-list">
                                                        ${(pair.type == 1 || pair.type == 3) ? `
                                                            <li class="trade-list__item">
                                                                <a href="${spotTradeUrl.replace(':symbol',pair.symbol)}" class="trade-list__link">
                                                                    Spot Trade
                                                                </a> 
                                                            </li>
                                                        ` : ''}

                                                        ${(pair.type == 2 || pair.type == 3) ? `
                                                            <li class="trade-list__item">
                                                                <a href="${binaryTradeUrl.replace(':id',pair.id)}" class="trade-list__link">
                                                                    Binary Trade
                                                                </a> 
                                                            </li>
                                                        ` : ''}

                                                        ${resp.active_future_pairs.includes(pair.id) ? `
                                                            <li class="trade-list__item">
                                                                <a href="${futureTradeUrl.replace(':symbol',pair.symbol)}" class="trade-list__link">
                                                                    Future Trade
                                                                </a> 
                                                            </li>
                                                        ` : ''}

                                                    </ul>
                                                </div>
                                            `;
                                        })()}
                                    </div>
                                </td> </tr>`
                            });


                            $('.load-more-market-list').removeClass('d-none');
                            loadMore ? $('#market-list-body').append(html) : $('#market-list-body').html(html);
                            if (skip == 0) {
                                tableDataLabel();
                            }
                            skip += parseInt(limit);
                            if (parseInt(skip) >= parseInt(resp.total)) {
                                $('.load-more-market-list').addClass('d-none')
                            } else {
                                $('.load-more-market-list').removeClass('d-none')
                            }
                        }
                    });
                }
                getPairList();

                function resetVariable() {
                    loadMore = false;
                    skip = 0;
                    limit = "{{ $meta->limit ?? 20 }}";
                }

                function removeSkeleton() {
                    setTimeout(() => {
                        $('.coin-pair-list tr').removeClass('skeleton');
                    }, 1000);
                }
                removeSkeleton();

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