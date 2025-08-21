@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method'))
            <div class="col-12">
                @include('admin.deposit.widget')
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Currency | Wallet')</th>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    @php
                                        $details = $deposit->detail ? json_encode($deposit->detail) : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="text-end text-lg-start">
                                                <span>{{ @$deposit->currency->symbol }}</span>
                                                <br>
                                                <small>{{ @$deposit->wallet->name }} | {{ __(strToUpper(@$deposit->wallet->type_text)) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold"> <a
                                                   href="{{ appendQuery('method', @$deposit->gateway->alias) }}">{{ __(@$deposit->gateway->name) }}</a>
                                            </span>
                                            <br>
                                            <small> {{ $deposit->trx }} </small>
                                        </td>

                                        <td>
                                            {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $deposit->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                   href="{{ appendQuery('search', @$deposit->user->username) }}"><span>@</span>{{ $deposit->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            {{ showAmount($deposit->amount, currencyFormat: false) }} + <span class="text-danger"
                                                  title="@lang('charge')">{{ showAmount($deposit->charge, currencyFormat: false) }} </span>
                                            <br>
                                            <strong title="@lang('Amount with charge')">
                                                {{ showAmount($deposit->amount + $deposit->charge, currencyFormat: false) }}
                                                {{ __($deposit->method_currency) }}
                                            </strong>
                                        </td>
                                        <td>
                                            @php echo $deposit->statusBadge @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.deposit.details', $deposit->id) }}" class="btn btn-sm btn-outline--primary ms-1">
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($deposits->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($deposits) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' placeholder='Username / TRX' />
@endpush

@push('style')
    <style>
        .estimated-badge {
            position: absolute;
            top: 0;
            right: 10px;
            font-size: 0.8rem;
            background: #1e9ff2;
            color: #fff;
            padding: 1px 5px;
            border-radius: 2px;
            font-weight: 600;
        }

        @media only screen and (min-width:1200px) {
            .estimated-badge {
                margin-top: -8px;
            }
        }

        @media only screen and (max-width: 731px) {
            .estimated-badge {
                top: 0px;
                right: 5px;
                font-size: 0.6rem;
            }
        }

        .widget-card {
            padding-bottom: 8px;
            padding-top: 20px;
        }

        @media (max-width: 575px) {
            .widget-card {
                padding: 18px 12px;
            }
        }
    </style>
@endpush
