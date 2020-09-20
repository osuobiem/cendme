<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Home | Cendme</title>
  <link href="{{url('assets/css/style.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/custom.css')}}" rel="stylesheet">

  <!-- Vendor Stylesheets -->
  <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ url('assets/vendor/unicons-2.0.1/css/unicons.css')}}" rel='stylesheet'>
  <link rel="stylesheet" href="{{ url('assets/vendor/splide-2.4.4/dist/css/splide.min.css')}}">

  <script src="{{ url('assets/js/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ url('assets/js/custom.js') }}"></script>

</head>

<body>

  {{-- Top Bar Component --}}
  @include('components.topbar')

  @yield('content')

  {{-- Footer Component --}}
  @include('components.footer')
</body>
</html>