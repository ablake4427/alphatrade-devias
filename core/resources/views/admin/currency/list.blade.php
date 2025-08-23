@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currencies as $currency)
                                    <tr>
                                        <td>
                                            <x-currency :currency=$currency />
                                        </td>
                                        <td>
                                            @if ($type == Status::CRYPTO_CURRENCY)
                                                {{ showAmount(@$currency->marketData->price ?? @$currency->rate) }}
                                            @else
                                                {{ showAmount(@$currency->rate) }}
                                            @endif
                                        </td>
                                        <td> @php echo $currency->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                    data-currency='@json($currency)' data-image="{{ $currency->image_url }}">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($currency->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this currency')?"
                                                        data-action="{{ route('admin.currency.status', $currency->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this currency')?"
                                                        data-action="{{ route('admin.currency.status', $currency->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($currencies->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($currencies) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div id="modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-lg-5">
                                <x-image-uploader class="w-100" type="currency" :required=false />
                            </div>
                            <div class="col-lg-7">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>@lang('Name')</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>@lang('Symbol')</label>
                                        <input type="text" class="form-control" name="symbol" value="{{ old('symbol') }}" required>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>@lang('Sign')</label>
                                        <input type="text" class="form-control" name="sign" value="{{ old('sign') }}">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>@lang('Price')</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" step="any" name="price" value="{{ old('price') }}"
                                                required>
                                            <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>@lang('P2P SN')</label>
                                        <input type="number" class="form-control" name="p2p_sn" value="{{ old('p2p_sn') }}">
                                    </div>
                                    @if ($type == Status::CRYPTO_CURRENCY)
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Highlight Coin')</label>
                                            <input type="checkbox" data-width="100%" data-height="40px" data-onstyle="-success" data-offstyle="-danger"
                                                data-bs-toggle="toggle" data-on="@lang('YES')" data-off="@lang('NO')"
                                                name="is_highlighted_coin">
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="import-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Import Crypto Currency')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.currency.import') }}" id="import-form" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="p-2 bg--info rounded">
                                @if ($type == Status::CRYPTO_CURRENCY)
                                    <p class="text-white">
                                        @lang("Cryptocurrency import from $currencyDataProvider->name. The start field represents the starting rank of the cryptocurrency to be imported. For example, if you enter 2, the import will start with the cryptocurrency ranked 2nd on $currencyDataProvider->name & limit field represents the maximum number of cryptocurrencies to be imported. Maximum 100 cryptocurrencies you will import at a time")
                                    </p>
                                @else
                                    <p class="text-white">
                                        @lang("Currency import from $currencyDataProvider->name. The start field represents the starting $currencyDataProvider->name rank of the currency to be imported. For example, if you enter 2, the import will start with the currency ranked 2nd on $currencyDataProvider->name & limit field represents the maximum number of currencies to be imported. Maximum 100 currencies you will import at a time")
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Start')</label>
                            <small title="@lang('Statr from ')"><i class="las la-circle-info"></i></small>
                            <input type="number" class="form-control form--control" name="start" required>
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Limit')</label>
                            <input type="number" class="form-control form--control" name="limit">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Import')</button>
                    </div>
                </form>
                <div class="modal-loader">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <x-search-form placeholder="Name,Symbol...." />
        <button type="button" class="btn btn-outline--info importBtn">
            <i class="las la-angle-down"></i> @lang('Import Currency')
        </button>
        <button type="button" class="btn btn-outline--primary addBtn ">
            <i class="las la-plus"></i>@lang('New Currency')
        </button>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            let modal = $('#modal');

            @if ($type == Status::CRYPTO_CURRENCY)
                $(`input[name=name]`).attr('placeholder', "@lang('e.g. Bitcoin')");
                $(`input[name=symbol]`).attr('placeholder', "@lang('e.g. BTC')");
                $(`input[name=sign]`).attr('placeholder', "@lang('e.g. ₿')");
            @else
                $(`input[name=name]`).attr('placeholder', "@lang('e.g. Us Dollar')");
                $(`input[name=symbol]`).attr('placeholder', "@lang('e.g. USD')");
                $(`input[name=sign]`).attr('placeholder', "@lang('e.g. $')");
            @endif

            $('.addBtn').on('click', function() {
                let action = `{{ route('admin.currency.save') }}`;
                $('.image-upload-preview').css({
                    "background-image": "url({{ getImage('', getFileSize('currency')) }})"
                });
                modal.find('form').trigger('reset');
                modal.find('form').prop('action', action);
                modal.find('.modal-title').text("@lang('New Currency')");
                $(modal).modal('show');
            });

            $('input[name=symbol]').on('input', (e) => {
                let symbol = e.target.value.toUpperCase()
                let hasElement = $(`body`).find('.symbol-input-group-text');
                if (symbol) {
                    if (hasElement.length) hasElement.text(`1 ${symbol} =`);
                    else $('input[name=price]').closest('.input-group').prepend(
                        `<span class="input-group-text symbol-input-group-text">1 ${symbol} =</span>`);
                } else {
                    hasElement.remove();
                }
                e.target.value = symbol;
            });

            $('.editBtn').on('click', function(e) {
                let action = `{{ route('admin.currency.save', ':id') }}`;
                let data = $(this).data('currency');
                let imagePath = $(this).data('image')

                $('.image-upload-preview').css({
                    "background-image": `url(${imagePath})`
                });
                modal.find('form').prop('action', action.replace(':id', data.id))
                modal.find("input[name=name]").val(data.name);
                modal.find("input[name=symbol]").val(data.symbol);
                modal.find("input[name=rank]").val(data.ranking);
                modal.find("input[name=sign]").val(data.sign);
                modal.find("input[name=p2p_sn]").val(data.p2p_sn);
                modal.find("input[name=price]").val(getAmount(data.rate));
                @if ($type == Status::CRYPTO_CURRENCY)
                    if (data.highlighted_coin == 1) {
                        modal.find('input[name=is_highlighted_coin]').bootstrapToggle('on');
                    } else {
                        modal.find('input[name=is_highlighted_coin]').bootstrapToggle('off');
                    }
                @endif
                modal.find('.modal-title').text("@lang('Update Currency')");
                $('input[name=symbol]').trigger('input');
                $(modal).modal('show');
            });

            $('.importBtn').on('click', function(e) {
                let modal = $("#import-modal");
                $(modal).modal('show');
            });

            $('#import-form').on('submit', function(event) {
                event.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#import-modal .modal-loader").addClass('d-flex')
                    },
                    complete: function() {
                        $("#import-modal .modal-loader").removeClass('d-flex')
                    },
                    success: function(resp) {
                        if (resp.success) {
                            notify('success', resp.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            notify('error', resp.message);
                        }
                    },
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .modal-loader {
            position: absolute;
            left: 0;
            top: 0;
            content: "";
            display: none;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: #ffffffc7;
        }

        .modal-loader .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
@endpush
