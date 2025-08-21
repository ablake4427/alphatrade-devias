<form action = "{{ route('user.p2p.payment.method.save') }}" method = "post">
    @csrf
    @include('components.viser-form', ['formData' => $paymentMethod->userData->form_data])
    <input type  = "hidden" name = "payment_method" value = "{{ $paymentMethod->id }}">
    <div class = "form-group">
        <label>@lang('Remark') <small>(@lang('Optional'))</small> </label>
        <textarea name = "remark" class = "form-control form--control"></textarea>
    </div>
    <button class = "btn btn--base w-100" type = "submit">
        @lang('Submit')
    </button>
</form>
