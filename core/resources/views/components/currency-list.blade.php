
@props([
    'name'      => 'currency',
    'class'     => 'form-control',
    'id'        => 'currency_list',
    'parent'    => 'currency_list_wrapper',
    'text'      => 'Select Currency',
    'action'    => route('admin.currency.all'),
    'type'      => 'all',
    'valueType' => 1, //1=id,other=symbol
    'multiple' => false,
    'displayType' => 1,
    'disabled' => false
])
<select  name="{{ $name }}"  class="{{ $class }}" id="{{ $id }}" required @if($multiple) multiple @endif @disabled($disabled)>
    @if (!$multiple)
        <option value="" selected disabled>{{ __($text) }}</option>
    @endif
</select>

@push('script')
    <script>
        "use strict";
        (function ($) {
            $(`#{{$id}}`).select2({
                ajax: {
                    url: "{{ $action }}",
                    type: "get",
                    dataType: 'json',
                    delay: 1000,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page,
                            type:"{{ $type }}"
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        let data = response.currencies.data;
                        let valueType=parseInt("{{ $valueType }}");
                        let displayType=parseInt("{{ $displayType }}");
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: displayType == 1 ?  item.name + '-' + item.symbol : item.symbol,
                                    id: valueType != 1 ? item.symbol : item.id,
                                }
                            }),
                            pagination: {
                                more: response.more
                            }
                        };
                    },
                    cache: false,
                },
                dropdownParent: $(`#{{$parent}}`)
            });
        })(jQuery);

    </script>
@endpush


