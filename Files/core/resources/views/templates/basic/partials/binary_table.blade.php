<thead>
    <tr>
        <th>@lang('TRX | Coin Pair')</th>
        <th>@lang('Trade Date')</th>
        <th>@lang('Invest')</th>
        <th>@lang('Duration')</th>
        <th>@lang('Direction')</th>
        <th>@lang('Win Amount')</th>
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
            <td>
                {{ __($trade->direction) }}
                <span>
                    @if ($trade->direction == 'higher')
                        <i class="las la-arrow-up text--success"></i>
                    @else
                        <i class="las la-arrow-down text--danger"></i>
                    @endif
                </span>
            </td>
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
                    echo $trade->winStatusBadge;
                @endphp
            </td>
        </tr>
    @empty
        @php echo userTableEmptyMessage('trade') @endphp
    @endforelse
</tbody>
