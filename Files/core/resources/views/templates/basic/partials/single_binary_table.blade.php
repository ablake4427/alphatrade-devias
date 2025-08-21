<tr>
    <td>
        <div>
            {{ $binaryTrade->trx }}
            <br>
            {{ @$binaryTrade->coinPair->symbol }}
        </div>
    </td>
    <td>{{ showDateTime($binaryTrade->created_at) }}</td>
    <td> {{ showAmount($binaryTrade->amount, currencyFormat: false) }} {{ __(@$binaryTrade->coinPair->coin->symbol) }}</td>
    <td>@php echo convertToMinutesSeconds($binaryTrade->duration)  @endphp</td>
    <td>{{ __($binaryTrade->direction) }}
        <span>
            @if ($binaryTrade->direction == 'higher')
                <i class="las la-arrow-up text--success"></i>
            @else
                <i class="las la-arrow-down text--danger"></i>
            @endif
        </span>
    </td>
    <td>{{ showAmount($binaryTrade->win_amount, currencyFormat: false) }} {{ __(@$binaryTrade->coinPair->coin->symbol) }}</td>
    <td>
        @php
            echo $binaryTrade->winStatusBadge;
        @endphp
    </td>
</tr>
