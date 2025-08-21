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
                                    <th>@lang('Coin')</th>
                                    <th class="text-start">@lang('Market')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Is Default')</th>
                                    <th>@lang('Buy Liquidity')</th>
                                    <th>@lang('Sell Liquidity')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pairs as $pair)
                                @php
                                    $marketCurrency = @$pair->market->currency;
                                    $marketCurrency->name = $pair->market->name;
                                @endphp
                                    <tr>
                                        <td>
                                            <x-currency :currency="@$pair->coin" />
                                        </td>
                                        <td>
                                            <x-currency :currency="@$marketCurrency" />
                                        </td>
                                        <td>{{ @$pair->symbol }}</td>
                                        <td>@php  echo $pair->isDefaultStatus @endphp</td>
                                        <td>{{ showAmount($pair->buy_liquidity,currencyFormat:false) }} {{ @$marketCurrency->symbol }}</td>
                                        <td>{{ showAmount($pair->sell_liquidity,currencyFormat:false) }} {{ @$marketCurrency->symbol }} </td>
                                        <td>@php  echo $pair->statusBadge @endphp</td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.coin.pair.edit', $pair->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                @if ($pair->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to enable this coin pair')?"
                                                        data-action="{{ route('admin.coin.pair.status', $pair->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to disable this coin pair')?"
                                                        data-action="{{ route('admin.coin.pair.status', $pair->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
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
                @if ($pairs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($pairs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Name,Symbol...." />
<a href="{{ route('admin.coin.pair.create') }}" class="btn btn-outline--primary addBtn h-45">
    <i class="las la-plus"></i>@lang('New Pair')
</a>

@endpush
