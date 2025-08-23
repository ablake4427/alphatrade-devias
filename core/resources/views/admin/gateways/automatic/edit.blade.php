@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.gateways.top_bar')
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.gateway.automatic.update', $gateway->code) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="alias" value="{{ $gateway->alias }}">
                    <input type="hidden" name="description" value="{{ $gateway->description }}">


                    <div class="card-body">
                        <div class="payment-method-item block-item">
                            <div class="row align-items-center gy-1">
                                <div class="col-lg-8 col-sm-6">
                                    <h3>{{ __($gateway->name) }}</h3>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    @if (count($supportedCurrencies) > 0)
                                        <div class="input-group d-flex flex-nowrap justify-content-sm-end">
                                            <select class="newCurrencyVal ">
                                                <option value="">@lang('Select currency')</option>
                                                @forelse($supportedCurrencies as $currency => $symbol)
                                                    <option value="{{ $currency }}" data-symbol="{{ $symbol }}">{{ __($currency) }} </option>
                                                @empty
                                                    <option value="">@lang('No available currency support')</option>
                                                @endforelse
                                            </select>
                                            <button type="button" class="btn btn--primary input-group-text newCurrencyBtn" data-crypto="{{ $gateway->crypto }}" data-name="{{ $gateway->name }}">@lang('Add new')</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="gateway-body ps-0">
                                <div class="gateway-content ps-0">
                                    @if ($gateway->code < 1000 && $gateway->extra)
                                        <div class="payment-method-body mt-2">
                                            <h4 class="mb-3">@lang('Configurations')</h4>
                                            <div class="row">
                                                @foreach ($gateway->extra as $key => $param)
                                                    <div class="form-group col-lg-6">
                                                        <label>{{ __(@$param->title) }}</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" value="{{ route($param->value) }}" readonly />
                                                            <button type="button" class="copyInput input-group-text" title="@lang('Copy')"><i
                                                                   class="fa fa-copy"></i></button>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div class="payment-method-body mt-2">
                                        <h4 class="mb-3">@lang('Global Setting for') {{ __($gateway->name) }}</h4>
                                        <div class="row">
                                            @foreach ($parameters->where('global', true) as $key => $param)
                                                <div class="form-group col-lg-6">
                                                    <label>{{ __(@$param->title) }}</label>
                                                    <input type="text" class="form-control" name="global[{{ $key }}]"
                                                           value="{{ @$param->value }}" required />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- payment-method-item start -->

                        @isset($gateway->currencies)
                            @foreach ($gateway->currencies as $gatewayCurrency)
                                <input type="hidden" name="currency[{{ $currencyIndex }}][symbol]" value="{{ $gatewayCurrency->symbol }}">
                                <input type="hidden" name="currency[{{ $currencyIndex }}][currency]" value="{{ $gatewayCurrency->currency }}">

                                <div class="payment-method-item block-item child--item">
                                    <div class="payment-method-header">
                                        <div class="content w-100 ps-0">
                                            <div class="d-flex justify-content-between">
                                                <div class="form-group">
                                                    <h4 class="mb-3">{{ __($gateway->name) }} - {{ __($gatewayCurrency->currency) }}</h4>
                                                    <input type="text" class="form-control" name="currency[{{ $currencyIndex }}][name]"
                                                           value="{{ $gatewayCurrency->name }}" required />
                                                </div>
                                                <div class="remove-btn">
                                                    <button type="button" class="btn btn--danger confirmationBtn" data-question="@lang('Are you sure to delete this gateway currency?')"
                                                            data-action="{{ route('admin.gateway.automatic.remove', $gatewayCurrency->id) }}">
                                                        <i class="la la-trash-o me-2"></i>@lang('Remove')
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="payment-method-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="card border border--primary mt-2">
                                                    <h5 class="card-header bg--primary">@lang('Range')</h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Minimum Amount')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control"
                                                                       name="currency[{{ $currencyIndex }}][min_amount]"
                                                                       value="{{ getAmount($gatewayCurrency->min_amount) }}" required />
                                                                <div class="input-group-text">{{ __($gatewayCurrency->currency) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Maximum Amount')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control"
                                                                       name="currency[{{ $currencyIndex }}][max_amount]"
                                                                       value="{{ getAmount($gatewayCurrency->max_amount) }}" required />
                                                                <div class="input-group-text">{{ __($gatewayCurrency->currency) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="card border border--primary mt-2">
                                                    <h5 class="card-header bg--primary">@lang('Charge')</h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Fixed Charge')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control"
                                                                       name="currency[{{ $currencyIndex }}][fixed_charge]"
                                                                       value="{{ getAmount($gatewayCurrency->fixed_charge) }}" required />
                                                                <div class="input-group-text">{{ __($gatewayCurrency->currency) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Percent Charge')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control"
                                                                       name="currency[{{ $currencyIndex }}][percent_charge]"
                                                                       value="{{ getAmount($gatewayCurrency->percent_charge) }}" required />
                                                                <div class="input-group-text">%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($parameters->where('global', false)->count() != 0)
                                                @php
                                                    $globalParameters = json_decode($gatewayCurrency->gateway_parameter);
                                                @endphp
                                                <div class="col-lg-12">
                                                    <div class="card border--primary mt-4">
                                                        <h5 class="card-header bg--dark">@lang('Configuration')</h5>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach ($parameters->where('global', false) as $key => $param)
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ __($param->title) }}</label>
                                                                            <input type="text" class="form-control"
                                                                                   name="currency[{{ $currencyIndex }}][param][{{ $key }}]"
                                                                                   value="{{ $globalParameters->$key }}" required />
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php $currencyIndex++ @endphp
                            @endforeach
                        @endisset

                        <div class="payment-method-item child--item newMethodCurrency d-none">
                            <input disabled type="hidden" class="currency--symbol" name="currency[{{ $currencyIndex }}][symbol]"
                                   class="currencySymbol">
                            <input disabled type="hidden" class="currency--symbol" name="currency[{{ $currencyIndex }}][currency]"
                                   class="currencySymbol">
                            <div class="payment-method-header">

                                <div class="content w-100 ps-0">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-group">
                                            <h4 class="mb-3" id="payment_currency_name">@lang('Name')</h4>
                                            <input disabled type="text" class="form-control" name="currency[{{ $currencyIndex }}][name]"
                                                   required />
                                        </div>
                                        <div class="remove-btn">
                                            <button type="button" class="btn btn-danger newCurrencyRemove">
                                                <i class="la la-trash-o me-2"></i>@lang('Remove')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="payment-method-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6  col-xl-6">
                                        <div class="card border--primary mt-2">
                                            <h5 class="card-header bg--primary">@lang('Range')</h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>@lang('Minimum Amount')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text currency--symbol">{{ __(gs('cur_text')) }}</div>
                                                        <input disabled type="number" step="any" class="form-control"
                                                               name="currency[{{ $currencyIndex }}][min_amount]" required />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>@lang('Maximum Amount')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text currency--symbol">{{ __(gs('cur_text')) }}</div>
                                                        <input disabled type="number" step="any" class="form-control"
                                                               name="currency[{{ $currencyIndex }}][max_amount]" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6  col-xl-6">
                                        <div class="card border--primary mt-2">
                                            <h5 class="card-header bg--primary">@lang('Charge')</h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>@lang('Fixed Charge')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text currency--symbol">{{ __(gs('cur_text')) }}</div>
                                                        <input disabled type="number" step="any" class="form-control"
                                                               name="currency[{{ $currencyIndex }}][fixed_charge]" required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>@lang('Percent Charge')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">%</div>
                                                        <input disabled type="number" step="any" class="form-control"
                                                               name="currency[{{ $currencyIndex }}][percent_charge]" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($parameters->where('global', false)->count() != 0)
                                        <div class="col-lg-12">
                                            <div class="card border--primary mt-4">
                                                <h5 class="card-header bg--dark">@lang('Configuration')</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($parameters->where('global', false) as $key => $param)
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>{{ __($param->title) }}</label>
                                                                    <input disabled type="text" class="form-control"
                                                                           name="currency[{{ $currencyIndex }}][param][{{ $key }}]" required />
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <x-confirmation-modal />

@endsection





@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.gateway.automatic.index') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.newCurrencyBtn').on('click', function() {

                var form = $('.newMethodCurrency');
                var getCurrencySelected = $('.newCurrencyVal').find(':selected').val();
                var currency = $(this).data('crypto') == 1 ? 'USD' : `${getCurrencySelected}`;
                if (!getCurrencySelected) return;

                form.find('input').removeAttr('disabled');
                var symbol = $('.newCurrencyVal').find(':selected').data('symbol');

                form.find('.currency--symbol').text(getCurrencySelected);
                form.find('.currency--symbol').val(getCurrencySelected);
                form.find('.currencyText').val(getCurrencySelected);
                $('#payment_currency_name').text(`${$(this).data('name')} - ${getCurrencySelected}`);
                form.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $('html, body').height()
                }, 'slow');

                $('.newCurrencyRemove').on('click', function() {
                    form.find('input').val('');
                    form.remove();
                });
            });

            $('.symbl').on('input', function() {
                var curText = $(this).data('crypto') == 1 ? 'USD' : $(this).val();
                $(this).parents('.payment-method-body').find('.currency--symbol').text(curText);
            });

            $('.copyInput').on('click', function(e) {
                var copybtn = $(this);
                var input = copybtn.closest('.input-group').find('input');
                if (input && input.select) {
                    input.select();
                    try {
                        document.execCommand('SelectAll')
                        document.execCommand('Copy', false, null);
                        input.blur();
                        notify('success', `Copied: ${copybtn.closest('.input-group').find('input').val()}`);
                    } catch (err) {
                        alert('Please press Ctrl/Cmd + C to copy');
                    }
                }
            });

        })(jQuery);
    </script>
@endpush
