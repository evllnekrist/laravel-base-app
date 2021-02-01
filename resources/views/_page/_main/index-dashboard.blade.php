@extends('_layout.default')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <!-- STATISTICS :: BEGIN -->
                
                <section id="dashboard-ecommerce">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex flex-column align-items-start pb-0">
                                    <div class="avatar bg-rgba-info p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-users text-info font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700 mt-1">{{$member['sum']}}</h2>
                                    <p class="mb-0">Total <b>Member</b></p>
                                </div>
                                <div class="card-content">
                                    <div id="line-area-chart-1" data-value="{{$member['a_year']}}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex flex-column align-items-start pb-0">
                                    <div class="avatar bg-rgba-info p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-users text-info font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700 mt-1">{{$staff['sum']}}</h2>
                                    <p class="mb-0">Total <b>Staff</b></p>
                                </div>
                                <div class="card-content">
                                    <div id="line-area-chart-2" data-value="{{$staff['a_year']}}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex flex-column align-items-start pb-0">
                                    <div class="avatar bg-rgba-info p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-users text-info font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700 mt-1">{{$pt['sum']}}</h2>
                                    <p class="mb-0">Total <b>Personal Trainer</b></p>
                                </div>
                                <div class="card-content">
                                    <div id="line-area-chart-3" data-value="{{$pt['a_year']}}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-end">
                                    <h4 class="mb-0">Today Absence Overview</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body px-0 pb-0">
                                        <div id="goal-overview-chart" class="mt-75" data-value="{{$card['sum']===0?0:number_format($card['absence']/$card['sum']*100,0,',','.')}}"></div>
                                        <div class="row text-center mx-0">
                                            <div class="col-6 border-top border-right d-flex align-items-between flex-column py-1">
                                                <p class="mb-50">Total Membership Card<br><br></p>
                                                <p class="font-large-1 text-bold-700">{{number_format($card['sum'],0,',','.')}}</p>
                                            </div>
                                            <div class="col-6 border-top d-flex align-items-between flex-column py-1">
                                                <p class="mb-50">Today Absence<br>(per card)</p>
                                                <p class="font-large-1 text-bold-700">{{number_format($card['absence'],0,',','.')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-end">
                                    <h4>Subscription Overview</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body pt-0">
                                        <div id="session-chart" class="mb-0" data-value="{{$sub['sum']}}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- STATISTICS :: END   -->
            </div>
        </div>
    </div>
    <?php
        dump($card);
        dump($sub);
    ?>
    <!-- END: Content-->
@endsection

@section('vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/card-analytics.css') }}">
@endsection

@section('vendor_js')
    <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
@endsection
@section('script')
    @include('_script._page.dashboard')
@endsection