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
                                <th>@lang('Gateway')</th>
                                <th>@lang('Slug')</th>
                                <th>@lang('Supported Currency')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($paymentMethods as $paymentMethod)
                                <tr>
                                    <td>{{__($paymentMethod->name)}}</td>
                                    <td>{{$paymentMethod->slug}}</td>
                                    <td>
                                        <span class="text--primary total-supported-currency" data-support-currency='@json(@$paymentMethod->supported_currency)'>
                                             {{ count(@$paymentMethod->supported_currency ?? []) }} 
                                        </span> 
                                    </td>
                                    <td>@php echo $paymentMethod->statusBadge @endphp</td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.p2p.payment.method.edit', $paymentMethod->id) }}" class="btn btn-sm btn-outline--primary editGatewayBtn">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </a>
                                            @if($paymentMethod->status == Status::DISABLE)
                                                <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-question="@lang('Are you sure to enable this payment method')?" data-action="{{ route('admin.p2p.payment.method.status',$paymentMethod->id) }}">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn" data-question="@lang('Are you sure to disable this payment method')?" data-action="{{ route('admin.p2p.payment.method.status',$paymentMethod->id) }}">
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
                 @if ($paymentMethods->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($paymentMethods) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
    <div id="modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Supported Currency')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
<x-search-form placeholder="Name" />
<a class="btn btn-outline--primary h-45" href="{{route('admin.p2p.payment.method.create')}}">
    <i class="la la-plus"></i> @lang('New Payment Method')
</a>
@endpush

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('.total-supported-currency').on('click',function(e){
                const supportCurrency=$(this).data('support-currency');
                var html="";
                if(supportCurrency){
                    $.each(supportCurrency, function (i, currency) { 
                        html+=`<span class="badge badge--primary">${currency}</span>`
                    });
                    $("#modal").find(".modal-body").html(`<div class="d-flex flex-wrap gap-2 pt-2 pb-2">${html}</div>`);
                    $("#modal").find('.modal-dialog').removeClass('modal-lg');
                }else{
                    $("#modal").find(".modal-body").html(`
                        <div class="text-center p-5">
                            <img src="{{ asset('assets/images/extra_images/empty_two.png') }}" alt="">
                            <span class="text--small d-block">
                                @lang('No supported currency found')
                            </span>
                        </div>
                        `);
                    $("#modal").find('.modal-dialog').addClass('modal-lg');
                }
                $("#modal").modal('show');
            })
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .total-supported-currency{
            cursor: pointer;
        }
    </style>
@endpush