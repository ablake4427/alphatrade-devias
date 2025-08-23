@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="market-overview py-50 section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading mb-0 style-left">
                   <h4 class="section-heading__title mb-2">@lang('Markets Overview')</h4>
                   <p class=" market-overview-subtitle fs-16">@lang('Explore your favorite coin pair on ') {{ __(gs("site_name")) }}</p>
                </div>
            </div>
        </div>
        <div class="row mt-4 justify-content-center gy-4">
            <div class="col-lg-4 col-md-6">
                <x-flexible-view :view="$activeTemplate.'coin.top_exchange_coin'"/>
            </div>
            <div class="col-lg-4 col-md-6">
                <x-flexible-view :view="$activeTemplate.'coin.highlight_coin'"/>
            </div>
            <div class="col-lg-4 col-md-6">
                <x-flexible-view :view="$activeTemplate.'coin.new_coin'"/>
            </div>
        </div>
    </div>
</div>
<div class="py-60 table-section">
    <div class="table-section__shape light-mood">
        <img src="{{ asset($activeTemplateTrue.'images/shapes/table-1.png') }}">
    </div>
    <div class="table-section__shape dark-mood style">
        <img src="{{ asset($activeTemplateTrue.'images/shapes/table-12.png') }}">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <x-flexible-view :view="$activeTemplate.'sections.coin_pair_list'" :meta="['limit' => 25]" />
            </div>
        </div>
    </div>
</div>

@if ($sections && $sections->secs != null)
    @foreach (json_decode($sections->secs) as $sec)
        @include($activeTemplate . 'sections.' . $sec)
    @endforeach
@endif
@endsection



