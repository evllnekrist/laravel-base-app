@extends('_layout.default')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Product</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        Inventory
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{-- url('product') --}}">Product</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrum-right"></div>
                </div>
            </div>
            <div class="content-detached content-right">
                <div class="content-body">
                    <!-- Ecommerce Content Section Starts -->
                    <section id="ecommerce-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="ecommerce-header-items">
                                    <div class="result-toggler">
                                        <button class="navbar-toggler shop-sidebar-toggler" type="button" data-toggle="collapse">
                                            <span class="navbar-toggler-icon d-block d-lg-none"><i class="feather icon-menu"></i></span>
                                        </button>
                                        <div class="search-results">
                                            <span id="product-total">{{-- number_format($products->total(), 0, ',', '.') --}}</span> results found
                                        </div>
                                    </div>
                                    <div class="view-options">
                                        <select class="price-options form-control" id="ecommerce-order-options">
                                            <option value="desc" selected="selected">Latest</option>
                                            <option value="asc">Oldest</option>
                                        </select>
                                        <div class="view-btn-option">
                                            <button class="btn btn-white view-btn grid-view-btn active">
                                                <i class="feather icon-grid"></i>
                                            </button>
                                            <button class="btn btn-white list-view-btn view-btn">
                                                <i class="feather icon-list"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Ecommerce Content Section Starts -->
                    <!-- background Overlay when sidebar is shown  starts-->
                    <div class="shop-content-overlay"></div>
                    <!-- background Overlay when sidebar is shown  ends-->

                    <!-- Ecommerce Search Bar Starts -->
                    <section id="ecommerce-searchbar">
                        <div class="row mt-1">
                            <div class="col-sm-12">
                                <fieldset class="form-group position-relative">
                                    <input type="text" class="form-control search-product" id="iconLeft5" placeholder="Search here">
                                    <div class="form-control-position">
                                        <i class="feather icon-search"></i>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </section>
                    <!-- Ecommerce Search Bar Ends -->

                    <!-- Ecommerce Products Starts -->
                    <!-- if (count($products) > 0) -->
                    <section id="ecommerce-products" class="grid-view">
                        <!-- include('product.load') -->
                    </section>
                    <!-- endif -->
                    <!-- Ecommerce Products Ends -->

                    <!-- Ecommerce Pagination Starts -->
                    <section id="ecommerce-pagination">
                        <div class="row">
                            <div class="col-sm-12">
                                <nav aria-label="Page navigation example">
                                    <ul id="products-pagination" class="pagination justify-content-center mt-2">
                                        {{-- $products->links() --}}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </section>
                    <!-- Ecommerce Pagination Ends -->

                </div>
            </div>
            <div class="sidebar-detached sidebar-left">
                <div class="sidebar">
                    <!-- Ecommerce Sidebar Starts -->
                    <div class="sidebar-shop" id="ecommerce-sidebar-toggler">

                        <div class="row">
                            <div class="col-sm-12">
                                <h6 class="filter-heading d-none d-lg-block">Filters</h6>
                            </div>
                        </div>
                        <span class="sidebar-close-icon d-block d-md-none">
                            <i class="feather icon-x"></i>
                        </span>
                        <div class="card">
                            <div class="card-body">
                                <div class="multi-range-price">
                                    <div class="multi-range-title pb-75">
                                        <h6 class="filter-title mb-0">Price Range</h6>
                                    </div>
                                    <ul class="list-unstyled price-range" id="price-range">
                                        <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="price-range" class="price-range-filter" checked value="all">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50">All</span>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="price-range" value="false" class="price-range-filter" data-min="{{ 0 }}" data-max="{{ 1000000 }}">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50"> &lt;Rp 1.000.000</span>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="price-range" value="false" class="price-range-filter" data-min="{{ 1000000*1  }}" data-max="{{ 1000000*10 }}">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50">Rp 1.000k - Rp 10.000k</span>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="price-range" value="false" class="price-range-filter" data-min="{{ 1000000*10 }}" data-max="{{ 1000000*100 }}">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50">Rp10.000k - Rp100.000k</span>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="price-range" value="false" class="price-range-filter" data-min="{{ 1000000*100 }}" data-max="{{-- $max_price --}}">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50">&gt;Rp 100.000.000</span>
                                            </span>
                                        </li>

                                    </ul>
                                </div>
                                <!-- /Price Filter -->
                                <hr>
                                <!-- /Price Slider -->
                                <div class="price-slider">
                                    <div class="price-slider-title mt-1">
                                        <h6 class="filter-title mb-0">Price</h6>
                                    </div>
                                    <div class="price-slider">
                                        <div class="price_slider_amount mb-2">
                                        </div>
                                        <div class="form-group">
                                            <div class="slider-sm my-1 range-slider" id="price-slider" data-min="0" data-max="{{-- $max_price --}}"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Price Range -->
                                <hr>
                                <!-- Categories Starts -->
                                <div id="product-categories">
                                    <div class="product-category-title">
                                        <h6 class="filter-title mb-1">Categories</h6>
                                    </div>
                                    <ul class="list-unstyled categories-list">
                                        <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="category-filter" class="category-filter" value="all" checked>
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50">All</span>
                                            </span>
                                        </li>
                                        <!-- foreach($categories as $category) -->
                                            <li>
                                            <span class="vs-radio-con vs-radio-primary py-25">
                                                <input type="radio" name="category-filter" class="category-filter" value="{{-- $category->category_key --}}">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="ml-50">{{-- $category->category --}}</span>
                                            </span>
                                            </li>
                                        <!-- endforeach -->

                                    </ul>
                                </div>
                                <!-- Categories Ends -->
                                <hr>
                                <!-- Brands -->
                                <div class="brands">
                                    <div class="brand-title mt-1 pb-1">
                                        <h6 class="filter-title mb-0">Brands</h6>
                                    </div>
                                    <div class="brand-list" id="brands">
                                        Retrieve brand data...
                                    </div>
                                </div>
                                <!-- /Brand -->
                                <hr>
                                <!-- Clear Filters Starts -->
                                <div id="clear-filters">
                                    <button class="btn btn-block btn-primary" id="clear_filter">CLEAR ALL FILTERS</button>
                                </div>
                                <!-- Clear Filters Ends -->

                            </div>
                        </div>
                    </div>
                    <!-- Ecommerce Sidebar Ends -->

                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@section('vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/nouislider.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/ui/prism.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/noui-slider.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-ecommerce-shop.css') }}">
@endsection

@section('vendor_js')
    <script src="{{ asset('app-assets/vendors/js/ui/prism.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/wNumb.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/nouislider.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.js') }}"></script>
@endsection
@section('script')
    <script src="{{ asset('assets/js/app-ecommerce-shop.js') }}"></script>
@endsection
