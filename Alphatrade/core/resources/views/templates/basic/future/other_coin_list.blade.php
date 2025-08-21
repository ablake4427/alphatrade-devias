    <div class="table-wrapper-two table-responsive">
        <table class="table table-two table-wrapper-two my-order-list-table prep-table">
            <thead>
                <tr>
                    <th>@lang('Symbol / Vol')</th>
                    <th>@lang('Last Price')</th>
                    <th>@lang('24h %')</th>
                </tr>
            </thead>
            <tbody class="order-list-body">
                @foreach ($otherPairs as $otherPair)
                    <tr class="{{ in_array($otherPair->id, $favoritePairs) ? '' : 'd-none' }}">
                        <td>
                            <div class="trading-dropdown-button d-flex">
                                <span class="trading-dropdown-button-icon favoriteBtn {{ in_array($otherPair->id, $favoritePairs) ? 'favorite favPair' : '' }}" data-pair_id="{{ $otherPair->id }}">
                                    <i class="fa-solid fa-star"></i>
                                </span>
                                <a href="{{ route('future.trade', $otherPair->coinPair->symbol) }}" class="d-block trading-dropdown-button-title">
                                    {{ $otherPair->coinPair->symbol }} <span class="d-block fs-12 vol">@lang('Mar. Cap') {{ formatNumber($otherPair->coinPair->marketData->market_cap) }}</span> </a>
                                <div>
                                    <span class="badge-prep">@lang('Prep')</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span>{{ showAmount($otherPair->coinPair->marketData->price, currencyFormat: false) }}</span>
                        </td>
                        <td>
                            <span class="{{ @$otherPair->coinPair->marketData->html_classes->percent_change_24h }}">{{ getAmount($otherPair->coinPair->marketData->percent_change_24h, 2) }}%</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-none emptyMessageHtml">
        <table>
            @php echo userTableEmptyMessage('favorite pair') @endphp
        </table>
    </div>

    @push('script')
        <script>
            (function($) {
                "use strict";

                let showOnlyFavorites = false;

                @if (auth()->check())
                    $('.favoriteBtn').on('click', function() {
                        let pairId = $(this).data('pair_id');
                        let $this = $(this);
                        $.ajax({
                            url: "{{ route('user.future.trade.favorite', '') }}/" + pairId,
                            type: "GET",
                            success: function(response) {
                                notify(response.status, response.message);
                                if (response.status == 'success') {
                                    if (response.data.is_favorite == 1) {
                                        $this.addClass('favPair favorite');
                                    } else {
                                        $this.removeClass('favPair favorite');
                                        if (showOnlyFavorites) {
                                            $this.closest('tr').addClass('d-none');
                                        }
                                    }
                                    applyFilters();

                                    let favList = response.data.favorite_list;
                                    let html = '';
                                    let route = `{{ route('future.trade') }}`;
                                    let futurePairId = `{{ $futurePair->id }}`;
                                    
                                    favList.forEach(fav => {
                                        console.log(fav);
                                        
                                        if (futurePairId != fav.future_trade_config.id) {
                                            html += `<a href="${route+'/'+fav.future_trade_config.coin_pair.symbol}"> ${fav.future_trade_config.coin_pair.symbol}
                                                            <span class="${fav.future_trade_config.coin_pair.market_data.html_classes.percent_change_24h }">${parseFloat(fav.future_trade_config.coin_pair.market_data.percent_change_24h).toFixed(2)}%</span>
                                                    </a>`;
                                        }
                                    });
                                    
                                    if(html == ''){
                                        $('.favoriteList').parent().addClass('d-none');
                                    }else{
                                        $('.favoriteList').parent().removeClass('d-none');
                                        $('.favoriteList').html(html);
                                    }

                                }
                            }
                        });
                    });
                @endif

                $(document).on('click', '.favoriteListBtn', function() {
                    showOnlyFavorites = true;
                    applyFilters();
                }).click();

                $(document).on('click', '.otherListBtn', function() {
                    showOnlyFavorites = false;
                    applyFilters();
                });

                $(document).on('input', '.search-input', function() {
                    applyFilters();
                });

                $(document).ready(function() {
                    showOnlyFavorites = true;
                    applyFilters();
                });

                function applyFilters() {
                    let searchValue = $('.search-input').val().toLowerCase();
                    let hasVisibleRows = false;

                    $('.order-list-body tr').each(function() {
                        let $row = $(this);
                        let symbol = $row.find('.trading-dropdown-button-title').text().toLowerCase();
                        let isFavorite = $row.find('.favoriteBtn').hasClass('favPair');

                        let matchesSearch = !searchValue || symbol.includes(searchValue);
                        let matchesFavorite = !showOnlyFavorites || isFavorite;

                        let isVisible = matchesSearch && matchesFavorite;
                        $row.toggleClass('d-none', !isVisible);

                        if (isVisible && !$row.hasClass('empty-message')) {
                            hasVisibleRows = true;
                        }
                    });

                    if (!hasVisibleRows) {
                        let emptyRow = $('.emptyMessageHtml').find('tr').clone().addClass('empty-message');
                        $('.order-list-body').append(emptyRow);
                    } else {
                        $('.empty-message').remove();
                    }
                }

            })(jQuery);
        </script>
    @endpush
