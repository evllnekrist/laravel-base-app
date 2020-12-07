@extends('_layout.default')
@section('content')
    <!-- BEGIN: Content-->
    <section class="users-edit">
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Account</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="false">
                <i class="feather icon-map-pin mr-25"></i><span class="d-none d-sm-block">Address</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" id="extended-info-tab" data-toggle="tab" href="#extended-info" aria-controls="extended-info" role="tab" aria-selected="false">
                <i class="feather icon-info mr-25"></i><span class="d-none d-sm-block">Extended Info</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
            <!-- users edit media object start -->
            <div class="col-md-12 col-12 page-users-view">
                <table>
                    <tr>
                        <td class="font-weight-bold">First Name</td>
                        <td>{{ $selected_data->first_name }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Last Name</td>
                        <td>{{ $selected_data->last_name }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Email</td>
                        <td>{{ $selected_data->email }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Phone</td>
                        <td>{{ $selected_data->phone }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Phone Alt</td>
                        <td>{{ $selected_data->phone_alt }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Card ID</td>
                        <td>{{ $selected_data->card_id }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Status</td>
                        <td>{{ $selected_data->is_active?"Active":"Non-active" }}</td>
                    </tr>
                </table>
            </div>
            <!-- users edit account form ends -->
        </div>
        <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
            <!-- users edit Info form start -->
            @if(!$selected_data_address->isEmpty())
                @foreach($selected_data_address as $data_address)
                    @if(!empty($loop->index))
                        <hr>
                    @endif
                    <div class="col-md-12 col-12 page-users-view">
                        <table>
                            <tr>
                                <td class="font-weight-bold" colspan="2">#{{ $loop->iteration }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address Name</td>
                                <td>{{ $data_address->address_name }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Consignee</td>
                                <td>{{ $data_address->penerima }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Phone</td>
                                <td>{{ $data_address->phone }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address</td>
                                <td>{{ $data_address->alamat }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Urban Village</td>
                                <td>{{ $data_address->kelurahan }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Sub District</td>
                                <td>{{ $data_address->kecamatan }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">City</td>
                                <td>{{ $data_address->kota }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Postal Code</td>
                                <td>{{ $data_address->kode_pos }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Longitude</td>
                                <td>{{ $data_address->longitude }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Latitude</td>
                                <td>{{ $data_address->latitude }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Is Primary</td>
                                <td>{{ $data_address->is_primary?"Yes":"No" }}</td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="text-center">Customer address not found!</div>
            @endif
            <!-- users edit Info form ends -->
        </div>
        <div class="tab-pane" id="extended-info" aria-labelledby="extended-info-tab" role="tabpanel"></div>
    </div>
</section>
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
    <script src="{{ asset('app-assets/vendors/js/extensions/dragula.min.js') }}"></script>
@endsection
@section('script')
    <script src="{{ asset('app-assets/js/core/libraries/jquery-ui.min.js') }}"></script>
@endsection