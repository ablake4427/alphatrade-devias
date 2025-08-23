@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="publisher-area">
        <div class="container">
            <div class="publisher">
                <div class="publisher-top">
                    <div class="publisher-profile">
                        <div class="publisher-profile__thumb">
                            @if (@$advertiser->image)
                                <img class="fit-image"
                                    src="{{ getImage(getFilePath('userProfile') . '/' . $advertiser->image, getFileSize('userProfile'), true) }}">
                            @else
                                <span class="user-short-name">{{ firstTwoCharacter($advertiser->fullname) }}</span>
                            @endif
                        </div>
                        <div class="publisher-profile__content">
                            <h5 class="title">
                                {{ __($advertiser->fullname) }}
                                @if ($advertiser->kv)
                                    <span class="verified-img">
                                        <img src="{{ asset('assets/images/extra_images/kyc.png') }}" class="verfied-image"
                                            data-bs-toggle="tooltip" title="@lang('KYC Verified')">
                                    </span>
                                @endif
                            </h5>
                            <div class="activiies-details d-flex align-items-center gap-2 fs-14">
                                <span>
                                    @lang('Joined ') {{ showDateTime($advertiser->create_at) }}
                                </span>
                            </div>
                            <ul class="verified-profile d-flex align-items-center gap-3 fs-14">
                                <li>
                                    @lang('Email')
                                    @if ($advertiser->ev)
                                        <span class="verified-badge bg--success">
                                            <i class="las la-check"></i>
                                        </span>
                                    @else
                                        <span class="verified-badge bg--danger">
                                            <i class="las la-times-circle"></i>
                                        </span>
                                    @endif
                                </li>
                                <li>
                                    @lang('SMS')
                                    @if ($advertiser->sv)
                                        <span class="verified-badge bg--success">
                                            <i class="las la-check"></i>
                                        </span>
                                    @else
                                        <span class="verified-badge bg--danger">
                                            <i class="las la-times-circle"></i>
                                        </span>
                                    @endif
                                </li>
                                <li>
                                    @lang('KYC')
                                    @if ($advertiser->kv)
                                        <span class="verified-badge bg--success">
                                            <i class="las la-check"></i>
                                        </span>
                                    @else
                                        <span class="verified-badge bg--danger">
                                            <i class="las la-times-circle"></i>
                                        </span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="publisher-order-details text-center">
                        <p class="title mb-2">@lang('Positive Feedback')</p>
                        <div class="order-reviews d-flex flex-column flex-wrap">
                            <div class="order-reviews-left">
                                <p>{{ @$feedback->positive_percentage }}%</p>
                            </div>
                            <ul class="order-reviews-right">
                                <li class="items">@lang('Positive') <span class="positive">{{ $feedback->positive }}</span>
                                </li>
                                <li class="items">@lang('Negative') <span class="negative">{{ $feedback->negative }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="publisher-bottom">
                    <div class="publisher-card">
                        <p class="publisher-card__title">@lang('Last Trade')</span>
                        </p>
                        <h4 class="publisher-card__times">
                            {{ @$widget['last_trade'] ? diffForHumans($widget['last_trade']) : 'N/A' }}
                        </h4>
                    </div>
                    <div class="publisher-card">
                        <p class="publisher-card__title">@lang('Running Trades')</span>
                        </p>
                        <h4 class="publisher-card__times">
                            {{ $widget['running_trade'] }}
                        </h4>
                    </div>
                    <div class="publisher-card">
                        <p class="publisher-card__title">@lang('Completed Trades')</span>
                        </p>
                        <h4 class="publisher-card__times">
                            {{ $widget['completed_trade'] }}
                        </h4>
                    </div>
                    <div class="publisher-card">
                        <p class="publisher-card__title">@lang('Total Trades')</span>
                        </p>
                        <h4 class="publisher-card__times">
                            {{ $widget['total_trade'] }}
                        </h4>
                    </div>
                    <div class="publisher-card">
                        <p class="publisher-card__title">@lang('Reported Trades')</span>
                        </p>
                        <h4 class="publisher-card__times">
                            {{ $widget['reported_trade'] }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ads-area py-60">
        <div class="container">
            <div class="mb-3">
                <h4 class="ads-title mb-1">
                    @lang('Buy Ads')
                </h4>
                <table class="table p2p-table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Advertiser')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Payment Window')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Trade')<small class="fs-11 text--info">( {{getAmount(gs('p2p_trade_charge'))}}% @lang('Fee'))</small> </th>
                        </tr>
                    </thead>
                    <tbody id="ad-list">
                        @include($activeTemplate . 'p2p.ad.list', ['ads' => $ads['buy'], 'type' => 'buy'])
                    </tbody>
                </table>
            </div>
            <div class="mb-3">
                <h5 class="ads-title mb-1">
                    @lang('Sell Ads')
                </h5>
                <table class="table p2p-table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Advertiser')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Available/Limit')</th>
                            <th>@lang('Payment Window')</th>
                            <th>@lang('Payment Method')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="ad-list">
                        @include($activeTemplate . 'p2p.ad.list', [
                            'ads' => $ads['sell'],
                            'type' => 'sell',
                        ])
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @include($activeTemplate . 'p2p.trade.buy_sell_script')
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/p2p.css') }}">
@endpush
