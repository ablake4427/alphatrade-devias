@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="order-details">
        <div class="order-details__header">
            <div class="container">
                <div class="order-details__header-left">
                    <div
                        class="d-flex flex-wrap align-items-center @if (Status::P2P_TRADE_COMPLETED != $trade->status) justify-content-between @else  justify-content-center text-center @endif">
                        <div>
                            <h4 class="title">
                                @lang('Order') @php echo str_replace("badge badge",'text',$trade->statusBadge) @endphp
                            </h4>
                            <p class="success-message">
                                @if (@$trade->buyer_id == $user->id)
                                    @lang('Buy')
                                    <span class="text--success">
                                        {{ showAmount($trade->asset_amount,currencyFormat:false) . ' ' . $trade->ad->asset->symbol }}
                                    </span>
                                    @lang('with ') <span class="text--danger">
                                        {{ showAmount($trade->fiat_amount,currencyFormat:false) . ' ' . $trade->ad->fiat->symbol }}
                                    </span>
                                @else
                                    @lang('Sell')
                                    <span class="text--danger">
                                        {{ showAmount($trade->asset_amount,currencyFormat:false) . ' ' . $trade->ad->asset->symbol }}
                                    </span>
                                    @lang('with ')
                                    <span class="text--success">
                                        {{ showAmount($trade->fiat_amount,currencyFormat:false) . ' ' . $trade->ad->fiat->symbol }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap flex-column text-end">
                            @if ($trade->buyer_id == $user->id && Status::P2P_TRADE_PENDING == $trade->status)
                                <div class="text-start text-md-end mt-2 mt-md-0">
                                    <button class="btn  btn--danger btn--sm outline confirmationBtn" data-question="@lang('Are you sure to cancel this trade')?"
                                        data-action="{{ route('user.p2p.trade.cancel', $trade->id) }}">
                                        <i class="las la-times-circle"></i> @lang('Cancel')
                                    </button>
                                    <button class="btn  btn--success btn--sm outline confirmationBtn" data-question="@lang('Are you sure to paid this trade')?"
                                        data-action="{{ route('user.p2p.trade.paid', $trade->id) }}">
                                        <i class="las la-check-circle"></i> @lang('Paid')
                                    </button>
                                </div>
                            @endif

                            @if (Status::P2P_TRADE_PENDING == $trade->status)
                                <div class="mt-2 mt-md-0">
                                    @if ($paymentTimeRemind > 0)
                                        <p class="text-start text-md-end"> {{ $user->id == $trade->seller_id ? 'You' : 'Seller' }} @lang(' will get permission to cancel this trade after')</p>
                                        <h3 class="mb-0 text--base text-start text-md-end">
                                            <span id="remind-time" class="text--danger">
                                                {{ (int) $paymentTimeRemind }}:{{ 60 - $paymentTimeRemindInSecond }}
                                            </span>
                                            @lang('MINUTE')
                                        </h3>
                                    @endif
                                    @if ($paymentTimeRemind <= 0 && $user->id == $trade->seller_id)
                                        <button class="btn  btn--danger btn--sm outline confirmationBtn" data-question="@lang('Are you sure to cancel this trade')?"
                                            data-action="{{ route('user.p2p.trade.cancel', $trade->id) }}">
                                            <i class="las la-times-circle"></i> @lang('Cancel')
                                        </button>
                                    @endif
                                </div>
                            @endif

                            @if ($trade->seller_id == $user->id && Status::P2P_TRADE_PAID == $trade->status)
                                <div class="mt-2 mt-md-0">
                                    <button class="btn  btn--danger btn--sm outline confirmationBtn" data-question="@lang('Are you sure to dispute this trade')?"
                                        data-action="{{ route('user.p2p.trade.dispute', $trade->id) }}">
                                        <i class="las la-times-circle"></i> @lang('Dispute')
                                    </button>
                                    <button class="btn  btn--success btn--sm outline confirmationBtn" data-question="@lang('Are you sure to release this trade')?"
                                        data-action="{{ route('user.p2p.trade.release', $trade->id) }}">
                                        <i class="las la-check-circle"></i> @lang('Release')
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="order-details__left">
                            <div class="d-flex justify-content-between">
                                <h6 class="order-details__left-title text--base">
                                    @lang('Trade Information')
                                </h6>
                            </div>
                            <div class="order-details__info-wrapper">
                                <div class="order-details__info">
                                    <div class="order-details__info-item">
                                        <span class="title"> @lang('Type') </span>
                                        <h6 class="amount-count mb-0">
                                            @if ($trade->ad->user_id == $user->id)
                                                @php echo $trade->ad->typeBadge @endphp
                                            @else
                                                @php echo $trade->typeBadge; @endphp
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="order-details__info-item">
                                        <span class="title"> @lang('Fiat Amount') </span>
                                        <h6 class="amount-count mb-0">
                                            {{ showAmount(@$trade->fiat_amount,currencyFormat:false) }} {{ __(@$trade->ad->fiat->symbol) }}
                                        </h6>
                                    </div>
                                    <div class="order-details__info-item">
                                        <span class="title">
                                            @lang('Asset Amount')
                                        </span>
                                        <h6 class="amount-count mb-0">
                                            {{ showAmount(@$trade->asset_amount,currencyFormat:false) }} {{ __(@$trade->ad->asset->symbol) }}
                                        </h6>
                                    </div>
                                    <div class="order-details__info-item">
                                        <span class="title"> @lang('Rate')</span>
                                        <h6 class="amount-count mb-0">
                                            {{ showAmount(@$trade->ad->price,currencyFormat:false) }} {{ __(@$trade->ad->fiat->symbol) }} /
                                            {{ __(@$trade->ad->asset->symbol) }}
                                        </h6>
                                    </div>
                                    <div class="order-details__info-item">
                                        <span class="title"> @lang('Order ID') </span>
                                        <h6 class="amount-count mb-0">
                                            {{ $trade->uid }}
                                        </h6>
                                    </div>
                                    <div class="order-details__info-item">
                                        <span class="title"> @lang('Time') </span>
                                        <h6 class="amount-count mb-0">
                                            {{ showDateTime($trade->created_at) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="order-details__payment-method-wrapper">
                                <div class="d-flex justify-content-between">
                                    <h6 class="order-details__left-title text--base">
                                        @lang('Payment Method')
                                    </h6>
                                </div>
                                <div class="order-details__info-wrapper">
                                    <div class="order-details__info">
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('Name') </span>
                                            <h6 class="amount-count mb-0">
                                                {{ __(@$trade->paymentMethod->name) }}
                                            </h6>
                                        </div>
                                        @foreach (@$sellerPaymentMethod->user_data ?? [] as $val)
                                            <div class="order-details__info-item">
                                                <span class="title"> {{ __(keyToTitle($val->name)) }}</span>
                                                <h6 class="amount-count mb-0">
                                                    @if ($val->type == 'checkbox')
                                                        {{ implode(',', $val->value ?? []) }}
                                                    @elseif($val->type == 'file')
                                                        <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"
                                                            class="me-3 text--base"><i class="fa fa-file"></i>
                                                            @lang('Attachment') </a>
                                                    @else
                                                        <span>{{ __($val->value) }}</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        @endforeach
                                        @if (@$sellerPaymentMethod->remark)
                                            <div class="order-details__info-item">
                                                <span class="title"> @lang('Remark')</span>
                                                <h6 class="amount-count mb-0">
                                                    {{ __(@$sellerPaymentMethod->remark) }}
                                                </h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="order-details__payment-method-wrapper">
                                <div class="d-flex justify-content-between">
                                    <h6 class="order-details__left-title">
                                        @if ($user->id == $trade->seller_id)
                                            <span class="text--success">@lang('Buyer Information')</span>
                                        @else
                                            <span class="text--danger">@lang('Seller Information')</span>
                                        @endif
                                    </h6>
                                </div>
                                <div class="order-details__info-wrapper">
                                    <div class="order-details__info">
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('Name') </span>
                                            <h6 class="amount-count mb-0">
                                                {{ __($trader->full_name) }}
                                            </h6>
                                        </div>
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('Mobile') </span>
                                            <h6 class="amount-count mb-0">
                                                {{ $trader->mobile }}
                                            </h6>
                                        </div>
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('Email') </span>
                                            <h6 class="amount-count mb-0">
                                                {{ $trader->email }}
                                            </h6>
                                        </div>
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('Email Verified') </span>
                                            <h6 class="amount-count mb-0">
                                                @if ($trader->ev)
                                                    <span class="text--success">@lang('Yes')</span>
                                                @else
                                                    <span class="text--danger">@lang('No')</span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('Mobile Verified') </span>
                                            <h6 class="amount-count mb-0">
                                                @if ($trader->sv)
                                                    <span class="text--success">@lang('Yes')</span>
                                                @else
                                                    <span class="text--danger">@lang('No')</span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="order-details__info-item">
                                            <span class="title"> @lang('KYC Verified') </span>
                                            <h6 class="amount-count mb-0">
                                                @if ($trader->kv)
                                                    <span class="text--success">@lang('Yes')</span>
                                                @else
                                                    <span class="text--danger">@lang('No')</span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-details__payment-method-wrapper">
                                <div class="d-flex justify-content-between">
                                    <h6 class="order-details__left-title text--base">
                                        @lang('Payment Details')
                                    </h6>
                                </div>
                                <div class="order-details__info-wrapper">
                                    @php echo @$trade->ad->payment_details @endphp
                                </div>
                            </div>
                            <div class="order-details__payment-method-wrapper">
                                <div class="d-flex justify-content-between">
                                    <h6 class="order-details__left-title text--base">
                                        @lang('Terms')
                                    </h6>
                                </div>
                                <div class="order-details__info-wrapper">
                                    @php echo @$trade->ad->terms_of_trade @endphp
                                </div>
                            </div>
                            @if (@$feedBackAbility && !@$tradeFeedback)
                                <div class="order-details__feedback">
                                    <h6 class="order-details__feedback-title mb-4">@lang('Feedback')</h6>
                                    <div class="order-details__feedback-btn">
                                        <button data-type="{{ Status::P2P_TRADE_FEEDBACK_POSITIVE }}" data-class="btn--success" type="button"
                                            class="feedback-btn btn--success">
                                            <span class="like">
                                                <i class="las la-thumbs-up"></i></span>
                                            @lang('Positive')
                                        </button>
                                        <button data-type="{{ Status::P2P_TRADE_FEEDBACK_NEGATIVE }}" data-class="btn--danger" type="button"
                                            class="feedback-btn">
                                            <span class="like"> <i class="las la-thumbs-down"></i></span>
                                            @lang('Negative')
                                        </button>
                                    </div>
                                    <div class="order-details__feedback-item">
                                        <form method="post" action="{{ route('user.p2p.trade.feedback', $trade->id) }}" class="feedback-form">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ Status::P2P_TRADE_FEEDBACK_POSITIVE }}">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form--label">@lang('Comment')</label>
                                                    <div class="mb-1">
                                                        <input class="form-check-input" type="checkbox" id="anonymous">
                                                        <label class="form--label" for="anonymous">@lang('Anonymous Comment')
                                                        </label>
                                                    </div>
                                                </div>
                                                <textarea class="form-ocntrol form--control" name="comment"></textarea>
                                            </div>

                                            <button type="submit" class="btn btn--base">@lang('Submit')</button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if (@$tradeFeedback)
                                <div class="order-details__feedback">
                                    <h6 class="order-details__feedback-title mb-4">
                                        @lang('Feedback')
                                        @if (Status::P2P_TRADE_FEEDBACK_POSITIVE == $tradeFeedback->type)
                                            <span class="text--success fs-12">
                                                (@lang('Positive'))
                                            </span>
                                        @else
                                            <span class="text--danger fs-12">
                                                (@lang('Negative'))
                                            </span>
                                        @endif
                                        <div class="order-details__feedback-btn mt-3 d-none show-hide-feedback">
                                            <button data-type="{{ Status::P2P_TRADE_FEEDBACK_POSITIVE }}" data-class="btn--success" type="button"
                                                class="feedback-btn @if ($tradeFeedback->type == Status::P2P_TRADE_FEEDBACK_POSITIVE) btn--success @endif">
                                                <span class="like">
                                                    <i class="las la-thumbs-up"></i></span>
                                                @lang('Positive')
                                            </button>
                                            <button data-type="{{ Status::P2P_TRADE_FEEDBACK_NEGATIVE }}" data-class="btn--danger" type="button"
                                                class="feedback-btn @if ($tradeFeedback->type == Status::P2P_TRADE_FEEDBACK_NEGATIVE) btn--danger @endif ">
                                                <span class="like"> <i class="las la-thumbs-down"></i></span>
                                                @lang('Negative')
                                            </button>
                                        </div>
                                    </h6>
                                    <div class="order-details__feedback-item d-none feedbackform">
                                        <form method="post" action="{{ route('user.p2p.trade.feedback', $trade->id) }}" class="feedback-form">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $tradeFeedback->type }}">
                                            <input type="hidden" name="feedback_id" value="{{ $tradeFeedback->id }}">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Comment')</label>
                                                <textarea class="form-ocntrol form--control" name="comment">{{ $tradeFeedback->comment }}</textarea>
                                            </div>
                                            <button type="button" class="btn btn--dark edit-cancel">@lang('Cancel')</button>
                                            <button type="submit" class="btn btn--base">@lang('Submit')</button>
                                        </form>
                                    </div>
                                    <div class="order-details__feedback-item">
                                        <h6 class="title mb-2">{{ __($tradeFeedback->comment) }}</h6>
                                        <div class="feedback-edit">
                                            <span class="fs-14">{{ showDateTime($tradeFeedback->created_at) }}</span>
                                            @if ($tradeFeedback->provide_by == $user->id)
                                                <button type="button" class="feedback-edit editBtn">
                                                    <i class="las la-edit"></i>
                                                </button>
                                                <button type="button" class="confirmationBtn" data-question="@lang('Are you sure to delete this feedback')?"
                                                    data-action="{{ route('user.p2p.trade.feedback.delete', $tradeFeedback->id) }}">
                                                    <i class="las la-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @include($activeTemplate . 'user.p2p.trade.chat', [
                            'trade' => $trade,
                            'user' => $user,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal isCustom="true" />
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $("#anonymous").on('click', function(e) {
                if (e.target.checked) {
                    $(`[name=comment]`).text("Anonymous Comment")
                } else {
                    $(`[name=comment]`).text("")
                }
            });

            $('.feedback-btn').on('click', function(e) {
                $(".feedback-btn").removeClass("btn--success btn--danger");
                $(this).addClass($(this).data('class'));
                $('.feedback-form').find(`[name=type]`).val($(this).data(`type`));
            });

            $('.editBtn').on('click', function(e) {
                $(".order-details__feedback-item").addClass('d-none');
                $(".feedbackform").removeClass('d-none');
                $(".order-details__feedback-btn ").removeClass('d-none');
            });

            $('.edit-cancel').on('click', function(e) {
                $(".order-details__feedback-item").removeClass('d-none');
                $(".feedbackform").addClass('d-none');
                $(".order-details__feedback-btn ").addClass('d-none');
            });

            let minute = Number("{{ $paymentTimeRemind }}");
            let second = 60 - Number("{{ $paymentTimeRemindInSecond }}");

            setInterval(() => {
                second = second - 1;
                if (second <= 0) {
                    second = 59;
                    minute = minute - 1;
                }
                $("#remind-time").text(`${parseInt(minute)}:${second < 10  ? ("0"+second): second}`)
            }, 1000);
        })(jQuery);
    </script>
@endpush
@push('style')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/p2p.css') }}" />
@endpush
