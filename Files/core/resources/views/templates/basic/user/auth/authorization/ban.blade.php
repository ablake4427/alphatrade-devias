@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center">
                        <h2 class="text-center text--danger">@lang('YOU ARE BANNED')</h2>
                        <p class="fw-bold mb-1">@lang('Reason'):</p>
                        <p>{{ $user->ban_reason }}</p>
                        <a href="{{ route('home') }}" class="btn btn--base btn--sm mt-4"> <i class="las la-long-arrow-alt-left"></i> @lang('Browse')
                            {{ __(gs('site_name')) }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        header,
        footer {
            display: none
        }

        body {
            justify-content: center;
        }
    </style>
@endpush
