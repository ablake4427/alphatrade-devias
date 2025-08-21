<div class="form-group">
    <label class="form-label">@lang('Payment Details')</label>
    <textarea class="form-control form--control nicEdit" name="payment_details" >{{ old('payment_details',@$ad->payment_details) }}</textarea>
</div>
<div class="form-group">
    <label class="form-label">@lang('Terms of Trade')</label>
    <textarea class="form-control form--control nicEdit" name="terms_of_trade" >{{ old('terms_of_trade',@$ad->terms_of_trade) }}</textarea>
</div>

<div class="form-group">
    <label class="form-label">@lang('Auto Reaply')</label> <span class="fs-13">(@lang('This reply will be sent automatically when a user places an order on your ad.'))</span>
    <textarea class="form-control form--control nicEdit" name="auto_replay_text" >{{ old('auto_replay_text',@$ad->auto_replay_text) }}</textarea>
</div>

<a href="{{ route('user.p2p.advertisement.create', $ad->id) . '?step=2' }}" class="btn btn--base outline">
    <i class="fas fa-chevron-left"></i> @lang('Previous')
</a>
<button type="submit" class="btn btn--base ms-2">
    @lang('Submit')
</button>

@push('script-lib')
<script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });
    </script>
@endpush
@push('style')
    <style>
        .p2p-form{
            max-width: 950px !important;
        }
        .nicEdit-main {
            outline: none !important;
        }

        .nicEdit-custom-main {
            border-right-color: #cacaca73 !important;
            border-bottom-color: #cacaca73 !important;
            border-left-color: #cacaca73 !important;
            border-radius: 0 0 5px 5px !important;
        }

        .nicEdit-panelContain {
            border-color: #cacaca73 !important;
            border-radius: 5px 5px 0 0 !important;
            background-color: #fff !important;
            padding: 8px;
        }

        .nicEdit-buttonContain div {
            background-color: #fff !important;
            border: 0 !important;
        }
    </style>
@endpush