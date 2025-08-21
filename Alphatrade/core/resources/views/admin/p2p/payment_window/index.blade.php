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
                                    <th>@lang('Payment Window')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($windows as  $window)
                                    <tr>
                                        <td>{{ __($window->minute) }} @lang('Minute')</td>
                                        <td>
                                            @php echo $window->statusBadge @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn" data-edit='@json($window)'>
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($window->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to enable this payment window')?"
                                                        data-action="{{ route('admin.p2p.payment.window.status', $window->id) }}">
                                                        <i class="la la-eye"></i>@lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to disable this payment window')?"
                                                        data-action="{{ route('admin.p2p.payment.window.status', $window->id) }}">
                                                        <i class="la la-eye-slash"></i>@lang('Disable')
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
                @if ($windows->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($windows) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
    <div id="modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">@lang('New Payment Window')</h5>
                        <p class="text--small">
                            @lang('Create multiple payment window at a time by pressing ') <i class="text--primary fw-bold">@lang('enter.')</i>
                        </p>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('admin.p2p.payment.window.save')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Minute')</label>
                            <input type="number" class="form-control create-multiple-field" name="minute" value="{{ old('minute') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 submitForm ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary addBtn">
        <i class="la la-plus"></i> @lang('New Payment Window')
    </button>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            const modal = $("#modal");

            $('.addBtn').on('click', function(e) {
                modal.find("form").trigger("reset");
                $(`input[name=minute]`).addClass("create-multiple-field");
                modal.find("form").attr('action',"{{route('admin.p2p.payment.window.save')}}");
                modal.modal('show')
            });

            $(".editBtn").on('click',function(e){
                const {edit:paymentWindow} = $(this).data();
                const action               = "{{route('admin.p2p.payment.window.save',':id')}}";
                modal.find("input[name=minute]").val(paymentWindow.minute);
                modal.find("form").attr('action',action.replace(":id",paymentWindow.id));
                $(`input[name=minute]`).removeClass("create-multiple-field");
                modal.modal('show')
            });

            $(`body`).on('keydown','.create-multiple-field',function (e) { 
                if(e.keyCode == 13){
                    $(".modal-body").append(`
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" class="form-control create-multiple-field" name="multiple_payment_window[]" required>
                                <span class="input-group-text bg--danger border-0 removeElement">
                                    <i class="las la-times"></i>
                                </span>
                            </div>
                        </div>
                    `);
                }   
            });
            $('.modal').on('click','.removeElement',function (e) { 
                $(this).closest('.form-group').remove();
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .removeElement{
            cursor: pointer;
        }
    </style>
@endpush
