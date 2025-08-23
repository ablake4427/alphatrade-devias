@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 gy-4">
        <div class="col-md-12 ">
            <div class="card bl--5-primary">
                <div class="card-body">
                    <p>
                        @lang('To dynamically set up the TradingView chart for different trading pairs use the shortcodes')
                        <span class="shortcode text--dark fw-bold">@{{pair}}</span> & <span class="shortcode text--dark fw-bold">@{{pairlistingmarket}}</span>
                        @lang('in the  widget field below. System Replace ') <span class="shortcode text--dark fw-bold">@{{pair}}</span>
                        @lang('with the trading pair symbol  &') <span class="shortcode text--dark fw-bold">@{{pairlistingmarket}}</span>
                        @lang('with the pair listing market name. Make sure that your specified trading pair must be exists on their listed markets.')
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Trading View Widget')</label>
                            <textarea class="form-control trading-view-widget" name="trading_view_widget"> {{ gs('trading_view_widget')}}</textarea>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/codemirror.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/monokai.min.css')}}">
@endpush

@push('script-lib')
    <script src="{{asset('assets/admin/js/codemirror.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/css.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/sublime.min.js')}}"></script>
@endpush
@push('script')
<script>
    "use strict";
    var editor = CodeMirror.fromTextArea(document.getElementsByClassName("trading-view-widget")[0], {
      lineNumbers: true,
      mode: "text/css",
      theme: "monokai",
      keyMap: "sublime",
      autoCloseBrackets: true,
      matchBrackets: true,
      showCursorWhenSelecting: true,
      matchBrackets: true
    });

    (function ($) {
        $('.shortcode').on('click',function (event) {
            const tempTextArea = document.createElement('textarea');
            const text         = $(this).text();
            tempTextArea.value =text;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);

            event.target.innerHTML =text + '✔️ ';
            setTimeout(function() {
                event.target.innerHTML =text;
            }, 1500);
        });
    })(jQuery);

</script>
@endpush





@push('style')
<style>
    .CodeMirror{
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        line-height: 1.3;
        height: 500px;
    }
    .CodeMirror-linenumbers{
      padding: 0 8px;
    }
    .custom-css p, .custom-css li, .custom-css span{
      color: white;
    }​
    .cm-s-monokai span.cm-tag{
        margin-left: 15px;
    }
    .shortcode{
        cursor: pointer;
    }
  </style>
@endpush
