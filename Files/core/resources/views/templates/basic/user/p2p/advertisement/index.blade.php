@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
    @if ($ads->count())
        <div class="table-wrapper">
            <table class="table table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('Type | Status')</th>
                        <th>@lang('Asset')</th>
                        <th>@lang('Fiat')</th>
                        <th>@lang('Rate')</th>
                        <th>@lang('Limit | Payment Window')</th>
                        <th>@lang('Publish Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ads as $ad)
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
                                    {{ showAmount(@$ad->price, currencyFormat: false) }} {{ @$ad->fiat->symbol }} / {{ __(@$ad->asset->symbol) }}
                                    <br>
                                    @php echo $ad->pricingTypeBadge; @endphp
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="d-block">
                                        {{ showAmount(@$ad->minimum_amount, currencyFormat: false) }} -
                                        {{ showAmount(@$ad->maximum_amount, currencyFormat: false) }}
                                        {{ @$ad->fiat->symbol }}
                                    </span>
                                    <span>{{ __(@$ad->paymentWindow->minute) }} @lang('Minute')</span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if ($ad->publish_status)
                                        <span class="badge badge--success">@lang('Yes')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('No')</span>
                                        @if ($ad->complete_step != 3)
                                            <i class="fas fa-info-circle text--danger cursor-pointer error-message" data-message="@lang("You didn't provide all the required data to publish an ad.")"></i>
                                        @else
                                            <i class="fas fa-info-circle text--danger cursor-pointer error-message" data-message="@lang('You don\'t have sufficient wallet balance to publish this ad.')"></i>
                                        @endif
                                    @endif
                                </div>
                            <td>
                                <div class="text-end">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('user.p2p.advertisement.create', $ad->id) }}?step=1" class="text--success dropdown-item">
                                                <i class="las la-edit"></i> @lang('Edit')
                                            </a>
                                            <button class="text--base detailsBtn dropdown-item" data-ad='@json($ad)'>
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </button>
                                            @if ($ad->status == Status::DISABLE)
                                                <button class="text--success confirmationBtn dropdown-item" data-question="@lang('Are you sure to enable this ad')?"
                                                    data-action="{{ route('user.p2p.advertisement.change.status', $ad->id) }}">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="text--danger confirmationBtn dropdown-item" data-question="@lang('Are you sure to disable this ad')?"
                                                    data-action="{{ route('user.p2p.advertisement.change.status', $ad->id) }}">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @include($activeTemplate . 'user.p2p.empty_message', [
            'text' => 'New Ad',
            'message' => "You haven't created any ad. By clicking below button create your first ad.",
            'url' => route('user.p2p.advertisement.create'),
        ])
    @endif

    <x-confirmation-modal isCustom="true" />
    <div class="offcanvas offcanvas-end p-5" tabindex="-1" id="canvas" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fs-18 offcanvas-title">
                @lang('Ad Details')
            </h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-times-circle"></i>
            </button>
        </div>
        <div class="offcanvas-body"></div>
    </div>

    <div id="error-modal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Publish Status')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".detailsBtn").on('click', function(e) {
                const canvas = $("#canvas");
                const ad = $(this).data('ad');
                canvas.find(`.offcanvas-body`).html(`
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
                        <span> ${ad?.payment_window?.minute} @lang('Minute')</span>
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
                new bootstrap.Offcanvas(document.getElementById('canvas')).show();
            });

            $(".error-message").on('click', function(e) {
                $("#error-modal").find(`.modal-body`).html(
                    `<p class="text--danger">${$(this).data('message')}</p>`);
                $("#error-modal").modal('show');
            });

        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .table .dropdown-toggle::after {
            display: none;
        }

        .table .dropdown-menu {
            background: #000000;
        }

        .table .dropdown-item {
            color: hsl(var(--white)/0.7);
            padding: 0.45rem 2rem;
        }

        .dropdown-item:focus,
        .dropdown-item:hover {
            background-color: hsl(var(--base)/0.19);
        }

        .list-group-item .ms-2:first-child {
            margin-left: 0px !important;
        }
    </style>
@endpush
