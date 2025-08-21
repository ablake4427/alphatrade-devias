@php
    $coinPrice      = @$ad->asset->marketData->price;
    $currencyPrice  = @$ad->fiat->rate;
    $originalPrice   = $coinPrice / $currencyPrice;
    $price          = @$ad->price > 0 ? @$ad->price : $originalPrice;
    $priceMargin    = @$ad->price_margin > 0 ? @$ad->price_margin : 100;
    $paymentMethods = App\Models\P2P\PaymentMethod::whereJsonContains('supported_currency', $ad->fiat->symbol)
        ->active()
        ->orderBy('name')
        ->get();
    $paymentWindows = App\Models\P2P\PaymentWindow::active()
        ->orderBy('minute')
        ->get();
$adPaymentMethodId = old('payment_method') ? old('payment_method') : @$ad->paymentMethods->pluck('payment_method_id')->toArray();
@endphp

<div class="row">
    <div class="form-group col-lg-12 position-relative">
        <label class="form-label">@lang('Payment Method')</label>
        <select class="form-control form--control form-select select2" name="payment_method[]" required multiple>
            <option disabled>@lang('Select One')</option>
            @foreach ($paymentMethods as $paymentMethod)
                <option value="{{ $paymentMethod->id }}" @selected(in_array($paymentMethod->id,@$adPaymentMethodId  ?? []))>
                    {{ __($paymentMethod->name) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-12 position-relative">
        <label class="form-label">@lang('Payment Window')</label>
        <select class="form-control form--control form-select select2" name="payment_window" required>
            <option value="" selected disabled>@lang('Select One')</option>
            @foreach ($paymentWindows as $paymentWindow)
                <option value="{{ $paymentWindow->id }}" @selected($paymentWindow->id == old('payment_window', @$ad->payment_window_id))>
                    {{ __($paymentWindow->minute) }} @lang('Minute')
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-12 position-relative">
        <label class="form-label">@lang('Pricing Type')</label>
        <select class="form-control form--control form-select select2" data-minimum-results-for-search="-1" name="price_type" required>
            <option value="{{ Status::P2P_AD_PRICE_TYPE_FIXED }}" @selected(Status::P2P_AD_PRICE_TYPE_FIXED == old('price_type', @$ad->price_type))>
                @lang('Fixed')
            </option>
            <option value="{{ Status::P2P_AD_PRICE_TYPE_MARGIN }}" @selected(Status::P2P_AD_PRICE_TYPE_MARGIN == old('price_type', @$ad->price_type))>
                @lang('Margin')
            </option>
        </select>
    </div>

    <div class="form-group col-lg-12  margin-input-wrapper @if (Status::P2P_AD_PRICE_TYPE_MARGIN != @$ad->price_type) d-none @endif">
        <label class="form-label">@lang('Margin')</label>
        <div class="input-group">
            <span class="input-group-text marginDecrement cursor-pointer"><i class="las la-minus"></i></span>
            <input type="number" step="any" class="form-control form--control" name="margin"
                value="{{ getAmount(old('margin', $priceMargin)) }}" required>
            <span class="input-group-text marginIncrement cursor-pointer"><i class="las la-plus"></i></span>
        </div>
    </div>
    <div class="form-group col-lg-12 price-input-wrapper">
        <label class="form-label">@lang('Price')</label>
        <div class="input-group coin-price">
            <input type="number" step="any" class="form-control form--control" name="price"
                value="{{ getAmount(old('price', $price)) }}" required @readonly(@$ad->price_type == Status::P2P_AD_PRICE_TYPE_MARGIN)>
        </div>
        <span class="mt-2 fs-13"> 1 {{ __(@$ad->asset->symbol) }} = {{ getAmount($originalPrice) }}
            {{ __(@$ad->fiat->symbol) }} </span>
    </div>
    <div class="form-group col-lg-6">
        <label class="form-label">@lang('Minimum Amount')</label>
        <div class="input-group">
            <input type="number" step="any" class="form-control form--control" name="minimum_amount"
                value="{{ getAmount(old('minimum_amount', @$ad->minimum_amount)) }}" required>
            <span class="input-group-text">{{ __(@$ad->fiat->symbol) }}</span>
        </div>
    </div>
    <div class="form-group col-lg-6">
        <label class="form-label">@lang('Maximum Amount')</label>
        <div class="input-group">
            <input type="number" step="any" class="form-control form--control" name="maximum_amount"
                value="{{ getAmount(old('maximum_amount', @$ad->maximum_amount)) }}" required>
            <span class="input-group-text">{{ __(@$ad->fiat->symbol) }}</span>
        </div>
    </div>
</div>

<a href="{{ route('user.p2p.advertisement.create', $ad->id) . '?step=1' }}" class="btn btn--base outline">
    <i class="fas fa-chevron-left"></i> @lang('Previous')
</a>

<button type="submit" class="btn btn--base ms-2">
    @lang('Next') <i class="fas fa-chevron-right"></i>
</button>


@push('script')
    <script>
        "use strict";
        (function($) {

            $(`select[name=price_type]`).on(`change`, function(e) {
                const pricingTye = $(this).val();

                if (pricingTye == 1) {
                    $(`.margin-input-wrapper`).addClass(`d-none`);
                    $(`.price-input-wrapper`).removeClass(`col-lg-12`);
                    $(`.price-input-wrapper`).addClass(``);
                } else {
                    $(`.margin-input-wrapper`).removeClass(`d-none`);
                    $(`.price-input-wrapper`).addClass(`col-lg-12`);
                    $(`.price-input-wrapper`).removeClass(`col-lg-6`);
                }
                calculate();
            });

            function calculate() {
                const coinPrice = Number("{{ $coinPrice }}");
                const currencyPrice = Number("{{ $currencyPrice }}");
                const price = Number("{{ $price }}");

                if (!coinPrice || !currencyPrice || !price) return;

                const coinWrapper = $(`.coin-price`);
                const priceType = Number($("select[name=price_type]").val());
                
                if (priceType == 1) {
                    const price = Number("{{ $price }}");
                    $(`input[name=price]`).val(getAmount(price));
                    $(`input[name=price]`).attr('readonly', false);
                } else {
                    const margin = Number($("input[name=margin]").val());
                    const price       = Number("{{ $originalPrice }}");
                    const modifyPrice = (price / 100) * margin;

                    $(`input[name=margin]`).val(`${margin}`);
                    $(`input[name=price]`).val(getAmount(modifyPrice));
                    $(`input[name=price]`).attr('readonly', true);
                }
            };

            $('.marginDecrement').on('click', function(e) {
                let value = Number($(`input[name=margin]`).val());
                if (value <= 1) return;
                value = value - 1;
                $(`input[name=margin]`).val(value);
                calculate();
            });

            $('.marginIncrement').on('click', function(e) {
                let value = Number($(`input[name=margin]`).val());
                value = value + 1;
                $(`input[name=margin]`).val(value);
                calculate();
            });

            $('input[name=margin]').on('input', function(e) {
                let value = Number($(this).val());
                if (value < 0) $(this).val(0);
                calculate();
            });

            
            @if(session()->has('EXTERNAL_REDIRECT'))
                const url="{{session()->get('EXTERNAL_REDIRECT')[0]}}";
                "{{session()->forget('EXTERNAL_REDIRECT')}}";
                setTimeout(() => {
                    window.open(url);
                },2000);
            @endif

        })(jQuery);
    </script>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
   
        .select2-container--default .select2-selection--multiple {
            padding: 10px;
            background-color: transparent;
            border-color: hsl(var(--white)/0.14);
            color: hsl(var(--body-color));
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: hsl(var(--base));
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: hsl(var(--base)/0.5);
            color: #fff;
            padding: 5px 2px;
            border: unset;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }

    </style>
@endpush
