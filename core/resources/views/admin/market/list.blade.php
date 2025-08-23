@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card    bg--transparent shadow-none">
                <div class="card-body p-0">
                     <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table bg-white">
                            <thead>
                                <tr>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($markets as $market)
                                    <tr>
                                        <td>
                                            <x-currency :currency="@$market->currency" />
                                        </td>
                                        <td>{{ __(@$market->name) }}</td>
                                        <td> @php echo $market->statusBadge; @endphp </td>
                                        <td>
                                            <button aria-expanded="true" class="btn btn-sm dropdown-toggle btn-outline--primary" data-bs-toggle="dropdown" type="button">
                                                <i class="las la-down-arrow"></i> @lang('Action')
                                            </button>

                                            <div class="dropdown-menu" data-popper-placement="bottom-end" >
                                                <button type="button" class="dropdown-item editBtn" data-market='@json($market)'>
                                                    <i class="la la-pencil-alt"></i> @lang('Edit')
                                                </button>
                                                <a href="{{ route('admin.coin.pair.create') }}?market_id={{$market->id}}" class="dropdown-item">
                                                    <i class="las la-calendar-plus"></i> @lang('New Pair')
                                                </a>
                                                <a href="{{ route('admin.coin.pair.list') }}?market_id={{$market->id}}" class="dropdown-item">
                                                    <i class="la la-list"></i> @lang('Pair List')
                                                </a>
                                                @if($market->status == Status::DISABLE)
                                                <button class="dropdown-item confirmationBtn"
                                                    data-question="@lang('Are you sure to enable this market')?"
                                                    data-action="{{ route('admin.market.status',$market->id) }}" type="button">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                                @else
                                                <button class="dropdown-item  confirmationBtn"
                                                    data-question="@lang('Are you sure to disable this market')?"
                                                    data-action="{{ route('admin.market.status',$market->id) }}" type="button">
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
                @if ($markets->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($markets) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="@lang('e.g. BTC Market')" required>
                        </div>
                        <div class="form-group position-relative" id="currency_list_wrapper">
                            <label>@lang('Currency')</label>
                            <x-currency-list />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <x-search-form placeholder="Name,Symbol...." />
        <button type="button" class="btn btn-outline--primary addBtn">
            <i class="las la-plus"></i>@lang('New Market')
        </button>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            let modal = $('#modal');

            $('.addBtn').on('click', function() {
                let action = `{{ route('admin.market.save') }}`;
                modal.find('form').trigger('reset');
                modal.find('form').prop('action', action);
                modal.find('.modal-title').text("@lang('New Market')");
                let newOption = new Option("@lang('Select One')", 0, true, true);
                $('#currency_list').append(newOption).trigger('change');
                $('#currency_list_wrapper').show();
                $(modal).modal('show');
            });

            $('.editBtn').on('click', function(e) {
                let action = `{{ route('admin.market.save', ':id') }}`;
                let data = $(this).data('market');
                modal.find('form').prop('action', action.replace(':id', data.id))
                modal.find("input[name=name]").val(data.name);
                modal.find('.modal-title').text("@lang('Update Market')");
                $('#currency_list_wrapper').hide();
                modal.find('[name=currency]').removeAttr('required');
                $(modal).modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style')
<style>
      .table-responsive {
            background: transparent;
            min-height: 350px;
        }

        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
</style>
@endpush
