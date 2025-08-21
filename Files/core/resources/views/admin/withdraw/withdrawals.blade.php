@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.withdraw.data.all') || request()->routeIs('admin.withdraw.method'))
            <div class="col-12">
                @include('admin.withdraw.widget')
            </div>
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Currency | Wallet')</th>
                                    <th>@lang('Method | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdraw)
                                    @php
                                        $details = $withdraw->withdraw_information != null ? json_encode($withdraw->withdraw_information) : null;
                                    @endphp
                                    <tr>

                                        <td>
                                            <div>
                                                <span>{{ @$withdraw->wallet->currency->symbol }}</span>
                                                <br>
                                                <small>{{ @$withdraw->wallet->name }} | {{ __(strtoupper(@$withdraw->wallet->type_text)) }}</small>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="fw-bold"><a href="{{ appendQuery('method', @$withdraw->method->id) }}">
                                                    {{ __(@$withdraw->method->name) }}</a></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </td>
                                        <td>
                                            {{ showDateTime($withdraw->created_at) }} <br> {{ diffForHumans($withdraw->created_at) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ $withdraw->user->fullname }}</span>
                                            <br>
                                            <span class="small"> <a
                                                   href="{{ appendQuery('search', @$withdraw->user->username) }}"><span>@</span>{{ $withdraw->user->username }}</a>
                                            </span>
                                        </td>


                                        <td>
                                            {{ showAmount($withdraw->amount, currencyFormat: false) }} - <span class="text-danger"
                                                  title="@lang('charge')">{{ showAmount($withdraw->charge, currencyFormat: false) }} </span>
                                            <br>
                                            <strong title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge, currencyFormat: false) }} {{ $withdraw->currency }}
                                            </strong>

                                        </td>



                                        <td>
                                            @php echo $withdraw->statusBadge @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.withdraw.data.details', $withdraw->id) }}"
                                               class="btn btn-sm btn-outline--primary ms-1">
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
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
                @if ($withdrawals->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($withdrawals) }}
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
