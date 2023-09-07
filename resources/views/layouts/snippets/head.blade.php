<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? env('APP_NAME', 'Sem Nome') ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">
    <!-- FancyBox -->
    <link rel="stylesheet" href="{{ asset('vendor/fancybox3/jquery.fancybox.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2-dark-adminlte-theme/select2-adminlte.min.css') }}">
    <!--- Others -->
    <link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="{{ asset('js/amcharts/index.js') }}"></script>
    <script src="{{ asset('js/amcharts/xy.js') }}"></script>
    <script src="{{ asset('js/amcharts/animated.js') }}"></script>

    @yield('head')

</head>
