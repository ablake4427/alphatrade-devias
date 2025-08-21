@extends($activeTemplate.'layouts.frontend')
@section('content')
@php 
    $content = getContent('contact_us.content',true); 
@endphp
<section class="contact-content-section ">
    <div class="contact-content-section__shape light-mood">
        <img src="{{asset($activeTemplateTrue.'images/shapes/banner_1.png')}}">
    </div>
    <div class="contact-content-section__shape dark-mood">
        <img src="{{asset($activeTemplateTrue.'images/shapes/banner_1_dark.png')}}">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading mb-2">
                    <h2 class="section-heading__title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$content->data_values->subheading) }} </p>
                </div>
            </div>
        </div>
    </div>
</section>
 <div class="contact pb-120">
    <div class="container">
        <div class="row gy-5 flex-wrap-reverse justify-content-center">
            <div class="col-lg-5 pe-lg-5">
                <div class="contact-item ">
                    <div class="contact-item__icon"><i class="las la-envelope"></i></div>
                    <p class="contact-item__desc"> {{ @$content->data_values->email }} </p>
                </div>
                <div class="contact-item">
                    <div class="contact-item__icon"><i class="las la-phone"></i></div>
                    <p class="contact-item__desc">{{ @$content->data_values->mobile}}</p>
                </div>
                <div class="contact-item__thumb l-mood">
                    <img src="{{ getImage('assets/images/frontend/contact_us/'.@$content->data_values->image_light,'410x410') }}">
                </div>
                <div class="contact-item__thumb d-mood">
                    <img src="{{ getImage('assets/images/frontend/contact_us/'.@$content->data_values->image_dark,'410x410') }}">
                </div>
            </div>
            <div class="col-lg-5">
                <div class="contactus-form">
                    @php
                        $user=auth()->user();
                    @endphp
                    <form method="post" action="" class="verify-gcaptcha">
                        @csrf
                        <div class="form-group">
                            <label class="form--label">@lang('Name')</label>
                            <input name="name" type="text" class="form--control" value="{{ old('name',@$user->fullname) }}" @if($user) readonly @endif required placeholder="@lang('Your name')">
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Email')</label>
                            <input name="email" type="email" class="form--control" value="{{  old('email',@$user->email) }}" @if($user) readonly @endif required placeholder="@lang('Your email')">
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Subject')</label>
                            <input name="subject" type="text" class="form--control" value="{{old('subject')}}" required placeholder="@lang('Write subject')">
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Message')</label>
                            <textarea name="message" wrap="off" class="form--control" required placeholder="@lang('Write Message')">{{old('message')}}</textarea>
                        </div>
                        <x-captcha isCustom="true" />
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


