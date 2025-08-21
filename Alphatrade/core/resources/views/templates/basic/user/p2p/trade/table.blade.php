<div class="table-wrapper">
    @if ($trades->count())
        <table class="table table--responsive--lg">
            <thead>
                <tr>
                    <th>@lang('Type | Status')</th>
                    <th>@lang('Order ID | Date')</th>
                    <th>@lang('Buyer | Seller')</th>
                    <th>@lang('Rate | Payment Method')</th>
                    <th>@lang('Asset | Fiat')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trades as $trade)
                    <tr>
                        <td>
                            <div>
                                @if (@$trade->ad->user_id == $user->id)
                                    @php echo $trade->ad->typeBadge @endphp
                                @else
                                    @php echo $trade->typeBadge; @endphp
                                @endif
                                <div class="mt-1">
                                    @php echo $trade->statusBadge; @endphp
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="d-block"> {{ $trade->uid }} </span>
                                <span>{{ showDateTime($trade->created_at) }} </span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="d-block text--success">
                                    {{ $user->id == @$trade->buyer_id ? __('ME') : @$trade->buyer->full_name }}
                                </span>
                                <span class="d-block text--danger">
                                    {{ $user->id == @$trade->seller_id ? __('ME') : @$trade->seller->full_name }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="d-block">
                                    {{ showAmount(@$trade->ad->price,currencyFormat:false) }} {{ __(@$trade->ad->fiat->symbol) }} /
                                    {{ __(@$trade->ad->asset->symbol) }}
                                </span>
                                <span>{{ __(@$trade->paymentMethod->name) }}</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="d-block">
                                    {{ showAmount(@$trade->asset_amount,currencyFormat:false) }} {{ __(@$trade->ad->asset->symbol) }}
                                </span>
                                <span>
                                    {{ showAmount(@$trade->fiat_amount,currencyFormat:false) }} {{ __(@$trade->ad->fiat->symbol) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('user.p2p.trade.details', $trade->id) }}"
                                class="btn btn--base btn--sm outline">
                                <i class="las la-desktop"></i> @lang('Details')
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @php echo userTableEmptyMessage("trade"); @endphp
    @endif
</div>
