<!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ url('home') }}">
                        <div class="brand-logo" style="background-position: center;background-size: contain;"></div>
                        <h2 class="brand-text mb-0">{{env('APP_NAME_2ND')}}</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" navigation-header"><span>Home</span></li>
                <li class="nav-item {{ (request()->segment(1) == 'home') ? 'active' : '' }}">
                    <a href="{{ url('home') }}">
                        <i class="feather icon-home"></i>
                        <span class="menu-title" data-i18n="Dashboard">Dashboard</span>
                    </a>
                </li>
                <li class=" navigation-header"><span>Sales</span></li>
                <li class=" nav-item {{ (request()->segment(1) == 'order') ? 'active' : '' }}">
                    <a href="{{ url('order') }}">
                        <i class="feather icon-shopping-cart"></i>
                        <span class="menu-title" data-i18n="Product">Order</span>
                    </a>
                </li>
                <li class=" nav-item {{ (request()->segment(1) == 'rent') ? 'active' : '' }}">
                    <a href="{{ url('rent') }}">
                        <i class="feather icon-layers"></i>
                        <span class="menu-title" data-i18n="Product">Rent</span>
                    </a>
                </li>
                <li class=" navigation-header"><span>Inventory</span></li>
                <li class=" nav-item {{ (request()->segment(1) == 'product') ? 'active' : '' }}">
                    <a href="{{ url('product') }}">
                        <i class="feather icon-package"></i>
                        <span class="menu-title" data-i18n="Product">Product</span>
                    </a>
                </li>
                <li class=" navigation-header"><span>Marketing</span></li>
    {{--            <li class=" nav-item {{ (request()->segment(1) == 'promo') ? 'active' : '' }}">--}}
    {{--                <a href="{{ url('promo') }}">--}}
    {{--                    <i class="feather icon-percent"></i>--}}
    {{--                    <span class="menu-title" data-i18n="Product">Promo</span>--}}
    {{--                </a>--}}
    {{--            </li>--}}
                <li class=" nav-item {{ (request()->segment(1) == 'email') ? 'active' : '' }}">
                    <a href="{{ url('email') }}">
                        <i class="feather icon-mail"></i>
                        <span class="menu-title" data-i18n="Product">Email Template</span>
                    </a>
                </li>
                <li class=" navigation-header"><span>Content</span></li>
                <li class=" nav-item {{ (request()->segment(1) == 'banner') ? 'active' : '' }}">
                    <a href="{{ url('banner') }}">
                        <i class="feather icon-layout"></i>
                        <span class="menu-title" data-i18n="Banner">Banner</span>
                    </a>
                </li>
                <li class=" nav-item {{ (request()->segment(1) == 'category') ? 'active' : '' }}">
                    <a href="{{ url('category') }}">
                        <i class="feather icon-sidebar"></i>
                        <span class="menu-title" data-i18n="Category">Category</span>
                    </a>
                </li>
                <li class=" navigation-header"><span>User</span></li>
                <li class=" nav-item {{ (request()->segment(1) == 'customer') ? 'active' : '' }}">
                    <a href="{{ url('customer') }}">
                        <i class="feather icon-users"></i>
                        <span class="menu-title" data-i18n="Customer">Customer</span>
                    </a>
                </li>
                <li class=" nav-item {{ (request()->segment(1) == 'admin') ? 'active' : '' }}">
                    <a href="{{ url('admin') }}">
                        <i class="feather icon-user"></i>
                        <span class="menu-title" data-i18n="Admin">Administrator</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
<!-- END: Main Menu-->