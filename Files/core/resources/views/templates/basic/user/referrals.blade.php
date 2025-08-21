@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row gy-4">
    <div class="col-12">
        <h4 class="mb-0">{{ __($pageTitle) }}</h4>
    </div>
    @if ($user->referrer)
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>@lang('You are referred by') <span class="text--base">{{ @$user->referrer->fullname }}</span></h4>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-12">
        @if ($user->allReferrals->count() > 0 && $maxLevel > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="text-start"> @lang('Users Referred By Me')</h5>
                </div>
                <div class="card-body">
                    <div class="treeview-container">
                        <ul class="treeview">
                            <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                                @include($activeTemplate . 'partials.under_tree', [
                                    'user'    => $user,
                                    'layer'   => 0,
                                    'isFirst' => true,
                                ])
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
        <div class="card">
            <div class="card-body p-5">
                <div class="empty-thumb text-center">
                    <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                    <p class="fs-14">@lang('No data found')</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/jquery.treeView.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/jquery.treeView.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.treeview').treeView();
        })(jQuery);
    </script>
@endpush

