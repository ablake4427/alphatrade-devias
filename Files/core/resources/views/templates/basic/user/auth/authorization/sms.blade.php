@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @php
        $content = getContent('account_verification.content', true);
    @endphp
    <section class="account">
        <div class="account-inner">
            <div class="account-left">
                <a href="{{ route('home') }}" class="account-left__logo">
                    <img src="{{ getImage(getFilePath('logoIcon') . '/logo_base.png') }}">
                </a>
                <div class="account-left__content">
                    <h5 class="account-left__subtitle mb-0">{{ __(@$content->data_values->title) }}</h5>
                    <h3 class="account-left__title">{{ __(@$content->data_values->heading) }}</h3>
                </div>
                <div class="account-left__thumb">
                    <img src="{{ getImage('assets/images/frontend/account_verification/' . @$content->data_values->image, '600x600') }}">
                </div>
            </div>
            <div class="account-right-wrapper">
                <div class="account-right account-right-custom">
                    <div class="account-content">
                        <div class="account-form">
                            <h3 class="account-form__title mb-0">@lang('Verify Mobile Number')</h3>
                            <p class="account-form__desc">
                                @lang('A 6 digit verification code sent to your mobile number') : +{{ showMobileNumber(auth()->user()->mobileNumber) }}
                            </p>
                            <form action="{{ route('user.verify.mobile') }}" method="POST" class="submit-form">
                                @csrf
                                @include($activeTemplate . 'partials.verification_code')
                                <div class="mb-3">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                                <div class="form-group">
                                    <p>
                                        @lang('If you don\'t get any code'), <span class="countdown-wrapper">@lang('try again after') <span id="countdown"
                                                  class="fw-bold text--base">--</span> @lang('seconds')</span> <a
                                           href="{{ route('user.send.verify.code', 'sms') }}" class="try-again-link d-none"> @lang('Try again')</a>
                                    </p>
                                    @if ($errors->has('resend'))
                                        <br />
                                        <small class="text--danger">{{ $errors->first('resend') }}</small>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@push('script')
    <script>
        var distance = Number("{{ @$user->ver_code_send_at->addMinutes(2)->timestamp - time() }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush
