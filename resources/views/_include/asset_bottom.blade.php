    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    @yield('vendor_js')
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/components.js') }}"></script>
    <!-- END: Theme JS-->
    <!-- BEGIN: Page JS-->
    @yield('script')
    <?php
        if(isset($header_data)){
            if (array_key_exists('js', $header_data)) {
                foreach($header_data['js'] as $js_item ){
                    $js_item = '_script._page.'.$js_item;
                    ?> 
                        @include($js_item) 
                    <?php
                }
            }
        }
    ?>
    <!-- END: Page JS-->
    <!-- BEGIN: Global JS-->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('../resources/js/helper.js') }}?var=00001"></script> <!-- embed to every page -->
    <!-- END: Global JS-->
