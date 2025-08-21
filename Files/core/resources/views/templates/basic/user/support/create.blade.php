@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="d-flex flex-between flex-wrap align-items-center">
                <h5 class="title mb-0">{{ __($pageTitle) }}</h5>
                <a href="{{ route('ticket.index') }}" class="btn btn--base btn--sm outline">
                    <i class="las la-list"></i> @lang('My Tickets')
                </a>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body">
                    <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-md-6 form-group">
                                <label class="form-label">@lang('Name')</label>
                                <input type="text" name="name" value="{{ @$user->firstname . ' ' . @$user->lastname }}"
                                    class="form-control form--control" required readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">@lang('Email Address')</label>
                                <input type="email" name="email" value="{{ @$user->email }}" class="form-control form--control" required readonly>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="form-label">@lang('Subject')</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control form--control" required>
                            </div>
                            <div class="col-md-6 form-group position-relative" >
                                <label class="form-label">@lang('Priority')</label>
                                <select name="priority" class="form-control form--control select2" required data-minimum-results-for-search="-1" data-width="100%">
                                    <option value="3">@lang('High')</option>
                                    <option value="2">@lang('Medium')</option>
                                    <option value="1">@lang('Low')</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label required">@lang('Message')</label>
                                <textarea name="message" id="inputMessage" rows="6" class="form-control form--control" required>{{ old('message') }}</textarea>
                            </div>

                            <div class="col-md-9">
                                <button type="button" class="btn btn-dark btn-sm addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment')
                                </button>
                                <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                <div class="row fileUploadsContainer">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn--base w-100 my-2" type="submit"><i class="las la-paper-plane"></i> @lang('Submit')
                                </button>
                            </div>

                            <div class="form--group col-sm-12 attachment-wrapper d-none">
                                <div class="file-upload"></div>
                                
                                <p class="ticket-attachments-message text-muted mt-2">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                    .@lang('pdf'), .@lang('doc'), .@lang('docx'). &nbsp;
                                    <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}
                                    </small>
                                </p>
                            </div>
                            <div class="col-12">
                                <div id="fileUploadsContainer"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush






@push('script')
    <script>
        (function ($) {
            "use strict";

         

            var fileAdded = 0;
            $('.addAttachment').on('click',function(){
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled',true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click','.removeFile',function(){
                $('.addAttachment').removeAttr('disabled',true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush