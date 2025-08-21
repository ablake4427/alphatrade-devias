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
                                    <th>@lang('Coin Pair')</th>
                                    <th>@lang('Buy Charge')</th>
                                    <th>@lang('Sell Charge')</th>
                                    <th>@lang('Is Default')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($futureTradeConfigs as $futureTradeConfig)
                                    <tr>
                                        <td>{{ $futureTradeConfig->coinPair->symbol }}</td>
                                        <td>{{ showAmount($futureTradeConfig->buy_charge, currencyFormat: false) }}%</td>
                                        <td>{{ showAmount($futureTradeConfig->sell_charge, currencyFormat: false) }}%</td>
                                        <td>@php  echo $futureTradeConfig->isDefaultStatus @endphp</td>
                                        <td>@php  echo $futureTradeConfig->statusBadge @endphp</td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.future.trade.config.edit', $futureTradeConfig->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                @if ($futureTradeConfig->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-question="@lang('Are you sure to enable this future trade pair')?" data-action="{{ route('admin.future.trade.config.status', $futureTradeConfig->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn" data-question="@lang('Are you sure to disable this future trade pair')?" data-action="{{ route('admin.future.trade.config.status', $futureTradeConfig->id) }}">
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
                @if ($futureTradeConfigs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($futureTradeConfigs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Name,Symbol...." />
    <a href="{{ route('admin.future.trade.config.create') }}" class="btn btn-outline--primary addBtn h-45">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
