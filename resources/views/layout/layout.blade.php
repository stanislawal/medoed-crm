<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Fonts and icons -->
    {{--bootstrap css v5.2.2--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('lib/css/fonts.min.css') }}">
    <script src="{{ asset('lib/js/plugin/webfont/webfont.min.js')}}"></script>

    <script>
        WebFont.load({
            google: {"families": ["Lato:300,400,700,900"]},
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('lib/css/fonts.min.css') }}"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('lib/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('lib/css/atlantis.min.css') }}">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=@version">

    @yield('custom_css')
</head>
<body>
@yield('html')
{{--bootstrap js v5.2.2--}}
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js') }}"></script>

<!--   Core JS Files   -->
<script src="{{ asset ('lib/js/core/jquery.3.2.1.min.js')}}"></script>
<script src="{{ asset ('lib/js/core/popper.min.js')}}"></script>
<script src="{{ asset ('lib/js/core/bootstrap.min.js')}}"></script>


<!-- jQuery UI -->
<script src="{{ asset ('lib/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
<script src="{{ asset ('lib/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')}}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset ('lib/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
<script src="{{ asset ('lib/js/plugin/plugin/webfont/webfont.min.js')}}"></script>

<!-- Atlantis JS -->
<script src="{{ asset ('lib/js/atlantis.min.js')}}"></script>

<!-- App JS -->
<script src="{{ asset ('js/app.js')}}?v=@version"></script>

<!-- Notification JS -->
<script src="{{ asset ('js/notification.js')}}?v=@version"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/i18n/defaults-zh_CN.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script src="{{ asset('lib/js/plugin/datatables/datatables.min.js') }}"></script>
@yield('custom_js')
</body>
</html>
