<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | Cendme</title>
  <link href="{{url('assets/css/styles.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/admin-style.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/custom.css')}}" rel="stylesheet">

  <!-- Vendor Stylesheets -->
  <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/DataTables/datatables.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/animate.min.css')}}" rel="stylesheet">
  
  <script src="{{ url('assets/js/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ url('assets/js/custom.js') }}"></script>

</head>

@yield('topbar')

<div id="layoutSidenav">

  @yield('sidebar')

  <div id="layoutSidenav_content">
    @yield('content')

    @yield('footer')
  </div>
</div>

<div class="alert alert-success top-alert d-none" role="alert" id="success-alert">
</div>

<div class="alert alert-danger top-alert d-none" role="alert" id="error-alert">
</div>

<script src="{{ url('assets/js/jQuery.print.min.js') }}"></script>
<script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('assets/vendor/DataTables/datatables.min.js') }}"></script>
<script src="{{ url('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/js/scripts.js') }}"></script>

@stack('scripts')
</body>

</html>