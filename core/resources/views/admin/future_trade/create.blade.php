@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-body">
                    <form action="{{ route('admin.future.trade.config.save', isset($futureTradeConfig) ? $futureTradeConfig->id : '') }}" method="POST" enctype="multipart/form-data" class="pair-form">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @if (!isset($futureTradeConfig))
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="form-group position-relative" id="currency_list_wrapper">
                                            <label>@lang('Coin Pair')</label>
                                            <select name="coin_pair_id" class="form-control select2">
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($coinPairs as $coinPair)
                                                    <option value="{{ $coinPair->id }}" data-coin_symbol="{{ $coinPair->coin->symbol }}">{{ $coinPair->symbol }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group col-xl-4 col-sm-6">
                                    <label>@lang('Minimum Buy Amount')</label>
                                    <small title="@lang('The minimum buy amount is the smallest quantity required to buy coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="min_buy_amount" value="{{ old('min_buy_amount', isset($futureTradeConfig) ? getAmount($futureTradeConfig->min_buy_amount, 8) : '') }}" required>
                                            <span class="input-group-text coinSymbol">{{ @$futureTradeConfig->coinPair->coin->symbol }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6">
                                    <label>@lang('Maximum Buy Amount')</label>
                                    <small title="@lang('The maximum buy amount is the highest quantity of coin to buy on this pair. Use -1 for no maximum limit.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="max_buy_amount" value="{{ old('max_buy_amount', isset($futureTradeConfig) ? getAmount($futureTradeConfig->max_buy_amount) : '') }}" required>
                                        <span class="input-group-text coinSymbol">{{ @$futureTradeConfig->coinPair->coin->symbol }}</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6">
                                    <label>@lang('Minimum Sell Amount')</label>
                                    <small title="@lang('The minimum sell amount is the smallest quantity required to sell coin on this pair.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="min_sell_amount" value="{{ old('min_sell_amount', isset($futureTradeConfig) ? getAmount($futureTradeConfig->min_sell_amount, 8) : '') }}" required>
                                        <span class="input-group-text coinSymbol">{{ @$futureTradeConfig->coinPair->coin->symbol }}</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6">
                                    <label>@lang('Maximum Sell Amount')</label>
                                    <small title="@lang('The maximum sell amount is the highest quantity of coin to sell on this pair. Use -1 for no maximum limit.')"><i class="las la-info-circle"></i></small>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="max_sell_amount" value="{{ old('max_sell_amount', isset($futureTradeConfig) ? getAmount($futureTradeConfig->max_sell_amount) : '') }}" required>
                                        <span class="input-group-text coinSymbol">{{ @$futureTradeConfig->coinPair->coin->symbol }}</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6 col-xl-4 col-sm-6">
                                    <label>@lang('Buy/Long Charge')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="buy_charge" value="{{ old('buy_charge', isset($futureTradeConfig) ? getAmount($futureTradeConfig->buy_charge) : '') }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6 col-xl-4 col-sm-6">
                                    <label>@lang('Sell/Short Charge')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="sell_charge" value="{{ old('sell_charge', isset($futureTradeConfig) ? getAmount($futureTradeConfig->sell_charge) : '') }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6 col-xl-4 col-sm-6">
                                    <label>@lang('Leverage Up To')</label>
                                    <div class="input-group">
                                        <input type="number" step="1" name="leverage" class="form-control" value="{{ old('leverage', isset($futureTradeConfig) ? $futureTradeConfig->leverage : '') }}" required>
                                        <span class="input-group-text">@lang('X')</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6 col-xl-4 col-sm-6">
                                    <label>@lang('Maintenance Margin Rate')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="maintenance_margin_rate" class="form-control" value="{{ old('maintenance_margin_rate', isset($futureTradeConfig) ? getAmount($futureTradeConfig->maintenance_margin_rate) : '') }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-sm-6 col-xl-4 col-sm-6">
                                    <label for="inputName">@lang('Default Pair')</label>
                                    <input type="checkbox" @checked(isset($futureTradeConfig) ? $futureTradeConfig->is_default : '') data-width="100%" data-height="40px" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('YES')" data-off="@lang('NO')" name="is_default">
                                </div>
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
    <a href="{{ route('admin.future.trade.config.pairs') }}" class="btn btn-outline--primary btn-sm">
        <i class="las la-list"></i>@lang('Future Trade Config')
    </a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $('[name=coin_pair_id]').on('change', function() {
                let coinSymbol = $(this).find(':selected').data('coin_symbol');                
                $('.coinSymbol').text(coinSymbol);
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
