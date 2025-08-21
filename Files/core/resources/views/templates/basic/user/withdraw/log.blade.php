@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3 align-items-center justify-content-between">
        <div class="col-lg-3">
            <h4 class="mb-0">{{ __($pageTitle) }}</h4>
        </div>
        <div class="col-lg-3">
            <div class="d-flex gap-3">
                <form action="" class="flex-fill">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                            placeholder="@lang('Search by transactions')">
                        <button class="input-group-text bg-primary text-white">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Currency | Wallet')</th>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $withdraw)

                            <tr>
                                <td>
                                    <div>
                                        <span>{{ @$withdraw->wallet->currency->symbol }}</span>
                                        <br>
                                        <small>{{ @$withdraw->wallet->name }} | {{ __(strtoupper(@$withdraw->wallet->type_text)) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold"><span class="text-primary">
                                                {{ __(@$withdraw->method->name) }}</span></span>
                                        <br>
                                        <small>{{ $withdraw->trx }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        {{ showAmount($withdraw->amount,currencyFormat:false) }} - <span class="text--danger"
                                            title="@lang('charge')">{{ showAmount($withdraw->charge,currencyFormat:false) }} </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                            {{ showAmount($withdraw->amount - $withdraw->charge,currencyFormat:false) }}
                                            {{ @$withdraw->currency }}
                                        </strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-end text-lg-start">
                                        @php echo $withdraw->statusBadge @endphp
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn--sm btn--base detailBtn outline"
                                        data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                        @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('withdraw ') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($withdraws->hasPages())
                {{ paginateLinks($withdraws) }}
            @endif
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData list-group-flush">

                    </ul>
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
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
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
