@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card custom--card">
                    <div class="card-body p-0">
                        <div class="row gy-4 justify-content-center flex-wrap-reverse">
                            <div class="col-md-5 col-lg-4">
                                <div class="user-image text-center">
                                    <img src="{{ getImage(getFilePath('userProfile').'/'. $user->image,getFileSize('userProfile'),true) }}">
                                </div>
                                <ul class="list-group list-group-flush h-100 p-3 information-list">
                                    <li
                                        class="list-group-item d-flex flex-column justify-content-between border-0 bg-transparent">
                                        <span class="fw-bold text-muted">{{ $user->username }}</span>
                                        <small class="text-muted"> <i class="la la-user"></i> @lang('Username')</small>
                                    </li>
                                    <li class="list-group-item d-flex flex-column justify-content-between border-0 bg-transparent">
                                        <span class="fw-bold text-muted">{{ $user->email }}</span>
                                        <small class="text-muted"><i class="la la-envelope"></i> @lang('Email')</small>
                                    </li>
                                    <li class="list-group-item d-flex flex-column justify-content-between border-0 bg-transparent">
                                        <span class="fw-bold text-muted">+{{ $user->mobile }}</span>
                                        <small class="text-muted"><i class="la la-mobile"></i> @lang('Mobile')</small>
                                    </li>
                                    <li class="list-group-item d-flex flex-column justify-content-between border-0 bg-transparent">
                                        <span class="fw-bold text-muted">{{ @$user->country_name }}</span>
                                        <small class="text-muted"><i class="la la-globe"></i> @lang('Country')</small>
                                    </li>
                                    <li class="list-group-item d-flex flex-column justify-content-between border-0 bg-transparent">
                                        <span class="fw-bold text-muted">{{ @$user->address }}</span>
                                        <small class="text-muted"><i class="la la-map-marked"></i> @lang('Address')</small>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-7 col-lg-8">
                                <form class="register py-3 pe-3 ps-3 ps-md-0" action="" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <h5 class="mb-3">@lang('Update Profile')</h5>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('First Name')</label>
                                                <input type="text" class="form-control form--control" name="firstname"
                                                    value="{{ $user->firstname }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Last Name')</label>
                                                <input type="text" class="form-control form--control" name="lastname"
                                                    value="{{ $user->lastname }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('State')</label>
                                                <input type="text" class="form-control form--control" name="state" value="{{ @$user->state }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('City')</label>
                                                <input type="text" class="form-control form--control" name="city" value="{{ @$user->city }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Zip Code')</label>
                                                <input type="text" class="form-control form--control" name="zip" value="{{ @$user->zip }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Address')</label>
                                                <input type="text" class="form-control form--control" name="address" value="{{ @$user->address }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Image')</label>
                                                <input type="file" class="form-control form--control" name="image">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .user-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        .user-image img {
            border-radius: inherit;
        }
    </style>
@endpush
