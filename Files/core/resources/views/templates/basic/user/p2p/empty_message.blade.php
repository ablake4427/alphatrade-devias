<div class="empty-wrapper text-center">
    <div class="empty-thumb">
        <img src=" {{ @$img ??  getImage('assets/images/extra_images/empty.png') }}" alt="">
    </div>
    <span class="d-block fs-14">
        {{ __($message) }}
    </span>
    <a href="{{$url}}" class="btn btn--base btn--sm" type="button">
        <i class=" {{ @$icon ?? 'las la-plus'}}"></i>
        {{ __($text) }}
    </a>
</div>
