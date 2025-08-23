<table class="table table-two my-order-list-table prep-table">
    <thead>
        <tr>
            <th>@lang('Time')</th>
            <th>@lang('Symbol')</th>
            <th>@lang('Side')</th>
            <th>@lang('Rate')</th>
            <th>@lang('Size')</th>
            <th>@lang('Filled')</th>
            <th>@lang('Type')</th>
            <th>@lang('Action')</th>
        </tr>
    </thead>
    <tbody class="order-list-body openOrderBody">
        @forelse ($pendingQueues as $pendingQueue)
            <tr class="pendingQueue-{{ $pendingQueue->id }}">
                <td>{{ showDateTime($pendingQueue->created_at) }}</td>
                <td><span>{{ __($pendingQueue->futureTradeConfig->coinPair->symbol) }}</span> <span class="fs-12">(@lang('Prep'))</span></td>
                <td>
                    @if ($pendingQueue->order_side == Status::BUY_SIDE_ORDER)
                        <span class="text--success">@lang('Buy')</span>
                    @elseif ($pendingQueue->order_side == Status::SELL_SIDE_ORDER)
                        <span class="text--danger">@lang('Sell')</span>
                    @endif
                </td>
                <td><span>{{ showAmount($pendingQueue->rate, currencyFormat: false) }}</span></td>
                <td><span>{{ showAmount($pendingQueue->size, currencyFormat: false) }} {{ __(@$pendingQueue->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                <td><span>{{ showAmount(($pendingQueue->size * ($pendingQueue->coin_amount - $pendingQueue->remaining_coin_amount)) / $pendingQueue->coin_amount, currencyFormat: false) }} {{ __(@$pendingQueue->futureTradeConfig->coinPair->market->currency->symbol) }}</span></td>
                <td>
                    <span>
                        @if ($pendingQueue->type == Status::QUEUE_MARKET_ORDER)
                            @lang('Market')
                        @elseif ($pendingQueue->type == Status::QUEUE_LIMIT_ORDER)
                            @lang('Limit')
                        @elseif ($pendingQueue->type == Status::ORDER_TYPE_STOP_LIMIT)
                            @lang('Stop Limit'): {{ showAmount($pendingQueue->stop_rate, exceptZeros:true, currencyFormat:false) }}
                        @else
                            @lang('Profit/Loss')
                        @endif

                        @if ($pendingQueue->margin_mode)
                             ({{ $pendingQueue->margin_mode == Status::MARGIN_MODE_CROSS ? __('Cross') : __('Isolated') }}   
                             -
                             {{ $pendingQueue->leverage }}@lang('X'))
                        @endif
                    </span>
                </td>
                <td>
                    <div class="action-buttons d-block">
                        <button type="button" class="delete-icon p-0 m-0 cancelQueueBtn" data-order_id="{{ $pendingQueue->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                                <g clip-path="url(#clip0_2078_683)">
                                    <path
                                        d="M5.0585 0.553125C5.22725 0.2125 5.57412 0 5.95225 0H9.71475C10.0929 0 10.4397 0.2125 10.6085 0.553125L10.8335 1H13.8335C14.3866 1 14.8335 1.44687 14.8335 2C14.8335 2.55312 14.3866 3 13.8335 3H1.8335C1.28037 3 0.833496 2.55312 0.833496 2C0.833496 1.44687 1.28037 1 1.8335 1H4.8335L5.0585 0.553125ZM1.8335 4H13.8335V14C13.8335 15.1031 12.9366 16 11.8335 16H3.8335C2.73037 16 1.8335 15.1031 1.8335 14V4ZM4.8335 6C4.5585 6 4.3335 6.225 4.3335 6.5V13.5C4.3335 13.775 4.5585 14 4.8335 14C5.1085 14 5.3335 13.775 5.3335 13.5V6.5C5.3335 6.225 5.1085 6 4.8335 6ZM7.8335 6C7.5585 6 7.3335 6.225 7.3335 6.5V13.5C7.3335 13.775 7.5585 14 7.8335 14C8.1085 14 8.3335 13.775 8.3335 13.5V6.5C8.3335 6.225 8.1085 6 7.8335 6ZM10.8335 6C10.5585 6 10.3335 6.225 10.3335 6.5V13.5C10.3335 13.775 10.5585 14 10.8335 14C11.1085 14 11.3335 13.775 11.3335 13.5V6.5C11.3335 6.225 11.1085 6 10.8335 6Z"
                                        fill="CurrentColor" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2078_683">
                                        <rect width="14" height="16" fill="currentColor" transform="translate(0.833496)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            @php echo userTableEmptyMessage('open order') @endphp
        @endforelse

    </tbody>
</table>


@include($activeTemplate . 'future.open_order_cancel_modal')
