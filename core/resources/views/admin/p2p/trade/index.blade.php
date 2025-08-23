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
                                    <th>@lang('Type')</th>
                                    <th>@lang('Asset | Fiat')</th>
                                    <th>@lang('Seller')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Order ID | Date')</th>
                                    <th>@lang('Rate | Payment Method')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trades as $trade)
                                    <tr>
                                        <td> @php echo $trade->typeBadge; @endphp </td>
                                        <td>
                                            <div>
                                                <span class="d-block">
                                                    {{ showAmount(@$trade->asset_amount, currencyFormat: false) }}
                                                    {{ __(@$trade->ad->asset->symbol) }}
                                                </span>
                                                <span>
                                                    {{ showAmount(@$trade->fiat_amount, currencyFormat: false) }}
                                                    {{ __(@$trade->ad->fiat->symbol) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ @$trade->seller->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', @$trade->seller->id) }}"><span>@</span>{{ @$trade->seller->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ @$trade->buyer->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', @$trade->buyer->id) }}"><span>@</span>{{ @$trade->buyer->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="d-block"> {{ $trade->uid }} </span>
                                                <span>{{ showDateTime($trade->created_at) }} </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="d-block">
                                                    {{ showAmount(@$trade->ad->price, currencyFormat: false) }}
                                                    {{ __(@$trade->ad->fiat->symbol) }}
                                                    /
                                                    {{ __(@$trade->ad->asset->symbol) }}
                                                </span>
                                                <span>{{ __(@$trade->paymentMethod->name) }}</span>
                                            </div>
                                        </td>
                                        <td> @php echo $trade->statusBadge; @endphp </td>
                                        <td>
                                            <a href="{{ route('admin.p2p.trade.details', $trade->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Details')
                                            </a>
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
                @if ($trades->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($trades) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .badge--info {
            background-color: #1e9ff290;
            border: 1px solid #1e9ff2;
            color: #000;
            font-weight: 600;
        }
    </style>
@endpush
