@php
    $meta    = (object) $meta;
    $screen  = @$meta->screen;
    $pair    = $meta->pair;
    $markets = $meta->markets;
@endphp

<div class="trading-bottom__tab">
    <div class="@if($screen == 'small')  d-sm-block d-md-none @endif">
        <ul class="nav nav-pills  mb-3 custom--tab "
        id="pills-{{ $screen }}-tab-list" role="tablist">
        @if ($screen == 'small')
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-{{ $screen }}-chart"
                    type="button" role="tab" aria-controls="pills-chartthree" aria-selected="true">
                    @lang('Chart')
                </button>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <button class="nav-link @if ($screen == 'medium') active @endif" data-bs-toggle="pill"
                data-bs-target="#pills-{{ $screen }}-order-book" type="button" role="tab"
                aria-controls="pills-orderbookthree" aria-selected="false">
                @lang('Order Book')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-{{ $screen }}-market-list"
                type="button" role="tab" aria-controls="pills-markettwentyfive" aria-selected="false">
                @lang('Market')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-{{ @$screen }}-tab" data-bs-toggle="pill"
                data-bs-target="#pills-{{ $screen }}-trade-history" type="button" role="tab"
                aria-controls="pills-historytwentyfive" aria-selected="false">
                @lang('History')
            </button>
        </li>
    </ul>
    </div>

    <div class="tab-content">
        @if ($screen == 'small')
            <div class="tab-pane fade show active" id="pills-{{ $screen }}-chart" role="tabpanel">
                <x-flexible-view :view="$activeTemplate . 'trade.chart'" :meta="['pair' => $pair, 'screen' => 'small']" />
            </div>
        @endif
        <div class="tab-pane fade @if ($screen == 'medium') show active @endif"
            id="pills-{{ $screen }}-order-book" role="tabpanel">
            <x-flexible-view :view="$activeTemplate . 'trade.order_book'" :meta="['pair' => $pair, 'screen' => 'small']" />
        </div>
        <div class="tab-pane fade" id="pills-{{ $screen }}-market-list" role="tabpanel">
            <x-flexible-view :view="$activeTemplate . 'trade.pair_list'" :meta="['markets' => $markets, 'screen' => 'small']" />
        </div>
        <div class="tab-pane fade" id="pills-{{ $screen }}-trade-history" role="tabpanel">
            <x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair]" />
        </div>
    </div>


</div>
