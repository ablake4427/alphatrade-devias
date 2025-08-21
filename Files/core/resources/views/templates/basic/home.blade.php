@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $banner   = getContent('banner.content', true);
        $elements = getContent('banner.element');
    @endphp

    <section class="banner-section">
        <div class="banner-section__shape light-mood">
            <img src="{{ asset($activeTemplateTrue . 'images/shapes/banner_1.png') }}">
        </div>
        <div class="banner-section__shape dark-mood">
            <img src="{{ asset($activeTemplateTrue . 'images/shapes/banner_1_dark.png') }}">
        </div>
        <div class="banner-section__shape-one light-mood">
            <img src="{{ asset($activeTemplateTrue . 'images/shapes/bg.png') }}">
        </div>
        <div class="banner-section__shape-one dark-mood">
            <img src="{{ asset($activeTemplateTrue . 'images/shapes/bg_dark.png') }}">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner-content ">
                        <h1 class="banner-content__title">
                            @php echo highLightedString(@$banner->data_values->heading); @endphp
                        </h1>
                        <p class="banner-content__desc">
                            @php echo highLightedString(@$banner->data_values->subheading,'fw-bold'); @endphp
                        </p>
                        <div class="banner-content__button d-flex align-items-center gap-3">
                            <a href="{{ @$banner->data_values->button_link }}" class="btn btn--base">
                                {{ __(@$banner->data_values->button_text) }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-right">
                        <div class="banner-right__thumb">
                            <img src="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image_one, '500x360') }}">
                            <div class="banner-right__thumb-shape">
                                <img src="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image_two, '155x155') }}">
                            </div>
                        </div>
                        <div class="banner-right__shape">
                            <img src="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image_three, '450x285') }}">
                        </div>
                        <div class="banner-right__bg">
                            <div class="banner-right__shape-bg-one bg"></div>
                            <div class="banner-right__shape-bg-two bg"></div>
                            <div class="banner-right__shape-bg-three bg"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-slider">
                        @foreach ($elements as $element)
                            <div class="single-slider__item">
                                <span class="single-slider__desc">
                                    <span class="badge badge--success">{{ __(@$element->data_values->badge) }}</span>
                                    {{ __(@$element->data_values->title) }}
                                    <a href="{{ @$element->data_values->link }}" class="single-slider__link"> @lang('More') </a>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include($activeTemplate . 'sections.blog')

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/swiper.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/swiper.css') }}">
@endpush

@php app()->offsetSet('swiper_assets',true) @endphp
