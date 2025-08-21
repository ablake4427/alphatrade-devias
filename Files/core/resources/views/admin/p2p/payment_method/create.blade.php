@extends('admin.layouts.app')
@section('panel')
    <form method="POST" action="{{ route('admin.p2p.payment.method.save', @$gateway->id) }}">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('Basic Information')</h5>
                        <span class="text--small">
                            <i>@lang('Create a P2P payment method by supplying essential details such as name, slug, & currency. This payment method is intended for peer-to-peer trading.')</i>
                        </span>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="form-group col-sm-8">
                                <label>@lang('Name')</label>
                                <div class="input-group">
                                    <input type="name" class="form-control" name="name" value="{{ old('name', @$gateway->name) }}" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label> @lang('Branding Color')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input type='text' class="form-control colorPicker" value="{{ @$gateway->branding_color }}" />
                                    </span>
                                    <input type="text" class="form-control colorCode" name="branding_color" value="{{ @$gateway->branding_color }}" />
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>@lang('Slug')</label>
                                <div class="input-group">
                                    <input type="name" class="form-control" name="slug" value="{{ old('slug', @$gateway->slug) }}" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group position-relative" id="currency_list_wrapper">
                                    <label>@lang('Currency')</label>
                                    <x-currency-list name="supported_currency[]" :type="Status::FIAT_CURRENCY" multiple="true" valueType="2" displayType="2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5>@lang('User Data')</h5>
                            <span class="text--small">
                                <i>@lang("Create an interactive form for gathering user information related to P2P payment methods, specifically designed for P2P activities utilizing the payment method you're constructing.")</i>
                            </span>
                        </div>
                        <div>
                            <button type="button"
                                class="btn btn-sm btn-outline--primary float-end form-generate-btn @if (!@$gateway && !@$gateway->userData) d-none @endif">
                                <i class="la la-fw la-plus"></i>@lang('Add New')
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <x-generated-form :form="@$gateway->userData" />
                            <div class="col-lg-12 @if (@$gateway && $gateway->userData) d-none @endif" id="empty-message">
                                <div class="text-center p-5">
                                    <img src="{{ asset('assets/images/extra_images/empty_two.png') }}" alt="">
                                    <span class="text--small d-block">
                                        @lang('No user data currently exists. Begin by ')
                                        <span class="text--primary create-first">@lang('Creating')</span>
                                        @lang('your initial set of user data')
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
            </div>
        </div>
    </form>
    <x-form-generator-modal />
@endsection


@push('script')
    <script>
        "use strict";

        (function($) {
            const manageEmptyMessage = () => {
                if ($(".addedField").find('.form-field-wrapper').length) {
                    $("#empty-message").addClass('d-none');
                    $(".form-generate-btn").removeClass('d-none');
                } else {
                    $("#empty-message").removeClass('d-none');
                    $(".form-generate-btn").addClass('d-none');
                }
            }

            $('.create-first').on('click', function() {
                $('.form-generate-btn').trigger('click')
            });

            $('#formGenerateModal').on('hide.bs.modal', function() {
                manageEmptyMessage()
            });

            $('body').on('click', '.removeFormData', function(e) {
                setTimeout(() => manageEmptyMessage());
            });

            const slug = () => {
                const name = $('input[name=name]').val();
                const slug = name.toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');

                $('input[name=slug]').val(slug)
            }

            $('input[name=name]').on('input', slug);

            @if (@$gateway && @$gateway->supported_currency)
                @json(@$gateway->supported_currency).forEach(element => {
                    let newOption = new Option(element, element, true, true);
                    $('#currency_list').append(newOption);
                });
            @endif

            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .create-first {
            cursor: pointer;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush
