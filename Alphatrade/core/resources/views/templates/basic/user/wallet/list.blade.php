@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-12">
            <div class="dashboard-header-menu justify-content-between align-items-center">
                <h4 class="mb-0">{{ __($pageTitle) }}</h4>
                <div class="div">
                    <a href="{{ route('user.wallet.list', 'spot') }}"
                        class="dashboard-header-menu__link  {{ menuActive('user.wallet.list', null, 'spot') }}">
                        @lang('Spot')
                    </a>
                    <a href="{{ route('user.wallet.list', 'funding') }}"
                        class="dashboard-header-menu__link  {{ menuActive('user.wallet.list', null, 'funding') }}">
                        @lang('Funding')
                    </a>
                    @if (gs('future_trade'))                        
                        <a href="{{ route('user.wallet.list', 'future') }}"
                            class="dashboard-header-menu__link  {{ menuActive('user.wallet.list', null, 'future') }}">
                            @lang('Future')
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Currency')</th>
                            <th>@lang('Available Balance')</th>
                            <th>@lang('In Order')</th>
                            <th>@lang('Total Balance')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallets as $wallet)
                            <tr>
                                <td>
                                    <div class="customer justify-content-end justify-content-lg-start">
                                        <div class="customer__thumb">
                                            <img src="{{ @$wallet->currency->image_url }}">
                                        </div>
                                        <div class="customer__content text-end text-lg-start">
                                            <h6 class="customer__name">{{ @$wallet->currency->name }}</h6>
                                            <small class="fs-12">{{ @$wallet->currency->symbol }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ __(@$wallet->currency->sign) }}{{ showAmount(@$wallet->balance,currencyFormat:false) }} </td>
                                <td>{{ __(@$wallet->currency->sign) }}{{ showAmount(@$wallet->in_order,currencyFormat:false) }} </td>
                                <td>{{ __(@$wallet->currency->sign) }}{{ showAmount(@$wallet->total_balance,currencyFormat:false) }} </td>
                                <td>
                                    <a href="{{ route('user.wallet.view',  [ 'type' => $wallet->typeText,'currencySymbol' => @$wallet->currency->symbol]) }}"
                                        class="btn btn--base btn--sm outline">
                                        <i class="las la-eye"></i> @lang('View')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('wallet') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($wallets->hasPages())
                {{ paginateLinks($wallets) }}
            @endif
        </div>
    </div>
@endsection
