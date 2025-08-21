@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-left gy-4">
        <div class="col-lg-7">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="text--primary"><i class="las la-info-circle "></i>@lang('Trade Information')</h6>
                                <h6>{{ $trade->uid }}</h6>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom">@lang('Type')</span>
                                    <span> @php echo  $trade->typeBadge; @endphp </span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom">@lang('Status')</span>
                                    <span> @php echo  $trade->statusBadge; @endphp </span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom">@lang('Asset Amount')</span>
                                    <span> {{ showAmount(@$trade->asset_amount,currencyFormat:false) }}
                                        {{ __(@$trade->ad->asset->symbol) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom">@lang('Fiat Amount')</span>
                                    <span>{{ showAmount(@$trade->fiat_amount,currencyFormat:false) }} {{ __(@$trade->ad->fiat->symbol) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Rate')</span>
                                    <span>
                                        {{ showAmount(@$trade->ad->price,currencyFormat:false) }}
                                        {{ __(@$trade->ad->fiat->symbol) }}
                                        /
                                        {{ __(@$trade->ad->asset->symbol) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Payment Method')</span>
                                    <span>
                                        {{ __(@$trade->paymentMethod->name) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Payment Window')</span>
                                    <span>
                                        {{ __(@$trade->ad->paymentWindow->minute) }} @lang('Minute')
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card ">
                        <div class="card-header">
                            <h6 class="text--success"><i class="las la-user"></i> @lang('Buyer Information')</h6>
                        </div>
                        <div class="card-body p-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Name')</span>
                                    <span>{{ __(@$trade->buyer->full_name) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Email')</span>
                                    <span>{{ __(@$trade->buyer->email) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Mobile')</span>
                                    <span>{{ __(@$trade->buyer->mobile) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Join At')</span>
                                    <span class="text-end">
                                        {{ showDateTime(@$trade->buyer->created_at) }} <br>
                                        <small>{{ diffForHumans(@$trade->buyer->created_at) }}</small>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card ">
                        <div class="card-header">
                            <h6 class="text--danger"><i class="las la-user"></i> @lang('Seller Information')</h6>
                        </div>
                        <div class="card-body p-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Name')</span>
                                    <span>{{ __(@$trade->seller->full_name) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Email')</span>
                                    <span>{{ __(@$trade->seller->email) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Mobile')</span>
                                    <span>{{ __(@$trade->seller->mobile) }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="font-weight-custom"> @lang('Join At')</span>
                                    <span class="text-end">
                                        {{ showDateTime(@$trade->seller->created_at) }} <br>
                                        <small>{{ diffForHumans(@$trade->seller->created_at) }}</small>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @if (Status::P2P_TRADE_REPORTED == $trade->status)
                    <div class="col-12">
                        <div class="d-flex gap-3">
                            <button class="h-45 flex-fill btn btn--success confirmationBtn"
                                data-question="@lang('Are you sure to release USDT to the buyer?')"
                                data-action="{{ route('admin.p2p.trade.complete', ['id' => $trade->id, 'action' => 'buyer']) }}">
                                @lang('In favor of the Buyer')
                            </button>
                            <button class="h-45 flex-fill btn btn--danger confirmationBtn"
                                data-question="@lang('Are you sure to return USDT to the seller?')"
                                data-action="{{ route('admin.p2p.trade.complete', ['id' => $trade->id, 'action' => 'seller']) }}">
                                @lang('In favor of the seller')
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-5">
            <div class="chatboard-chat-area h-100">
                <div class="chat-box">
                    <div class="chat-box__header">
                        <div class="chat-box__author-content">
                            <h5 class="title mb-0"> @lang('Chat  History') </h5>
                        </div>
                    </div>
                    <div class="chat-box__thread" id="message">
                        @foreach ($messages as $message)
                            @include('admin.p2p.trade.single_message')
                        @endforeach
                    </div>
                    <div class="chat-box__footer">
                        <div class="chat-send-area">
                            <div class="chat-send-field">
                                <form action="{{ route('admin.p2p.trade.message.save', $trade->id) }}" method="POST"
                                    class="send__msg">
                                    <div class="input-group">
                                        <textarea type="text" id="message" syle="overflow:hidden" class="form--control form-control" name="message"
                                            placeholder="@lang('Message')..."></textarea>
                                        @if ($trade->status != Status::P2P_TRADE_COMPLETED && $trade->status != Status::P2P_TRADE_CANCELED )
                                            <button type="submit" class="btn--base btn-sm chat-send-btn">
                                                <i class="las la-paper-plane"></i>
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".send__msg").on('submit', function(e) {
                e.preventDefault();
                $(".skeleton").removeClass(".skeleton");
                const token = "{{ csrf_token() }}";
                const formData = new FormData($(this)[0]);
                const action = $(this).attr('action');
                const submitBtn = $(this).find(`button[type=submit]`);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: action,
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        submitBtn.attr('disabled', true);
                    },
                    complete: function() {
                        submitBtn.attr('disabled', false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $('#message').append(resp.data.html);
                            scollBottomChatBox();
                            $(".send__msg").trigger('reset');
                        } else {
                            notify("error", resp.message);
                        }
                    },
                    error: function(e) {
                        notify("error", "@lang('something went wrong')");
                    }
                });
            });

            function scollBottomChatBox() {
                const $element = $('#message');
                $element.scrollTop($element[0].scrollHeight);
            }

            function messageReceived(data) {
                $('#message').append(data.adminHtml);
                scollBottomChatBox();
            }

            scollBottomChatBox();

            pusherConnection(`p2p-message-{{ $trade->id }}-{{ $trade->seller_id }}`, messageReceived);
            pusherConnection(`p2p-message-{{ $trade->id }}-{{ $trade->buyer_id }}`, messageReceived);
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .list-group-item {
            border: 1px solid rgba(140, 140, 140, 0.125);
            padding: 10px;
        }

        .card-header {
            padding: 1rem;
        }

        .font-weight-custom {
            color: #34495e;
            font-weight: 500;
        }


        /* chatboard right design start here */

        .chat-box__header {
            padding: 1rem;
            background-color: #fff;
            border-bottom: 1px solid rgba(140, 140, 140, 0.125);
        }

        .chat-box__author-info {
            display: inline-flex;
            gap: 10px;
        }

        .chat-box__author-info .report-btn {
            color: #4634ff;
            font-size: 0.875rem;
        }

        .chat-box__author {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #4634ff;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            flex-shrink: 0;
        }

        .chat-author span {
            color: #fff !important;
        }


        .chat-box {
            border: 1px solid rgba(140, 140, 140, 0.125);
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff;
        }

        .chat-box__thread {
            height: 505px;
            overflow-y: auto;
            padding: 15px;
        }

        .chat-author {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #4634ff;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff !important;
            flex-shrink: 0;
        }

        .chat-author p {
            font-size: 0.875rem;
        }



        .chat-author .content .active-status {
            color: rgba(255, 255, 255, 0.6);
        }

        .single-message+.single-message {
            margin-top: 20px;
        }

        .single-message {
            display: flex;
            width: 100%;
            gap: 8px;
            max-width: 90%;
        }

        .single-message.message--right {
            margin-left: auto;
            justify-content: flex-end;
        }

        .single-message.message--right .message-content-outer-wrapper {
            display: flex;
            gap: 10px;
        }

        .single-message.message--left .message-content-outer-wrapper {
            display: flex;
            gap: 10px;
        }

        .single-message.message--left {
            flex-direction: row-reverse;
            margin-right: auto;
            justify-content: flex-end;
        }

        .single-message.message--left .message-content {
            border-radius: 10px 10px 10px 0;
        }

        .single-message.message--left .message-time {
            text-align: left !important;
        }

        .single-message .message-content {
            padding: 8px 10px;
            min-width: 180px;
            background-color: #f3f3f9;
        }

        .single-message.message--right .message-content {
            border-radius: 10px 10px 0 10px;
        }

        .single-message.message--left .message-content {
            background-color: #f3f3f9;
            margin-left: auto;
        }

        .single-message.message--right .message-content {
            background-color: #f3f3f9;
            color: #fff;
        }


        .single-message.message--right .name {
            order: -1;
            padding-left: 0;
            padding-right: 15px;
            font-size: 15px;
        }

        .single-message .message-content .name {
            font-size: 16px;
        }

        .single-message .message-text {
            font-size: 14px;
            display: block;
        }

        .single-message .message-author {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            align-items: center;
            width: 35px;
            height: 35px;
        }



        .single-message .message-time {
            font-size: 12px;
            font-style: italic;
        }

        .single-message .messgae-attachment {
            margin-top: 10px;
        }

        .chat-box textarea.form--control {
            min-height: 50px !important;
            border-radius: 30px !important;
            width: 100% !important;
            padding-right: 45px;
            height: 45px !important;
            resize: none;
            font-size: 0.875rem;
            padding: 12px 18px;
        }

        .chat-send-btn {
            border-radius: 30px !important;
            position: absolute;
            right: 7px;
            top: 5px;
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #4634ff;
            color: #fff;
            z-index: 3;
        }

        .chat-box__footer {
            padding: 15px 15px;
            border-top: 1px solid rgba(140, 140, 140, 0.125);
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
@endpush
