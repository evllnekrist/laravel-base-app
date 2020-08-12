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
                            <h2 class="content-header-title float-left mb-0">Category</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        Content
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{-- url('category') --}}">Category</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrum-right">
                        <button class="btn btn-outline-primary" id="add-new-category"><span><i class="feather icon-plus"></i> Add New Category</span></button>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Data list view starts -->
                <section id="data-thumb-view" class="data-thumb-view-header">
                    <!-- dataList starts -->
                    <section id="dd-with-handle">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 save-category-structure-action d-none">
                                                    <button class="btn btn-primary mb-3 save-category-structure"><span><i class="feather icon-save"></i> Save Your Changes</span></button>
                                                </div>
                                                <div class="col-sm-12">
                                                    <ul class="list-group list-group-flush channel_category">
                                                        <!-- foreach($categoryModel as $parenCategory) -->
                                                            <li class="list-group-item border-0 draggable">
                                                                <span class="handle" data-id="{{-- $parenCategory->id --}}" data-async="false">+</span>
                                                                <span class="category-name" data-hash="{{-- md5($parenCategory->id.$website_key) --}}">{{-- $parenCategory->category --}}</span>
                                                                <span class="float-right pl-1 cursor-pointer category-delete" data-hash="{{-- md5($parenCategory->id.$website_key) --}}" data-title="{{-- $parenCategory->category --}}">
                                                                    <i class="feather icon-trash"></i>
                                                                </span>
                                                                <span class="float-right pl-1 cursor-pointer category-edit" data-hash="{{-- md5($parenCategory->id.$website_key) --}}" data-title="{{-- $parenCategory->category --}}">
                                                                    <i class="feather icon-settings"></i>
                                                                </span>
                                                                <span class="float-right pl-1 cursor-pointer category-mapping" data-hash="{{-- md5($parenCategory->id.$website_key) --}}" data-title="{{-- $parenCategory->category --}}">
                                                                    <i class="feather icon-link"></i>
                                                                </span>
                                                                <div id="parent-data-{{-- $parenCategory->id --}}" class="list-group-item-child d-none">
                                                                    <ul class="list-group-flush pt-75 channel_category p-1">
                                                                        <!-- foreach($subCategoryModel->where('parent_id',$parenCategory->id) as $subCategoryLevel1) -->
                                                                            <li class="list-group-item border-0 draggable">
                                                                                <span class="handle" data-id="{{-- $subCategoryLevel1->id --}}" data-async="true">+</span>
                                                                                <span class="category-name" data-hash="{{-- md5($subCategoryLevel1->id.$website_key) --}}">{{-- $subCategoryLevel1->category --}}</span>
                                                                                <span class="float-right pl-1 cursor-pointer category-delete" data-hash="{{-- md5($subCategoryLevel1->id.$website_key) --}}" data-title="{{-- $subCategoryLevel1->category --}}">
                                                                                    <i class="feather icon-trash"></i>
                                                                                </span>
                                                                                <span class="float-right pl-1 cursor-pointer category-edit" data-hash="{{-- md5($subCategoryLevel1->id.$website_key) --}}" data-title="{{-- $subCategoryLevel1->category --}}">
                                                                                    <i class="feather icon-settings"></i>
                                                                                </span>
                                                                                <span class="float-right pl-1 cursor-pointer category-mapping" data-hash="{{-- md5($subCategoryLevel1->id.$website_key) --}}" data-title="{{-- $subCategoryLevel1->category --}}">
                                                                                    <i class="feather icon-link"></i>
                                                                                </span>
                                                                                <div id="parent-data-{{-- $subCategoryLevel1->id --}}" class="list-group-item-child d-none">
                                                                                    <ul class="list-group-flush pt-75 channel_category p-1"></ul>
                                                                                </div>
                                                                            </li>
                                                                        <!-- endforeach -->
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        <!-- endforeach -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- dataList ends -->

                    <!-- add new sidebar starts -->
                    <div class="add-new-data-sidebar">
                        <div class="overlay-bg overlay-bg-create"></div>
                        <div class="add-new-data add-new-data-create">
                            <div class="div mt-2 px-2 d-flex new-data-title justify-content-between">
                                <div>
                                    <h4 id="edit_title" class="text-uppercase">Edit Category</h4>
                                </div>
                                <div class="hide-data-sidebar">
                                    <i class="feather icon-x"></i>
                                </div>
                            </div>
                            <div class="data-items pb-3">
                                <div class="data-fields px-2 mt-3">
                                    <div id="edit_body" class="row"></div>
                                </div>
                            </div>
                            <div class="add-data-footer d-flex justify-content-around px-3 mt-2">
                                <div class="add-data-btn">
                                    <button class="btn btn-primary" id="add_file">Update Category</button>
                                </div>
                                <div class="cancel-data-btn">
                                    <button class="btn btn-outline-primary">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- add new sidebar ends -->

                    <!-- update new sidebar starts -->
                    <div class="add-new-data-sidebar">
                        <div class="overlay-bg overlay-bg-update"></div>
                        <div class="add-new-data add-new-data-update">
                            <div class="div mt-2 px-2 d-flex new-data-title justify-content-between">
                                <div>
                                    <h4 id="update_title" class="text-uppercase">Manage Attributes</h4>
                                </div>
                                <div class="hide-data-sidebar">
                                    <i class="feather icon-x"></i>
                                </div>
                            </div>
                            <div class="data-items pb-3 data-items-update">
                                <div class="data-fields px-2 mt-3">
                                    <div id="update_body" class="row"></div>
                                </div>
                            </div>
                            <div class="add-data-footer d-flex justify-content-around px-3 mt-2">
                                <div class="add-data-btn">
                                    <button class="btn btn-primary" id="update_file">Set Attributes</button>
                                </div>
                                <div class="cancel-data-btn">
                                    <button class="btn btn-outline-primary">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- update new sidebar ends -->

                </section>
                <!-- Data list view end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@section('vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/dragula.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/data-list-view.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/drag-and-drop.css') }}">
@endsection

@section('vendor_js')
    <script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/draggable.bundle.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('script')
    <script src="{{ asset('app-assets/js/core/libraries/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/category-list-view.js') }}"></script>
    <script>
        var categories = {
            // foreach($categoryModel as $parenCategory)
                {{-- $parenCategory->id --}}:'{{-- $parenCategory->category --}}',
            // endforeach
        }
    </script>
@endsection