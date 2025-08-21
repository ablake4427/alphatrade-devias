@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two highlighted-table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Date Time')</th>
                                    <th>@lang('Coin Pair')</th>
                                    <th>@lang('Side')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Charge')</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trades as $trade)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$trade->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $trade->user_id) }}"><span>@</span>{{ @$trade->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            {{ @$trade->futureTradeConfig->coinPair->symbol }}
                                        </td>
                                        <td>{{ showDateTime($trade->created_at) }}</td>
                                        <td>
                                            @if ($trade->trade_side == Status::BUY_SIDE_ORDER)
                                                <span class="text--success">@lang('Buy/Long')</span>
                                            @elseif ($trade->trade_side == Status::SELL_SIDE_ORDER)
                                                <span class="text--danger">@lang('Sell/Short')</span>
                                            @endif
                                        </td>
                                        <td>{{ showAmount($trade->rate, currencyFormat: false) }}/{{ __(@$trade->futureTradeConfig->coinPair->market->currency->symbol) }} </td>
                                        <td> {{ showAmount($trade->amount, currencyFormat: false) }} {{ __(@$trade->futureTradeConfig->coinPair->coin->symbol ) }}</td>

                                        <td><span>{{ showAmount($trade->charge, currencyFormat: false) }} {{ __(@$trade->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>

                                     
                                       
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($trades->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($trades) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username, Pair, Coin" />
@endpush
