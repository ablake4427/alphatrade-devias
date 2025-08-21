@php
    $meta = (object) $meta;
    $gateways = $meta->gateways;
    $singleCurrency = @$meta->single_currency ?? null;
    $walletType = @$meta->wallet_type ?? null;
@endphp
<div class="offcanvas offcanvas-end p-5" tabindex="-1" id="deposit-canvas" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h4 class="mb-0 fs-18 offcanvas-title">
            @lang('Deposit Preview')
        </h4>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa fa-times-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('user.deposit.insert') }}" method="post" class="@if ($gateways->count() <= 0) d-none @endif">
            @csrf
            <input type="hidden" name="currency" value="{{ @$singleCurrency->symbol }}">
            <div class="form-group">
                <label class="form-label">@lang('Amount')</label>
                <div class="input-group">
                    <input type="number" step="any" class="form--control form-control" name="amount" required>
                    <span class="input-group-text text-white deposit-currency-symbol">{{ __(@$singleCurrency->symbol) }}</span>
                </div>
            </div>
            <div class="form-group position-relative">
                <label class="form-label">@lang('Gateway')</label>
                <select class="form-control form--control form-select select2" name="gateway" required
                    data-minimum-results-for-search="-1">
                    @if (@$singleCurrency)
                        @foreach ($gateways as $gateway)
                            <option value="{{ $gateway->method_code }}" data-gateway='@json($gateway)'
                                data-image-src="{{ getImage(getFilePath('gateway') . '/' . @$gateway->method->image) }}">
                                {{ __($gateway->name) }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            @if ($walletType)
                <input type="hidden" name="wallet_type" value="{{ $walletType }}">
            @else
                <div class="form-group position-relative">
                    <label class="form-label">@lang('Wallet Type')</label>
                    <select class="form-control form--control form-select canvas-select2" data-minimum-results-for-search="-1" name="wallet_type"
                        required>
                        <option value="" selected disabled>@lang('Select One')</option>
                        @foreach (gs('wallet_types') as $k => $walletType)
                            @if (checkWalletConfiguration($k, 'deposit'))
                                <option value="{{ $k }}">{{ __($walletType->title) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="form-group preview-details d-none">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Limit')</span>
                        <span>
                            <span class="min fw-bold">0</span>
                            - <span class="max fw-bold">0</span>
                            <span class="deposit-currency-symbol">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Charge')</span>
                        <span>
                            <span class="charge fw-bold">0</span>
                            <span class="deposit-currency-symbol">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span> @lang('Payable')</span>
                        <span>
                            <span class="payable fw-bold">0</span>
                            <span class="deposit-currency-symbol">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                    </li>
                </ul>
            </div>
            <button class="deposit__button btn btn--base w-100" type="submit"> @lang('Submit') </button>
        </form>
        <div class="p-5 text-center empty-gateway @if ($gateways->count() > 0) d-none @endif">
            <img src="{{ asset('assets/images/extra_images/no_money.png') }}">
            <h6 class="mt-3">
                @lang('No payment gateway available for ')
                <span class="text--base deposit-currency-symbol">{{ __(@$singleCurrency->symbol) }}</span>
                @lang('Currency')
            </h6>
        </div>
    </div>
</div>
@push('script')
    <script>
        "use strict";
        (function($) {

            @if (!@$singleCurrency)
                $('.deposit-form').on('submit', function(e) {
                    e.preventDefault();
                    let currency = $(`.deposit-form select[name=currency]`).val();

                    $(`select[name=wallet_type]`).val();


                    let amount = $(`.deposit-form input[name=amount]`).val();

                    if (!currency) {
                        notify('error', "@lang('Currency field is required')");
                        return false;
                    }

                    if (!amount) {
                        notify('error', "@lang('Amount field is required')");
                        return false;
                    }

                    let gateways         = @json($gateways);
                    let currencyGateways = gateways.filter(ele => ele.currency == currency);
                    

                    if (currencyGateways && currencyGateways.length) {

                        let gatewaysOption = "<option selected disabled> @lang('Select Payment Gateway')</option>";
                        $.each(currencyGateways, function(i, currencyGateway) {
                            gatewaysOption += `
                        <option value="${currencyGateway.method_code}"  data-gateway='${JSON.stringify(currencyGateway)}' >
                                ${currencyGateway.name}
                        </option>`;
                        });

                        $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
                        $("#deposit-canvas").find("form").removeClass('d-none');

                        $("#deposit-canvas").find('select[name=gateway]').html(gatewaysOption);
                        $("#deposit-canvas").find('.deposit-currency-symbol').text(currency);
                        $("#deposit-canvas").find('input[name=currency]').val(currency);
                        $("#deposit-canvas").find(`input[name=amount]`).val(amount);
                    } else {
                        $("#deposit-canvas").find(".empty-gateway").removeClass('d-none');
                        $("#deposit-canvas").find("form").addClass('d-none');
                    }


                    $("#deposit-canvas").find('.deposit-currency-symbol').text(currency);

                    var myOffcanvas = document.getElementById('deposit-canvas');
                    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas).show();
                });
            @endif


            $('#deposit-canvas').on('change', 'select[name=gateway]', function() {

                if (!$(this).val()) {
                    $('#deposit-canvas .preview-details').addClass('d-none');
                    return false;
                }

                var resource = $('select[name=gateway] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);

                $('#deposit-canvas .min').text(getAmount(resource.min_amount));
                $('#deposit-canvas .max').text(getAmount(resource.max_amount));

                var amount = parseFloat($('#deposit-canvas input[name=amount]').val());
                if (!amount) {
                    $('#deposit-canvas .preview-details').addClass('d-none');
                    return false;
                }

                $('#deposit-canvas .preview-details').removeClass('d-none');

                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100));
                var payable = parseFloat((parseFloat(amount) + parseFloat(charge)));

                $('#deposit-canvas .charge').text(getAmount(charge));
                $('#deposit-canvas .payable').text(getAmount(payable));

                $('#deposit-canvas .method_currency').text(resource.currency);
                $('#deposit-canvas input[name=amount]').on('input');
            });

            $('#deposit-canvas').on('input', 'input[name=amount]', function() {
                var data = $('#deposit-canvas select[name=gateway]').change();
                $('#deposit-canvas .amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
    </script>
@endpush
