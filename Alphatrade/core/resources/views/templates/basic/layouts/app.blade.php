<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />


    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/binary-trade.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="manifest" href="{{ route('pwa.configuration') }}">

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}" rel="stylesheet">
</head>
@php echo loadExtension('google-analytics') @endphp

<body>
    <div class="progress-cricle">
        <h4 class="progress-cricle__number"></h4>
    </div>
    @stack('fbComment')
    <div class="preloader-wrapper">
        <div class="preloader">
            <div class="wrapper">
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @yield('main-content')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a href="{{ route('cookie.policy') }}" class="text--base" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/binary-trade.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @php
        $pusherConfig = gs('pusher_config');
    @endphp
    <script>
        window.my_pusher = {
            'app_key': "{{ base64_encode(@$pusherConfig->pusher_app_key) }}",
            'app_cluster': "{{ base64_encode(@$pusherConfig->pusher_app_cluster) }}",
            'base_url': "{{ route('home') }}"
        }
        window.allow_decimal = "{{ gs('allow_decimal_after_number') }}";
    </script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')


    <script>
        (function($) {
            "use strict";

            $('.change-lang').on('click', function(e) {
                let langCode = $(this).data('code');
                window.location.href = "{{ route('home') }}/change/" + langCode;
            });

            @if (!in_array(request()->route()->getName(), ['trade', 'binary', 'future.trade']))
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                    let windowsIsDarkTheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (windowsIsDarkTheme) {
                        setTheme('light');
                    } else {
                        setTheme('dark');
                    }
                });

                const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
                const currentTheme = localStorage.getItem('theme');

                if (currentTheme) {
                    setTheme(currentTheme);
                } else {
                    let defaultTheme = `{{ gs('default_theme') }}`;

                    if (defaultTheme == 'dark') {
                        setTheme('dark');
                    } else {
                        setTheme('light');
                    }
                }

                function setTheme(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    localStorage.setItem('theme', theme);
                    theme == 'dark' ? toggleSwitch.checked = true : toggleSwitch.checked = false;
                }

                toggleSwitch.addEventListener('change', function(e) {
                    setTheme(e.target.checked ? 'dark' : 'light');
                });
            @endif

            var inputElements = $('input,select');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

        })(jQuery);


        async function registerSW() {
            if ('serviceWorker' in navigator) {
                try {
                    await navigator.serviceWorker.register(
                        "{{ asset($activeTemplateTrue . 'js/pwa/serviceworker.js') }}");
                } catch (e) {
                    console.warn('SW registration failed');
                }
            }
        }
        window.addEventListener('load', () => {
            registerSW();
        });
    </script>
</body>

</html>
