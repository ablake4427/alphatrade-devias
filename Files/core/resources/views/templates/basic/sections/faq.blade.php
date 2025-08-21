@php
$content  = getContent('faq.content',true);
$elements = getContent('faq.element');
@endphp

<div class="faq-section py-120 section-bg">
    <div class="container">
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-7 pe-lg-5">
                <div class="section-heading style-left mb-0">
                    <h2 class="section-heading__title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$content->data_values->subheading) }}</p>
                </div>
                <div class="faq-thumb l-mood">
                    <img src="{{ getImage('assets/images/frontend/faq/'.@$content->data_values->image_light,'535x535') }}">
                </div>
                <div class="faq-thumb d-mood">
                    <img src="{{ getImage('assets/images/frontend/faq/'.@$content->data_values->image_dark,'535x535') }}">
                </div>
            </div>
            <div class="col-lg-5">
                <div class="accordion custom--accordion" id="accordionExample">
                    @foreach ($elements as $element)
                    <div class="accordion-item ">
                        <h2 class="accordion-header" id="heading{{ $loop->index }}">
                            <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collaps{{ $loop->index }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false'}}"
                                aria-controls="collaps{{ $loop->index }}">
                                {{ __(@$element->data_values->question) }}
                            </button>
                        </h2>
                        <div id="collaps{{ $loop->index }}" class="accordion-collapse collapse @if($loop->first)  show @endif"
                             data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {{ __(@$element->data_values->answer) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
