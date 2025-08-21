<div class = "py-120 table-section ">
    <div class="table-section__shape light-mood">
        <img src="{{ asset($activeTemplateTrue . 'images/shapes/table-1.png') }}">
    </div>
    <div class="table-section__shape dark-mood style">
        <img src="{{ asset($activeTemplateTrue . 'images/shapes/table-12.png') }}">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <x-flexible-view :view="$activeTemplate . 'sections.coin_pair_list'" :meta="['from_section' => true]" />
            </div>
        </div>
    </div>
</div>
