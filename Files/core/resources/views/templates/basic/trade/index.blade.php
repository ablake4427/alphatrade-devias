@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="trading-section bg-color py-60">
        <div class="container custom--container">
            <div class="row">
                <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair, 'screen' => 'small']" />
                <div class="col-xl-9">
                    <div class="row gy-2">
                        <div class="col-xl-4 pe-lg-1">
                            <x-flexible-view :view="$activeTemplate . 'trade.order_book'" :meta="['pair' => $pair, 'screen' => 'big']" />
                        </div>
                        <div class="col-xl-8 col-md-7">
                            <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair]" />
                            <x-flexible-view :view="$activeTemplate . 'trade.tab'" :meta="['screen' => 'small', 'markets' => $markets, 'pair' => $pair]" />
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'big',
                            ]" />
                            <div class="d-none d-md-block d-xl-none">
                                <div class="trading-bottom__tab">
                                    <x-flexible-view :view="$activeTemplate . 'trade.tab'" :meta="['screen' => 'medium', 'markets' => $markets, 'pair' => $pair]" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 d-xl-none d-block p-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'medium',
                            ]" />
                        </div>
                        <div class="col-sm-12 mt-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.my_order'" :meta="['pair' => $pair]" />
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 ps-lg-1">
                    <div class="trading-sidebar">
                        <x-flexible-view :view="$activeTemplate . 'trade.pair_list'" :meta="['markets' => $markets]" />
                        <x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair]" />
                    </div>
                </div>
                <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                    'pair' => $pair,
                    'marketCurrencyWallet' => $marketCurrencyWallet,
                    'coinWallet' => $coinWallet,
                    'screen' => 'small',
                ]" />
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end p-5" tabindex="-1" id="deposit-canvas" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-header">
            <span class="fs-18">
                @lang('Deposit Money')
            </span>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-times-circle"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @auth
                <form action="{{ route('user.deposit.insert') }}" method="post">
                    @csrf
                    <input type="hidden" name="currency" class="deposit-currency-symbol">
                    <input type="hidden" value="spot" name="wallet_type">
                    <div class="form-group">
                        <label class="form-label">@lang('Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form--control form-control" name="amount" required>
                            <span class="input-group-text deposit-currency-symbol"></span>
                        </div>
                    </div>
                    <div class="form-group position-relative">
                        <label class="form-label">@lang('Payment Gateway')</label>
                        <select class="form-control form--control form-select select2" name="gateway" required data-minimum-results-for-search="-1">
                        </select>
                    </div>
                    <div class="form-group preview-details d-none">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Limit')</span>
                                <span>
                                    <span class="min fw-bold">0</span>
                                    - <span class="max fw-bold">0</span>
                                    <span class="deposit-currency-symbol"></span>
                                </span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('Charge')</span>
                                <span>
                                    <span class="charge fw-bold">0</span>
                                    <span class="deposit-currency-symbol"></span>
                                </span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span> @lang('Payable')</span>
                                <span>
                                    <span class="payable fw-bold">0</span>
                                    <span class="deposit-currency-symbol"></span>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <button class="deposit__button btn btn--base w-100" type="submit"> @lang('Submit') </button>
                </form>
                <div class="p-5 text-center empty-gateway">
                    <img src="{{ asset('assets/images/extra_images/no_money.png') }}" alt="">
                    <span class="mt-3 fs-14">
                        @lang('No payment gateway available for ')
                        <span class="text--base deposit-currency-symbol"></span>
                        @lang('Currency')
                    </span>
                </div>
            @else
                <div class="p-5 text-center d-flex flex-column align-items-center justify-content-center h-100">
                    <img src="{{ asset('assets/images/extra_images/user.png') }}">
                    <span class="fs-12">@lang('Login required for deposit money')</span>
                    <div class="mt-3">
                        <a class="fs-12 text--base" href="{{ route('user.login') }}">@lang('Login')</a>
                        <span>@lang('or')</span>
                        <a class="fs-12 text--base" href="{{ route('user.register') }}">@lang('Register')</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script')
    <script>
        "use strict";



        $.each($('.select2'), function(index, element) {
            $(element).select2({
                dropdownParent: $(this).closest('.position-relative')
            });
        });

        $('.new--deposit').on('click', function(e) {

            @auth
            let currency = $(this).data('currency');
            let gateways = @json($gateways);
            let currencyGateways = gateways.filter(ele => ele.currency == currency);
            

            if (currencyGateways && currencyGateways.length > 0) {
                let gatewaysOption = "";
                $.each(currencyGateways, function(i, currencyGateway) {
                    gatewaysOption += `<option value="${currencyGateway.method_code}"  data-gateway='${JSON.stringify(currencyGateway)}' >
                            ${currencyGateway.name}
                        </option>`;
                });
                $("#deposit-canvas").find('select[name=gateway]').html(gatewaysOption);
                $("#deposit-canvas").find('.deposit-currency-symbol').val(currency);

                $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
                $("#deposit-canvas").find("form").removeClass('d-none');
            } else {
                $("#deposit-canvas").find(".empty-gateway").removeClass('d-none');
                $("#deposit-canvas").find("form").addClass('d-none');
            }
            $("#deposit-canvas").find('.deposit-currency-symbol').text(currency);
        @endauth
        var myOffcanvas = document.getElementById('deposit-canvas');
        var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas).show();
        });

        @auth
        $('#deposit-canvas').on('change', 'select[name=gateway]', function() {

            if (!$(this).val()) {
                $('#deposit-canvas .preview-details').addClass('d-none');
                return false;
            }

            var resource = $('select[name=gateway] option:selected').data('gateway');
            var fixed_charge = parseFloat(resource.fixed_charge);
            var percent_charge = parseFloat(resource.percent_charge);
            var rate = parseFloat(resource.rate);
            var amount = parseFloat($('#deposit-canvas input[name=amount]').val());

            $('#deposit-canvas .min').text(getAmount(resource.min_amount));
            $('#deposit-canvas .max').text(getAmount(resource.max_amount));


            if (!amount) {
                $('#deposit-canvas .preview-details').addClass('d-none');
                return false;
            }

            $('#deposit-canvas .preview-details').removeClass('d-none');

            var charge = parseFloat(fixed_charge + (amount * percent_charge / 100));
            var payable = parseFloat((parseFloat(amount) + parseFloat(charge)));

            $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
            $("#deposit-canvas").find("form").removeClass('d-none');

            $('#deposit-canvas .charge').text(getAmount(charge));
            $('#deposit-canvas .payable').text(getAmount(payable));

            $('#deposit-canvas .method_currency').text(resource.currency);
            $('#deposit-canvas input[name=amount]').on('input');

        });

        $('#deposit-canvas').on('input', 'input[name=amount]', function() {
            var data = $('#deposit-canvas select[name=gateway]').change();
            $('#deposit-canvas .amount').text(parseFloat($(this).val()).toFixed(2));
        });
        @endauth

        pusherConnection('market-data', marketChangeHtml);

        var swiper = new Swiper(".myswiper-two", {
            slidesPerView: 5,
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next-two",
                prevEl: ".swiper-button-prev-two",
            },
            breakpoints: {
                575: {
                    slidesPerView: 7,
                    spaceBetween: 0,
                },
                992: {
                    slidesPerView: 7,
                    spaceBetween: 0,
                },
            },
        });

        window.visit_pair = {
            selection: "{{ @$pair->marketData->id }}",
            symbol: "{{ @$pair->symbol }}",
            site_name: "{{ __(gs('site_name')) }}"
        };

        $('header').find(`.container`).addClass(`custom--container`);



    </script>
@endpush


@push('style')
    <style>
        .cookies-card {
            background-color: #181d20 !important;
            color: #93988f !important;
        }

        .has-mega-menu .mega-menu {
            background: #323b41 !important;
        }

        .select2-image {
            max-width: 35px;
        }


        /* ////////////////// select 2 //////////////// */
        .select2-dropdown {
            background-color: #09171a;
            border-color: hsl(var(--white)/0.14);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-radius: 6px;
            font-weight: 400;
            outline: none;
            width: 100%;
            padding: 10px;
            background-color: transparent;
            border-color: hsl(var(--white) / 0.2) !important;
            color: #fff !important;
            line-height: 1;
            margin: 10px 0px;
        }

        .select2-container--default .select2-selection--single {
            background-color: transparent !important;
            border-color: hsl(var(--white)/0.14);
            height: 52px;
            padding: 10px 0px;

        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: hsl(var(--body-color)) !important;
        }

        .select2-container .selection {
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 11px !important;
            right: 5px !important;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: hsl(var(--base-d-400)) !important;
        }

        @media (min-width:1200px) {
            .trading-bottom {
                min-height: calc(100% - 660px);
            }
        }
        .trading-header {
            gap: 10px;
        }
    </style>
@endpush
