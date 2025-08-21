@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            @php
                                $showStatus = request()->routeIs('admin.order.history');
                            @endphp
                            <thead>
                                <tr>
                                    <th>@lang('Date | Pair')</th>
                                    <th>@lang('Order Side')</th>
                                    <th>@lang('Order Type')</th>
                                    <th>@lang('Amount | Rate')</th>
                                    <th>@lang('Total')</th>
                                    <th>@lang('Filled')</th>
                                    @if ($showStatus)
                                        <th>@lang('Status')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ $order->formatted_date }}
                                                <br>
                                                {{ @$order->pair->symbol }}
                                            </div>
                                        </td>
                                        <td> @php echo $order->orderSideBadge; @endphp </td>
                                        <td> @php echo $order->orderTypeBadge; @endphp </td>
                                        <td>
                                            <div>
                                            {{ showAmount($order->amount,currencyFormat:false) }} {{ @$order->pair->coin->symbol }} <br>
                                                {{ showAmount($order->rate,currencyFormat:false) }} {{ @$order->pair->market->currency->symbol }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ showAmount($order->amount,currencyFormat:false) }} {{ @$order->pair->coin->symbol }}
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ getAmount($order->filed_percentage) }}%;"
                                                        aria-valuenow="{{ getAmount($order->filed_percentage) }} ">
                                                    </div>
                                                </div>
                                                <span class="fs-10">
                                                    <small>{{ getAmount($order->filed_percentage) }}%</small> |
                                                    <small>{{ showAmount($order->filled_amount,currencyFormat:false) }}
                                                        {{ @$order->pair->coin->symbol }}
                                                    </small>
                                                </span>
                                            </div>
                                        </td>
                                        @if ($showStatus)
                                            <td> @php echo $order->statusBadge; @endphp </td>
                                        @endif
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
                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <x-search-form placeholder="Pair,coin,currency..." />
        <form>
            <div class="input-group">
                <select name="order_side" class="form-control select2" data-minimum-results-for-search="-1">
                    <option value="">@lang('Order Side')</option>
                    <option value="{{ Status::BUY_SIDE_ORDER }}">@lang('Buy')</option>
                    <option value="{{ Status::SELL_SIDE_ORDER }}">@lang('Sell')</option>
                </select>
                <button class="btn btn--primary input-group-text" type="submit"><i class="la la-search"></i></button>
            </div>
        </form>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(`select[name=order_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });

            @if (request()->order_side)
                $(`select[name=order_side]`).val("{{ request()->order_side }}");
            @endif ()

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .progress {
            height: 9px;
        }
    </style>
@endpush
