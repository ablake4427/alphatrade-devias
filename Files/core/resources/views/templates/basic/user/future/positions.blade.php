@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-between gy-3 align-items-center">
        @include('Template::user.future.top_nav')

        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Margin Mode | Coin Pair')</th>
                            <th>@lang('Date Time')</th>
                            <th>@lang('Side')</th>
                            <th>@lang('Entry Price')</th>
                            <th>@lang('Avg Close')</th>
                            <th>@lang('Liquidation')</th>
                            <th>@lang('Closing Pnl')</th>
                            @if (request()->routeIs('user.future.position.open'))
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
                                    <div>
                                        {{ $position->margin_mode == Status::MARGIN_MODE_ISOLATED ? 'Isolated' : 'Cross' }}
                                        <br>
                                        {{ @$position->futureTradeConfig->coinPair->symbol }}
                                    </div>
                                </td>
                                <td>{{ showDateTime($position->created_at) }}</td>
                                <td>
                                    @if ($position->order_side == Status::BUY_SIDE_ORDER)
                                        <span class="text--success">@lang('Long/Buy')</span>
                                    @elseif ($position->order_side == Status::SELL_SIDE_ORDER)
                                        <span class="text--danger">@lang('Short/Sell')</span>
                                    @endif
                                </td>
                                <td>{{ showAmount($position->rate, currencyFormat: false) }}</td>
                                <td> {{ showAmount($position->avg_closing, currencyFormat: false) }}</td>

                                <td><span>{{ showAmount($position->liquidation_rate, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>

                                <td><span class="{{ $position->pnl < 0 ? 'text--danger' : 'text--success' }}">{{ showAmount($position->pnl, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                                @if (request()->routeIs('user.future.position.open'))
                                    @php
                                        $currentPrice = $position->futureTradeConfig->coinPair->marketData->price;
                                        $pnl = ($currentPrice - $position->rate) * ($position->size / $currentPrice);
                                        if ($position->order_side == Status::SELL_SIDE_ORDER) {
                                            $pnl = $pnl * -1;
                                        }
                                    @endphp

                                    <td><span class="{{ $pnl < 0 ? 'text--danger' : 'text--success' }}">{{ showAmount($pnl, currencyFormat: false) }} {{ __(@$position->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                                @else
                                    <td>
                                        @php
                                            echo $position->statusBadge;
                                        @endphp
                                    </td>
                                @endif

                            </tr>
                        @empty
                            <tr>
                                @php echo userTableEmptyMessage('position') @endphp
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ paginateLinks($positions) }}
            </div>
        </div>
    </div>

    <x-confirmation-modal isCustom="true" />
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush
