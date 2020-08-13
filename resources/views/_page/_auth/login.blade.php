@extends('_layout.slim')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
            
                <section class="row flexbox-container">
                    <div class="col-xl-7 col-10 d-flex justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0 w-100">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                    <img src="{{ asset('app-assets/svg/login.svg') }}" alt="branding logo" style="max-height: 200px">
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2 pb-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="mb-0">@lang('app.2nd_name_longer')</h4>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <form method="post" action="{{url('/login')}}">
                                                    @csrf
                                                    <fieldset class="form-label-group position-relative has-icon-left">
                                                        <input type="text" name="username" class="form-control" id="user-name" placeholder="Username" required>
                                                        <div class="form-control-position">
                                                            <i class="feather icon-user"></i>
                                                        </div>
                                                        <label for="user-name">Username</label>
                                                    </fieldset>

                                                    <fieldset class="form-label-group position-relative has-icon-left">
                                                        <input type="password" name="password" class="form-control" id="user-password" placeholder="Password" required>
                                                        <div class="form-control-position">
                                                            <i class="feather icon-lock"></i>
                                                        </div>
                                                        <label for="user-password">Password</label>
                                                    </fieldset>
                                                    <button type="submit" 
                                                            class="btn btn-primary float-right">Login</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // $(document).ready(function(){
        //     @if(!empty($error))
        //         toastr.error('{{ $error }}', 'Login Failed!', { "progressBar": true })
        //     @endif
        // });

        $("form").submit(function(e){
            e.preventDefault();
            let username = $('input[name="username"]').val();
            let password = $('input[name="password"]').val();
            
            if(username && password){
                console.log(username,password);
                $.ajax({
                    type: "POST",
                    url: "{{route('doLogin')}}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        username: username,
                        password: password,
                    },
                    dataType: 'json',
                    // headers: ajax_headers,
                    success: function(result){        
                        console.log('RESULT', result);
                        if(result['status']){
                            window.location.href = "{{url('/home')}}";
                        }else{
                            toastr.error(result['message'], 'Login Failed!', { "progressBar": true });
                        }
                    },
                    error: function (err){
                        toastr.error(err, 'Login Failed!', { "progressBar": true })
                    }
                });
            }

        });
    </script>
@endsection