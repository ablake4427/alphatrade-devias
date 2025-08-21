@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-between gy-3 align-items-center">
        @include('Template::user.future.top_nav')

        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Coin Pair')</th>
                            <th>@lang('Date Time')</th>
                            <th>@lang('Side')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Size')</th>
                            <th>@lang('Filled')</th>
                            @if (request()->routeIs('user.future.order.open'))
                                <th>@lang('Action')</th>
                            @else
                                <th>@lang('Status')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <div>
                                        {{ @$order->futureTradeConfig->coinPair->symbol }}
                                    </div>
                                </td>
                                <td>{{ showDateTime($order->created_at) }}</td>
                                <td>
                                    @if ($order->order_side == Status::BUY_SIDE_ORDER)
                                        <span class="text--success">@lang('Buy')</span>
                                    @elseif ($order->order_side == Status::SELL_SIDE_ORDER)
                                        <span class="text--danger">@lang('Sell')</span>
                                    @endif
                                </td>
                                <td>{{ showAmount($order->rate, currencyFormat: false) }}</td>
                                <td> {{ showAmount($order->size, currencyFormat: false) }} {{ __(@$order->futureTradeConfig->coinPair->market->currency->symbol) }}</td>

                                <td><span>{{ showAmount(($order->size * ($order->coin_amount - $order->remaining_coin_amount)) / $order->coin_amount, currencyFormat: false) }} {{ __(@$order->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>

                                @if (request()->routeIs('user.future.order.open'))
                                    <td>
                                        <div>
                                            <button type="button" class="btn btn--danger btn--cancel outline btn--sm confirmationBtn ms-2" data-question="Are you sure to cancel this order?" data-action="{{ route('user.future.trade.order.cancel', $order->id) }}">
                                                <i class="las la-times-circle"></i> @lang('Cancel')
                                            </button>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        @php
                                            echo $order->statusBadge;
                                        @endphp
                                    </td>
                                @endif

                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('order') @endphp
                        @endforelse
                    </tbody>
                </table>
                {{ paginateLinks($orders) }}
            </div>
        </div>
    </div>

    <x-confirmation-modal isCustom="true" />
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush
