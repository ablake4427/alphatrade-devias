@php
    $meta = (object) $meta;
    $markets = @$meta->markets;
@endphp

<div class="trading-right">
    @if (@$meta->screen == 'big')
        <span class="sidebar__close d-xl-none d-block"><i class="fas fa-times"></i></span>
    @endif
    <div class="trading-right__top">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="trading-right__title">@lang('Markets') </h5>
            <form id="search-market">
                <div class="input--group">
                    <button class="search-btn" type="submit"><i class="las la-search"></i></button>
                    <input type="text" class="form--control style-two" placeholder="@lang('Search')" name="search">
                </div>
            </form>
        </div>
        <div class="swiper myswiper-two trading-right__tab">
            <ul class="nav nav-pills mb-3 custom--tab tab-two  swiper-wrapper" id="pills-tabsixteen" role="tablist">
                <li class="nav-item swiper-slide swiper-slide-active toggle-favorite-list">
                    <button class="nav-link" type="button">
                        <span class="icon ms-4"><i class="fas fa-star"></i></span>
                    </button>
                </li>
                @foreach ($markets ?? [] as $market)
                    <li class="nav-item swiper-slide market-item" data-market="{{ $market->id }}">
                        <button type="button" class="nav-link">
                            {{ @$market->currency->symbol }}
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="swiper-button-next-two"></div>
            <div class="swiper-button-prev-two"></div>
        </div>
    </div>
    <div class="d-flex trading-market__header flex-wrap flex-between">
        <div class="trading-market__header-one">
            @lang('Pair')
        </div>
        <div class="trading-market__header-three trading-market__header--price ">
            @lang('Price')
        </div>
        <div class="trading-market__header-two">
            @lang('Change')
        </div>
    </div>
    <div class="tab-content" id="pills-tabContentsixteen">
        <div class="tab-pane fade show active" id="pills-margin1" role="tabpanel" aria-labelledby="pills-margin1-tab"
             tabindex="0">
            <div class="market-wrapper">
                <div class="market pair-list"></div>
            </div>
        </div>
    </div>
</div>

@if (!app()->offsetExists('market_list_script'))
    @php app()->offsetSet('market_list_script',true) @endphp
    @push('script')
        <script>
            "use strict";
            (function($) {
                    let marketId = "";
                    let search = "";
                    $('.toggle-favorite-list').on('click', function(e) {
                        $(this).find(`button`).addClass('text--base');
                        let favoriteElementsCount = $('.pair-list').find('.favorite-pair').length;
                        if (favoriteElementsCount <= 0) {
                            $('.pair-list').html(`
                                <div class="empty-thumb">
                                    <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                    <p class="empty-sell">@lang('No favorite pair list found')</p>
                                </div>
                            `);
                            return false;
                        }
                        $('.pair-list').find('.market__list').addClass('d-none');
                        $('.pair-list').find('.favorite-pair').removeClass('d-none');
                    });

                    $('.market-item').on('click', function(e) {
                        marketId = $(this).data('market');
                        $('.market-item').find('button').removeClass('active');
                        $(this).find('button').addClass('active');
                        $('.toggle-favorite-list').find(`button`).removeClass('text--base');
                        getPairList();
                    });

                    $('.trading-right__top').on('submit', '#search-market', function(e) {
                        e.preventDefault();
                        search = $(this).find(`input[name=search]`).val();
                        getPairList();
                    });

                    $('.pair-list').on('click', '.favorite-btn', function() {
                            @guest
                            notify('error', "@lang('Please login to add a pair to your favorite list')");
                            return false;
                        @endguest

                        let symbol = $(this).data('symbol');
                        let action = "{{ route('user.add.pair.to.favorite', ':pairSymbol') }}";
                        let $this = $(this); $.ajax({
                            url: action.replace(':pairSymbol', symbol),
                            type: "GET",
                            dataType: 'json',
                            cache: false,
                            success: function(resp) {
                                if (resp.success) {
                                    if (resp.deleted) {
                                        $($this).removeClass('text--gold');
                                    } else {
                                        $($this).addClass('text--gold');
                                    }
                                    notify('success', resp.message);
                                } else {
                                    notify('error', resp.message);
                                }
                            }
                        });

                    });

                function getPairList() {
                    let action = "{{ route('trade.pairs') }}";
                    let tradeUrl = "{{ route('trade', ':sym') }}";

                    $.ajax({
                        url: action,
                        type: "GET",
                        dataType: 'json',
                        cache: false,
                        data: {
                            marketId,
                            search
                        },
                        complete: function() {
                            setTimeout(() => {
                                $('.market__list').removeClass('skeleton mb-2');
                                $('.empty-thumb').removeClass('skeleton');
                            }, 500);
                        },
                        success: function(resp) {
                            let html = ``;
                            if (resp.success) {
                                if (resp.pairs.length > 0) {
                                    let favoritePairId = resp.favoritePairId;
                                    $.each(resp.pairs, function(i, pair) {
                                        let marketData = pair.market_data;
                                        let htmlClasses = marketData.html_classes;
                                        let isFavoritePair = favoritePairId.indexOf(pair.id) != -1 ? true : false;
                                        html += `
                                            <ul class="market__list flex-between  skeleton mb-2  ${ isFavoritePair ? 'favorite-pair' : ''}">
                                                <li class="market__pair-item">
                                                    <span class="market__pair-icon favorite-btn ${ isFavoritePair ? 'text--gold' : ''}" data-symbol="${pair.symbol}">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                    <a href="${tradeUrl.replace(':sym',pair.symbol)}">${pair.symbol.replace('_','/')}</a>
                                                </li>
                                                <li class=" market__change-item ">
                                                    <span class="market-price-${marketData.id} ${htmlClasses ? htmlClasses.price_change : ''}">
                                                        ${showAmount(marketData.price)}
                                                    </span>
                                                </li>
                                                <li class="market__price-item ">
                                                    <span class="market-percent-change-1h-${marketData.id} ${htmlClasses ?  htmlClasses.percent_change_1h : ''}">
                                                        ${showAmount(marketData.percent_change_1h,2)}%
                                                    </span>
                                                </li>
                                            </ul>`;
                                    });
                                    $('.pair-list').removeClass('text-center');
                                } else {
                                    html += `
                                        <div class="empty-thumb skeleton">
                                                <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                                <p class="empty-sell">@lang('No pair found')</p>
                                        </div>
                                    `;
                                    $('.pair-list').addClass('text-center');
                                }
                            }
                            $('.pair-list').html(html);
                        }
                    });
                }
                getPairList();
            })(jQuery);
        </script>
    @endpush

    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/swiper.js') }}"></script>
    @endpush

    @push('style-lib')
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/swiper.css') }}">
    @endpush
@endif
