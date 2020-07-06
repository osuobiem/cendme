<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
      <a class="nav-link {{ Request::is('vendor') ? 'active' : '' }}" href="{{ url('vendor') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
          Dashboard
        </a>
        <a class="nav-link {{ Request::is('vendor/products') ? 'active' : '' }}" href="{{ url('vendor/products') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
          Products
        </a>
        <a class="nav-link {{ Request::is('vendor/orders') ? 'active' : '' }}" href="{{ url('vendor/orders') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-reply-all"></i></div>
          Orders
        </a>
        <a class="nav-link {{ Request::is('vendor/wallet') ? 'active' : '' }}" href="{{ url('vendor/wallet') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
          My Wallet
        </a>
        <a class="nav-link {{ Request::is('vendor/account') ? 'active' : '' }}" href="{{ url('vendor/account') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
          My Account
        </a>
        <a class="nav-link" href="{{ url('vendor/logout') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
          Logout
        </a>
      </div>
    </div>
  </nav>
</div>