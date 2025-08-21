@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-xxl-3 col-xl-4">
            <div class="p2p-sidebar">
                <div class="p2p-sidebar__wrapper">
                    <span class="p2p-sidebar__close d-xl-none d-block"><i class="fas fa-times"></i></span>
                    <div class="p2p-sidebar__header">
                        <div class="p2p-sidebar__author">
                            <div class="p2p-sidebar__author-info">
                                <h5 class="mb-0 name">@lang('P2P CENTER')</h5>
                                <span class="fs-13 text-white">@lang('Manage P2P tasks conveniently from this location.')</span>
                            </div>
                        </div>
                    </div>
                    <ul class="p2p-sidebar-list">
                        <li class="p2p-sidebar-list__item ">
                            <a href="{{ route('user.p2p.dashboard') }}"
                                class="p2p-sidebar-list__link {{ menuActive('user.p2p.dashboard') }}">
                                <span class="icon"><i class="las la-tachometer-alt"></i></span>
                                <span class="text">@lang('Manage Dashboard')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item">
                            <a href="{{ route('user.p2p.trade.list', 'running') }}"
                                class="p2p-sidebar-list__link {{ menuActive('user.p2p.trade.list', null, 'running') }}">
                                <span class="icon"><i class="las la-chart-bar"></i></span>
                                <span class="text">@lang('Running Trade')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item">
                            <a href="{{ route('user.p2p.trade.list', 'completed') }}"
                                class="p2p-sidebar-list__link {{ menuActive('user.p2p.trade.list', null, 'completed') }}">
                                <span class="icon"><i class="las la-chart-bar"></i></span></span>
                                <span class="text">@lang('Completed Trade')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item ">
                            <a href="{{ route('user.p2p.advertisement.create') }}"
                                class="p2p-sidebar-list__link {{ menuActive('user.p2p.advertisement.create') }}">
                                <span class="icon"><i class="lab la-adn"></i></span>
                                <span class="text">@lang('New Advertisement')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item ">
                            <a href="{{ route('user.p2p.advertisement.index') }}"
                                class="p2p-sidebar-list__link {{ menuActive('user.p2p.advertisement.index') }}">
                                <span class="icon"><i class="las la-ad"></i></span>
                                <span class="text">@lang('Manage Advertisement')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item">
                            <a href="{{ route('user.p2p.payment.method.create') }}"
                                class="p2p-sidebar-list__link   {{ menuActive('user.p2p.payment.method.create') }}">
                                <span class="icon"><i class="las la-credit-card"></i></span>
                                <span class="text">@lang('New Payment Method')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item">
                            <a href="{{ route('user.p2p.payment.method.list') }}"
                                class="p2p-sidebar-list__link   {{ menuActive('user.p2p.payment.method.list') }}">
                                <span class="icon"><i class="las la-money-check-alt"></i></span>
                                <span class="text">@lang('Manage Payment Method')</span>
                            </a>
                        </li>
                        <li class="p2p-sidebar-list__item ">
                            <a href="{{ route('user.p2p.feedback.list') }}" class="p2p-sidebar-list__link {{ menuActive('user.p2p.feedback.list') }}">
                                <span class="icon"><i class="las la-comments"></i></span>
                                <span class="text">@lang('All FeedBack')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-9 col-xl-8">
            @yield('p2p-content')
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/p2p.css') }}">
@endpush
