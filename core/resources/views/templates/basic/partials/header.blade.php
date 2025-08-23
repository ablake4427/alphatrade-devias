<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}">
                <img src="{{ siteLogo() }}">
            </a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu me-auto align-items-lg-center flex-wrap">
                    <li class="nav-item d-block d-lg-none">
                        @if (gs('multi_language'))
                            @php
                                $langDetails = $languages->where('code', config('app.locale'))->first();
                            @endphp
                            <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                                <div class="custom--dropdown">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <div class="thumb">
                                            <img
                                                 src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                        </div>
                                        <span class="text">{{ __(@$langDetails->name) }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item change-lang "
                                                data-code="{{ @$language->code }}">
                                                <div class="thumb">
                                                    <img
                                                         src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                                </div>
                                                <span class="text">{{ __(@$language->name) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <ul class="login-registration-list d-flex flex-wrap align-items-center">
                                    @guest
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.login') }}" class="sign-in ">@lang('Login')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.register') }}"
                                               class="btn btn--base btn--sm ">@lang('Sign up') </a>
                                        </li>
                                    @else
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.home') }}"
                                               class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                        </li>
                                    @endguest
                                </ul>
                            </div>
                        @endif
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('market') }}">@lang('Market')</a>
                    </li>

                    <li class="nav-item has-mega-menu">
                        <a class="nav-link" href="javascript:void(0)">@lang('Trade')</a>
                        <div class="mega-menu">
                            <div class="mega-menu__inner">
                                <ul class="mega-menu-list {{ gs('future_trade') ? 'list-grid' : '' }}">
                                    <li class="mega-menu-list__item mega-item-bg1">
                                        <a href="{{ route('trade') }}" class="mega-menu-list__link">
                                            <div class="mega-menu-list__content">
                                                <span class="mega-menu-list__title">@lang('SPOT')</span>
                                                <p class="mega-menu-list__desc">@lang('Trade smartly with necessary Spot market tools.')</p>
                                            </div>
                                            <span class="mega-menu-list__icon">
                                                <img class="fit-image" src="{{ getImage('assets/images/extra_images/bar-chart.png', null) }}">
                                            </span>
                                        </a>
                                    </li>
                                    <li class="mega-menu-list__item mega-item-bg2">
                                        <a href="{{ route('p2p') }}" class="mega-menu-list__link">
                                            <div class="mega-menu-list__content">
                                                <span class="mega-menu-list__title">@lang('P2P')</span>
                                                <p class="mega-menu-list__desc">@lang('Buy & sell crypto with your preferred payment methods.')</p>
                                            </div>
                                            <span class="mega-menu-list__icon">
                                                <img class="fit-image" src="{{ getImage('assets/images/extra_images/p2p.png', null) }}">
                                            </span>
                                        </a>
                                    </li>
                                    <li class="mega-menu-list__item mega-item-bg3">
                                        <a href="{{ route('binary') }}" class="mega-menu-list__link">
                                            <div class="mega-menu-list__content">
                                                <span class="mega-menu-list__title">@lang('Binary')</span>
                                                <p class="mega-menu-list__desc">@lang('Binary trading predicts asset price changes over time.')</p>
                                            </div>
                                            <span class="mega-menu-list__icon">
                                                <img class="fit-image" src="{{ getImage('assets/images/extra_images/trading.png', null) }}">
                                            </span>
                                        </a>
                                    </li>
                                    @if (gs('future_trade'))
                                        <li class="mega-menu-list__item mega-item-bg4">
                                            <a href="{{ route('future.trade') }}" class="mega-menu-list__link">
                                                <div class="mega-menu-list__content">
                                                    <span class="mega-menu-list__title">@lang('Future')</span>
                                                    <p class="mega-menu-list__desc">@lang('Futures trading is predicting if prices go up or down.')</p>
                                                </div>
                                                <span class="mega-menu-list__icon">
                                                    <img class="fit-image" src="{{ getImage('assets/images/extra_images/future.png') }}">
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('crypto_currencies') }}">@lang('Crypto Currency')</a>
                    </li>
                    @php
                        $pages = App\Models\Page::where('is_default', Status::NO)
                            ->where('tempname', $activeTemplate)
                            ->get();
                    @endphp
                    @foreach ($pages as $item)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages', ['slug' => $item->slug]) }}">
                                {{ __($item->name) }}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}"> @lang('Contact') </a>
                    </li>
                </ul>
            </div>
            <ul class="header-right d-lg-block d-none">
                <li class="nav-item">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                        @if (gs('multi_language'))
                            <div class="custom--dropdown">
                                <div class="custom--dropdown__selected dropdown-list__item">
                                    <div class="thumb">
                                        <img
                                             src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                    </div>
                                    <span class="text">{{ __(@$langDetails->name) }}</span>
                                </div>
                                <ul class="dropdown-list">
                                    @foreach ($languages as $language)
                                        <li class="dropdown-list__item change-lang "
                                            data-code="{{ @$language->code }}">
                                            <div class="thumb">
                                                <img
                                                     src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                            </div>
                                            <span class="text">{{ __(@$language->name) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <ul class="login-registration-list d-flex flex-wrap align-items-center">
                            @guest
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.login') }}" class="sign-in">@lang('Login')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.register') }}"
                                       class="btn btn--base btn--sm">@lang('Sign up') </a>

                                </li>
                            @else
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.home') }}"
                                       class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </li>
            </ul>
            @if (!request()->routeIs('trade') && !request()->routeIs('future.trade'))
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox">
                        <input type="checkbox" class="d-none" id="checkbox">
                        <span class="slider">
                            <i class="las la-sun"></i>
                        </span>
                    </label>
                </div>
            @endif
        </nav>
    </div>
</header>

