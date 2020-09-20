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

  {{-- Mobile Menu --}}
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