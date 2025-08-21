@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Order Date | Pair')</th>
                                    <th>@lang('Trade Date')</th>
                                    <th>@lang('Trade Side')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trades as $trade)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ $trade->order->formatted_date }}
                                                <br>
                                                {{ @$trade->order->pair->symbol }}
                                            </div>
                                        </td>
                                        <td>{{ showDateTime($trade->created_at) }}</td>
                                        <td> @php  echo $trade->tradeSideBadge; @endphp </td>
                                        <td>
                                            {{ showAmount($trade->rate,currencyFormat:false) }} {{ @$trade->order->pair->market->currency->symbol }}
                                        </td>
                                        <td> {{ showAmount($trade->amount,currencyFormat:false) }} {{ @$trade->order->pair->coin->symbol }}</td>
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
                @if ($trades->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($trades) }}
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
                <select name="trade_side" class="form-control select2" data-minimum-results-for-search="-1">
                    <option value="">@lang('Trade Side')</option>
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
            $(`select[name=trade_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });

            @if (request()->trade_side)
                $(`select[name=trade_side]`).val("{{ request()->trade_side }}");
            @endif
        })(jQuery);
    </script>
@endpush
