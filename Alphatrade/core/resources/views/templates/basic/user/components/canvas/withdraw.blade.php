@php
    $meta = (object) $meta;
    $withdrawMethods = $meta->withdrawMethods;
    $singleCurrency = @$meta->single_currency ?? null;
    $walletType = @$meta->wallet_type ?? null;
@endphp

<div class="offcanvas offcanvas-end p-3 p-md-5" tabindex="-1" id="withdraw-offcanvas">
    <div class="offcanvas-header">
        <h4 class="mb-0 fs-18 offcanvas-title">
            @lang('Withdraw')
        </h4>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa fa-times-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('user.withdraw.money') }}" method="post" class="@if ($withdrawMethods->count() <= 0) d-none @endif">
            @csrf
            <input type="hidden" name="currency" value="{{ @$singleCurrency->symbol }}">
            <div class="form-group">
                <label class="form-label">@lang('Amount')</label>
                <div class="input-group">
                    <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                    <span class="input-group-text text-white withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                </div>
            </div>
            <div class="form-group position-relative">
                <label class="form-label">@lang('Method')</label>
                <select class="form-control form--control form-select select2" name="method_code" required
                    data-minimum-results-for-search="-1">
                    @if (@$singleCurrency)
                        @foreach ($withdrawMethods as $method)
                            <option value="{{ $method->id }}" data-resource='@json($method)'
                                data-image-src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image) }}">
                                {{ __($method->name) }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            @if ($walletType)
                <input type="hidden" name="wallet_type" value="{{ $walletType }}">
            @else
                <div class="form-group">
                    <label class="form-label">@lang('Wallet Type')</label>
                    <select class="form-control form--control form-select canvas-select2" name="wallet_type" required data-minimum-results-for-search="-1">
                        <option value="" selected disabled>@lang('Select One')</option>
                        @foreach (gs('wallet_types') as $k => $walletType)
                            @if (checkWalletConfiguration($k, 'withdraw'))
                                <option value="{{ $k }}" for_fiat="{{ @$walletType->for_fiat }}"
                                    for_crypto="{{ @$walletType->for_crypto }}">{{ __($walletType->title) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="mt-3 preview-details d-none">
                <ul class="list-group text-center list-group-flush">
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Limit')</span>
                        <span>
                            <span class="min fw-bold">0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span> -
                            <span class="max fw-bold">0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Charge')</span>
                        <span>
                            <span class="charge fw-bold">0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Receivable')</span>
                        <span>
                            <span class="receivable fw-bold"> 0</span>
                            <span class="withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
                        </span>
                    </li>
                </ul>
            </div>
            <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
        </form>
        <div class="p-5 text-center empty-gateway @if ($withdrawMethods->count() > 0) d-none @endif">
            <img src="{{ asset('assets/images/extra_images/no_money.png') }}" alt="">
            <h6 class="mt-3">
                @lang('No withdraw method available for ')
                <span class="text--base withdraw-cur-sym">{{ __(@$singleCurrency->symbol) }}</span>
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
                $('.withdraw-form').on('submit', function(e) {
                    e.preventDefault();
                    let currency = $(`.withdraw-form select[name=currency]`).val();
                    let amount = $(`.withdraw-form input[name=amount]`).val();

                    if (!currency) {
                        notify('error', "@lang('Currency field is required')");
                        return false;
                    }

                    if (!amount) {
                        notify('error', "@lang('Amount field is required')");
                        return false;
                    }

                    let withdrawMethods = @json($withdrawMethods);
                    let currencyWithdrawMethods = withdrawMethods.filter(ele => ele.currency == currency);


                    if (currencyWithdrawMethods && currencyWithdrawMethods.length > 0) {
                        let methodsOptions = "<option selected disabled> @lang('Select Method')</option>";

                        $.each(currencyWithdrawMethods, function(i, currencyWithdrawMethod) {
                            methodsOptions += `<option value="${currencyWithdrawMethod.id}" data-resource='${JSON.stringify(currencyWithdrawMethod)}'>
                                    ${currencyWithdrawMethod.name}
                                </option>
                            `;
                        });

                        $("#withdraw-offcanvas").find('select[name=method_code]').html(methodsOptions);
                        $('#withdraw-offcanvas input[name=currency]').val(currency);
                        $('#withdraw-offcanvas input[name=amount]').val(amount);

                        $("#withdraw-offcanvas").find(".empty-gateway").addClass('d-none');
                        $("#withdraw-offcanvas").find("form").removeClass('d-none');

                    } else {
                        $("#withdraw-offcanvas").find(".empty-gateway").removeClass('d-none');
                        $("#withdraw-offcanvas").find("form").addClass('d-none');
                    }


                    $('#withdraw-offcanvas .withdraw-cur-sym').text(currency);
                    var myOffcanvas = document.getElementById('withdraw-offcanvas');
                    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas).show();

                });
            @endif

            $('#withdraw-offcanvas').on('change', 'select[name=method_code]', function() {

                if (!$(this).val()) {
                    $('#withdraw-offcanvas .preview-details').addClass('d-none');
                    return false;
                }

                var resource = $('select[name=method_code] option:selected').data('resource');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);

                $('#withdraw-offcanvas  .min').text(getAmount(resource.min_limit));
                $('#withdraw-offcanvas  .max').text(getAmount(resource.max_limit));

                var amount = parseFloat($('#withdraw-offcanvas input[name=amount]').val());

                if (!amount) {
                    $('#withdraw-offcanvas .preview-details').addClass('d-none');
                    return false;
                }

                $('#withdraw-offcanvas .preview-details').removeClass('d-none');

                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100));
                $('#withdraw-offcanvas  .charge').text(getAmount(charge));

                var receivable = parseFloat((parseFloat(amount) - parseFloat(charge)));

                $('#withdraw-offcanvas .receivable').text(getAmount(receivable));

                $('#withdraw-offcanvas .base-currency').text(resource.currency);
                $('#withdraw-offcanvas .method_currency').text(resource.currency);
                $('#withdraw-offcanvas input[name=amount]').on('input');
            });

            $('#withdraw-offcanvas input[name=amount]').on('input', function() {
                var data = $('select[name=method_code]').change();
                $('#withdraw-offcanvas .amount').text(parseFloat($(this).val()).toFixed(2));
            });

        })(jQuery);
    </script>
@endpush
