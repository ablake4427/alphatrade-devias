@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
    <div class="publisher-area">
        <div class="publisher">
            <div class="row gy-4">
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--danger">
                                <i class="las la-spinner"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.trade.list','running') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Running Trade') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount($widget['running_trade']) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--success">
                                <i class="las la-check-circle"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.trade.list','completed') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Completed Trade')
                                </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount($widget['completed_trade']) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--base">
                                <i class="las la-chart-bar"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.trade.list','completed') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Total Trade') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount($widget['total_trade']) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--success">
                                <i class="las la-check-circle"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.advertisement.index') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Active Ad') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount($widget['active_ad']) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--danger">
                                <i class="las la-times-circle"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.advertisement.index') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Inactive Ad') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount($widget['in_active_ad']) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--base">
                                <i class="las la-ad"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.advertisement.index') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Total Ad') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount($widget['total_ad']) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--danger">
                                <i class="las la-thumbs-down"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.feedback.list') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Negative FeedBack') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount(@$widget['feedback']->negative) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--success">
                                <i class="las la-thumbs-up"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.feedback.list') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Possitive FeedBack') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount(@$widget['feedback']->positive) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-xxl-4 col-sm-6">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dashboard-card__icon text--base">
                                <i class="las la-comments"></i>
                            </span>
                            <div class="dashboard-card__content">
                                <a href="{{ route('user.p2p.feedback.list') }}" class="dashboard-card__coin-name mb-0 ">
                                    @lang('Total FeedBack') </a>
                                <h6 class="dashboard-card__coin-title text-end"> {{ getAmount(@$widget['feedback']->total) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <h5 class="transection__title"> @lang('Recent Trade') </h5>
                    @include($activeTemplate . 'user.p2p.trade.table', [
                        'trades' => $trades,
                        'user' => $user,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
