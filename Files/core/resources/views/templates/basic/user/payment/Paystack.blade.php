@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card custom--card">
            <div class="card-header">
                <h5 class="card-title">@lang('Paystack')</h5>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                    @csrf
                    <ul class="list-group text-center list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            @lang('You have to pay '):
                            <strong>{{showAmount($deposit->final_amount,currencyFormat:false)}} {{__($deposit->method_currency)}}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            @lang('You will get '):
                            <strong>{{showAmount($deposit->amount,currencyFormat:false)}}  {{__($deposit->method_currency)}} </strong>
                        </li>
                    </ul>
                    <button type="button" class="btn btn--base w-100 mt-3" id="btn-confirm">@lang('Pay Now')</button>
                    <script
                        src="//js.paystack.co/v1/inline.js"
                        data-key="{{ $data->key }}"
                        data-email="{{ $data->email }}"
                        data-amount="{{ round($data->amount) }}"
                        data-currency="{{$data->currency}}"
                        data-ref="{{ $data->ref }}"
                        data-custom-button="btn-confirm"
                    >
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
