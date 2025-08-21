@php
    $content  = getContent('how_to_invest.content',true);
    $elements = getContent('how_to_invest.element',orderById:true);
@endphp
<section class="invest-section py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading style-left">
                   <h2 class="section-heading__title">{{ __(@$content->data_values->heading) }}</h2>
                   <p class="section-heading__desc">{{ __(@$content->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 align-items-center">
            <div class="col-lg-6 pe-lg-5">
              <ul class="invest-item">
                @foreach ($elements as $element)
                <li>
                    <div class="invest-item__content">
                        <span class="invest-item__style"> {{ $loop->iteration }} </span>
                        <h4 class="invest-item__title">{{ __(@$element->data_values->heading) }}</h4>
                        <p class="invest-item__desc">{{ __(@$element->data_values->subheading) }} </p>
                    </div>
                </li>
                @endforeach
              </ul>
              <div class="invest__button">
                <a href="{{ @$content->data_values->button_link }}" class="btn btn--base"> {{ __(@$content->data_values->button_text) }} </a>
              </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="invest-thumb l-mood">
                    <img src="{{ getImage('assets/images/frontend/how_to_invest/'.@$content->data_values->image_light,'630x470') }}" >
                </div>
                <div class="invest-thumb d-mood">
                    <img src="{{ getImage('assets/images/frontend/how_to_invest/'.@$content->data_values->image_dark,'630x470') }}" >
                </div>
            </div>
        </div>
    </div>
</section>
