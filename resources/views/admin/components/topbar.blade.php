<body class="sb-nav-fixed">
  
<nav class="sb-topnav navbar navbar-expand navbar-light bg-clr">
    <a class="navbar-brand logo-brand" href="{{ url('/') }}"><img src="{{ url('assets/images/cendme-logo-l.png') }}" alt="Cendme Logo" id="top-logo"></a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
        class="fas fa-bars"></i></button>
    <a>Cendme {{ ucfirst(Auth::guard('admins')->user()->type) }}</a>
    <a href="{{ url('/') }}" class="frnt-link ml-4"><i class="fas fa-external-link-alt"></i>Home</a>
    <ul class="navbar-nav ml-auto mr-md-0">
      <li class="nav-item">
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">{{ Auth::guard('admins')->user()->name }}&nbsp;&nbsp;<i class="fas fa-user fa-fw"></i></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item admin-dropdown-item" href="{{ url('admin/account') }}">My Account</a>
          <a class="dropdown-item admin-dropdown-item" href="{{ url('admin/logout') }}">Logout</a>
        </div>
      </li>
    </ul>
  </nav>