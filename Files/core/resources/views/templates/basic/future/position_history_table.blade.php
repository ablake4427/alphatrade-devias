@forelse ($positionHistories as $positionHistory)
    <tr>
        <td>{{ __(@$positionHistory->futureTradeConfig->coinPair->symbol) }}</td>
        <td>{{ showAmount($positionHistory->rate, currencyFormat: false) }}</td>
        <td>{{ showAmount($positionHistory->avg_closing, currencyFormat: false) }}</td>
        <td>{{ $positionHistory->leverage }}</td>
        <td>
            <span class="{{ $positionHistory->pnl > 0 ? 'text--success' : 'text--danger' }}">
                {{ showAmount(abs($positionHistory->pnl), currencyFormat: false) }} 
                {{ __(@$positionHistory->futureTradeConfig->coinPair->market->currency->symbol) }}
            </span>
        </td>
        <td>
            @if ($positionHistory->order_side == Status::BUY_SIDE_ORDER)
                <span class="text--success">@lang('Buy')</span>
            @else
                <span class="text--danger">@lang('Sell')</span>
            @endif
        </td>
        <td>{{ showDateTime($positionHistory->updated_at) }}</td>
        <td>
            @php
                 echo $positionHistory->statusBadge;
            @endphp
        </td>
    </tr>
@empty
    @php echo userTableEmptyMessage('position') @endphp
@endforelse

@if ($positionHistories->nextPageUrl())
    <tr>
        <td colspan="100%" class="text-center">
            <button class="btn btn--base outline btn--sm load-more-position-history"> @lang('Load More')</button>
        </td>
    </tr>
@endif
