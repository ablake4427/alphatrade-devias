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
                            <h3 class="account-form__title mb-0">@lang('Reset Password')</h3>
                            <p class="account-form__desc">
                                @lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')
                            </p>
                            <form method="POST" action="{{ route('user.password.update') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group">
                                    <label class="form--label">@lang('Password')</label>
                                    <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif " name="password" placeholder="@lang('Enter password')" required>
                                </div>
                                <div class="form-group">
                                    <label class="form--label">@lang('Confirm Password')</label>
                                    <input type="password" class="form-control form--control" name="password_confirmation" autocomplete="off" placeholder="@lang('Enter confirm password')" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100"> @lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
