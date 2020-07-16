<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
      <a class="nav-link {{ Request::is('vendor') ? 'active' : '' }}" href="{{ url('vendor') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
          Dashboard
        </a>
        <a class="nav-link {{ Request::is('vendor/account') ? 'active' : '' }}" href="{{ url('vendor/account') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
          My Account
        </a>
        <a class="nav-link" href="{{ url('admin/logout') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
          Logout
        </a>
      </div>
    </div>
  </nav>
</div>