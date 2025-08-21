@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-between gy-3 align-items-center">
    <div class="col-lg-4">
        <h4 class="mb-0">{{ __($pageTitle) }}</h4>
    </div>
    <div class="col-lg-4">
        <form class="d-flex gap-2 flex-wrap">
            <div class="flex-fill position-relative">
               <select class="form-control form--control submit-form-on-change form-select select2" name="trade_side" 
               data-minimum-results-for-search="-1" data-width="100%">
                    <option value="" selected disabled>@lang('Trade Side')</option>
                    <option value="">@lang('All')</option>
                    <option value="{{ Status::BUY_SIDE_TRADE }}" @selected(request()->trade_side ==  Status::BUY_SIDE_TRADE)>@lang('Buy')</option>
                    <option value="{{ Status::SELL_SIDE_TRADE }}" @selected(request()->trade_side ==  Status::SELL_SIDE_TRADE)>@lang('Sell')</option>
               </select>
            </div>
            <div class="flex-fill">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Pair,coin,currency...')">
                    <button type="submit" class="input-group-text bg-primary text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12">
        <div class="table-wrapper">
            <table class="table table--responsive--lg">
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
                                {{ $trade->order->formatted_date}}
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
                        @php echo userTableEmptyMessage('trade') @endphp
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush


