@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead> 
                                <tr>
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Default')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($providers as $provider)
                                <tr>
                                    <td>
                                        <div class="user">
                                            <div class="thumb">
                                                <img src="{{ getImage(getFilePath('currency_data_provider') .'/'.$provider->image,getFileSize('currency_data_provider')) }}">
                                            </div>
                                            <span class="name">{{$provider->name}}</span>
                                        </div>
                                    </td>
                                    <td> @php echo $provider->statusBadge; @endphp </td>
                                    <td> @php echo $provider->defaultStatusBadge @endphp </td>
                                    <td>
                                        <div class="button--group">
                                            <button type="button" class="btn btn-sm btn-outline--primary ms-1 mb-2 configureBtn" data-provider='@json($provider)'>
                                                <i class="la la-cogs"></i> @lang('Configure')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--dark ms-1 mb-2 helpBtn"
                                                    data-provider="{{$provider }}">
                                                    <i class="la la-question"></i>@lang('Help')
                                            </button>
                                            @if($provider->status == Status::DISABLE)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline--success ms-1 mb-2 confirmationBtn"
                                                        data-action="{{ route('admin.currency.data.provider.status', $provider->id) }}"
                                                        data-question="@lang('Are you sure to enable this currency data provider')?">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline--danger mb-2 confirmationBtn"
                                                data-action="{{ route('admin.currency.data.provider.status', $provider->id) }}"
                                                data-question="@lang('Are you sure to disable this currency data provider')?">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                            @if($provider->is_default != Status::YES)
                                                <button type="button" class="btn btn-sm btn-outline--success mb-2 confirmationBtn"
                                                data-action="{{ route('admin.currency.data.provider.default', $provider->id) }}"
                                                data-question="@lang('Are you sure to set default this currency data provider')?">
                                                        <i class="la la-check"></i> @lang('Set Defualt')
                                                </button>
                                            @endif
                                        </div>
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
            </div>
        </div>
    </div>

    {{-- EDIT METHOD MODAL --}}
    <div id="modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Currency Data Provider'): <span class="provider-name"></span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST">
                    @csrf
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- HELP METHOD MODAL --}}
    <div id="helpModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Need Help')?</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
        <div class="d-inline">
            <div class="input-group justify-content-end">
                <input type="text" name="search_table" class="form-control bg--white" placeholder="@lang('Search')...">
                <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
            </div>
        </div>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $(document).on('click', '.configureBtn',function () {
                var modal     = $('#modal');
                var provider  = $(this).data('provider');
                var action    = "{{ route('admin.currency.data.provider.update',':id') }}";
                var shortcode = provider.configuration;

                modal.find('.provider-name').text(provider.name);
                modal.find('form').attr('action', action.replace(':id',provider.id));

                var html = '';
                $.each(shortcode, function (key, item) {
                    html += `<div class="form-group">
                        <label class="col-md-12 control-label fw-bold">${item.title}</label>
                        <div class="col-md-12">
                            <input name="${key}" class="form-control" placeholder="--" value="${item.value}" required>
                        </div>
                    </div>`;
                })
                modal.find('.modal-body').html(html);
                modal.modal('show');
            });

            $(document).on('click', '.helpBtn',function () {
                var modal = $('#helpModal');
                var path  = "{{ asset(getFilePath('extensions')) }}";
                const {name,instruction}  = $(this).data('provider');
                modal.find('.modal-title').html(`@lang('${name} api key setting instruction')`);
                modal.find('.modal-body').html(instruction);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
