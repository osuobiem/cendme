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
      </div>
    </div>
  </nav>
</div>