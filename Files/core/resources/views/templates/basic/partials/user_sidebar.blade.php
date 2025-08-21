<div class="sidebar-menu">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times"></i></span>
        <div class="sidebar-logo">
            <a href="{{ route('home') }}" class="sidebar-logo__link">
                <img src="{{ siteLogo() }}">
            </a>
        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.home') }}" class="sidebar-menu-list__link {{ menuActive('user.home') }}">
                    <span class="icon"><span class="icon-dashboard"></span></span>
                    <span class="text">@lang('Dashboard')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.order.open') }}" class="sidebar-menu-list__link {{ menuActive('user.order.*') }} ">
                    <span class="icon"><span class="icon-order"></span></span>
                    <span class="text">@lang('Manage Order')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.trade.history') }}" class="sidebar-menu-list__link {{ menuActive('user.trade.history') }} ">
                    <span class="icon"><span class="icon-trade"></span></span>
                    <span class="text">@lang('Trade History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.binary.trade.all') }}" class="sidebar-menu-list__link {{ menuActive('user.binary.trade.*') }} ">
                    <span class="icon"><i class="las la-chart-line"></i></span>
                    <span class="text">@lang('Binary Trade History')</span>
                </a>
            </li>
            @if (gs('future_trade')) 
                <li class="sidebar-menu-list__item">
                    <a href="{{ route('user.future.order.open') }}" class="sidebar-menu-list__link {{ menuActive('user.future.*') }} ">
                        <span class="icon"><i class="las la-chart-bar"></i></span>
                        <span class="text">@lang('Future Trade')</span>
                    </a>
                </li>
            @endif
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.p2p.dashboard') }}" class="sidebar-menu-list__link {{ menuActive('user.p2p.dashboard') }} ">
                    <span class="icon"><span class="icon-trade"></span></span>
                    <span class="text">@lang('P2P Center')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.wallet.list', 'spot') }}" class="sidebar-menu-list__link {{ menuActive('user.wallet.*') }}">
                    <span class="icon"><span class="icon-wallet"></span></span>
                    <span class="text">@lang('Manage Wallet')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.deposit.history') }}" class="sidebar-menu-list__link {{ menuActive('user.deposit.*') }}">
                    <span class="icon"><span class="icon-deposit"></span></span>
                    <span class="text">@lang('Deposit History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.withdraw.history') }}" class="sidebar-menu-list__link {{ menuActive('user.withdraw.history') }}">
                    <span class="icon"><span class="icon-withdraw"></span></span>
                    <span class="text">@lang('Withdraw History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.referrals') }}" class="sidebar-menu-list__link {{ menuActive('user.referrals') }}">
                    <span class="icon"><span class="icon-affiliation"></span></span>
                    <span class="text">@lang('My Affiliation')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.transactions') }}" class="sidebar-menu-list__link {{ menuActive('user.transactions') }}">
                    <span class="icon"><span class="icon-transaction"></span></span>
                    <span class="text">@lang('Transaction Histoy')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('ticket.index') }}" class="sidebar-menu-list__link {{ menuActive('ticket.*') }}">
                    <span class="icon"><span class="icon-support"></span></span>
                    <span class="text">@lang('Get Support')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.twofactor') }}" class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}">
                    <span class="icon"><span class="icon-security"></span></span>
                    <span class="text">@lang('Security')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
                    <span class="icon"><span class="icon-logout"></span></span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
