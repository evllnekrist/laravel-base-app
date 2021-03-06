    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    @yield('vendor_js')
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/components.js') }}"></script>
    <!-- END: Theme JS-->
    <!-- BEGIN: Page JS-->
    @yield('script')
    <!-- END: Page JS-->
    <!-- BEGIN: Global JS-->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('../resources/js/helper.js') }}?var=00001"></script> <!-- embed to every page -->
    <!-- END: Global JS-->
