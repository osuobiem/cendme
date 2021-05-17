<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
      <a class="nav-link {{ Request::is('admin') ? 'active' : '' }}" href="{{ url('admin') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
          Dashboard
        </a>
        <a class="nav-link {{ Request::is('admin/vendors') ? 'active' : '' }}" href="{{ url('admin/vendors') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-store"></i></div>
          Vendors
        </a>
        <a class="nav-link {{ Request::is('admin/shoppers') ? 'active' : '' }}" href="{{ url('admin/shoppers') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
          Shoppers
        </a>
        <a class="nav-link {{ Request::is('admin/users') ? 'active' : '' }}" href="{{ url('admin/users') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
          Users
        </a>
        <a class="nav-link {{ Request::is('admin/account') ? 'active' : '' }}" href="{{ url('admin/account') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
          My Account
        </a>
        
        <a class="nav-link collapsed {{ Request::is('admin/settings/*') ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseAreas"
							aria-expanded="false" aria-controls="collapseAreas">
							<div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
							Settings
							<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
						</a>
						<div class="collapse" id="collapseAreas" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
							<nav class="sb-sidenav-menu-nested nav">
								<a class="nav-link sub_nav_link" href="{{ url('admin/settings/credentials') }}">Credentials</a>
							</nav>
            </div>
            
        <a class="nav-link" href="{{ url('admin/logout') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
          Logout
        </a>
      </div>
    </div>
  </nav>
</div>