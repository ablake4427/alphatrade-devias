@php
    $user = auth()->user();
@endphp
<div class="dashboard-header">
    <div class="dashboard-header__inner">
        <div class="dashboard-header__left">
            <div class="copy-link">
                <input type="text" class="copyText" value="{{ route('home') }}?reference={{ $user->username }}" readonly>
                <button class="copy-link__button copyTextBtn" data-bs-toggle="tooltip" data-bs-placement="right" title="@lang('Copy URL')">
                    <span class="copy-link__icon"><i class="las la-copy"></i>
                    </span>
                </button>
            </div>
        </div>
        <div class="dashboard-header__right">
            <div class="trade-btn-wrapper">
                <button class="btn allTradeBtn btn--base btn--sm outline">
                    @lang('Trade')<i class="ms-2 las la-lg la-ellipsis-v"></i>
                </button>
                <ul class="trade-list">
                    <li class="trade-list__item">
                        <a href="{{ route('trade') }}" class="trade-list__link">
                            @lang('Spot Trade')
                        </a>
                    </li>
                    <li class="trade-list__item">
                        <a href="{{ route('binary') }}" class="trade-list__link">
                            @lang('Binary Trade')
                        </a>
                    </li>
                    @if (gs('future_trade')) 
                        <li class="trade-list__item">
                            <a href="{{ route('future.trade') }}" class="trade-list__link">
                                @lang('Future Trade')
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="user-info">
                <div class="user-info__right">
                    <div class="user-info__button">
                        <div class="user-info__profile">
                            <p class="user-info__name">{{ __($user->username) }}</p>
                        </div>
                    </div>
                </div>
                <ul class="user-info-dropdown">
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.profile.setting') }}">
                            <span class="icon"><i class="far fa-user-circle"></i></span>
                            <span class="text">@lang('My Profile')</span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.change.password') }}">
                            <span class="icon"><i class="fa fa-key"></i></span>
                            <span class="text">@lang('Change Password')</span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.logout') }}">
                            <span class="icon"><i class="far fa-user-circle"></i></span>
                            <span class="text">@lang('Logout')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
