@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                     
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label> @lang('Pusher App ID')</label>
                                <input type="text" class="form-control" placeholder="@lang('Pusher App ID')" name="pusher_app_id" value="{{ @gs('pusher_config')->pusher_app_id }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label> @lang('Pusher App Key')</label>
                                <input type="text" class="form-control" placeholder="@lang('Pusher App Key')"
                                    name="pusher_app_key" value="{{ @gs('pusher_config')->pusher_app_key }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label> @lang('Pusher App Secret')</label>
                                <input type="text" class="form-control" placeholder="@lang('Pusher App Secret')"
                                    name="pusher_app_secret" value="{{ @gs('pusher_config')->pusher_app_secret }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label> @lang('Pusher App Cluster')</label>
                                <input type="text" class="form-control" placeholder="@lang('Pusher App Cluster')" name="pusher_app_cluster" value="{{ @gs('pusher_config')->pusher_app_cluster }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

