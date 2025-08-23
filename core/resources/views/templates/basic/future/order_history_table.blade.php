@forelse ($orderHistories as $orderHistory)
    <tr>
        <td>{{ showDateTime($orderHistory->created_at) }}</td>
        <td>{{ $orderHistory->futureTradeConfig->coinPair->symbol }}</td>
        <td>
            @if ($orderHistory->order_side == Status::BUY_SIDE_ORDER)
                <span class="text--success">@lang('Buy')</span>
            @else
                <span class="text--danger">@lang('Sell')</span>
            @endif
        </td>
        <td>{{ getAmount($orderHistory->size) }} {{ __(@$orderHistory->futureTradeConfig->coinPair->market->currency->symbol) }}</td>
        <td>{{ getAmount($orderHistory->rate) }}</td>
        <td>
            @if ($orderHistory->status == Status::FUTURE_QUEUE_COMPLETED)
                <span class="text text--success">@lang('Completed')</span>
            @else
                <span class="text text--danger">@lang('Canceled')</span>
            @endif
        </td>
    </tr>
@empty
    @php echo userTableEmptyMessage('order') @endphp
@endforelse

@if ($orderHistories->nextPageUrl())
    <tr>
        <td colspan="100%" class="text-center">
            <button class="btn btn--base outline btn--sm load-more-order-history">@lang('Load More')</button>
        </td>
    </tr>
@endif
