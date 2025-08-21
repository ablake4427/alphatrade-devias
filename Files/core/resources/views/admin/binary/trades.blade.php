@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two highlighted-table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('TRX | Coin Pair')</th>
                                    <th>@lang('Trade Date')</th>
                                    <th>@lang('Invest')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Direction')</th>
                                    <th>@lang('Win Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Win Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trades as $trade)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$trade->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $trade->user_id) }}"><span>@</span>{{ @$trade->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $trade->trx }}
                                                <br>
                                                {{ @$trade->coinPair->symbol }}
                                            </div>
                                        </td>
                                        <td>{{ showDateTime($trade->created_at) }}</td>
                                        <td> {{ showAmount($trade->amount, currencyFormat: false) }} {{ __(@$trade->coinPair->coin->symbol) }}</td>
                                        <td>@php echo convertToMinutesSeconds($trade->duration)  @endphp</td>
                                        <td>{{ __($trade->direction) }}</td>
                                        <td>{{ showAmount($trade->win_amount, currencyFormat: false) }} {{ __(@$trade->coinPair->coin->symbol) }}</td>
                                        <td>
                                            @php
                                                echo $trade->tradeStatusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                echo $trade->winStatusBadge;
                                            @endphp
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
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
    <x-search-form />
@endpush
