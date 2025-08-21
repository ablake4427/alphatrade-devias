@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">
            <div class="dashboard-header-menu justify-content-between">
                <div class="div">
                    <a href="{{ route('user.order.open') }}"
                        class="dashboard-header-menu__link   {{ menuActive('user.order.open') }}">@lang('Open')</a>
                    <a href="{{ route('user.order.completed') }}"
                        class="dashboard-header-menu__link   {{ menuActive('user.order.completed') }}">@lang('Completed')</a>
                    <a href="{{ route('user.order.pending') }}"
                        class="dashboard-header-menu__link   {{ menuActive('user.order.pending') }}">@lang('Pending')</a>
                    <a href="{{ route('user.order.canceled') }}"
                        class="dashboard-header-menu__link   {{ menuActive('user.order.canceled') }}">@lang('Canceled')</a>
                    <a href="{{ route('user.order.history') }}"
                        class="dashboard-header-menu__link   {{ menuActive('user.order.history') }}">@lang('History')</a>
                </div>
                <form class="d-flex gap-2 flex-wrap">
                    <div class="flex-fill">
                        <div class="input-group position-relative">
                            <select name="order_type"
                                class="form-control form--control submit-form-on-change form-select select2"
                                data-minimum-results-for-search="-1" data-width="150px">
                                <option value="" selected disabled>@lang('Order Type')</option>
                                <option value="">@lang('All')</option>
                                <option value="{{ Status::ORDER_TYPE_LIMIT }}" @selected(request()->order_type == Status::ORDER_TYPE_LIMIT)>
                                    @lang('Limit')
                                </option>
                                <option value="{{ Status::ORDER_TYPE_MARKET }}" @selected(request()->order_type == Status::ORDER_TYPE_MARKET)>
                                    @lang('Market')
                                </option>
                                <option value="{{ Status::ORDER_TYPE_STOP_LIMIT }}" @selected(request()->order_type == Status::ORDER_TYPE_STOP_LIMIT)>
                                    @lang('Stop Limit Order')
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="position-relative">
                            <select class="form-control form--control submit-form-on-change form-select select2"
                                name="order_side" data-minimum-results-for-search="-1" data-width="150px">
                                <option value="" selected disabled>@lang('Order Side')</option>
                                <option value="">@lang('All')</option>
                                <option value="{{ Status::BUY_SIDE_ORDER }}" @selected(request()->order_side == Status::BUY_SIDE_ORDER)>
                                    @lang('Buy')
                                </option>
                                <option value="{{ Status::SELL_SIDE_ORDER }}" @selected(request()->order_side == Status::SELL_SIDE_ORDER)>
                                    @lang('Sell')
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form--control"
                                value="{{ request()->search }}" placeholder="@lang('Pair,coin,currency...')">
                            <button type="submit" class="input-group-text bg--primary text-white">
                                <i class="las la-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    @php
                        $actionShow = request()->routeIs('user.order.pending') || request()->routeIs('user.order.open') || request()->routeIs('user.order.history');
                    @endphp
                    <thead>
                        <tr>
                            <th>@lang('Date | Pair')</th>
                            <th>@lang('Side | Type')</th>
                            <th>@lang('Amount | Rate')</th>
                            <th>@lang('Charge | Total')</th>
                            <th>@lang('Filled')</th>
                            <th>@lang('Status')</th>
                            @if ($actionShow)
                                <th>@lang('Action')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="tr-{{ $order->id }}">
                                @php
                                    $marketCurrencySymbol = @$order->pair->market->currency->symbol;
                                @endphp
                                <td>
                                    <div>
                                        {{ $order->formatted_date }}
                                        <br>
                                        {{ @$order->pair->symbol }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @php echo $order->orderSideBadge; @endphp <br>
                                        @php echo $order->orderTypeBadge; @endphp
                                    </div>
                                </td>
                                <td>
                                    <div class="order--amount-rate-wrapper">
                                        <span class="order--amount d-block">
                                            <span
                                                class="order--amount-value">{{ showAmount($order->amount, currencyFormat: false) }}</span>
                                            @if ($order->status == Status::ORDER_OPEN)
                                                <span class="amount-rate-update" data-order='@json($order->only('amount', 'rate', 'id'))'
                                                    data-update-filed="amount">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            @endif
                                        </span>
                                        <span class="order--rate d-block">
                                            <span
                                                class="order--rate-value">{{ showAmount($order->rate, currencyFormat: false) }}</span>
                                            @if ($order->status == Status::ORDER_OPEN)
                                                <span class="amount-rate-update" data-order='@json($order->only('amount', 'rate', 'id'))'
                                                    data-update-filed="rate">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="d-block">
                                            {{ showAmount($order->charge, currencyFormat: false) }}
                                            {{ __($marketCurrencySymbol) }}
                                        </span>
                                        <span>
                                            {{ showAmount($order->total, currencyFormat: false) }}
                                            {{ __($marketCurrencySymbol) }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress-wrapper">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ getAmount($order->filed_percentage) }}%;"
                                                aria-valuenow=" {{ getAmount($order->filed_percentage) }}">
                                            </div>
                                        </div>
                                        <span class="fs-10">
                                            <span>{{ getAmount($order->filed_percentage) }}%</span> |
                                            <span>{{ showAmount($order->filled_amount, currencyFormat: false) }}</span>
                                        </span>
                                    </div>
                                </td>

                                <td> @php echo $order->statusBadge; @endphp </td>
                                @if ($actionShow)
                                    <td>
                                        @if ($order->status == Status::ORDER_OPEN || $order->status == Status::ORDER_PENDING)
                                            @php
                                                if ($order->status == Status::ORDER_OPEN) {
                                                    $backAmount = orderCancelAmount($order);
                                                    $amount = $backAmount['amount'];
                                                    $chargeBackAmount = $backAmount['charge_back_amount'];
                                                    $cancelMessage = __(
                                                        'Are you sure to cancel this order? after cancelling the order you will get ',
                                                    );

                                                    if ($order->order_side == Status::BUY_SIDE_ORDER) {
                                                        $symbol = @$order->pair->market->currency->symbol;
                                                        $cancelMessage .=
                                                            showAmount($amount, currencyFormat: false) .
                                                            '+' .
                                                            showAmount($chargeBackAmount, currencyFormat: false) .
                                                            '=' .
                                                            showAmount(
                                                                $amount + $chargeBackAmount,
                                                                currencyFormat: false,
                                                            ) .
                                                            __(" $symbol to your $symbol wallet");
                                                    } else {
                                                        $symbol = @$order->pair->coin->symbol;
                                                        $cancelMessage .=
                                                            showAmount($amount) . __(" $symbol to your $symbol wallet");
                                                    }
                                                } else {
                                                    $cancelMessage = trans('Are you sure to cancel this order?');
                                                }
                                            @endphp
                                            <div>
                                                <button type="button"
                                                    class="btn btn--danger btn--cancel outline btn--sm confirmationBtn ms-2"
                                                    data-question="{{ $cancelMessage }}"
                                                    data-action="{{ route('user.order.cancel', $order->id) }}">
                                                    <i class="las la-times-circle"></i> @lang('Cancel')
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('order') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($orders->hasPages())
                {{ paginateLinks($orders) }}
            @endif
        </div>
    </div>
    <x-confirmation-modal isCustom="true" />
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {

            $('table').on('click', '.order-update-form-remove', function(e) {
                $(`.order--rate`).removeClass('d-none');
                $(`.order--amount`).removeClass('d-none');
                $(this).closest('.order-update-form').remove();
            })

            let editColumn = null;

            $('table').on('click', '.amount-rate-update', function(e) {

                $('.order-update-form').remove();
                $(`.order--rate`).removeClass('d-none');
                $(`.order--amount`).removeClass('d-none');

                editColumn = $(this).closest('td');

                let order = $(this).attr('data-order');
                order = JSON.parse(order);
                let updateField = $(this).data('update-filed');
                let action = "{{ route('user.order.update', ':id') }}";

                let html = `<form class="order-update-form" action="${action.replace(':id', order.id)}">
                    <input type="hidden" name="update_filed" value="${updateField}">
                    <div class="input-group">
                        <span class="input-group-text">
                            ${updateField == 'amount' ? "@lang('Amount')" : "@lang('Rate')"}
                        </span>
                        <input type="text" class="form--control form-control" name="${updateField}"  value="${updateField == 'amount' ? getAmount(order.amount) : getAmount(order.rate)}">
                        <button type="submit" class="input-group-text">
                            <i class="fas fa-check text--success"></i>
                        </button>
                        <button type="button" class="input-group-text order-update-form-remove">
                            <i class="fas fa-times text--danger"></i>
                        </button>
                    </div>
                </form>`;
                editColumn.find('.order--amount-rate-wrapper').append(html);
            });

            $('table').on('submit', '.order-update-form', function(e) {
                e.preventDefault();

                let formData = new FormData($(this)[0]);
                let action = $(this).attr('action');
                let token = "{{ csrf_token() }}";
                let $this = $(this);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: action,
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $($this).find('button[type=submit]').html(
                            `<i class="fa fa-spinner fa-spin text--success"></i>`);
                        $($this).find('button[type=button]').addClass('d-none');
                        $($this).attr(`disabled`, true);
                    },
                    complete: function() {
                        $($this).find('button[type=submit]').html(
                            `<i class="fa fa-check text--success"></i>`);
                        $($this).find('button[type=button]').removeClass('d-none');
                        $($this).attr(`disabled`, false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            editColumn.find(`.order--rate`).removeClass('d-none');
                            editColumn.find(`.order--amount`).removeClass('d-none');
                            editColumn.find('.order-update-form').remove();

                            let newOrder = editColumn.find('.amount-rate-update').data('order');
                            if (resp.data.order_amount) {
                                editColumn.find(`.order--amount-value`).text(getAmount(resp.data
                                    .order_amount));
                                newOrder.amount = getAmount(resp.data.order_amount);
                            }
                            if (resp.data.order_rate) {
                                editColumn.find(`.order--rate-value`).text(getAmount(resp.data
                                    .order_rate));
                                newOrder.rate = getAmount(resp.data.order_rate);
                            }
                            editColumn.find('.amount-rate-update').attr('data-order', JSON
                                .stringify(newOrder))
                            notify('success', resp.message);
                        } else {
                            notify('error', resp.message);
                        }
                    },
                });
            });

        })(jQuery);
    </script>
@endpush
