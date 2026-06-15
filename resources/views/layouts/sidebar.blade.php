<header class="main-nav">
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>

            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Menu</h6>
                        </div>
                    </li>

                    {{-- TOKO --}}
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('view.toko') ? 'active' : '' }}"
                           href="{{ route('view.toko') }}">
                            <i data-feather="home"></i>
                            <span>Toko</span>
                        </a>
                    </li>

                    {{-- NOMINAL --}}
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('view.nominal') ? 'active' : '' }}"
                           href="{{ route('view.nominal') }}">
                            <i data-feather="dollar-sign"></i>
                            <span>Nominal</span>
                        </a>
                    </li>

                    {{-- AREA SALES --}}
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('view.area_sales') ? 'active' : '' }}"
                           href="{{ route('view.area_sales') }}">
                            <i data-feather="map-pin"></i>
                            <span>Area Sales</span>
                        </a>
                    </li>   

                    {{-- SALES --}}
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('view.sales') ? 'active' : '' }}"
                           href="{{ route('view.sales') }}">
                            <i data-feather="users"></i>
                            <span>Sales</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </div>
    </nav>
</header>