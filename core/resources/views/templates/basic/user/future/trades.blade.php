@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-between gy-3 align-items-center">
        @include('Template::user.future.top_nav')

        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Date Time')</th>
                            <th>@lang('Coin Pair')</th>
                            <th>@lang('Side')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Charge')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trades as $trade)
                            <tr>
                                <td>{{ showDateTime($trade->created_at) }}</td>
                                <td>{{ @$trade->futureTradeConfig->coinPair->symbol }}</td>
                                <td>
                                    @if ($trade->trade_side == Status::BUY_SIDE_ORDER)
                                        <span class="text--success">@lang('Buy')</span>
                                    @elseif ($trade->trade_side == Status::SELL_SIDE_ORDER)
                                        <span class="text--danger">@lang('Sell')</span>
                                    @endif
                                </td>
                                <td>{{ showAmount($trade->rate, currencyFormat: false) }}</td>
                                <td> {{ showAmount($trade->amount, currencyFormat: false) }} {{ __(@$trade->futureTradeConfig->coinPair->coin->symbol) }}</td>
                                <td> {{ showAmount($trade->charge, currencyFormat: false) }} {{ __(@$trade->futureTradeConfig->coinPair->market->currency->symbol) }}</td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('order') @endphp
                        @endforelse
                    </tbody>
                </table>
                {{ paginateLinks($trades) }}
            </div>
        </div>
    </div>

    <x-confirmation-modal isCustom="true" />
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush
