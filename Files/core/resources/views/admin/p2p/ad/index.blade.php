@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('Type | Status')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Asset')</th>
                                    <th>@lang('Fiat')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Limit | Payment Window')</th>
                                    <th>@lang('Publish Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ads as $ad)
                                    <tr>
                                        <td>
                                            <div>
                                                @php echo $ad->typeBadge; @endphp
                                                <div class="mt-1">
                                                    @php echo $ad->statusBadge; @endphp
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $ad->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', $ad->user->id) }}"><span>@</span>{{ $ad->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-end text-lg-start">
                                                <span>{{ @$ad->asset->symbol }}</span>
                                                <br>
                                                <small>{{ __(@$ad->asset->name) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-end text-lg-start">
                                                <span>{{ @$ad->fiat->symbol }}</span>
                                                <br>
                                                <small>{{ __(@$ad->fiat->name) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showAmount(@$ad->price,currencyFormat:false) }} {{ @$ad->fiat->symbol }} /
                                                {{ __(@$ad->asset->symbol) }}
                                                <br>
                                                @php echo $ad->pricingTypeBadge; @endphp
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span>
                                                    {{ showAmount(@$ad->minimum_amount,currencyFormat:false) }} -
                                                    {{ showAmount(@$ad->maximum_amount,currencyFormat:false) }}
                                                    {{ @$ad->fiat->symbol }}
                                                </span> <br>
                                                <span>
                                                    {{__(@$ad->paymentWindow->minute)}} @lang('Minute')
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($ad->publish_status)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('No')</span>
                                                @if ($ad->complete_step != 3)
                                                    <i class="fas fa-info-circle text--danger cursor-pointer error-message"
                                                        data-message="@lang("Seller didn't provide all the required data to publish an ad.")"></i>
                                                @else
                                                    <i class="fas fa-info-circle text--danger cursor-pointer error-message"
                                                        data-message="@lang('Seller don\'t have sufficient wallet balance to publish this ad.')"></i>
                                                @endif
                                            @endif
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary detailsBtn"
                                                data-ad='@json($ad)'>
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </button>
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
                @if ($ads->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($ads) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
    <div id="modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search..." />
    <form>
        <div class="input-group">
            <select name="type" class="form-control submit-on-change">
                <option value="">@lang('Ad Type')</option>
                <option value="{{ Status::P2P_TRADE_SIDE_BUY }}" @selected(Status::P2P_TRADE_SIDE_BUY == request()->type)>
                    @lang('Buy')
                </option>
                <option value="{{ Status::P2P_TRADE_SIDE_SELL }}" @selected(Status::P2P_TRADE_SIDE_SELL == request()->type)>
                    @lang('Sell')
                </option>
            </select>
            <button class="btn btn--primary input-group-text" type="submit"><i class="la la-search"></i></button>
        </div>
    </form>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".detailsBtn").on('click', function(e) {
                const modal = $("#modal");
                const ad = $(this).data('ad');
                modal.find(`.modal-body`).html(`
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Asset')</span>
                        <span class="text-end">
                            <span class="d-block">${ad.asset?.symbol}</span>
                            <span class="d-block">${ad.asset?.name}</span>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Fiat')</span>
                        <span class="text-end">
                            <span class="d-block">${ad.fiat?.symbol}</span>
                            <span class="d-block">${ad.fiat?.name}</span>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Rate')</span>
                        <span>${ getAmount(ad.price) } ${ad.fiat?.symbol}/${ad.asset?.symbol}</span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Limit')</span>
                        <span>${ getAmount(ad.minimum_amount)}-${getAmount(ad.maximum_amount)} ${ad.fiat.symbol}</span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Payment Window')</span>
                        <span> ${ad?.payment_window?.minute} @lang('Minute') </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Payment Method')</span>
                        <span>${(ad.payment_methods || []).map(ele => `<span class="ms-2">${ele.payment_method.name}</span>`).toString()} </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Terms')</span>
                        <div>${ad.terms_of_trade || ''} </div>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Payment Dteails')</span>
                        <div>${ad.payment_details || ''} </div>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Auto Reaply')</span>
                        <div>${ad.auto_replay_text || ''} </div>
                    </li>
                </ul>
                `);
                modal.find(`.modal-title`).text("@lang('Ad Details')")
                modal.modal('show');
            });

            $(".error-message").on('click', function(e) {
                $("#modal").find(`.modal-title`).text("@lang('Publish Status')")
                $("#modal").find(`.modal-body`).html(
                    `<p class="text--danger">${$(this).data('message')}</p>`);
                $("#modal").modal('show');
            });

            $(".submit-on-change").on('change',function(){
                $(this).closest('form').submit();
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .cursor-pointer{
            cursor: pointer;
        }
    </style>
@endpush