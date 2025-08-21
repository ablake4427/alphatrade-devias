@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="sidebar-overlay"></div>
    <div class="trading-section py-3 bg-color">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="future-trading-main">
                        <div class="future-trading-left flex-fill">
                            <div class="future-trading-content">
                                <div class="future-trading-content__chart">
                                    <div class="future-trade-header gap-2 d-flex justify-content-between align-items-center {{ count(array_filter($favoritePairs, fn($v) => $v != $futurePair->id)) == 0 ? 'd-none' : '' }} ">
                                        <div class="future-trade-header__chart d-flex flex-wrap favoriteList">
                                            @foreach ($otherPairs as $otherPair)
                                                @if (in_array($otherPair->id, $favoritePairs))
                                                    <a href="{{ route('future.trade', $otherPair->coinPair->symbol) }}"> {{ $otherPair->coinPair->symbol }}
                                                        <span class="{{ @$otherPair->coinPair->marketData->html_classes->percent_change_24h }}">{{ getAmount(@$otherPair->coinPair->marketData->percent_change_24h, 2) }}%</span>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="d-flex trading-header-wrapper flex-wrap">
                                        <div class="trading-header-wrapper-left">
                                            <div class="trading-dropdown">
                                                <div class="trading-dropdown-button d-flex align-items-center">
                                                    <span class="trading-dropdown-button-icon favoriteBtn {{ in_array($futurePair->id, $favoritePairs) ? 'favorite' : '' }}" data-pair_id="{{ $futurePair->id }}">
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                    <span class="d-block trading-dropdown-button-title">{{ $pair->symbol }}</span>
                                                    <div>
                                                        <span class="badge-prep">@lang('Prep')</span>
                                                    </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="20" viewBox="0 0 13 20" fill="none">
                                                        <g clip-path="url(#clip0_2638_585)">
                                                            <path d="M5.36717 14.6328C5.85545 15.1211 6.64842 15.1211 7.1367 14.6328L12.1367 9.63281C12.4961 9.27344 12.6015 8.73828 12.4062 8.26953C12.2109 7.80078 11.7578 7.49609 11.25 7.49609L1.24998 7.5C0.746071 7.5 0.28904 7.80469 0.0937273 8.27344C-0.101585 8.74219 0.00778983 9.27734 0.363259 9.63672L5.36326 14.6367L5.36717 14.6328Z" fill="#6B7280" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2638_585">
                                                                <rect width="12.5" height="20" fill="currentColor" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </div>
                                                <div class="trading-dropdown-box">
                                                    <div class="trading-dropdown-box__search">
                                                        <div class="search-inner">
                                                            <button type="button" class="search-icon"> <i class="fas fa-search"></i></button>
                                                            <input class="search-input form--control style-three" placeholder="Search">
                                                        </div>
                                                    </div>
                                                    <div class="trading-dropdown-tabs">
                                                        <ul class="nav nav-pills mb-2  custom--tab tab-three" id="tradingCoins" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link favoriteListBtn active" id="favotab" data-bs-toggle="pill" data-bs-target="#pills-favo" type="button" role="tab" aria-controls="pills-favo" aria-selected="true">@lang('Favorites')</button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link otherListBtn" id="usdM-tab" data-bs-toggle="pill" data-bs-target="#usdM" type="button" role="tab" aria-controls="usdM" aria-selected="false">@lang('USD$-M')</button>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content" id="tradingCoinsContent">
                                                            @include($activeTemplate . 'future.other_coin_list')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="trading-price">
                                                <h4 class="mb-1 market-price-{{ @$pair->coin->marketData->id }} {{ @$pair->marketData->html_classes->price_change }}">{{ showAmount(@$pair->coin->marketData->price, currencyFormat: false) }}</h4>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-between align-items-center flex-grow-1">
                                            <div class="trading-header selected-pair future-trading-header">
                                                <div>
                                                    <span class="text--base fs-12">@lang('Last Price')</span>
                                                    <p class="trading-header__number ">
                                                        <span class="market-percent-change-1h-2 up">{{ showAmount(@$pair->coin->marketData->last_price, currencyFormat: false) }}</span>
                                                    </p>
                                                </div>
                                                <div>
                                                    <span class="text--base fs-12"> @lang('1H Change') </span>
                                                    <p class="trading-header__number {{ @$pair->marketData->html_classes->percent_change_1h }}">
                                                        {{ getAmount(@$pair->marketData->percent_change_1h, 2) }}%
                                                    </p>
                                                </div>
                                                <div>
                                                    <span class="text--base fs-12"> @lang('24H Change') </span>
                                                    <p class="trading-header__number {{ @$pair->marketData->html_classes->percent_change_24h }}">
                                                        {{ getAmount(@$pair->marketData->percent_change_24h, 2) }}%
                                                    </p>
                                                </div>
                                                <div>
                                                    <span class="text--base fs-12"> @lang('7D Change') </span>
                                                    <p class="trading-header__number {{ @$pair->marketData->html_classes->percent_change_7d }}">
                                                        {{ getAmount(@$pair->marketData->percent_change_7d, 2) }}%
                                                    </p>
                                                </div>

                                                <div>
                                                    <span class="text--base fs-12">@lang('24h Volume')</span>
                                                    <p class="trading-header__number">{{ formatNumber(@$pair->marketData->volume_24h, 2) }}</p>
                                                </div>

                                                <div>
                                                    <span class="text--base fs-12">@lang('24h Volume Change')</span>
                                                    <p class="trading-header__number {{ @$pair->marketData->volume_change_24h >= 0 ? 'up' : 'down' }}">{{ showAmount(abs(@$pair->marketData->volume_change_24h), 2, currencyFormat: false) }}%</p>
                                                </div>

                                                <div>
                                                    <span class="text--base fs-12">@lang('Market Cap')</span>
                                                    <p class="trading-header__number">{{ formatNumber($pair->marketData->market_cap, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="trading-bottom__tab">
                                        <x-flexible-view :view="$activeTemplate . 'trade.chart'" :meta="['pair' => $pair]" />
                                    </div>

                                </div>
                                <div class="future-trading-content__history">
                                    @include($activeTemplate . 'future.order_book')

                                    <div class="trading-right__bottom">
                                        <div class="trading-history trading-left__top">
                                            <h5 class="trading-history__title">@lang('Recent Trade')</h5>
                                        </div>
                                        <div class="d-flex trading-market__header justify-content-between text-center">
                                            <div class="trading-market__header-two">
                                                @lang('Price')({{ @$pair->market->currency->symbol }})
                                            </div>
                                            <div class="trading-market__header-one">
                                                @lang('Amount') ({{ @$pair->coin->symbol }})
                                            </div>
                                            <div class="trading-market__header-three">
                                                @lang('Date')
                                            </div>
                                        </div>
                                        <div class="tab-content" id="pills-tabContentfortyfour">
                                            <div class="tab-pane fade show active" id="pills-marketnineteen" role="tabpanel" aria-labelledby="pills-marketnineteen-tab" tabindex="0">
                                                <div class="market-wrapper">
                                                    <div class="history trade-history">
                                                        @forelse ($recentTrades as $recentTrade)
                                                            <ul class="history__list flex-between trade-history-item" data-rate="69492.67670000">
                                                                <li class="history__amount-item text-start {{ $recentTrade->trade_side == Status::BUY_SIDE_ORDER ? 'text--success' : 'text--danger' }}">
                                                                    {{ showAmount($recentTrade->rate, currencyFormat: false) }}
                                                                </li>
                                                                <li class="history__price-item text-center"> {{ showAmount($recentTrade->amount, currencyFormat: false) }}</li>
                                                                <li class="history__date-item"> {{ showDateTime($recentTrade->created_at, 'Y.m.d H:i') }}</li>
                                                            </ul>
                                                        @empty
                                                            <div class="emptyTradeHistory empty-thumb">
                                                                <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                                                                <p class="empty-trade">@lang('No trade found')</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="trading-bottom__fixed">
                                    <div class="trading-bottom__footer d-flex">
                                        <div class="trading-bottom__button buy-btn">
                                            <button class="btn btn--base-two w-100 btn--sm buy-btn-sm">@lang('Buy/Long')</button>
                                        </div>
                                        <div class="trading-bottom__button sell-btn">
                                            <button class="btn btn--danger w-100 btn--sm sell-btn-sm">@lang('Sell/Short')</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-md-block d-lg-none">
                                        <form class="buy-sell-form buy-sell buy-sell-one buy--form" method="POST">
                                            <div class="trading-bottom">
                                                @include($activeTemplate . 'future.place_order', ['onlyBuy' => true])
                                            </div>
                                        </form>
                                        <form class="buy-sell-form buy-sell buy-sell-two  sel--form" method="POST">
                                            <div class="trading-bottom">
                                                @include($activeTemplate . 'future.place_order', ['onlySell' => true])
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="trading-table two w-100 d-noe">
                                        <div class="flex-between trading-table__header gap-2">
                                            <div class="flex-between">
                                                <ul class="nav nav-pills custom--tab mb-0" id="pills-tabtwenty" role="tablist">
                                                    <li class="nav-item order-status" role="presentation">
                                                        <button type="button" class="nav-link active" id="pills-positionthree-tab" data-bs-target="#positions-nav" data-bs-toggle="pill" aria-controls="positions-nav" role="tab">
                                                            @lang('Positions')(<span class="positioned-count">0</span>)
                                                        </button>
                                                    </li>
                                                    <li class="nav-item order-status" role="presentation">
                                                        <button type="button" class="nav-link" id="pills-openthree-tab" data-bs-target="#open-order" data-bs-toggle="pill" aria-controls="open-order" role="tab">
                                                            @lang('Open Order')(<span class="open-count">0</span>)
                                                        </button>
                                                    </li>
                                                    <li class="nav-item order-status" role="presentation">
                                                        <button type="button" class="nav-link" id="pills-orderdthree-tab" data-bs-target="#orderhistory" data-bs-toggle="pill" aria-controls="orderhistory" role="tab">
                                                            @lang('Order History')
                                                        </button>
                                                    </li>
                                                    <li class="nav-item order-status" role="presentation">
                                                        <button type="button" class="nav-link" id="pills-tradethree-tab" data-bs-target="#tradehistory" data-bs-toggle="pill" aria-controls="tradehistory" role="tab">
                                                            @lang('Trade History') </button>
                                                    </li>
                                                    <li class="nav-item order-status" role="presentation">
                                                        <button type="button" class="nav-link" id="pills-positiondthree-tab" data-bs-target="#positionhistory" data-bs-toggle="pill" aria-controls="positionhistory" role="tab">
                                                            @lang('Position History')
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-content future-trade-tab-content" id="pills-tabContenttwenty">
                                            <div class="tab-pane fade show active" role="tabpanel" id="positions-nav" aria-labelledby="pills-positionthree-tab" tabindex="0">
                                                @auth
                                                    <div class="table-responsive positionedTable">
                                                    </div>
                                                @else
                                                    <div class="empty-thumb">
                                                        <img src="{{ asset('assets/images/extra_images/user.png') }}" />
                                                        <p class="empty-sell">@lang('Please login to explore your position')</p>
                                                    </div>
                                                @endauth
                                            </div>
                                            <div class="tab-pane fade" role="tabpanel" id="open-order" aria-labelledby="pills-openthree-tab" tabindex="0">
                                                @auth
                                                    <div class="table-responsive openOrderTable">
                                                    </div>
                                                @else
                                                    <div class="empty-thumb">
                                                        <img src="{{ asset('assets/images/extra_images/user.png') }}" />

                                                        <p class="empty-sell">@lang('Please login to explore your order')</p>
                                                    </div>
                                                @endauth
                                            </div>
                                            <div class="tab-pane fade" role="tabpanel" id="orderhistory" aria-labelledby="pills-orderdthree-tab" tabindex="0">
                                                @auth
                                                    <div class="table-responsive">
                                                        <table class="table table-two my-order-list-table prep-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Date')</th>
                                                                    <th>@lang('Pair')</th>
                                                                    <th>@lang('Side')</th>
                                                                    <th>@lang('Size')</th>
                                                                    <th>@lang('Entry Price')</th>
                                                                    <th>@lang('Status')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="order-list-body orderHistoryTableBody">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="empty-thumb">
                                                        <img src="{{ asset('assets/images/extra_images/user.png') }}" />

                                                        <p class="empty-sell">@lang('Please login to explore your order history')</p>
                                                    </div>
                                                @endauth


                                            </div>
                                            <div class="tab-pane fade" role="tabpanel" id="tradehistory" aria-labelledby="pills-tradethree-tab" tabindex="0">
                                                @auth
                                                    <div class="table-responsive">
                                                        <table class="table table-two my-order-list-table prep-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Time')</th>
                                                                    <th>@lang('Symbol')</th>
                                                                    <th>@lang('Side')</th>
                                                                    <th>@lang('Size')</th>
                                                                    <th>@lang('Rate')</th>
                                                                    <th>@lang('Charge')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="order-list-body tradeHistoryTableBody">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="empty-thumb">
                                                        <img src="{{ asset('assets/images/extra_images/user.png') }}" />
                                                        <p class="empty-sell">@lang('Please login to explore your trade history')</p>
                                                    </div>
                                                @endauth
                                            </div>
                                            <div class="tab-pane fade" role="tabpanel" id="positionhistory" aria-labelledby="pills-positiondthree-tab" tabindex="0">
                                                @auth
                                                    <div class="table-responsive">
                                                        <table class="table table-two my-order-list-table prep-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Pair')</th>
                                                                    <th>@lang('Entry Price')</th>
                                                                    <th>@lang('Avg. Close')</th>
                                                                    <th>@lang('Leverage')</th>
                                                                    <th>@lang('Pnl')</th>
                                                                    <th>@lang('Side')</th>
                                                                    <th>@lang('Closed/Liquidation At')</th>
                                                                    <th>@lang('Status')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="order-list-body positionHistoryTableBody">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="empty-thumb">
                                                        <img src="{{ asset('assets/images/extra_images/user.png') }}" />

                                                        <p class="empty-sell">@lang('Please login to explore your position history')</p>
                                                    </div>
                                                @endauth

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="future-trading-right d-none d-lg-block">
                            <div class="trading-bottom mt-0">
                                @include($activeTemplate . 'future.place_order')
                            </div>

                            <div class="trading-asset">
                                <div class="trading-asset__header">
                                    <h4 class="text--base">@lang('Assets')</h4>
                                </div>
                                <div class="trading-asset__body">
                                    <ul class="trading-asset-info">
                                        <li class="trading-asset-info__item">
                                            <span>@lang('Overall Balance'):</span> <span>{{ showAmount($asset['wallet_balance']) }}</span>
                                        </li>
                                        <li class="trading-asset-info__item">
                                            <span>@lang('Available Balance'):</span>
                                            <span>
                                                <span class="avl-market-cur-wallet">0.00</span>
                                                {{ @$pair->market->currency->symbol }}
                                            </span>
                                        </li>
                                        <li class="trading-asset-info__item">
                                            <span>@lang('Unrealized Pnl'):</span>
                                            <span>
                                                <span class="totalUnrealizedPnl">0.00</span>
                                                {{ @$pair->market->currency->symbol }}
                                            </span>
                                        </li>
                                        <li class="trading-asset-info__item">
                                            <span>@lang('Running Position'):</span> <span class="positioned-count">0</span>
                                        </li>
                                        <li class="trading-asset-info__item">
                                            <span>@lang('Open Orders'):</span> <span class="open-count">0</span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    {{-- Margin Mode start here --}}
    <div class="modal favorites-modal margin-mode-modal custom--modal fade zoomIn" id="crossModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 p-0 pb-4">
                    <h4 class="modal-title" id="staticBackdropLabel">@lang('Margin Mode')</h4>
                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body p-0">
                    <div class="margin-mode">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="ilolated-tab" data-bs-toggle="pill" data-bs-target="#ilolated" type="button" role="tab" aria-controls="ilolated" aria-selected="false">@lang('Isolated')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="cross-tab" data-bs-toggle="pill" data-bs-target="#pills-cross" type="button" role="tab" aria-controls="pills-cross" aria-selected="true">@lang('Cross')</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade" id="pills-cross" role="tabpanel" aria-labelledby="cross-tab" tabindex="0">
                                <p>@lang('Cross Margin Mode: All cross positions under the same margin asset share the same asset cross margin balance. In the event of liquidation, your assets full margin balance along with any remaining open positions under the asset may be forfeited.')</p>
                                <button type="button" class="btn btn--base w-100 btn--sm h-40 mt-4 crossActiveBtn" data-bs-dismiss="modal">
                                    @lang('Confirm')
                                </button>
                            </div>
                            <div class="tab-pane fade show active" id="ilolated" role="tabpanel" aria-labelledby="ilolated-tab" tabindex="0">
                                <p>@lang('Isolated Margin Mode: Manage your risk on individual positions by restricting the amount of margin allocated to each. If the margin ratio of a position reached 100%, the position will be liquidated. Margin can be added or removed to positions using this mode.')</p>
                                <button type="button" class="btn btn--base w-100 btn--sm h-40 mt-4 isolatedActiveBtn" data-bs-dismiss="modal">
                                    @lang('Confirm')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Margin Mode end here --}}

    @include($activeTemplate . 'future.leverage_modal')
    @include($activeTemplate . 'future.trade_profit_loss_modal')

    <x-confirmation-modal isCustom="true" />
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/range-ui.css') }}">
    <link href="{{ asset($activeTemplateTrue . 'css/future-trade.css') }}" rel="stylesheet">
@endpush


@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let widgetHeight;

            if (window.innerWidth >= 1600) {
                widgetHeight = 650;
            } else if (window.innerWidth >= 1200) {
                widgetHeight = 585;
            } else if (window.innerWidth >= 992) {
                widgetHeight = 550;
            } else if (window.innerWidth >= 768) {
                widgetHeight = 500;
            } else if (window.innerWidth >= 576) {
                widgetHeight = 450;
            } else {
                widgetHeight = 400;
            }

            $('#tradingview_92622').find('div').css('height', widgetHeight + 'px');

            $(".sidebar-close").on("click", (function(e) {
                e.preventDefault();
                $(".asset-mode__sidebar").removeClass("show");
                $(".sidebar-overlay").removeClass("show");
            }));

            $(".sidebar-overlay").on("click", (function() {
                $(".asset-mode__sidebar").removeClass("show");
                $(".sidebar-overlay").removeClass("show");
            }));

            // trading dropdown box start here

            document.addEventListener("DOMContentLoaded", function() {
                const dropdownButton = document.querySelector(".trading-dropdown-button");
                const dropdownBox = document.querySelector(".trading-dropdown-box");

                dropdownButton.addEventListener("mouseenter", () => {
                    dropdownBox.style.display = "block";
                });

                dropdownButton.addEventListener("mouseleave", () => {
                    setTimeout(() => {
                        if (!dropdownBox.matches(":hover")) {
                            dropdownBox.style.display = "none";
                        }
                    }, 200);
                });

                dropdownBox.addEventListener("mouseleave", () => {
                    dropdownBox.style.display = "none";
                });
            });

            // trading dropdown box end here


            // =========================  Increament & Decreament Js Start =====================
            const productQty = $(".product-qty");
            productQty.each(function() {
                const qtyIncrement = $(this).find(".product-qty__increment");
                const qtyDecrement = $(this).find(".product-qty__decrement");
                let qtyValue = $(this).find(".product-qty__value");
                qtyIncrement.on("click", function() {
                    var oldValue = parseFloat(qtyValue.val());
                    var newVal = oldValue + 1;
                    qtyValue.val(newVal).trigger("change");
                    $('[name=adjust_leverage]').trigger('input');
                });
                qtyDecrement.on("click", function() {
                    var oldValue = parseFloat(qtyValue.val());
                    if (oldValue <= 0) {
                        var newVal = oldValue;
                    } else {
                        var newVal = oldValue - 1;
                    }
                    qtyValue.val(newVal).trigger("change");
                    $('[name=adjust_leverage]').trigger('input');
                });
            });
            // ========================= Increament & Decreament Js End =====================



            $('.crossActiveBtn').click(function() {
                $('[name=margin_mode]').val({{ Status::MARGIN_MODE_CROSS }});
                $('.marginModeBtnText').text('Cross');
            });

            $('.isolatedActiveBtn').click(function() {
                $('[name=margin_mode]').val({{ Status::MARGIN_MODE_ISOLATED }});
                $('.marginModeBtnText').text('Isolated');
            });

            $('.adjustLeverageBtn').click(function() {
                let leverage = $('[name=adjust_leverage]').val();
                $('.leverageBtnText').text(leverage + 'X');
                $('[name=leverage]').val(leverage).trigger('change');
            });

            $('.leverageBtnText').click(function() {
                let leverage = $('[name=leverage]').val();
                let maxLeverage = `{{ $futurePair->leverage }}` * 1;
                let percent = leverage / maxLeverage * 100;

                $(".leverage-slider").find('.ui-widget-header').css({
                    'width': `${percent}%`
                });

                $(".leverage-slider").find('.ui-state-default').css({
                    'left': `${percent >= 97 ? 97 : percent}%`
                });
            });


            {{-- blade-formatter-disable --}}
            @auth
                // load order history
                
                getOrderHistory();

                let orderHistoryPage = 1;

                function getOrderHistory(orderHistoryPage = 1, $this = null) {
                    $.ajax({
                        url: "{{ route('user.future.trade.order.history') }}?page=" + orderHistoryPage,
                        type: "GET",
                        beforeSend: function() {
                            $('.load-more-order-history').html(`<i class="fa fa-spinner fa-spin"></i>@lang('Load More')`);
                        },
                        complete: function() {
                            $('.load-more-order-history').html(`<i class="fa fa-spinner"></i> @lang('Load More')`);
                            $($this).closest('tr').remove();
                        },
                        success: function(response) {
                            if(orderHistoryPage == 1){
                                $('.orderHistoryTableBody').html(response.data.html);
                            }else{
                                $('.orderHistoryTableBody').append(response.data.html);
                            }
                        }
                    });
                }

                $(document).on('click', '.load-more-order-history', function() {
                    let $this = $(this);
                    getOrderHistory(++orderHistoryPage, $this);
                });

                // load position history
                getPositionList();

                let positionHistoryPage = 1;

                function getPositionList(positionHistoryPage = 1, $this = null) {
                    $.ajax({
                        url: "{{ route('user.future.trade.position.history') }}?page=" + positionHistoryPage,
                        type: "GET",
                        beforeSend: function() {
                            $('.load-more-position-history').html(`<i class="fa fa-spinner fa-spin"></i> @lang('Load More')`);
                        },
                        complete: function() {
                            $('.load-more-position-history').html(`<i class="fa fa-spinner"></i> @lang('Load More')`);
                            $($this).closest('tr').remove();
                        },
                        success: function(response) {
                            if(positionHistoryPage == 1){
                                $('.positionHistoryTableBody').html(response.data.html);
                            }else{
                                $('.positionHistoryTableBody').append(response.data.html);
                            }
                        }
                    });
                }

                $(document).on('click', '.load-more-position-history', function() {
                    let $this = $(this);
                    getPositionList(++positionHistoryPage, $this);
                });

                // get trade history

                getTradeHistory();

                let tradeHistoryPage = 1;

                function getTradeHistory(tradeHistoryPage = 1, $this = null) {
                    $.ajax({
                        url: "{{ route('user.future.trade.trading.history') }}?page=" + tradeHistoryPage,
                        type: "GET",
                        beforeSend: function() {
                            $('.load-more-btn').html(`<i class="fa fa-spinner fa-spin"></i>@lang('Load More')`);
                        },
                        complete: function() {
                            $('.load-more-btn').html(`<i class="fa fa-spinner"></i> @lang('Load More')`);
                            $($this).closest('tr').remove();
                        },
                        success: function(response) {
                            if(tradeHistoryPage == 1){
                                $('.tradeHistoryTableBody').html(response.data.html);
                            }else{
                                $('.tradeHistoryTableBody').append(response.data.html);
                            }
                        }
                    });
                }

                $(document).on('click', '.load-more-btn', function() {
                    let $this = $(this);
                    getTradeHistory(++tradeHistoryPage, $this);
                });


                // cancel open order
                let route = "{{ route('user.future.trade.order.cancel', '') }}";

                $(document).on('click', '.cancelQueueBtn', function() {
                    let modal = $('#cancelQueueModal');
                    let orderId = $(this).data('order_id');
                    modal.find('form').attr('action', route + '/' + orderId);
                    modal.modal('show');
                });

                $(document).on('submit', '.orderCancelForm', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let url = form.attr('action');
                    let data = {
                        _token: `{{ csrf_token() }}`,
                    };

                    $.post(url, data, function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            $('.pendingQueue-' + response.data.id).remove();
                            $('.open-count').text(response.data.totalQueueOrderCount);
                            getPendingQueue();
                            getOrderHistory();
                            getPositionedOrder();
                        } else {
                            notify('error', response.message);
                        }
                    });
                });

                // close position 
                $(document).on('submit', '.closePositionForm', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let url = form.attr('action');
                    let data = {
                        _token: `{{ csrf_token() }}`,
                        future_order_id: form.find('.futureOrderId').val(),
                        coin_amount: form.find('.closePositionAmount').val(),
                        trigger_price: form.find('.closePositionTriggerPrice').val(),
                    };

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.status == 'success') {
                                notify('success', response.message);
                                form.find('.closePositionSize').val('');
                                getPendingQueue();
                                getPositionedOrder();
                            } else {
                                notify('error', response.message);
                            }
                        }
                    });
                });

            @endauth
            
            {{-- blade-formatter-enable --}}


            @php
                $symbol = strToLower(str_replace('_', '', @$futurePair->coinPair->symbol));
                $url = 'wss://stream.binance.com:9443/ws/' . $symbol . '@ticker';
            @endphp

            const socket = new WebSocket(`{{ $url }}`);
            let lastPrice = parseFloat($('.currentPrice').text());
            socket.onmessage = function(event) {
                const data = JSON.parse(event.data);
                let price = getAmount(parseFloat(data.c));
                
                if(price > lastPrice){
                    $('.currentPriceArrow').html(`<i class="fas fa-arrow-up"></i>`);
                    $('.currentPrice, .currentPriceArrow').removeClass('down').addClass('up');
                }else if(price < lastPrice){
                    $('.currentPriceArrow').html(`<i class="fas fa-arrow-down"></i>`);
                    $('.currentPrice, .currentPriceArrow').removeClass('up').addClass('down');
                }
                lastPrice = price;
                $('.currentPrice').text(price);
            };

            document.documentElement.setAttribute('data-theme', 'light');

        })(jQuery);
    </script>
@endpush
