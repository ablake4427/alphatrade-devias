@php
    $registrationDisabled = getContent('register_disable.content', true);
@endphp
<div class="register-disable">
    <div class="container">
        <div class="register-disable-image mb-4 text-center">
            <img class="mw-100 mh-100" src="{{ frontendImage('register_disable', @$registrationDisabled->data_values->image, '280x280') }}"
                alt="">
        </div>

        <div class="text-center">
            <h5 class="register-disable-title">{{ __(@$registrationDisabled->data_values->heading) }}</h5>
            <p class="register-disable-desc">
                {{ __(@$registrationDisabled->data_values->subheading) }}
            </p>
            <div class="text-center mt-3">
                <a href="{{ @$registrationDisabled->data_values->button_url }}"
                    class="btn btn--base btn--sm">{{ __(@$registrationDisabled->data_values->button_name) }}</a>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .register-disable {
            display: flex;
            align-items: center;
            min-height: 100vh;
            align-items: center;
            width: 100%;
        }
    </style>
@endpush
