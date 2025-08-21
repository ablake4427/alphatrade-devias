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
                            <h3 class="account-form__title mb-0">{{ __($pageTitle) }}</h3>
                            <p class="account-form__desc">
                                @lang('To recover your account please provide your email or username to find your account.')
                            </p>
                            <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha submit-form">
                                @csrf
                                <div class="form-group">
                                    <label class="form--label">@lang('Email or Username')</label>
                                    <input type="text" class="form--control" name="value" placeholder="@lang(' Email or username')" value="{{ old('value') }}"
                                           required autofocus="off">
                                </div>
                                <x-captcha />
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
