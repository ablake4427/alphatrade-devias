@php
    $coins      = App\Models\Currency::with('marketData:currency_id,price')->crypto()->active()->get();
    $currencies = App\Models\Currency::fiat()->where('rate', '>', 0)->active()->get();
@endphp

<input type="hidden" name="asset" @if(@$ad) value="{{@$ad->asset->id}}" @endif>
<input type="hidden" name="fiat" @if(@$ad) value="{{@$ad->fiat->id}}" @endif>

<div class="form-group">
    <label class="form-label">@lang('Assset')</label>
    <div class="p2p-custom--dropdown mb-4">
        <div class="p2p-custom--dropdown-right dropdown">
            <div class="p2p-custom--dropdown-select"  data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="p2p-custom--dropdown-select-box justify-content-between">
                    <div class="d-flex align-items-center flex-wrap gap-1 dropdown-selcted-result">
                        @if(@$ad)
                        <img src="{{@$ad->asset->image_url}}"/>
                        <span class="f-14">{{ __(@$ad->asset->symbol) }}</span>
                        @else
                        <span class="f-14">@lang('Select One')</span>
                        @endif
                    </div>
                    @if (@$ad->complete_step !=3)
                    <i class="las la-angle-down"></i>
                    @endif
                </div>
            </div>
            @if (@$ad->complete_step !=3)
            <ul class="p2p-custom--dropdown-menu dropdown-menu" aria-labelledby="dropdownMenuButton2">
                <li class="p2p-custom--dropdown-search-item">
                    <div class="search-inner">
                        <button type="button" class="search-icon"> <i class="fas fa-search"></i></button>
                        <input class="search-input form--control search-inside-drodown" placeholder="@lang('Search')">
                    </div>
                </li>
                @foreach ($coins as $coin)
                    <li class="p2p-custom--dropdown-menu-item searchable-item" data-value="{{ $coin->id }}"
                        data-field-name="asset">
                        <div slot="select-item" class="link">
                            <img src="{{ $coin->image_url }}">
                            <span class="text">{{ __($coin->symbol) }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <label class="form-label">@lang('Fiat')</label>
    <div class="p2p-custom--dropdown mb-4">
        <div class="p2p-custom--dropdown-right dropdown">
            <div class="p2p-custom--dropdown-select"  data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="p2p-custom--dropdown-select-box justify-content-between">
                    <div class="p2p-custom--dropdown-select-box justify-content-between dropdown-selcted-result">
                        @if(@$ad)
                        <img src="{{@$ad->fiat->image_url}}"/>
                        <span class="f-14">{{ __(@$ad->fiat->symbol) }}</span>
                        @else
                        <span class="f-14">@lang('Select One')</span>
                        @endif
                    </div>
                    @if (@$ad->complete_step !=3)
                    <i class="las la-angle-down"></i>
                    @endif
                </div>
            </div>
            @if (@$ad->complete_step !=3)
            <ul class="p2p-custom--dropdown-menu dropdown-menu" aria-labelledby="dropdownMenuButton2">
                <li class="p2p-custom--dropdown-search-item">
                    <div class="search-inner">
                        <button class="search-icon" type="search"> <i class="fas fa-search"></i></button>
                        <input class="search-input form--control search-inside-drodown" placeholder="@lang('Search')">
                    </div>
                </li>
                @foreach ($currencies as $currency)
                    <li class="p2p-custom--dropdown-menu-item searchable-item" data-value="{{ $currency->id }}" data-field-name="fiat">
                        <div slot="select-item" class="link">
                            <img src="{{ $currency->image_url }}">
                            <span class="text">{{ __($currency->symbol) }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</div>

@if (@$ad->complete_step == 3)
<a href="{{route("user.p2p.advertisement.create", $ad->id) . "?step=2"}}" class="btn btn--base outline">
    @lang('Next') <i class="fas fa-chevron-right"></i>
</a>
@else
<button type="submit" class="btn btn--base">
    @lang('Next') <i class="fas fa-chevron-right"></i>
</button>
@endif

@if (@$ad->complete_step != 3)
@push('script')
    <script>
        "use strict";
        (function($) {
            
            $(".search-inside-drodown").on('input', function(e) {
                const searchValue = $(this).val().toUpperCase();
                const searchItems = $(this).closest(".dropdown-menu").find('.searchable-item');

                $.each(searchItems, function(indexInArray, searchItem) {
                    const searchItemText = $(searchItem).find('.text').text().toUpperCase();
                    if (searchItemText.indexOf(searchValue) != -1) {
                        $(searchItem).removeClass('d-none');
                    } else {
                        $(searchItem).addClass('d-none');
                    }
                });
            });
            

            $(".dropdown-menu").on('click', ".searchable-item", function(e) {
                const text = $(this).find('.text').text();
                const imageUrl = $(this).find('img').attr('src');
                const value = $(this).data('value');
                const filedName = $(this).data("field-name");

                $(this).closest(".dropdown").find(`.dropdown-selcted-result`).html(`
                    <img src="${imageUrl}"/>
                    <span class="f-14">${text}</span>
                `);

                $(document).find(`[name=${filedName}]`).val(value);
            });

            $('.ad-type').on('click', function(e) {
                const type = $(this).data('type');
                $('.ad-type').removeClass('active').filter(this).addClass('active');
                $(`input[name=type]`).val(type);
            });
        })(jQuery);
    </script>
@endpush
@endif
