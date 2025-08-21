@extends('admin.layouts.app')
@section('panel')
   <form method="POST">
    @csrf
    <div class="row gy-4">
        @foreach (gs('wallet_types') as $walletType)
            <div class="col-sm-6">
                <div class="card h-100">
                    <div class="card-header h-100">
                        <h4 class="card-title mb-0">{{__($walletType->title)}}</h4>
                        <p class="pt-0">
                            <small>{{ __(@$walletType->description) }} </small>
                        </p>
                    </div>
                    <div class="card-body pt-1">
                        <ul class="list-group list-group-flush">
                            @foreach ($walletType->configuration as $k=> $configuration)
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap flex-sm-nowrap gap-2">
                                <div>
                                    <p class="fw-bold mb-0">{{ __(@$configuration->title) }}</p>
                                    <p class="mb-0">
                                        <small>{{ __(@$configuration->description) }}</small>
                                    </p>
                                </div>
                                <div class="mn-100">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('No')" name="configuration[{{$walletType->name}}][{{$k}}]" @checked(@$configuration->status)>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer p-3">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update Setting')</button>
                    </div>
                </div>
            </div>            
        @endforeach
        <div class="col-12">
        </div>
    </div>
   </form>
@endsection

@push('style')
    <style>
        .mn-100{
            min-width:6.25rem !important;
        }
    </style>
@endpush