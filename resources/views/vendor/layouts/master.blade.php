<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description-gambolthemes" content="">
  <meta name="author-gambolthemes" content="">
  <title>@yield('title') | Cendme</title>
  <link href="{{url('assets/css/styles.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/admin-style.css')}}" rel="stylesheet">

  <!-- Vendor Stylesheets -->
  <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">

  <script src="{{ url('assets/js/jquery-3.4.1.min.js') }}"></script>

</head>

<body class="sb-nav-fixed">

  @yield('topbar')

  @yield('content')

  <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/vendor/chart/highcharts.js') }}"></script>
  <script src="{{ url('assets/vendor/chart/exporting.js') }}"></script>
  <script src="{{ url('assets/vendor/chart/export-data.js') }}"></script>
  <script src="{{ url('assets/vendor/chart/accessibility.js') }}"></script>
  <script src="{{ url('assets/js/scripts.js') }}"></script>
  <script src="{{ url('assets/js/chart.js') }}"></script>
</body>

</html>