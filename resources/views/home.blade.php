@extends('layouts.master')

@section('content')
<div class="wrapper pb-0 b-high">
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
  <div class="about-steps-group white-bg pt-5 b-low" data-aos="fade-up" data-aos-duration="2000">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="default-title">
            <h2>Why should I use Cendme?</h2>
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

  <div class="how-it-works-bg" style="background: url('{{ url('assets/images/su2.jpg') }}')" data-aos="fade-up"
    data-aos-duration="1500">
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

@endsection