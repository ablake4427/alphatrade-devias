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
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">


    @if (session('app'))
        <style>
            .dashboard-header,
            .dashboardBodyNav {
                display: none !important;
            }
        </style>
    @endif

</head>
@php echo loadExtension('google-analytics') @endphp

<body>
    @if (!request()->routeIs('user.home'))
        <div class="preloader">
            <div class="loader-p"></div>
        </div>
    @endif

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    <div class="dashboard-fluid position-relative">
        <div class="dashboard__inner">
            @include($activeTemplate . 'partials.user_sidebar')
            <div class="dashboard__right">
                @include($activeTemplate . 'partials.user_topbar')
                <div class="dashboard-body">
                    <div class="d-flex justify-content-between mb-3 align-items-center dashboardBodyNav">
                        <div class="dashboard-body__bar d-xl-none d-inline-block">
                            <button class="dashboard-sidebar-filter__button">
                                <i class="las la-bars"></i>
                            </button>
                        </div>
                        @if (request()->routeIs('user.home'))
                            <div class="dashboard-body__bar style ">
                                <span class="dashboard-body__bar-two-icon toggle-dashboard-right"><i class="fas fa-bars"></i></span>
                            </div>
                        @endif

                        @if (request()->routeIs('user.p2p*'))
                            <div class="p2p-sidebar__menu">
                                <span class="p2p-sidebar__menu-icon">
                                    <i class="fas fa-bars"></i>
                                </span>
                            </div>
                        @endif
                    </div>
                    @stack('topContent')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    @stack('script-lib')

    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/main.js') }}"></script>

    <script>
        window.allow_decimal = "{{ gs('allow_decimal_after_number') }}";
    </script>

    @include('partials.notify')

    @php echo loadExtension('tawk-chat') @endphp

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        (function($) {
            "use strict";

            var inputElements = $('[type=text],[type=password],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }
            });

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    if (row.querySelectorAll('td').length > 1) {
                        Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                            colum.setAttribute('data-label', heading[i].innerText)
                        });
                    }
                });
            });

            @if (session('app'))
                $('.btn--base').each(function() {
                    var isInForm = $(this).closest('form').length > 0;
                    if (isInForm) {
                        $(this).closest('form').on("submit", function() {
                            let html = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
                            $(this).find('.btn--base').attr('disabled', true).html(html);
                        });
                    } else {
                        $(this).on('click', function() {
                            let html = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
                            $(this).attr('disabled', true).html(html);
                        });
                    }
                });
            @endif
        })(jQuery);
    </script>
</body>

</html>
