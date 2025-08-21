@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @php
        $languages = App\Models\Language::get();
        $content = getContent('login.content', true);
        $policyPages = getContent('policy_pages.element', false, null, true);
        $langDetails = $languages->where('code', config('app.locale'))->first();
        $credentials = gs('socialite_credentials');
    @endphp

    <section class="account">
        <div class="account-inner">
            <div class="account-left">
                <a href="{{ route('home') }}" class="account-left__logo">
                    <img src="{{ getImage(getFilePath('logoIcon') . '/logo_base.png') }}">
                </a>
                <div class="account-left__content">
                    <h5 class="account-left__subtitle">{{ __(@$content->data_values->heading_one) }}</h5>
                    <h3 class="account-left__title">{{ __(@$content->data_values->subheading_one) }}</h3>
                </div>
                <div class="account-left__thumb">
                    <img src="{{ getImage('assets/images/frontend/login/' . @$content->data_values->image, '600x600') }}">
                </div>
            </div>
            <div class="account-right-wrapper">
                <div class="account-content__top">
                    <div class="account-content__member gap-2">
                        <p class="account-content__member-text"> @lang("Don't have an account")? </p>
                        <a href="{{ route('user.register') }}" class="account-link">@lang('Sign Up')</a>
                        @if (gs('multi_language'))
                            <div class="custom--dropdown">
                                <div class="custom--dropdown__selected dropdown-list__item">
                                    <div class="thumb">
                                        <img
                                             src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                    </div>
                                    <span class="text">{{ __(@$langDetails->name) }}</span>
                                </div>
                                <ul class="dropdown-list">
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item change-lang " data-code="{{ @$language->code }}">
                                                <div class="thumb">
                                                    <img
                                                         src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                                </div>
                                                <span class="text">{{ __(@$language->name) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </ul>
                            </div>
                        @endif
                        <div class="theme-switch-wrapper">
                            <label class="theme-switch" for="checkbox">
                                <input type="checkbox" class="d-none" id="checkbox">
                                <span class="slider">
                                    <i class="las la-sun"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="account-right">
                    <div class="account-content">
                        <div class="account-form">
                            <h3 class="account-form__title mb-0">{{ __(@$content->data_values->heading_two) }}</h3>
                            <p class="account-form__desc">{{ __(@$content->data_values->subheading_two) }}</p>
                            @include($activeTemplate . 'partials.social_loign')


                            <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                                @csrf
                                <div class="form-group">
                                    <label class="form--label">@lang('Username or Email')</label>
                                    <input type="text" name="username" value="{{ old('username') }}"
                                           class="form--control" placeholder="@lang('Enter your username or email')">
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between">
                                        <label class="form--label">@lang('Password')</label>
                                        <a href="{{ route('user.password.request') }}"
                                           class="forget-password">@lang('Forget Password')?</a>
                                    </div>
                                    <div class="position-relative">
                                        <input name="password" type="password" class="form--control"
                                               placeholder="@lang('Enter your password')">
                                        <div class="password-show-hide far fa-eye toggle-password fa-eye-slash"
                                             id="#toogle-password"></div>
                                    </div>
                                </div>
                                <x-captcha isCustom="true" />
                                <div class="form-group form-check"><input class="form-check-input" type="checkbox"
                                           name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        @lang('Remember Me')
                                    </label>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Log In')</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row gy-3 mt-auto">
                    <div class="col-md-6">
                        <div class="bottom-footer__text">
                            @php echo copyRightText(); @endphp
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bottom-footer__right">
                            <span class="bottom-footer__right-text">
                                @foreach ($policyPages as $policy)
                                    <a class="bottom-footer__right-link" href="{{ route('policy.pages', $policy->slug) }}"
                                       target="_blank">
                                        {{ __(@$policy->data_values->title) }}
                                    </a>
                                @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
