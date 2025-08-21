@props(['currency'])

<div class="user">
    <div class="thumb">
        <img src="{{@$currency->image_url }}">
    </div>
    <div class="text-start">
        <span class="name">{{@$currency->symbol}}</span> <br>
        <small class="ms-2">{{__(@$currency->name)}}</small>
    </div>
</div>
