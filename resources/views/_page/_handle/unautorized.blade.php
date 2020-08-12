@extends('_layout.default')
@section('content')
    <!-- BEGIN: Content-->
    <div class="content-wrapper">
        <div class="content">
            <center style="margin-top:20vh;margin-bottom:50vh">
                <h2>Oops!</h2><hr>
                <h6>trying <i style="color:#fa8231">{{\URL::previous()}}</i> ?</h6><br>
                <h4>you have no authority to access this page</h4>
            </center>
        </div>
    </div>
    <!-- END: Content-->
@stop