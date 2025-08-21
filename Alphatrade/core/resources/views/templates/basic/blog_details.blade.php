@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-120">
        <div class="container">
            <div class="row gy-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="blog-details">
                        <div class="blog-details__thumb">
                            <img class="w-100"
                                src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '730x465') }}">
                        </div>
                        <div class="blog-details__content">
                            <ul class="text-list">
                                <li class="text-list__item">
                                    <span class="text-list__item-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ showDateTime($blog->create_at, 'M d, Y') }}
                                    </span>
                                </li>
                            </ul>
                            <h3 class="blog-details__title ms-0">{{ __(@$blog->data_values->title) }}
                                </h2>
                                <div class="blog-details__desc">
                                    @php echo @$blog->data_values->description_nic; @endphp
                                </div>
                                <div
                                    class="blog-details__share d-flex align-items-center flex-wrap justify-content-between gap-2">
                                    <h5 class="social-share__title mb-0 me-sm-3 me-1 d-inline-block">
                                        @lang('Share This Post')
                                    </h5>
                                    <ul class="social-list">
                                        <li class="social-list__item">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                class="social-list__icon">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li class="social-list__item">
                                            <a href="https://twitter.com/intent/tweet?text={{ __(@$blog->data_values->title) }}%0A{{ url()->current() }}"
                                                class="social-list__icon">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li class="social-list__item">
                                            <a class="social-list__icon"
                                                href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$blog->data_values->title) }}&media={{ getImage('assets/images/frontend/blog/' . @$blog->data_values->blog_image, '800x580') }}">
                                                <i class="fab fa-pinterest-p"></i>
                                            </a>
                                        </li>
                                        <li class="social-list__item">
                                            <a class="social-list__icon"
                                                href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$blog->data_values->title) }}&amp;summary={{ __(@$blog->data_values->short_details) }}">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="fb-comments" data-href="{{ route('blog.details', [$blog->id, slug(@$blog->data_values->title)]) }}"
                                    data-numposts="5">
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
