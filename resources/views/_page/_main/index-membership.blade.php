@extends('_layout.default')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Membership</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Main</li>
                                    <li class="breadcrumb-item"><a href="{{ route('membership') }}">Membership</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrum-right"></div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic example section start -->
                <section id="basic-examples">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="ag-grid-btns d-flex justify-content-between flex-wrap mb-1">
                                            <div class="dropdown sort-dropdown mb-1 mb-sm-0">
                                                <button class="btn btn-white filter-btn dropdown-toggle border text-dark" type="button" id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    1 - 10 of 0
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton6">
                                                    <a class="dropdown-item">10</a>
                                                    <a class="dropdown-item">20</a>
                                                    <a class="dropdown-item">50</a>
                                                    <a class="dropdown-item">100</a>
                                                    <a class="dropdown-item">150</a>
                                                </div>
                                                <button id="add-data" class="btn btn-outline-primary p-1 rounded-pill">
                                                    Add New Administrator
                                                </button>
                                            </div>
                                            <div class="ag-btns d-flex flex-wrap">
                                                <input type="text" class="ag-grid-filter form-control w-50 mr-1 mb-1 mb-sm-0" id="filter-text-box" placeholder="Search...." />
                                                <div class="btn-export">
                                                    <button class="btn btn-primary ag-grid-export-btn">
                                                        Export as CSV
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="myGrid" class="aggrid ag-theme-material"></div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic example section end -->
                <div class="modal fade text-left" id="admin-add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-capitalize" id="admin-add-modal-title">Add New Member</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addForm" onsubmit="return false;">
                                <div class="modal-body" id="admin-add-modal-body">
                                    <div class="col-md-12 col-12 page-users-view">
                                        <div class="row">
                                            <div class="col-md-5 col-12 mt-1">
                                                <span class="font-weight-bold">First Name</span>
                                                <input type="text" class="form-control" placeholder="Enter First Name" name="first_name" maxlength="50" required>
                                            </div>
                                            <div class="col-md-7 col-12 mt-1">
                                                <span class="font-weight-bold">Last Name</span>
                                                <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name" maxlength="50" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 col-12 mt-1">
                                                <span class="font-weight-bold">Role</span>
                                                <select id="role_add_selector" name="member_role_id" class="select2 form-control" style="width: 100%" required>
                                                    @foreach($list_role as $role)
                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-7 col-12 mt-1">
                                                <span class="font-weight-bold">Status</span>
                                                <select id="status_add_selector" name="status_code" class="select2 form-control" style="width: 100%" required>
                                                    @foreach($list_status as $status)
                                                        <option value="{{$status->code}}">{{$status->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 col-12 mt-1">
                                                <span class="font-weight-bold">KTP Number</span>
                                                <input type="text" class="form-control" placeholder="Enter KTP Number" name="ktp_number" maxlength="16" required>
                                            </div>
                                            <div class="col-md-7 col-12 mt-1">
                                                <br>
                                                <input type="file" class="form-control" placeholder="Enter KTP File" name="ktp_file" style="border:none;" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 col-12 mt-1">
                                                <span class="font-weight-bold">Place of Birth</span>
                                                <input type="text" class="form-control" placeholder="Enter PoB" name="pob" maxlength="20" required>
                                            </div>
                                            <div class="col-md-7 col-12 mt-1">
                                                <span class="font-weight-bold">Date of Birth</span>
                                                <input type="date" class="form-control" placeholder="Enter DoB" name="dob" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 col-12 mt-1">
                                                <span class="font-weight-bold">Email</span>
                                                <input type="email" class="form-control" placeholder="Enter Email" name="email" maxlength="50" required>
                                            </div>
                                            <div class="col-md-4 col-12 mt-1">
                                                <span class="font-weight-bold">Phone Number</span>
                                                <input type="text" class="form-control" placeholder="Enter Phone Number" name="phone" maxlength="20" required>
                                            </div>
                                            <div class="col-md-3 col-12 mt-1">
                                                <span class="font-weight-bold">Gender</span>
                                                <select id="gender_add_selector" name="gender_code" class="select2 form-control" style="width: 100%" required>
                                                    @foreach($list_gender as $gender)
                                                        <option value="{{$gender->code}}">{{$gender->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 col-12 mt-1">
                                                <span class="font-weight-bold">Province</span>
                                                <input type="text" class="form-control" placeholder="Enter Province" name="province" maxlength="30" required>
                                            </div>
                                            <div class="col-md-4 col-12 mt-1">
                                                <span class="font-weight-bold">City</span>
                                                <input type="text" class="form-control" placeholder="Enter City" name="city" maxlength="30" required>
                                            </div>
                                            <div class="col-md-3 col-12 mt-1">
                                                <span class="font-weight-bold">Post Code</span>
                                                <input type="text" class="form-control" placeholder="Enter Post Code" name="post_code" maxlength="5" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-12 mt-1">
                                                <span class="font-weight-bold">Address</span>
                                                <textarea rows="4" class="form-control" placeholder="Enter Address" name="address" maxlength="200" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" id="button-add-save" class="btn btn-outline-primary" value="Save"/>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-left" id="admin-details-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-capitalize" id="admin-details-modal-title">Membership Detail</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="admin-details-modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" id="button-edit" class="btn btn-outline-primary">Edit</button>
                                <button type="button" id="button-cancel" class="btn btn-outline-primary hidden">Cancel</button>
                                <button type="button" id="button-close" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
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