@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-body">
                    <form action="{{ route('admin.coin.pair.save', @$coinPair->id) }}" method="POST" enctype="multipart/form-data" class="pair-form">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group position-relative" id="currency_list_wrapper">
                                        <label>@lang('Coin')</label>
                                        <x-currency-list name="coin" :type="Status::CRYPTO_CURRENCY" :disabled="@$coinPair ? true : false" />
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 position-relative" id="market-list">
                                    <label>@lang('Market')</label>
                                    <select class="form-control" required name="market" @disabled(@$coinPair)>
                                        <option selected disabled>@lang('Select One')</option>
                                        @php
                                            $selecetdMarketId = old('market', @$coinPair->market_id) ? old('market', @$coinPair->market_id) : request()->market_id ?? '';
                                        @endphp
                                        @foreach ($markets as $market)
                                            <option value="{{ $market->id }}" data-cur-sym="{{ $market->currency->symbol }}"
                                                    @selected($market->id == $selecetdMarketId)>
                                                {{ __($market->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>@lang('Minimum Buy Amount')</label>
                                    <small title="@lang('The minimum buy amount is the smallest quantity required to buy coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="minimum_buy_amount"
                                               value="{{ old('minimum_buy_amount', @$coinPair ? getAmount(@$coinPair->minimum_buy_amount) : '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>@lang('Maximum Buy Amount')</label>
                                    <small title="@lang('The maximum buy amount is the highest quantity of coin to buy on this pair. Use -1 for no maximum limit.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="maximum_buy_amount"
                                               value="{{ old('maximum_buy_amount', @$coinPair ? getAmount(@$coinPair->maximum_buy_amount) : '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>@lang('Minimum Sell Amount')</label>
                                    <small title="@lang('The minimum sell amount is the smallest quantity required to sell coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="minimum_sell_amount"
                                               value="{{ old('minimum_sell_amount', @$coinPair ? getAmount(@$coinPair->minimum_sell_amount) : '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>@lang('Maximum Sell Amount')</label>
                                    <small title="@lang('The maximum sell amount is the highest quantity of coin to sell on this pair. Use -1 for no maximum limit.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="maximum_sell_amount"
                                               value="{{ old('maximum_sell_amount', @$coinPair ? getAmount(@$coinPair->maximum_sell_amount) : '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4">
                                    <label>@lang('Percent Charge For Buy')</label>
                                    <small title="@lang('Set applicable percent charge for the buy of coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="percent_charge_for_buy"
                                               value="{{ old('percent_charge_for_buy', @$coinPair ? getAmount(@$coinPair->percent_charge_for_buy) : '') }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4">
                                    <label>@lang('Percent Charge For Sell')</label>
                                    <small title="@lang('Set applicable percent charge for the sell of coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="percent_charge_for_sell"
                                               value="{{ old('percent_charge_for_sell', @$coinPair ? getAmount(@$coinPair->percent_charge_for_sell) : '') }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label>@lang('Listed Market')</label>
                                    <small title="@lang('Set the listed market name where this coin pair is listed.')"><i class="las la-info-circle"></i></small>
                                    <input type="text" class="form-control" name="listed_market_name"
                                           value="{{ old('listed_market_name', @$coinPair->listed_market_name) }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="inputName">@lang('Choose Trade Type')<span class="text--danger">*</span></label>
                                    <select class="form-control" name="type" required>
                                        <option value="1" @selected(@$coinPair->type == Status::SPOT_TRADE)>@lang('Spot Trade')</option>
                                        <option value="2" @selected(@$coinPair->type == Status::BINARY_TRADE)>@lang('Binary Trade')</option>
                                        <option value="3" @selected(@$coinPair->type == Status::BOTH_TRADE)>@lang('Both Trade')</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="inputName">@lang('Default Pair')</label>
                                    <input type="checkbox" @checked(@$coinPair->is_default) data-width="100%" data-height="40px" data-onstyle="-success"
                                           data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('YES')" data-off="@lang('NO')"
                                           name="is_default">
                                </div>
                            </div>

                            <h6 class="my-3">@lang('Binary Trade')</h6>
                            <div class="row">
                                <div class="form-group col-xl-3 col-sm-6">
                                    <label>@lang('Minimum Trade Amount')</label>
                                    <small title="@lang('The minimum binary trade amount is the smallest quantity required to buy coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="min_binary_trade_amount"
                                               value="{{ old('min_binary_trade_amount', @$coinPair ? getAmount(@$coinPair->min_binary_trade_amount) : '') }}" >
                                    </div>
                                </div>
                                <div class="form-group col-xl-3 col-sm-6">
                                    <label>@lang('Maximum Trade Amount')</label>
                                    <small title="@lang('The maximum binary trade amount is the highest quantity of coin to buy on this pair. Use -1 for no maximum limit.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="max_binary_trade_amount"
                                               value="{{ old('max_binary_trade_amount', @$coinPair ? getAmount(@$coinPair->max_binary_trade_amount) : '') }}" >
                                    </div>
                                </div>
                                <div class="form-group col-xl-3 col-sm-6">
                                    <label>@lang('Increment Amount')</label>
                                    <div class="input-group appnend-coin-sym">
                                        <input type="number" step="any" class="form-control" name="binary_increment_amount"
                                               value="{{ old('binary_increment_amount', @$coinPair ? getAmount(@$coinPair->binary_increment_amount) : '') }}" >
                                    </div>
                                </div>
                                <div class="form-group col-xl-3 col-sm-6">
                                    <label>@lang('Profit of Binary Trade')</label>
                                    <small title="@lang('Set applicable percent charge for the binary trade of coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="binary_trade_profit"
                                               value="{{ old('binary_trade_profit', @$coinPair ? getAmount(@$coinPair->binary_trade_profit) : '') }}" >
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="my-3 d-flex justify-content-between flex-wrap align-items-center">
                                <h6>@lang('Binary Trade Duration')</h6>
                                <button type="button" class="btn btn-outline--primary addDuration"><i class="las la-plus"></i> @lang('Add More')</button>
                            </div>

                            <div class="row duration-area">
                                <div class="form-group col-sm-4">
                                    <label>@lang('Duration')</label>
                                    <div class="input-group">
                                        <input type="number" name="binary_trade_duration[]" min="1" value="{{ @$coinPair->binary_trade_duration[0] }}" placeholder="@lang('Duration in seconds')" class="form-control">
                                        <span class="input-group-text">@lang('seconds')</span>
                                    </div>
                                </div>
                                @foreach (@$coinPair->binary_trade_duration ?? [] as $duration)
                                    @if ($loop->first)
                                        @continue
                                    @endif
                                    <div class="form-group col-sm-4">
                                        <label>@lang('Duration')</label>
                                        <div class="input-group">
                                            <input type="number" name="binary_trade_duration[]" min="1" value="{{ $duration }}" placeholder="@lang('Duration in seconds')" class="form-control">
                                            <span class="input-group-text">@lang('seconds')</span>
                                            <button type="button" class="input-group-text bg--red text-white border-0 removeDuration"><i class="las la-times"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.coin.pair.list') }}" class="btn btn-outline--primary btn-sm">
        <i class="las la-list"></i>@lang('Coin Pair List')
    </a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            @if (@$coinPair)
                let newOption = new Option("{{ @$coinPair->coin->symbol }}-{{ @$coinPair->coin->name }}", "{{ @$coinPair->coin_id }}", true, true);
                $('#currency_list').append(newOption).trigger('change');
                $("select[name=coin]").attr('readonly', true);
                coinSym("{{ @$coinPair->coin->symbol }}");
            @endif

            $('select[name=coin]').on('change', function(e) {
                let coin = $(this).find('option:selected').text().split('-');
                let symbol = coin.pop();
                coinSym(symbol)
            });

            function coinSym(coinSym) {
                $.each($('.appnend-coin-sym'), function(i, element) {
                    let symbolHtml = $(element).find('.input-group-text');
                    if (symbolHtml.length) {
                        symbolHtml.text(coinSym)
                    } else {
                        $(element).append(`<span class="input-group-text">${coinSym}</span>`)
                    }
                });
            };

            $("select[name=market]").select2({
                dropdownParent: $("#market-list")
            });

            $('.addDuration').on('click', function(e) {
                e.preventDefault();
                var durationArea = $('.duration-area');
                var durationHtml = `<div class="form-group col-sm-4">
                                <label>@lang('Duration')<span class="text--danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="binary_trade_duration[]" min="1" placeholder="@lang('Duration in seconds')" class="form-control">
                                    <span class="input-group-text">@lang('seconds')</span>
                                    <button type="button" class="input-group-text bg--red text-white border-0 removeDuration"><i class="las la-times"></i></button>
                                </div>
                            </div>`;
                durationArea.append(durationHtml);
            });

            $(document).on('click', '.removeDuration', function(e) {
                e.preventDefault();
                $(this).closest('.form-group').remove();
            });


        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .select2-container {
            z-index: 97 !important;
        }
    </style>
@endpush
