
@php
$blogContent = getContent('blog.content', true);
$blogs       = getContent('blog.element');
@endphp

@if ($blogContent)
<div class="ongoing-campaign section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <span class="ongoing-campaign__title"> {{ __(@$blogContent->data_values->heading) }} </span>
            </div>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($blogs as $blog)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.details', @$blog->slug) }}"
                            class="testimonials-card">
                            <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '300x150') }}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
