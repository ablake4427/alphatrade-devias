@props(['action'])


@php
    $credentials = gs('socialite_credentials');
@endphp

@if (@$credentials->google->status == Status::ENABLE)
    <x-flexible-view :view="$activeTemplate . 'user.auth.social_provider.provider'" action="{{ $action }}" provider="google" />
@endif

@if (@$credentials->facebook->status == Status::ENABLE)
    <x-flexible-view :view="$activeTemplate . 'user.auth.social_provider.provider'" action="{{ $action }}" provider="facebook" />
@endif

@if (@$credentials->linkedin->status == Status::ENABLE)
    <x-flexible-view :view="$activeTemplate . 'user.auth.social_provider.provider'" action="{{ $action }}" provider="linkedin" />
@endif
