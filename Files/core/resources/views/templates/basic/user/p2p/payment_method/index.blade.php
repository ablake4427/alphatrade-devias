@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
    @if ($paymentMethods->count())
        <div class="payment-methods-tabs">
            @foreach ($paymentMethods as $paymentMethod)
                <div class="payment-methods__system">
                    <div class="payment-methods__system-item">
                        <div class="payment-methods__system-header d-flex align-items-center justify-content-between mb-3">
                            <div class="methods-name">
                                <p>
                                    <span style="background-color: #{{ $paymentMethod->paymentMethod->branding_color }}"></span>
                                    {{ __($paymentMethod->paymentMethod->name) }}
                                </p>
                            </div>
                            <div class="payment-methods__action">
                                <a href="{{ route('user.p2p.payment.method.edit', $paymentMethod->id) }}" class="action-btn">
                                    <i class="las la-edit"></i>
                                </a>
                                <button class="action-btn text--danger confirmationBtn" type="button"
                                    data-question="@lang('Are you sure to remove this payment method')?"
                                    data-action="{{ route('user.p2p.payment.method.delete', $paymentMethod->id) }}">
                                    <i class="las la-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="payment-methods__system-bottom">
                            @foreach ($paymentMethod->user_data as $val)
                                <div class="payment-methods__system-bottom-left mb-2">
                                    <p> {{ __(keyToTitle($val->name)) }}</p>
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value ?? []) }}
                                        @elseif($val->type == 'file')
                                            <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"
                                                class="me-3 text--base"><i class="fa fa-file"></i> @lang('Attachment') </a>
                                        @else
                                            <span>{{ __($val->value) }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                            @if ($paymentMethod->remark)
                                <div class="payment-methods__system-bottom-left">
                                    <p> @lang('Remark')</p>
                                    <span>{{ __($paymentMethod->remark) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{paginateLinks($paymentMethods)}}
        </div>
    @else
        @include($activeTemplate . 'user.p2p.empty_message', [
            'text' => 'Add Payment Method',
            'message' =>
                "You haven't added any payment methods. By clicking below button to set up your preferred payment method.",
            'url' => route('user.p2p.payment.method.create'),
        ])
    @endif
    <x-confirmation-modal isCustom="true" />
@endsection

