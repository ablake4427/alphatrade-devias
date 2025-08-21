<div class="order-details-info-wrapper mt-3 mb-3">
    <div class="row">
        <div class="col-lg-7">
            <div class="p2p-order-info-left section-bg">
                <div class="advertiser d-flex gap-2 align-items-center mb-4">
                    <div class="advertiser__thumb">
                        @if (@$ad->user->image)
                            <img class="fit-image"
                                src="{{ getImage(getFilePath('userProfile') . '/' . @$ad->user->image, getFileSize('userProfile'), true) }}">
                        @else
                            <span class="user-short-name">{{ firstTwoCharacter(@$ad->user->fullname) }}</span>
                        @endif
                    </div>
                    <div class="advertiser__content">
                        <div class="customer__name d-flex justify-content-between">
                            <div>
                                {{ __($ad->user->fullname) }}   {{ __($ad->id) }}  
                                @if ($ad->user->kv)
                                    <span class="verified-profile">
                                        <img src="{{ asset('assets/images/extra_images/kyc.png') }}" class="verfied-image" data-bs-toggle="tooltip" title="@lang('KYC Verified')">
                                    </span>
                                @endif
                            </div>
                            <div>
                                <button type="button" class="feedback-btn badge">
                                    <i class="las la-thumbs-up"> </i>
                                    {{  @$feedback->positive_percentage}}@lang('%') 
                                </button>
                                <button type="button" class="feedback-btn badge btn--danger">
                                    <i class="las la-thumbs-down"> </i>
                                    {{  @$feedback->negative_percentage}}@lang('%') 
                                </button>
                            </div>
                        </div>
                        <div class="advertiser__content-order-details d-flex align-items-center gap-1 fs-14">
                            <span>{{ __($ad->total_trade)}}</span>
                            <span>|</span>
                            @if ($ad->total_trade > 0 && $ad->trade_count > 0 )
                                <span>{{ __($ad->total_trade/$ad->trades_count)}} @lang('% Completed')</span>
                            @else
                                @lang('0% Completed')
                            @endif
                        </div>
                    </div>
                </div>
                <div class="order-details__info-wrapper">
                    <div class="order-details__info">
                        <div class="order-details__info-item">
                            <span class="title">
                                @lang('Price')
                            </span>
                            <h6 class="amount-count mb-0">{{ showAmount($ad->price,currencyFormat:false) }} {{ __(@$ad->fiat->symbol) }}</h6>
                        </div>
                        <div class="order-details__info-item">
                            <span class="title">
                                @lang('Payment Time Limit')
                            </span>
                            <h6 class="amount-count mb-0">{{ __(@$ad->paymentWindow->minute) }} @lang('Minute')</h6>
                        </div>
                        <div class="order-details__info-item">
                            <span class="title"> @lang('Available') </span>
                            <h6 class="amount-count mb-0"> {{ showAmount($coinWalletBalance,currencyFormat:false) }}
                                {{ __(@$ad->asset->symbol) }}</h6>
                        </div>
                    </div>
                </div>
                <ul class="order-details__payment-method">
                    @foreach ($ad->paymentMethods as $paymentMethod)
                        <li>
                            <span class="line-color" style="background-color: #{{ @$paymentMethod->paymentMethod->branding_color }}"></span>
                            {{ __(@$paymentMethod->paymentMethod->name) }}
                        </li>
                    @endforeach
                </ul>
                <div class="p2p-trams">
                    <div class="p2p-trams-content">
                        <div class="p2p-trams-title">
                            <p class="mb-2">@lang("Advertiser's Terms") </p>
                        </div>
                        <div> @php echo $ad->terms_of_trade @endphp </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <form class="p2p-order-info-left trade-rquest-form">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="form-group">
                    <label class="form--label">@lang('I want to pay ')</label>
                    <div class="input-group">
                        <input type="number" step="any" class="form-control form--control fiat-amount"
                            name="fiat_amount">
                        <span class="input-group-text">{{ __(@$ad->fiat->symbol) }}</span>
                    </div>
                    <span class="mt-1  text--warning fs-11">
                        {{ showAmount($ad->minimum_amount,currencyFormat:false) }}-{{ showAmount($ad->maximum_amount,currencyFormat:false) }}
                        {{ __(@$ad->fiat->symbol) }}
                    </span>
                </div>
                <div class="form-group">
                    <label class="form--label">@lang('I will receive')</label>
                    <div class="input-group">
                        <input type="number" step="any" class="form-control form--control asset-amount"
                            name="asset_amount">
                        <span class="input-group-text">{{ __(@$ad->asset->symbol) }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form--label">@lang('Payment Via')</label>
                    <select name="payment_method" class="form--control register-select">
                        <option selected disabled>@lang('Select One')</option>
                        @foreach ($ad->paymentMethods as $paymentMethod)
                            <option value="{{ @$paymentMethod->id }}">
                                {{ __(@$paymentMethod->paymentMethod->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="p2p-button d-flex flex-wrap gap-2">
                    <button type="button" class="btn flex-fill btn--p2p section-bg cancel-trade-request">@lang('Cancel')</button>
                    <button class="btn flex-fill btn--success" type="submit">
                        @lang('Buy ') {{ __(@$ad->asset->symbol) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
