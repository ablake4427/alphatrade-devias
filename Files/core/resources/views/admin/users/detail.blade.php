@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="row gy-4">
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="7" link="{{ route('admin.order.history') }}?user_id={{ $user->id }}" title="Total Order" icon="las la-money-bill-wave-alt" value="{{ getAmount($widget['total_order']) }}" bg="primary" type="2" />
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="7" link="{{ route('admin.trade.history') }}?trader_id={{ $user->id }}" title="Total Trade" icon="las la-wallet" value="{{ getAmount($widget['total_trade']) }}" bg="primary" type="2" />
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="7" link="{{ route('admin.deposit.list', $user->id) }}" title="Total Deposit" icon="las la-wallet" value="{{ getAmount($widget['total_deposit']) }}" bg="8" type="2" />
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="7" link="{{ route('admin.report.transaction', $user->id) }}" title="Transactions" icon="las la-exchange-alt" value="{{ getAmount($widget['total_transaction']) }}" bg="17" type="2" />
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-act="add">
                        <i class="las la-plus-circle"></i> @lang('Balance')
                    </button>
                </div>

                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="sub">
                        <i class="las la-minus-circle"></i> @lang('Balance')
                    </button>
                </div>

                <div class="flex-fill">
                    <a href="{{ route('admin.report.login.history') }}?search={{ $user->username }}" class="btn btn--primary btn--shadow w-100 btn-lg">
                        <i class="las la-list-alt"></i>@lang('Logins')
                    </a>
                </div>
                <div class="flex-fill">
                    <a href="{{ route('admin.users.notification.log', $user->id) }}" class="btn btn--secondary btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>@lang('Notifications')
                    </a>
                </div>
                @if ($user->kyc_data)
                    <div class="flex-fill">
                        <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn--dark btn--shadow w-100 btn-lg">
                            <i class="las la-user-check"></i>@lang('KYC Data')
                        </a>
                    </div>
                @endif
                <div class="flex-fill">
                    @if ($user->status == Status::USER_ACTIVE)
                        <button type="button" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                            <i class="las la-ban"></i>@lang('Ban User')
                        </button>
                    @else
                        <button type="button" class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                            <i class="las la-undo"></i>@lang('Unban User')
                        </button>
                    @endif
                </div>
            </div>

            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $user->fullname }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname" required value="{{ $user->firstname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname" required value="{{ $user->lastname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email') </label>
                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number') </label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code">+{{ $user->dial_code }}</span>
                                        <input type="number" name="mobile" value="{{ $user->mobile }}" id="mobile" class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label>@lang('Address')</label>
                                    <input class="form-control" type="text" name="address" value="{{ @$user->address }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input class="form-control" type="text" name="city" value="{{ @$user->city }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('State')</label>
                                    <input class="form-control" type="text" name="state" value="{{ @$user->state }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Zip/Postal')</label>
                                    <input class="form-control" type="text" name="zip" value="{{ @$user->zip }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Country') <span class="text--danger">*</span></label>
                                    <select name="country" class="form-control select2">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" @selected($user->country_code == $key)>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label>@lang('Email Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev" @if ($user->ev) checked @endif>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label>@lang('Mobile Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv" @if ($user->sv) checked @endif>
                                </div>
                            </div>
                            <div class="col-xl-3 col-12">
                                <div class="form-group">
                                    <label>@lang('2FA Verification') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ts" @if ($user->ts) checked @endif>
                                </div>
                            </div>
                            <div class="col-xl-3 col-12">
                                <div class="form-group">
                                    <label>@lang('KYC') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="kv" @if ($user->kv == Status::KYC_VERIFIED) checked @endif>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 gy-3">
        <div class="col-12">
            <h6>@lang('User Wallet Balance')</h6>
        </div>
        @forelse ($user->wallets->where('balance',">",0)->sortByDesc('balance') as $wallet)
            <div class="col-xxl-3 col-sm-6">
                <div class="widget-two box--shadow2 b-radius--5 bg--white">
                    <div class="widget-two__icon b-radius--5">
                        <img src="{{ @$wallet->currency->image_url }}">
                    </div>
                    <div class="widget-two__content">
                        <h3>{{ showAmount($wallet->balance, currencyFormat: false) }} {{ @$wallet->currency->symbol }}</h3>
                        <p>@lang('Total Balance')</p>
                        @if (Status::WALLET_TYPE_SPOT == $wallet->wallet_type)
                            <span class="badge badge--primary">
                                @lang('SPOT')
                            </span>
                        @elseif(Status::WALLET_TYPE_FUNDING == $wallet->wallet_type)
                            <span class="badge badge--success">
                                @lang('FUNDING')
                            </span>
                        @else
                            <span class="badge badge--info">
                                @lang('FUTURE')
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="card">
                    <div class="card-body">
                        <h6>@lang("This user haven't any wallet balance")</h6>
                    </div>
                </div>
            </div>
        @endforelse
    </div>



    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.add.sub.balance', $user->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="act">
                    <div class="modal-body">
                        <div class="form-group position-relative">
                            <label>@lang('Wallet')</label>
                            <select name="wallet" class="form-control select2" required>
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" data-symbol="{{ $currency->symbol }}">{{ $currency->symbol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Wallet Type')</label>
                            <select class="form-control select2" data-minimum-results-for-search="-1" name="wallet_type" required>
                                <option selected disabled>@lang('Select One')</option>
                                @foreach (gs('wallet_types') as $wallet)
                                    <option value="{{ $wallet->name }}">{{ __($wallet->title) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')" required>
                                <div class="input-group-text wallet-cur-symbol">{{ __(gs('cur_text')) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Remark')</label>
                            <textarea class="form-control" placeholder="@lang('Remark')" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($user->status == Status::USER_ACTIVE)
                            @lang('Ban User')
                        @else
                            @lang('Unban User')
                        @endif
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($user->status == Status::USER_ACTIVE)
                            <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                            <div class="form-group">
                                <label>@lang('Reason')</label>
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                        @else
                            <p><span>@lang('Ban reason was'):</span></p>
                            <p>{{ $user->ban_reason }}</p>
                            <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if ($user->status == Status::USER_ACTIVE)
                            <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        @else
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--primary">
        <i class="las la-sign-in-alt"></i>@lang('Login as User')
    </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"
            $('.bal-btn').on("click", function() {
                var act = $(this).data('act');
                $('#addSubModal').find('input[name=act]').val(act);
                if (act == 'add') {
                    $('.type').text('Add');
                } else {
                    $('.type').text('Subtract');
                }
            });

            let mobileElement = $('.mobile-code');

            $('select[name=country]').on("change", function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            });

            $('select[name=country]').val('{{ @$user->country_code }}');

            let dialCode = $('select[name=country] :selected').data('mobile_code');
            let mobileNumber = `{{ $user->mobile }}`;

            mobileNumber = mobileNumber.replace(dialCode, '');
            $('input[name=mobile]').val(mobileNumber);
            mobileElement.text(`+${dialCode}`);

            $('select[name=wallet]').on('change', function(e) {
                let symbol = $(this).find('option:selected').data('symbol');
                $(`.wallet-cur-symbol`).text(symbol);
            });

        })(jQuery);
    </script>
@endpush
