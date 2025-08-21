@extends($activeTemplate . 'layouts.p2p')
@section('p2p-content')
    <div class="card custom--card">
        <div class="card-body">
            <form action="{{route('user.p2p.payment.method.save',@$paymentMethod->id)}}" method="post">
                @csrf
                <div class="form-group">
                    <label class="form-label">@lang('Payment Method')</label>
                    <select class="form--control form-select payment-method select2" name="payment_method">
                        <option value="" selected disabled>@lang('Select One')</option>
                        @foreach ($methods as $method)
                            <option value="{{$method->id}}" data-form='@json(@$method->userData->form_data)' @selected(old('payment_method',@$paymentMethod->payment_method_id) == $method->id)>
                                {{ __($method->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="append"></div>
                <div class="form-group">
                    <label class="form-label">@lang('Remark') <small>(@lang('Optional'))</small> </label>
                    <textarea name="remark" class="form-control form--control">{{old('remark',@$paymentMethod->remark)}}</textarea>
                </div>
                <button class="btn btn--base w-100" type="submit">
                    @lang('Submit')
                </button>
            </form>
        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush


@push('script')
    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/viserformdata.js') }}"></script>

    <script>
        $('.payment-method').on('change', function() {

            const formData        = $(this).find(`option:selected`).data(`form`);
            const oldData         = @json(session()->getOldInput() ?? []);
            const alreadyHasValue = @json(@$paymentMethod->user_data ?? []);

            formData && viserformdata.generatHtml({
                data_for_generate_html: formData,
                old_input_value       : oldData,
                input_value           : alreadyHasValue
            });

        }).change();
    </script>
@endpush
