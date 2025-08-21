@forelse ($tradeHistories as $tradeHistory)
    <tr>
        <td>{{ showDateTime($tradeHistory->created_at) }}</td>
        <td>{{ __(@$tradeHistory->futureTradeConfig->coinPair->symbol) }}</td>
        <td>
            @if ($tradeHistory->trade_side == Status::BUY_SIDE_ORDER)
                <span class="text--success">@lang('Buy')</span>
            @else
                <span class="text--danger">@lang('Sell')</span>
            @endif
        </td>
        <td>{{ showAmount($tradeHistory->amount * $tradeHistory->rate, currencyFormat: false) }} {{ __(@$tradeHistory->futureTradeConfig->coinPair->market->currency->symbol) }}</td>
        <td>{{ showAmount($tradeHistory->rate, currencyFormat: false) }}</td>
        <td>{{ showAmount($tradeHistory->charge, currencyFormat: false) }} {{ __(@$tradeHistory->futureTradeConfig->coinPair->market->currency->symbol) }}</td>
    </tr>
@empty
    @php echo userTableEmptyMessage('trade') @endphp
@endforelse

@if ($tradeHistories->nextPageUrl())
    <tr>
        <td colspan="100%" class="text-center">
            <button class="btn btn--base outline btn--sm load-more-btn">@lang('Load More')</button>
        </td>
    </tr>
@endif
