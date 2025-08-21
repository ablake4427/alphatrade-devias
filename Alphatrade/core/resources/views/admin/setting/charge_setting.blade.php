@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 gy-4">
        <div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>@lang('Other Users Transfer Charge')</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="other_user_transfer_charge" required value="{{ getAmount(gs('other_user_transfer_charge')) }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>@lang('P2P Trade Charge')</label>
                            <div class="input-group">
                                <input class="form-control" type="number" step="any" name="p2p_trade_charge" required value="{{ getAmount(gs('p2p_trade_charge')) }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
