@php
    $content      = @getContent('p2p_how_to_work.content', true)->data_values;
    $sellElements = App\Models\Frontend::where('data_keys',"p2p_how_to_work.element")->where('data_values->buy_or_sell','sell')->get();
    $buyElements  = App\Models\Frontend::where('data_keys',"p2p_how_to_work.element")->where('data_values->buy_or_sell','buy')->get();
@endphp

<section class="how-to-work  py-120 section-bg">
    <div class="container">
        <div class="work-area">
            <div class="row align-items-center gy-4 mb-5">
                <div class="col-lg-6">
                    <div class="section-heading style-left mb-0">
                        <h2 class="section-heading__title mb-0">
                            @php echo highLightedString(@$content->heading) @endphp
                        </h2>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-tabs custom--tab-work" id="myTab" role="tablist">
                        <li class="nav-item active">
                            <button aria-controls="first" aria-selected="true" class="nav-link active" data-bs-target="#first" data-bs-toggle="tab" id="first-tab" role="tab" type="button">
                                @lang('Buy Crypto')
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="second" aria-selected="false" class="nav-link" data-bs-target="#second" data-bs-toggle="tab" id="second-tab" role="tab" tabindex="-1" type="button">
                                @lang('Sell Crypto')
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div aria-labelledby="first-tab" class="tab-pane fade active show" id="first" role="tabpanel">
                    <div class="row gy-4 justify-content-center">
                        @foreach ($buyElements as $buyElement)
                        <div class="col-lg-4 col-md-6">
                            <div class="p2p-trade">
                                <div class="p2p-trade__icon">
                                   @php echo @$buyElement->data_values->icon @endphp
                                </div>
                                <div class="p2p-trade__content">
                                    <h4 class="p2p-trade__title">{{ __(@$buyElement->data_values->heading) }}</h4>
                                    <p class="p2p-trade__desc">{{ __(@$buyElement->data_values->small_description) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div aria-labelledby="second-tab" class="tab-pane fade" id="second" role="tabpanel">
                    <div class="row justify-content-center">
                        @foreach ($sellElements as $sellElement)
                        <div class="col-lg-4 col-md-6">
                            <div class="p2p-trade">
                                <div class="p2p-trade__icon">
                                    @php echo @$sellElement->data_values->icon; @endphp
                                </div>
                                <div class="p2p-trade__content">
                                    <h4 class="p2p-trade__title">{{ __(@$sellElement->data_values->heading) }}</h4>
                                    <p class="p2p-trade__desc">{{ __(@$sellElement->data_values->small_description) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>