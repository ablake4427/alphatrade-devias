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
                                    <th>@lang('Margin Mode | Coin Pair')</th>
                                    <th>@lang('Date Time')</th>
                                    <th>@lang('Side')</th>
                                    <th>@lang('Size')</th>
                                    <th>@lang('Entry Price')</th>
                                    <th>@lang('Liquidation')</th>
                                    <th>@lang('Closing Pnl')</th>
                                    @if (request()->routeIs('admin.future.trade.position.open'))
                                        <th>@lang('Unrealized Pnl')</th>
                                    @else
                                        <th>@lang('Status')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($positions as $position)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$position->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $position->user_id) }}"><span>@</span>{{ @$position->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            {{ $position->margin_mode == Status::MARGIN_MODE_ISOLATED ? 'Isolated' : 'Cross' }}
                                            <br>
                                            {{ @$position->futureTradeConfig->coinPair->symbol }}
                                        </td>
                                        <td>{{ showDateTime($position->created_at) }}</td>
                                        <td>
                                            @if ($position->order_side == Status::BUY_SIDE_ORDER)
                                                <span class="text--success">@lang('Buy/Long')</span>
                                            @elseif ($position->order_side == Status::SELL_SIDE_ORDER)
                                                <span class="text--danger">@lang('Sell/Short')</span>
                                            @endif
                                        </td>
                                        <td> {{ showAmount($position->size, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</td>
                                        <td>{{ showAmount($position->rate, currencyFormat: false) }}</td>

                                        <td><span>{{ showAmount($position->liquidation_rate, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>

                                        <td><span class="{{ $position->pnl < 0 ? 'text--danger' : 'text--success' }}">{{ showAmount($position->pnl, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                                        @if (request()->routeIs('admin.future.trade.position.open'))
                                            @php
                                                $currentPrice = $position->futureTradeConfig->coinPair->marketData->price;
                                                $pnl = ($currentPrice - $position->rate) * ($position->size / $currentPrice);
                                                if ($position->order_side == Status::SELL_SIDE_ORDER) {
                                                    $pnl = $pnl * -1;
                                                }
                                            @endphp

                                            <td><span class="{{ $pnl < 0 ? 'text--danger' : 'text--success' }}">{{ showAmount($pnl, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                                        @else
                                            <td>@php  echo $position->statusBadge @endphp</td>
                                        @endif
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
                @if ($positions->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($positions) }}
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
