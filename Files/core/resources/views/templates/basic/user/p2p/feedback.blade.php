@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
    @if ($feedbacks->count())
        <div class="payment-methods-tabs">
            @foreach ($feedbacks as $feedback)
                <div class="payment-methods__system">
                    <div class="payment-methods__system-item">
                        <div class="payment-methods__system-header d-flex align-items-center justify-content-between mb-3">
                            <div class="methods-name">
                                <p>
                                    @if ($feedback->type == Status::P2P_TRADE_FEEDBACK_POSITIVE)
                                        <span class="bg--success"></span>
                                        @lang('Positive')
                                    @else
                                        <span class="bg--danger"></span>
                                        @lang('Negative')
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="payment-methods__system-bottom">
                            <div class="payment-methods__system-bottom-left">
                                <span>{{ __($feedback->comment) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($feedbacks->hasPages())
                {{ paginateLinks($feedbacks) }}
            @endif
        </div>
    @else
        @include($activeTemplate . 'user.p2p.empty_message', [
            'text' => 'Trade Now',
            'message' => 'No feedback found. Click the below button to explore our P2P trade.',
            'url' => route('p2p'),
            'icon' => 'far fa-chart-bar',
        ])
    @endif
@endsection
