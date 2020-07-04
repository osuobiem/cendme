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

  <header class="header clearfix">
    <div class="top-header-group d-flex">
      <div class="top-header" style="padding-left: 15px;">
        <div class="main_logo" id="logo">
          <a href="index.html"><img src="{{ url('assets/images/cendme-logo-l.png') }}" alt=""></a>
          <a href="index.html"><img class="logo-inverse" src="{{ url('assets/images/cendme-logo-l.png') }}" alt=""></a>
        </div>

        <div class="sub-header-group d-none d-lg-block">
          <div class="sub-header justify-content-end">
            <nav class="navbar navbar-expand-lg navbar-light py-3">
              <div class="container-fluid">
                <div
                  class="collapse navbar-collapse d-flex flex-column flex-lg-row flex-xl-row justify-content-lg-end bg-dark1 p-3 p-lg-0 mt1-5 mt-lg-0">
                  <ul class="navbar-nav main_nav align-self-stretch">
                    <li class="nav-item"><a href="{{ url('/') }}" class="nav-link active" title="Home">Home</a></li>
                    <li class="nav-item"><a href="#" class="nav-link new_item" title="Shop Now">Shop Now</a>
                    <li class="nav-item"><a href="#" class="nav-link new_item" title="Become a Shopper">Become a
                        Shopper</a>
                    </li>
                    <li class="nav-item"><a href="{{ url('vendor') }}" class="nav-link" title="Start Selling">Start
                        Selling</a></li>
                  </ul>
                </div>

              </div>
            </nav>
          </div>
        </div>

      </div>
      <div class="header_right">
        <div class="p-2" style="width: max-content;">
          @if(Auth::guest())
          <a class="btn header-btn d-none d-lg-block" href="{{ url('vendor') }}">Start Selling</a>
          @else
          <a class="btn header-btn d-none d-lg-block" href="{{ url('vendor') }}"><i class="uil uil-user"
              style="font-size: inherit"></i>
            My Account</a>
          @endif
          <button onclick="openMenu(this)" class="navbar-toggler menu_toggle_btn d-lg-none" type="button"
            data-target="#navbarSupportedContent">
            <i class="uil uil-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </header>
  <div class="sub-header-group d-none d-lg-none" id="mobile-men">
    <div class="sub-header">
      <nav class="navbar navbar-expand-lg navbar-light py-3">
        <div class="container-fluid">
          <div class="collapse navbar-collapse flex-column bg-dark1 p-3 mobileMenu d-flex">
            <ul class="navbar-nav main_nav align-self-stretch">
              <li class="nav-item"><a href="{{ url('/') }}" class="nav-link active" title="Home">Home</a></li>
              <li class="nav-item"><a href="#" class="nav-link new_item" title="Shop Now">Shop Now</a>
              <li class="nav-item"><a href="#" class="nav-link new_item" title="Become a Shopper">Become a
                  Shopper</a>
              </li>
              <li class="nav-item"><a href="{{ url('vendor') }}" class="nav-link" title="Start Selling">Start
                  Selling</a></li>
            </ul>
          </div>

        </div>
      </nav>
    </div>
  </div>

  <div class="wrapper pb-0">
    <div class="default-dt home-banner">
      <div id="image-slider" class="splide" style="height: inherit !important;">
        <div class="splide__track">
          <ul class="splide__list">
            <li class="splide__slide">
              <img src="{{ url('assets/images/sub7.jpg') }}">
              <div class="text-center header-layer">
                <h2 class="banner-txt">Stay Home, <span style="color: rgb(238, 91, 45);">Shop Online</span></h2>
                <p class="banner-txt-sm">We'll deliver your orders at your doorstep</p>
                <a class="btn banner-btn" href="#">Shop Now</a>
              </div>
            </li>
            <li class="splide__slide slide">
              <img src="{{ url('assets/images/sub5.jpg') }}">
              <div class="text-center header-layer">
                <h2 class="banner-txt">We'll do your <span style="color: rgb(238, 91, 45);">shopping</span></h2>
                <p class="banner-txt-sm">Always ready to do the shopping in your supermarket of confidence</p>
                <br>
                <span class="banner-txt-sm">Interested in making extra money?</span>&nbsp;&nbsp;
                <a class="btn banner-btn" href="#">Become a Shopper</a>
              </div>
            </li>
            <li class="splide__slide">
              <img src="{{ url('assets/images/sub8.jpg') }}">
              <div class="text-center header-layer">
                <h2 class="banner-txt">Selling Just Got Easier with <span style="color: rgb(238, 91, 45);">Cendme</span>
                </h2>
                <p class="banner-txt-sm">Let us put your business online</p>
                <a class="btn banner-btn" href="{{ url('vendor') }}">Start Selling</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="life-gambo p-0">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="default-title left-text">
              <h2>Grocery shopping has never been so easy</h2>
            </div>
            <div class="about-content">
              <p>Cendme is a mobile app, where you can order
                groceries and have them delivered to your
                door in as little as 1 hour!</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="about-img">
              <img src="{{ url('assets/images/sub.jpg')}}" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="about-steps-group white-bg pt-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="default-title">
              <h2>Why you should use Cendme?</h2>
              <img src="{{ url('assets/images/line.svg')}}" alt="">
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="about-step">
              <i class="uil uil-stopwatch why-ico"></i>
              <h4>Your shopping in 1 hour</h4>
              <p>Do not ever wait all afternoon to reach the purchase.
                We'll deliver it to you whenever you want in one-hour intervals.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="about-step">
              <i class="uil uil-chat-bubble-user why-ico"></i>
              <h4>Personalized service</h4>
              <p>You will have direct contact with the Shopper.
                A Shopper will prepare your shopping as if it were theirs,
                always looking for the best for you.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="about-step">
              <i class="uil uil-user-check why-ico"></i>
              <h4>Trusted products</h4>
              <p>Great variety of supermarkets to choose from.
                Choose from one of our online supermarkets to do your shopping.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="about-step">
              <i class="uil uil-truck-loading why-ico"></i>
              <h4>Fresh products</h4>
              <p>Buying fresh products online is no longer a problem. Our shoppers will buy your products to order
                respecting the cold
                chain.</p>
              <p class="d-none" id="more">Your purchase will come home as if you had bought it. The freshest and highest
                quality
                products direct
                to your fridge.
                In addition, you can buy frozen products and receive them in perfect condition.</p>
              <a id="read-m-l" onclick="showMore(true)">Read more</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="how-it-works-bg" style="background: url('{{ url('assets/images/su2.jpg') }}');">
      <div class="how-order-gambo pt-5" style="background: #4e4b4b94;">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="default-title">
                <h2 style="color: #fff;">How it works</h2>
                <img src="{{ url('assets/images/line.svg')}}" alt="">
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-mobile-android"></i>
                </div>
                <h4>Login to the App</h4>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-shop"></i>
                </div>
                <h4>Choose your preferred Store</h4>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-list-ul"></i>
                </div>
                <h4>Scroll through to to select your groceries.</h4>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-file-check-alt"></i>
                </div>
                <h4>Confirm your Items</h4>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-envelope-send"></i>
                </div>
                <h4>Send a Request</h4>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-shopping-basket"></i>
                </div>
                <h4>Our Shoppers will care for your order</h4>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="how-order-steps">
                <div class="how-order-icon">
                  <i class="uil uil-shopping-cart-alt"></i>
                </div>
                <h4>Your Groceries delivered at your Door Step</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <footer class="footer pt-5" style="background: #0000000d">
    <div class="footer-first-row p-0">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-6 mt-2 text-center">
            <p class="m-0" style="color:#424361">Contact us</p>
            <ul class="call-email-alt">
              <li><a href="tel:+234 801 2345 678" class="callemail"><i class="uil uil-phone"></i>+234 801 2345 678</a>
              </li>
              <li><a href="mailto:info@cendme.com" class="callemail"><i
                    class="uil uil-envelope-alt"></i>info@cendme.com</a></li>
            </ul>
          </div>
          <div class="col-md-6 col-sm-6 mt-2">
            <div class="social-links-footer text-center">
              <p class="m-0" style="color:#424361">Follow us on social media</p>
              <ul>
                <li><a href="#"><i class="uil uil-facebook"></i></a></li>
                <li><a href="#"><i class="uil uil-twitter"></i></a></li>
                <li><a href="#"><i class="uil uil-instagram"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-last-row" style="background: unset">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="footer-bottom-links">
              <ul>
                <li><a href="#">Become a Shopper</a></li>
                <li><a href="{{ url('vendor') }}">Start Selling</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Term & Conditions</a></li>
                <li><a href="#">Refund & Return Policy</a></li>
              </ul>
            </div>
            <div class="copyright-text">
              <i class="uil uil-copyright"></i>{{ date('Y') }} <b>Cendme</b>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      new Splide('#image-slider', {
        cover: true,
        focus: 'center',
        type: 'loop',
        rewind: true,
        autoplay: true,
      }).mount();
    });

    function showMore(status) {
      if (status) {
        $('#more').removeClass('d-none');
        $('#read-m-l').attr('onclick', "showMore(false)");
        $('#read-m-l').text('Less')
      }
      else {
        $('#more').addClass('d-none');
        $('#read-m-l').attr('onclick', "showMore(true)");
        $('#read-m-l').text('Read more')
      }
    }

    function openMenu(el) {
      $('#mobile-men').removeClass('d-none')
      $(el).html('<i class="uil uil-multiply"></i>')
      $(el).attr('onclick', 'closeMenu(this)')
    }

    function closeMenu(el) {
      $('#mobile-men').addClass('d-none')
      $(el).html('<i class="uil uil-bars"></i>')
      $(el).attr('onclick', 'openMenu(this)')
    }
  </script>

  <script src="{{ url('assets/vendor/splide-2.4.4/dist/js/splide.min.js')}}"></script>
  <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/vendor/DataTables/datatables.min.js') }}"></script>
  <script src="{{ url('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
  <script src="{{ url('assets/js/scripts.js') }}"></script>
</body>