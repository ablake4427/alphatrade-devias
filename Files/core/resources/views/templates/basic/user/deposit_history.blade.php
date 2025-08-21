@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-between align-items-center gy-4">
        <div class="col-lg-4">
            <h4 class="mb-0">{{ __($pageTitle) }}</h4>
        </div>
        <div class="col-lg-3">
            <form>
                <div class="input-group">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
                    <button class="input-group-text bg-primary text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Currency | Wallet')</th>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            @php
                                $symbol = @$deposit->wallet->currency->symbol;
                            @endphp
                            <tr>
                                <td>
                                    <div class="text-end text-lg-start">
                                        <span>{{ $symbol }}</span>
                                        <br>
                                        <small>{{ @$deposit->wallet->name }} | {{ __(strToUpper(@$deposit->wallet->type_text)) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        <span class="text-primary fw-bold">{{ __($deposit->gateway?->name) }}</span>
                                        <br>
                                        <small> {{ $deposit->trx }} </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start fw-normal">
                                        <span>{{ showDateTime($deposit->created_at) }}</span> <br>
                                        <small>{{ diffForHumans($deposit->created_at) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start fw-normal">
                                        {{ showAmount($deposit->amount, currencyFormat: false) }} +
                                        <span class="text--danger" title="@lang('charge')">{{ showAmount($deposit->charge, currencyFormat: false) }}
                                        </span>
                                        <br>
                                        <span title="@lang('Amount with charge')">
                                            {{ showAmount($deposit->amount + $deposit->charge, currencyFormat: false) }}
                                            {{ $symbol }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-end text-lg-start">
                                        @php echo $deposit->statusBadge @endphp
                                    </div>
                                </td>
                                @php
                                    $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                @endphp
                                <td>
                                    <button type="button"
                                        class="btn btn--base btn--sm outline @if ($deposit->method_code >= 1000) detailBtn @else disabled @endif"
                                        @if ($deposit->method_code >= 1000) data-info="{{ $details }}" @endif
                                        @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('deposit') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($deposits->hasPages())
                {{ paginateLinks($deposits) }}
            @endif
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Deposit Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2 list-group-flush"></ul>
                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
