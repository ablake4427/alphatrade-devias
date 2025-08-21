@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-md-12 mb-30">
            <div class="card bl--5-primary">
                <div class="card-body">
                    <p class="fw-bold text--info">@lang('If the logo and favicon are not changed after you update from this page, please clear the cache from your browser. As we keep the filename the same after the update, it may show the old image for the cache. usually, it works after clear the cache but if you still see the old logo or favicon, it may be caused by server level or network level caching. Please clear them too.')</p>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('Logo For Dark Background')</label>
                                <x-image-uploader name="logo" :darkMode="true" :imagePath="siteLogo() . '?' . time()" :size="false" class="w-100" id="uploadLogo" :required="false" />
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('Logo For Base Background')</label>
                                <x-image-uploader name="logo_base" :imagePath="siteLogo('base') . '?' . time()" :size="false" class="w-100 baseColor" id="uploadLogoBase" :required="false" />
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('Favicon')</label>
                                <x-image-uploader name="favicon" :imagePath="siteFavicon() . '?' . time()" :size="false" class="w-100" id="uploadFavicon" :required="false" />
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-lg-4">
                                <label>@lang('PWA Thumb')</label>
                                <x-image-uploader name="pwa_thumb" :imagePath="getImage(getFilePath('logoIcon') . '/pwa_thumb.png', getFileSize('pwa_thumb'))" :size="getFileSize('pwa_thumb')" class="w-100" id="pwaThumb" :required="false" />
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang('PWA Favicon')</label>
                                <x-image-uploader name="pwa_favicon" :imagePath="getImage(getFilePath('logoIcon') . '/pwa_favicon.png', getFileSize('pwa_favicon'))" :size="getFileSize('pwa_favicon')" class="w-100" id="pwaFavicon" :required="false" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .baseColor .image-upload-preview {
            background-color: #{{ gs('base_color') }}
        }
    </style>
@endpush
