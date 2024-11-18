 


@extends('layouts.master-without-nav')

@section('title')
@lang('translation.Login')
@endsection

@section('css')
<!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
@endsection

@section('body')

<body class="auth-body-bg">
    @endsection

    @section('content')

    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">

                <div class="col-xl-9">
                    <div class="auth-full-bg pt-lg-5 p-4">
                        <div class="w-100">
                            <div class="bg-overlay"></div>
                            <div class="d-flex h-100 flex-column">

                                <div class="p-4 mt-auto">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-7">
                                            <div class="text-center">

                                               

                                                <div dir="ltr">
                                                    <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                                        <div class="item">
                                                            <div class="py-3">
                                                         

                                                                <div>
                                                                  
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="item">
                                                            <div class="py-3">
                                                           

                                                                <div>
                                                                    
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-3">
                    <div class="auth-full-page-content p-md-5 p-4">
                        <div class="w-100">

                            <div class="d-flex flex-column h-100">
                                <!-- <div class="mb-4 mb-md-5">
                                    <a href="index" class="d-block auth-logo">
                                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="38" class="auth-logo-dark">
                                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="18" class="auth-logo-light">
                                    </a>
                                </div>  -->
                                 <div class="mb-4 mb-md-5 text-center">
                                    <a href="index" class="d-block auth-logo">
                                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="58" width="100" class="auth-logo-dark mx-auto">
                                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="38" class="auth-logo-light mx-auto">
                                    </a>
                                </div>
                                
                                <div class="my-auto">

                                    <div>
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p class="text-muted">Super Admin Login for Register New Company to Codice 1%.</p>
                                    </div>

                                    <div class="mt-4">
                                   
                                                <!-- <form method="POST" action="{{ route('admin.login.submit') }}">
                                                    @csrf
                                                    <div>
                                                        <label>Password:</label>
                                                        <input type="password" name="password" required>
                                                    </div>
                                                    @if($errors->has('password'))
                                                        <div>{{ $errors->first('password') }}</div>
                                                    @endif
                                                    <button type="submit"  class="btn btn-primary  " >Login</button>
                                                </form> -->

                                                <!DOCTYPE html>
 
 
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                </div>
                @if($errors->has('password'))
                    <div class="alert alert-danger mt-2">{{ $errors->first('password') }}</div>
                @endif
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </form>
 
 




                                        <div class="mt-5 text-center">
                                            <!-- <p>Don't have an account ? <a href="{{ url('register') }}" class="fw-medium text-primary"> Signup now </a> </p> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">© <script>
                                            document.write(new Date().getFullYear())
                                        </script> Appcontratti Crafted with <i class="mdi mdi-heart text-danger"></i> by
                                        Codice 1%</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container-fluid -->
    </div>

    @endsection
    @section('script')
    <!-- owl.carousel js -->
    <script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>
    <!-- auth-2-carousel init -->
    <script src="{{ URL::asset('build/js/pages/auth-2-carousel.init.js') }}"></script>
    @endsection
