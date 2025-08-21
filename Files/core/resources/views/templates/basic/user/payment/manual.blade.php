@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-header card-header-bg">
                    <h5 class="card-title">{{ __($pageTitle) }}</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center mt-2">
                                    @lang('You have requested') <b class="text--success">
                                        {{ showAmount($data['amount'],currencyFormat:false) }}
                                        {{ __(@$data->method_currency) }}</b> , @lang('Please pay')
                                    <b class="text--success">
                                        {{ showAmount($data['amount'],currencyFormat:false) }} +
                                        <span data-bs-toggle="tooltip" title="@lang('Charge')">{{ showAmount($data['charge'],currencyFormat:false) }}</span> =
                                        {{ showAmount($data['final_amount'],currencyFormat:false) . ' ' . $data['method_currency'] }}
                                    </b> @lang('for successful payment')
                                </p>
                                <h4 class="mb-4">@lang('Please follow the instruction below')</h4>
                                <p class="my-4">@php echo  $data->gateway->description @endphp</p>
                            </div>
                            <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
