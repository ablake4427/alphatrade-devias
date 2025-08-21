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
                                    <th>@lang('Coin Pair')</th>
                                    <th>@lang('Date Time')</th>
                                    <th>@lang('Side')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Size')</th>
                                    <th>@lang('Filled')</th>
                                    @if (request()->routeIs('admin.future.trade.order.history')) 
                                        <th>@lang('Status')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$order->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $order->user_id) }}"><span>@</span>{{ @$order->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>{{ @$order->futureTradeConfig->coinPair->symbol }}</td>
                                        <td>{{ showDateTime($order->created_at) }}</td>
                                        <td>
                                            @if ($order->order_side == Status::BUY_SIDE_ORDER)
                                                <span class="text--success">@lang('Buy/Long')</span>
                                            @elseif ($order->order_side == Status::SELL_SIDE_ORDER)
                                                <span class="text--danger">@lang('Sell/Short')</span>
                                            @endif
                                        </td>
                                        <td>{{ showAmount($order->rate, currencyFormat: false) }}</td>
                                        <td> {{ showAmount($order->size, currencyFormat: false) }} {{ __(@$order->futureTradeConfig->coinPair->market->currency->symbol) }}</td>
        
                                        <td><span>{{ showAmount(($order->size * ($order->coin_amount - $order->remaining_coin_amount)) / $order->coin_amount, currencyFormat: false) }} {{ __(@$order->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                                        @if (request()->routeIs('admin.future.trade.order.history')) 
                                            <td>@php  echo $order->statusBadge @endphp</td>
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
                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
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
