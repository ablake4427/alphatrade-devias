@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-between gy-3 align-items-center">
        <div class="col-12">
            <div class="dashboard-header-menu justify-content-between">
                <div class="div">
                    <a href="{{ route('user.binary.trade.all') }}"
                       class="dashboard-header-menu__link   {{ menuActive('user.binary.trade.all') }}">@lang('All')</a>
                    <a href="{{ route('user.binary.trade.win') }}"
                       class="dashboard-header-menu__link   {{ menuActive('user.binary.trade.win') }}">@lang('Win')</a>
                    <a href="{{ route('user.binary.trade.lose') }}"
                       class="dashboard-header-menu__link   {{ menuActive('user.binary.trade.lose') }}">@lang('Lose')</a>
                    <a href="{{ route('user.binary.trade.refund') }}"
                       class="dashboard-header-menu__link   {{ menuActive('user.binary.trade.refund') }}">@lang('Refund')</a>
                </div>
                <form class="d-flex gap-2 flex-wrap">
                    <div class="flex-fill">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Pair,coin,trx...')">
                            <button type="submit" class="input-group-text bg--primary text-white"><i class="las la-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('TRX | Coin Pair')</th>
                            <th>@lang('Trade Date')</th>
                            <th>@lang('Invest')</th>
                            <th>@lang('Duration')</th>
                            <th>@lang('Direction')</th>
                            <th>@lang('Win Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Win Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trades as $trade)
                            <tr>
                                <td>
                                    <div>
                                        {{ $trade->trx }}
                                        <br>
                                        {{ @$trade->coinPair->symbol }}
                                    </div>
                                </td>
                                <td>{{ showDateTime($trade->created_at) }}</td>
                                <td> {{ showAmount($trade->amount, currencyFormat: false) }} {{ __(@$trade->coinPair->coin->symbol) }}</td>
                                <td>@php echo convertToMinutesSeconds($trade->duration)  @endphp</td>
                                <td>{{ __($trade->direction) }}</td>
                                <td>
                                    @if ($trade->win_status == Status::BINARY_TRADE_WIN)
                                        <span class="text--success">{{ showAmount($trade->win_amount, currencyFormat: false) }} {{ __(@$trade->coinPair->coin->symbol) }}</span>
                                    @elseif($trade->win_status == Status::BINARY_TRADE_LOSE)
                                        <span class="text--danger">{{ showAmount($trade->win_amount, currencyFormat: false) }} {{ __(@$trade->coinPair->coin->symbol) }}</span>
                                    @else
                                        {{ showAmount($trade->win_amount, currencyFormat: false) }} {{ __(@$trade->coinPair->coin->symbol) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        echo $trade->tradeStatusBadge;
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        echo $trade->winStatusBadge;
                                    @endphp
                                </td>
                            </tr>
                        @empty
                            <tr>
                                @php echo userTableEmptyMessage('trade') @endphp
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ paginateLinks($trades) }}
            </div>
        </div>
    </div>
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush
