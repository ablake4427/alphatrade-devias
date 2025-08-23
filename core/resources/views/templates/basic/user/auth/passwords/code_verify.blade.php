@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @php
        $content = getContent('account_recovery.content', true);
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
                    <img src="{{ getImage('assets/images/frontend/account_recovery/' . @$content->data_values->image, '600x600') }}">
                </div>
            </div>
            <div class="account-right-wrapper">
                <div class="account-right account-right-custom">
                    <div class="account-content">
                        <div class="account-form">
                            <h3 class="account-form__title mb-0">@lang('Verify Email Address')</h3>
                            <p class="account-form__desc">
                                @lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}
                            </p>
                            <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                @include($activeTemplate . 'partials.verification_code')
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                                <div class="form-group">
                                    @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                    <a class="text--base" href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
