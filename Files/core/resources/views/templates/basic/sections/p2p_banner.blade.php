@php
    $p2pBanner = @getContent('p2p_banner.content', true)->data_values;
@endphp
<section class="banner-section-two bg-img" style="background-image: url({{ getImage('assets/images/frontend/p2p_banner/' . @$p2pBanner->image_two, '1920x300') }})">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="banner-content">
                    <h3 class="banner-content__title mb-3">
                        @php echo highLightedString($p2pBanner->heading); @endphp
                    </h3>
                    <span class="banner-content__subtitle mb-2"> {{ __($p2pBanner->subheading) }} </span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="banner-thumb text-end">
                    <img src="{{ getImage('assets/images/frontend/p2p_banner/' . @$p2pBanner->image_one, '340x260') }}">
                </div>
            </div>
        </div>
    </div>
</section>