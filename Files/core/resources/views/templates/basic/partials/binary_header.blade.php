<header class="header-new">
    <div class="container-fluid container-fluid--custom">
        <div class="header-new__wrapper">
            <a class="header-new-logo" href="{{ route('home') }}">
                <img src="{{ siteLogo() }}" alt="">
            </a>

            <nav class="nav-horizontal">
                <button class="nav-horizontal__btn prev"><i class="las la-angle-left"></i></button>
                <button class="nav-horizontal__btn next"><i class="las la-angle-right"></i></button>
                <ul class="nav-horizontal-menu" id="show-currency-list">
                    @foreach ($maxTradeCoinPairs as $coinPair)
                        <li class="nav-horizontal-menu__item">
                            <div class="asset-compact-card coinBtn {{ $coinPair->id == $activeCoin->id ? 'active' : '' }}"
                                data-id="{{ $coinPair->id }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="{{ str_replace('_', '/', $coinPair->symbol) }} - {{ getAmount($coinPair->binary_trade_profit) }}% profit">
                                <div class="avatar">
                                    <img class="avatar-img"
                                        src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->coin->image, getFileSize('currency')) }}"
                                        alt="">
                                    <img class="avatar-img"
                                        src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->market->currency->image, getFileSize('currency')) }}"
                                        alt="">
                                </div>
                                <div class="asset-compact-card__content">
                                    <h6 class="asset-compact-card__title">{{ str_replace('_', '/', $coinPair->symbol) }}
                                    </h6>
                                    <span
                                        class="asset-compact-card__percentage">{{ getAmount($coinPair->binary_trade_profit) }}%</span>
                                </div>
                                @if (!$loop->first)
                                    <button class="asset-compact-card__close" type="button"><i
                                            class="fas fa-times"></i></button>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <button class="trade-right-toggle d-md-none" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chart-line">
                    <path d="M3 3v16a2 2 0 0 0 2 2h16" />
                    <path d="m19 9-5 5-4-4-3 3" />
                </svg>
            </button>

            <div class="dropdown assets--dropdown">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus"></i>
                </button>
                <div class="dropdown-menu">
                    <div class="dropdown-menu__header">
                        <ul class="nav nav-tabs custom--tab-new" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#trending-tab">@lang('Trending')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link " data-bs-toggle="tab"
                                    data-bs-target="#options-tab">@lang('Options')</button>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown-menu__body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="trending-tab" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="dropdown-menu-section">
                                    <h4 class="dropdown-menu-section__title">@lang('Max Trading Coins')</h4>
                                    <div class="dropdown-slider">
                                        @foreach ($dropDownMaxCoinPairs as $key => $coinPair)
                                            <div class="dropdown-slider__slide">
                                                <div class="asset-card coinBtn" data-id="{{ $coinPair->id }}">
                                                    <div class="asset-card__header">
                                                        <div class="avatar">
                                                            <img class="avatar-img"
                                                                src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->coin->image, getFileSize('currency')) }}"
                                                                alt="">
                                                            <img class="avatar-img"
                                                                src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->market->currency->image, getFileSize('currency')) }}"
                                                                alt="">
                                                        </div>
                                                        <h6 class="asset-card__title">
                                                            {{ str_replace('_', '/', $coinPair->symbol) }}</h6>
                                                    </div>
                                                    <div class="asset-card__body">
                                                        <div class="asset-card__profit">
                                                            <span class="label">@lang('Max Profit')</span>
                                                            <span
                                                                class="value">{{ getAmount($coinPair->binary_trade_profit) }}%</span>
                                                        </div>
                                                    </div>
                                                    <div class="asset-card__footer">
                                                        <div class="asset-card__price">
                                                            <span class="label">@lang('Price')</span>
                                                            <span
                                                                class="value">{{ showAmount(@$coinPair->marketData->price) }}</span>
                                                        </div>
                                                        <div class="asset-card__change">
                                                            <span class="label">@lang('1 hr change')</span>
                                                            <span
                                                                class="value">{{ @$coinPair->marketData->last_percent_change_1h }}%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="dropdown-menu-section">
                                    <h4 class="dropdown-menu-section__title">@lang('Min Trading Coins')</h4>
                                    <div class="dropdown-slider">
                                        @foreach ($minTradeCoinPairs as $key => $coinPair)
                                            <div class="dropdown-slider__slide">
                                                <div class="asset-card coinBtn" data-id="{{ $coinPair->id }}">
                                                    <div class="asset-card__header">
                                                        <div class="avatar">
                                                            <img class="avatar-img"
                                                                src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->coin->image, getFileSize('currency')) }}"
                                                                alt="">
                                                            <img class="avatar-img"
                                                                src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->market->currency->image, getFileSize('currency')) }}"
                                                                alt="">
                                                        </div>
                                                        <h6 class="asset-card__title">
                                                            {{ str_replace('_', '/', $coinPair->symbol) }}</h6>
                                                    </div>
                                                    <div class="asset-card__body">
                                                        <div class="asset-card__profit">
                                                            <span class="label">@lang('Max Profit')</span>
                                                            <span
                                                                class="value">{{ getAmount($coinPair->binary_trade_profit) }}%</span>
                                                        </div>
                                                    </div>
                                                    <div class="asset-card__footer">
                                                        <div class="asset-card__price">
                                                            <span class="label">@lang('Price')</span>
                                                            <span
                                                                class="value">{{ showAmount(@$coinPair->marketData->price) }}</span>
                                                        </div>
                                                        <div class="asset-card__change">
                                                            <span class="label">@lang('1 hr change')</span>
                                                            <span
                                                                class="value">{{ @$coinPair->marketData->last_percent_change_1h }}%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="options-tab" role="tabpanel"
                                aria-labelledby="profile-tab" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-new" id="binaryTable">
                                        <thead>
                                            <tr>
                                                <th width="150px">@lang('Coin Pair')</th>
                                                <th>@lang('Price')</th>
                                                <th>@lang('Max Profit')</th>
                                                <th>@lang('1 hr Change')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allCoins as $coinPair)
                                                <tr class="coinBtn" data-id="{{ $coinPair->id }}">
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="avatar">
                                                                <img class="avatar-img"
                                                                    src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->coin->image, getFileSize('currency')) }}"
                                                                    alt="">
                                                                <img class="avatar-img"
                                                                    src="{{ getImage(getFilePath('currency') . '/' . @$coinPair->market->currency->image, getFileSize('currency')) }}"
                                                                    alt="">
                                                            </div>
                                                            <h6 class="fs-14 mb-0 text-white">
                                                                {{ str_replace('_', '/', $coinPair->symbol) }}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="text--danger text-center">{{ showAmount(@$coinPair->marketData->price) }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="text--success w-100 text-center">{{ getAmount($coinPair->binary_trade_profit) }}%</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="text-muted">{{ @$coinPair->marketData->last_percent_change_1h }}%</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-new-auth">
                @auth
                    <div class="dropdown user--dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="dropdown-toggle__avatar">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 17.3333C19.1947 17.3333 22.1 18.2586 24.2373 19.5613C25.304 20.2146 26.216 20.9813 26.8747 21.8146C27.5227 22.636 28 23.6173 28 24.6666C28 25.7933 27.452 26.6813 26.6627 27.3146C25.916 27.9146 24.9307 28.312 23.884 28.5893C21.78 29.1453 18.972 29.3333 16 29.3333C13.028 29.3333 10.22 29.1466 8.116 28.5893C7.06933 28.312 6.084 27.9146 5.33733 27.3146C4.54667 26.68 4 25.7933 4 24.6666C4 23.6173 4.47733 22.636 5.12533 21.8146C5.784 20.9813 6.69467 20.2146 7.76267 19.5613C9.9 18.2586 12.8067 17.3333 16 17.3333Z"
                                        fill="#CBD5E1" />
                                    <path opacity="0.3"
                                        d="M16 2.66669C21.132 2.66669 24.34 8.22269 21.7733 12.6667C21.1882 13.6801 20.3467 14.5217 19.3332 15.1068C18.3198 15.6919 17.1702 16 16 16C10.868 16 7.65999 10.444 10.2267 6.00002C10.8118 4.98658 11.6533 4.14501 12.6668 3.55989C13.6802 2.97477 14.8298 2.66672 16 2.66669Z"
                                        fill="#CBD5E1" />
                                </svg>
                            </div>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('user.home') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-layout-dashboard">
                                    <rect width="7" height="9" x="3" y="3" rx="1" />
                                    <rect width="7" height="5" x="14" y="3" rx="1" />
                                    <rect width="7" height="9" x="14" y="12" rx="1" />
                                    <rect width="7" height="5" x="3" y="16" rx="1" />
                                </svg>
                                <span>@lang('Dashboard')</span>
                            </a>
                            <a class="dropdown-item" href="{{ route('user.logout') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" x2="9" y1="12" y2="12" />
                                </svg>
                                <span>@lang('Logout')</span>
                            </a>
                        </div>
                    </div>
                @else
                    <a class="btn-new btn-new-outline--base " href="{{ route('user.login') }}" role="button">
                        <i class="fa-regular fa-circle-user"></i>
                        <span>@lang('Login')</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
