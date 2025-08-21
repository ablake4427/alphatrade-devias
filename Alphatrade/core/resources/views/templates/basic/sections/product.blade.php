
@php
    $content  = getContent('product.content',true);
    $elements = getContent('product.element',orderById:true);
@endphp

<div class="product-section py-120">
    <div class="product-section__shape">
        <img src="{{ asset($activeTemplateTrue . 'images/shapes/bg.png') }}">
    </div>
    <div class="container">
        <div class="row gy-4 justify-content-center align-items-center">
            <div class="col-lg-6 pe-lg-5">
                <div class="section-heading style-left">
                    <h2 class="section-heading__title">{{ __(@$content->data_values->heading) }} </h2>
                    <p class="section-heading__desc"> {{ __(@$content->data_values->subheading) }} </p>
                  </div>
                @foreach ($elements as $element)
                <div class="product-item">
                    <span class="product-item__icon">
                        @php echo @$element->data_values->icon; @endphp
                    </span>
                    <div class="product-item__content">
                        <h4 class="product-item__title"> {{ __(@$element->data_values->heading) }}</h4>
                        <p class="product-item__desc"> {{ __(@$element->data_values->subheading) }} </p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="product-thumb l-mood">
                    <img src="{{ getImage('assets/images/frontend/product/'.@$content->data_values->image_light,'630x470') }}" >
                </div>
                <div class="product-thumb d-mood">
                    <img src="{{ getImage('assets/images/frontend/product/'.@$content->data_values->image_light,'630x470') }}" >
                </div>
            </div>
        </div>
    </div>
</div>
