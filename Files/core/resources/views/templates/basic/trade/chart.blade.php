@php
 $meta   = (object) $meta;
 $pair   = $meta->pair;
 $widget = gs("trading_view_widget");

 $symbol = str_replace("_","",$pair->symbol);
 $widget = str_replace('{{pair}}',$symbol,$widget);
 $widget = str_replace('{{pairlistingmarket}}',$pair->listed_market_name,$widget);
@endphp
<div class="trading-chart  p-0 two">
    @php echo $widget; @endphp
</div>

