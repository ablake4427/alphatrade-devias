@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
@endphp
<div class="trading-table two">
    <div class="flex-between trading-table__header">
        <div class="flex-between">
            <h4 class="trading-table__title mb-2">@lang('My Order')</h4>
            @auth
                <ul class="nav nav-pills mb-2 custom--tab" id="pills-tabtwenty" role="tablist">
                    <li class="nav-item order-status" data-status="all" role="presentation">
                        <button type="button" class="nav-link active" id="pills-allthree-tab">@lang('All')</button>
                    </li>
                    <li class="nav-item order-status" data-status="open" role="presentation">
                        <button type="button" class="nav-link" id="pills-openthree-tab"> @lang('Open') </button>
                    </li>
                    <li class="nav-item order-status" data-status="completed" role="presentation">
                        <button type="button" class="nav-link" id="pills-completedthree-tab"> @lang('Completed') </button>
                    </li>
                    <li class="nav-item order-status" data-status="canceled" role="presentation">
                        <button type="button" class="nav-link" id="pills-completedthree-tab"> @lang('Canceled') </button>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
    <div class="tab-content" id="pills-tabContenttwenty">
        <div class="tab-pane fade show active">
            <div class="table-wrapper-two">
                @auth
                    <table class="table table-two my-order-list-table">
                        <thead>
                            <tr>
                                <th>@lang('Date')</th>
                                <th>@lang('Pair')</th>
                                <th>@lang('Side')</th>
                                <th>@lang('Amount | Rate')</th>
                                <th>@lang('Total')</th>
                                <th>@lang('Filled')</th>
                                <th>@lang('Status')</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="order-list-body"></tbody>
                    </table>
                @else
                    <div class="empty-thumb">
                        <img src="{{ asset('assets/images/extra_images/user.png') }}" />
                        <p class="empty-sell">@lang('Please login to explore your order')</p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>


<x-confirmation-modal isCustom="true" />

@push('script')
    <script>
        "use strict";
        (function($) {
            let status = 'all';

            $('.order-status').on('click', function(e) {
                status = $(this).data('status');
                $('.order-status').find(`button`).removeClass('active');
                $(this).find(`button`).addClass('active');
                orderHistory();
            });

            function orderHistory() {
                let action = "{{ route('trade.order.list', ':pairSym') }}";
                let cancelMessage = "@lang('Are you sure to cancel this order?')";
                let openStatus = "{{ Status::ORDER_OPEN }}";
                let actionCancel = "{{ route('user.order.cancel', ':id') }}";
                $.ajax({
                    url: action.replace(':pairSym', "{{ @$pair->symbol }}"),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    data: {
                        status
                    },
                    complete: function() {
                        setTimeout(() => {
                            $(`.my-order-list-table tr`).removeClass('skeleton');
                        }, 500);
                    },
                    success: function(resp) {
                        if (!resp.success) {
                            notify('error', resp.message);
                            return;
                        }

                        let html = ``;
                        if (resp.orders.length > 0) {
                            $.each(resp.orders, function(i, order) {
                                let dNoneClassForCancel = parseInt(order.status) == parseInt(
                                    openStatus) ? '' : 'd-none';
                                let updateData = {
                                    id: order.id,
                                    amount: order.amount,
                                    rate: order.rate
                                }
                                html += `
                                <tr class="skeleton">
                                    <td>${order.formatted_date}</td>
                                    <td>${order.pair.symbol.replace('_','/')}</td>
                                    <td>${order.order_side_badge}</td>
                                    <td>
                                        <div class="order--amount-rate-wrapper">
                                            <span class="order-amount d-block">
                                                <span class="order--amount-value">${getAmount(order.amount)}</span>
                                                <span class="amount-rate-update ${dNoneClassForCancel}" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="amount">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                            <span class="order-amount d-block">
                                               <span class="order--rate-value"> ${getAmount(order.rate)}</span>
                                                <span class="amount-rate-update ${dNoneClassForCancel}" data-order='${JSON.stringify(updateData)}'
                                                    data-update-filed="rate">
                                                    <i class="las la-edit"></i>
                                                </span>
                                            </span>
                                            <span class="order-amount ${(order.status == 2 && order.is_draft == 1) ? 'd-lock' : 'd-none'}">
                                               <span class="order--rate-value"> 
                                                ${order.order_side == 2 ? "<=" : ">=" } ${getAmount(order.stop_rate)}
                                                </span>
                                            </span>
                                        </div>
                                    </td>
                                    <td> ${getAmount(order.total)}</td>
                                    <td>${getAmount(order.filled_amount)}</td>
                                    <td> ${order.status_badge.replaceAll('badge','text')} </td>
                                    <td>
                                        <div class="action-buttons ${(parseInt(order.status) == parseInt(openStatus) || parseInt(order.status) == 2) ? 'd-block' : 'd-none'  }">
                                            <button type="button" class="delete-icon p-0 m-0 confirmationBtn" data-question="${cancelMessage}" data-action="${actionCancel.replace(':id',order.id)}"><i class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>`
                            });
                        } else {
                            html += ` <tr class="text-center">
                                    <td colspan="100%">
                                        <div class="empty-thumb">
                                            <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                            <p class="empty-sell">@lang('No order found')</p>
                                        </div>
                                    </td>
                                </tr>
                                `
                        }
                        $('.order-list-body').html(html);
                    }
                });
            }
            orderHistory();


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

                let coinSymbol = $(this).data('coin-symbol');
                let currencySymbol = $(this).data('currency-symbol');
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
                    }
                });
            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .custom--modal .modal-content {
            background-color: #0d1e23 !important;
            border-radius: 10px !important;
        }

        .custom--modal .modal-title {
            color: hsl(var(--white)/0.5);
        }

        .custom--modal .modal-header,
        .custom--modal .modal-footer {
            border-color: hsl(var(--white)/0.2) !important;
        }

        .btn-dark,
        .btn-dark:hover,
        .btn-dark:focus {
            border-color: hsl(var(--white)/0.1) !important;
            color: #ffffff !important;
        }
    </style>
@endpush
