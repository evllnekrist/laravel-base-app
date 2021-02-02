@extends('_layout.default')
@section('content')
    <style>
        body{
            background-color:  #1d1e22 !important;
        }
    </style>
    <!-- BEGIN: Content-->
    <div class="row">
        <div class="col-md-5 col-12">
            <div class="app-content content">
                <div class="content-wrapper">
                    <div class="content-body"> 
                        <div class="row">
                            <div class="col-md-3 col-12 align-self-center">
                                <strong class="ft-white">Card Id</strong>
                            </div>  
                            <div class="col-md-9 col-12"><br>
                                <form id="addForm" onsubmit="return false;">        
                                    <input  type="text" class="form-control" placeholder="Enter Card Id" 
                                            name="card_id" maxlength="112" required><br>
                                    <strong id="addForm_info" class="pull-left"></strong>
                                </form>
                            </div>
                        </div>
                        <div id="addDetail" style="display:none" class="pad-top-5">
                            <strong class="ft-white">Last Attende<br><br></strong> 
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body"> 
                                        <center>
                                        <table class="table table-sm table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Card Id</th><td>:</td>
                                                    <td id="attende_card_id" colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <th>Name</th><td>:</td>
                                                    <td id="attende_name" colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <th>Role</th><td>:</td>
                                                    <td class="avatar" id="attende_role_color"><div class="avatar-content-md"></div></td>
                                                    <td id="attende_role"></td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th><td>:</td>
                                                    <td class="avatar" id="attende_status_color"><div class="avatar-content-md"></div></td>
                                                    <td id="attende_status"></td>
                                                </tr>
                                                <tr id="attende_package_area" style="display:none">
                                                    <th>Package</th><td>:</td>
                                                    <td colspan="2">
                                                        <div id="attende_package"></div><br>
                                                        <strong id="attende_package_end_date"></strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table> 
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-12">
            <div class="app-content pad-top-10">
                <div class="content-wrapper">
                    <div class="content-body">
                        <!-- Basic example section start -->
                        <section id="basic-examples">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div id="myGrid" class="aggrid ag-theme-material"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- // Basic example section end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@section('vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/ag-grid/ag-grid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/ag-grid/ag-theme-material.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/dragula.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/jquery.contextMenu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/aggrid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/drag-and-drop.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-user.css') }}">
@endsection

@section('vendor_js')
    <script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/ag-grid/ag-grid-community.min.noStyle.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/dragula.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/dragula.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/jquery.contextMenu.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/jquery.ui.position.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/form.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('script')
    <script src="{{ asset('app-assets/js/core/libraries/jquery-ui.min.js') }}"></script>
@endsection
