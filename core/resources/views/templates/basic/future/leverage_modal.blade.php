<div class="modal favorites-modal custom--modal adjust-modal fade zoomIn" id="exampleModalLeverage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 p-0 pb-4">
                <h4 class="modal-title">@lang('Adjust Leverage')</h4>
                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            </div>
            <div class="modal-body p-0">
                <div class="leverage-content">
                    <div class="leverage-content-header mb-3">
                        <h5 class="leverage-content-header__title text-white fs-14 mb-3">
                            @lang('Leverage')
                        </h5>
                        <ul class="qnty-cart-list">
                            <li class="qnty-cart-list__item">
                                <div class="product-qty input--group group-two">
                                    <button type="button" class="product-qty__btn product-qty__decrement" @guest disabled @endguest><i class="fas fa-minus"></i></button>
                                    <input type="number" class="product-qty__value form--control style-three" placeholder="Price" value="{{ $futurePair->leverage }}" name="adjust_leverage" @guest readonly @endguest>
                                    <button type="button" class="product-qty__btn product-qty__increment" @guest disabled @endguest><i class="las la-plus"></i></button>
                                </div>
                            </li>
                        </ul>
                        <div class="custom--range mt-3">
                            <div class="leverage-slider custom--range__range slider-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                            <ul class="range-list leverage-range">
                                <li class="range-list__number" data-percent="1">@lang('1%')<span></span></li>
                                <li class="range-list__number" data-percent="25">@lang('25%')<span></span></li>
                                <li class="range-list__number" data-percent="50">@lang('50%')<span></span></li>
                                <li class="range-list__number" data-percent="75">@lang('75%')<span></span></li>
                                <li class="range-list__number" data-percent="100">@lang('100%')<span></span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="leverage__desc">
                        <p class="mb-2 fs-12">@lang('Please note that leverage changing will also apply for open positions
                            and open orders.')</p>
                            
                        <p class="text--danger fs-12">@lang('Selecting higher leverage such as [10x] increases your
                            liquidation risk. Always manage your risk levels. See our help article for more information.')
                        </p>
                    </div>
                    <button type="button" class="btn btn--base w-100 btn--sm h-40 mt-4 adjustLeverageBtn" data-bs-dismiss="modal">
                        @lang('Confirm')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- blade-formatter-disable --}}

@push('script')
   <script>
       (function($) {
           "use strict";
           
           $('[name=adjust_leverage]').on('input', function() {
                @guest
                    return false;
                @endguest

                let value = $(this).val() * 1;
                let maxLeverage = `{{ $futurePair->leverage }}` * 1;
                if (value > maxLeverage) {                    
                    $(this).val(maxLeverage);
                    notify('error', "@lang('Max leverage is') " + maxLeverage);
                    return false;
                }

                let percent = (value / maxLeverage) * 100;
                
                changeSliderRangeAndData(percent);
            });
           
           $('.leverage-range').on('click', '.range-list__number', function(e) {
                @guest
                    return false;
                @endguest

                let percent = parseInt($(this).data('percent'));                
                changeSliderRangeAndData(percent);
            });

            function changeSliderRangeAndData(percent, callFunction = true){
                if(callFunction){
                    changeBuyAmountRange(percent);
                }
                
                $(".leverage-slider").find('.ui-widget-header').css({
                    'width': `${percent}%`
                });

                $(".leverage-slider").find('.ui-state-default').css({
                    'left': `${percent ==100 ? 97 : percent}%`
                });
            }

            function changeBuyAmountRange(percent) {
                @guest
                    return false;
                @endguest

                percent = parseFloat(percent);

                if (percent > 100) {
                    notify('error', "@lang('Limit')");
                    return false;
                }

                let maxLeverage = `{{ $futurePair->leverage }}`;
                let percentAmount = parseInt((maxLeverage / 100) * percent).toFixed(0);
                $('[name=adjust_leverage]').val(percentAmount).trigger('change');
                console.log(percentAmount);
                
                changeSliderRangeAndData(percent, false)
            }

            $(".leverage-slider").slider({
                range: 'min',
                min: 0,
                max: 100,
                slide: function(event, ui) {
                    changeBuyAmountRange(ui.value);
                },
                change: function(event, ui) {
                    changeBuyAmountRange(ui.value);
                }
            });


       })(jQuery);
   </script>
@endpush

{{-- blade-formatter-enable --}}
