<div class="page-main-header {{ Request::is('monitoring/map') ? 'navbar-map' : '' }}">
  <div class="main-header-right row m-0">
    <div class="main-header-left">
      {{-- Light mode logo --}}
      <div class="logo-wrapper"><a href="{{ url('/') }}" class="text-decoration-none"><h5 class="mb-0 fw-bold" style="color: #24695c;">Pencatatan</h5></a></div>
      {{-- Dark mode logo --}}
      <div class="dark-logo-wrapper"><a href="{{ url('/') }}" class="text-decoration-none"><h5 class="mb-0 fw-bold" style="color: #ffffff;">Pencatatan</h5></a></div>
      
      @if(!Request::is('monitoring/map'))
        <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle">    </i></div>
      @endif
    </div>
    <div class="left-menu-header col">
      {{-- Empty center --}}
    </div>
    <div class="nav-right col pull-right right-menu p-0">
      <ul class="nav-menus">
        <li class="nav-item-custom">
          <a class="nav-link-custom" href="{{ route('dashboard') }}" title="Dashboard"><i data-feather="grid"></i><span>Dashboard</span></a>
        </li>
      </ul>
    </div>
    <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
  </div>
</div>

<style>
  /* Base Overrides for Navbar Features */
  .page-main-header.navbar-map .main-header-left #sidebar-toggle {
    display: none !important;
  }
  
  /* Proportional Nav Menus */
  .nav-menus {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  .nav-item-custom {
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
  }
  .nav-link-custom {
    color: #444 !important;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    transition: all 0.3s ease;
  }
  .nav-link-custom i, .nav-item-custom i {
    width: 20px !important;
    height: 20px !important;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .nav-link-custom:hover, .nav-item-custom:hover i {
    color: #24695c !important;
    transform: scale(1.2);
  }
  
  .btn-primary-light {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
  }
  
  @media (max-width: 991px) {
    .nav-link-custom span { display: none; }
  }
</style>
