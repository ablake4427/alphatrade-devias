@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="@guest py-120 mt-5 mt-md-0 @endguest">
        <div class="@guest container @endguest">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="d-flex flex-between flex-wrap align-items-center">
                        <h5 class="title mb-0">
                            @php echo $myTicket->statusBadge; @endphp
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h5>
                        <div>
                            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                <button type="button" class="btn btn--danger close-button btn--sm confirmationBtn outline" type="button"
                                    data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}">
                                    <i class="las la-times-circle"></i> @lang('Close Ticket')
                                </button>
                            @endif
                            @auth
                                <a href="{{ route('ticket.index') }}" class="btn btn--base btn--sm outline ms-1">
                                    <i class="las la-list"></i> @lang('My Tickets')
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card custom--card">

                        <div class="card-body">
                            <form action="{{ route('ticket.reply', $myTicket->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-3">
                                    <div class="form--group col-sm-12">
                                        <label for="message" class="form-label required">@lang('Message')</label>
                                        <textarea name="message" class="form-control form--control" required>{{ old('message') }}</textarea>
                                    </div>
                                    <div class="form--group col-sm-12 attachment-wrapper d-none">
                                        <div class="file-upload"></div>
                                        <div id="fileUploadsContainer"></div>
                                        <p class="ticket-attachments-message text-muted mt-2">
                                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                            .@lang('pdf'), .@lang('doc'), .@lang('docx'). &nbsp;
                                            <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-dark btn-sm addAttachment my-2"> <i class="fas fa-plus"></i>
                                            @lang('Add Attachment') </button>
                                        <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                    </div>
                                    <div class="col-md-3 form--group">
                                        <button type="submit" class="btn btn--base w-100"> <i class="fa fa-reply"></i> @lang('Reply')</button>
                                    </div>
                                    <div class="col-12">
                                        <div class="row fileUploadsContainer">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card custom--card mt-4">
                        <div class="card-body">
                            @foreach ($messages as $message)
                                @if ($message->admin_id == 0)
                                    <div class="row border border--base border-radius-3 my-3 py-3 mx-2">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3">{{ $message->ticket->fullname  }}</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="text-muted fw-bold my-3">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                            <p>{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3 text--base"><i
                                                                class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row border border--warning border-radius-3 my-3 py-3 mx-2">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3">{{ $message->admin->name }}</h5>
                                            <p class="lead text-muted">@lang('Staff')</p>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="text-muted fw-bold my-3">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                            <p>{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i
                                                                class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal isCustom="true" />
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger text-white"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `);
            });

            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);

        ///customize confirmation modal
        window.addEventListener('DOMContentLoaded', function(e) {
            let confirmationModal = $('#confirmationModal');
            if (confirmationModal.length > 0) {
                $(confirmationModal).find('.btn--primary').addClass('btn--base btn--sm').removeClass('btn--primary');
                $(confirmationModal).find('.btn--dark').addClass('btn--sm');
            }
        });
    </script>
@endpush
