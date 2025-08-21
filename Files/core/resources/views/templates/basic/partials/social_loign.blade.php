@if (
    @$credentials->linkedin->status ||
        @$credentials->facebook->status == Status::ENABLE ||
        @$credentials->google->status == Status::ENABLE)
    <div class=" d-flex gap-3 flex-wrap">
        @if (@$credentials->google->status == Status::ENABLE)
            <div class="continue-google flex-fill">
                <a href="{{ route('user.social.login', 'google') }}" class="btn w-100">
                    <span class="google-icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icons/google.svg') }}">
                    </span>
                </a>
            </div>
        @endif
        @if (@$credentials->facebook->status == Status::ENABLE)
            <div class="continue-google flex-fill">
                <a href="{{ route('user.social.login', 'facebook') }}" class="btn w-100">
                    <span class="google-icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icons/facebook.svg') }}">
                    </span>
                </a>
            </div>
        @endif
        @if (@$credentials->linkedin->status == Status::ENABLE)
            <div class="continue-google flex-fill">
                <a href="{{ route('user.social.login', 'linkedin') }}" class="btn w-100">
                    <span class="google-icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icons/linkedin.svg') }}">
                    </span>
                </a>
            </div>
        @endif
    </div>
@endif
@if (gs('metamask_login'))
    @include($activeTemplate . 'partials.metamask_login')
@endif

@if (
    @$credentials->linkedin->status ||
        @$credentials->facebook->status == Status::ENABLE ||
        @$credentials->google->status == Status::ENABLE ||
        gs('metamask_login'))
    <div class="other-option">
        <span class="other-option__text">@lang('OR')</span>
    </div>
@endif
