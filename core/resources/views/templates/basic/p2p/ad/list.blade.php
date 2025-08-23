@forelse ($ads as $ad)
    <tr>
        <td>
            <div class="advertiser d-flex gap-2 align-items-center ">
                <div class="advertiser__thumb">
                    @if (@$ad->user->image)
                        <img class="fit-image" src="{{ getImage(getFilePath('userProfile') . '/' . @$ad->user->image, getFileSize('userProfile'), true) }}">
                    @else
                        <span class="user-short-name">{{ firstTwoCharacter(@$ad->user->fullname) }}</span>
                    @endif
                </div>
                <div class="advertiser__content">
                    <h6 class="customer__name">
                        <a class="fw-normal" target="_blank" href="{{ route('p2p.advertiser', encrypt($ad->user->id)) }}">
                            {{ __(@$ad->user->fullname) }}
                        </a>
                        @if ($ad->user->kv)
                            <span class="verified-profile">
                                <img data-bs-toggle="tooltip" title="@lang('KYC Verified')" src="{{ asset('assets/images/extra_images/kyc.png') }}">
                            </span>
                        @endif
                    </h6>
                    <div class="advertiser__content-order-details d-flex align-items-center gap-1">
                        <span>{{ $ad->total_trade }}</span>
                        <span>|</span>
                        @if ($ad->total_trade > 0 && $ad->trades_count > 0)
                            <span>{{ (@$ad->trades_count / $ad->total_trade) * 100 }}@lang('% Completed')</span>
                        @else
                            @lang('0% Completed')
                        @endif
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="price fs-14  text--base">
                <span class="amount">{{ showAmount($ad->price, currencyFormat: false) }}</span>
                <span class="currency">{{ __($ad->fiat->symbol) }}</span>
            </div>
        </td>
        <td>
            <div class="price-limit ">
                <p class="fs-14">
                    {{ showAmount($ad->balance, currencyFormat: false) }}
                    <span>{{ __(@$ad->asset->symbol) }}</span>
                </p>
                <p class="fs-14">
                    {{ showAmount($ad->minimum_amount, currencyFormat: false) }} - {{ showAmount($ad->maximum_amount, currencyFormat: false) }}
                    {{ __($ad->fiat->symbol) }}
                </p>
            </div>
        </td>
        <td>
            <span class="">
                {{ __(@$ad->paymentWindow->minute) }} @lang('Minute')
            </span>
        </td>
        <td>
            <ul class="payment-list fs-14 ">
                @foreach ($ad->paymentMethods as $paymentMethod)
                    <li class="payment-list__item danger">
                        <span class="color" style="background-color: #{{ @$paymentMethod->paymentMethod->branding_color }}"></span>
                        {{ __(@$paymentMethod->paymentMethod->name) }}
                    </li>
                @endforeach
            </ul>
        </td>
        <td>
            @if ($type == 'buy')
                <button type="button" class="btn btn--success btn--sm trade-request" data-type="buy" data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}" data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">
                    @lang('Buy') {{ __(@$ad->asset->symbol) }}
                </button>
            @else
                <button type="button" class="btn btn--danger btn--sm trade-request" data-type="sell" data-id="{{ encrypt($ad->id) }}" data-price="{{ $ad->price }}" data-min="{{ $ad->minimum_amount }}" data-max="{{ $ad->maximum_amount }}">
                    @lang('Sell') {{ __(@$ad->asset->symbol) }}
                </button>
            @endif
        </td>
    </tr>
@empty
    @php echo userTableEmptyMessage('ad') @endphp
@endforelse

