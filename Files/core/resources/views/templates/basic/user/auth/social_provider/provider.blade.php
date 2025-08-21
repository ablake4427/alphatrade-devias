@props(['provider','action'])

<div class="continue-google">
    <a href="{{ route('user.social.login', $provider) }}" class="btn w-100">
        <span class="google-icon">
            <img src="{{ asset($activeTemplateTrue . "images/icons/$provider.svg") }}">
        </span> {{ __(ucfirst($action)) }} @lang('with ') {{ __(ucfirst($provider)) }}
    </a>
</div>
