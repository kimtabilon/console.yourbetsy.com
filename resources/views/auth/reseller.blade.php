@extends('layout.layout')

@section('content')
<div class="wrapper login">
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <img class="login-logo" src="/assets/img/company-logo.png" alt="Betsy">
                            <div class="card">
                                <div class="card-header card-header-admin">
                                    <h4 class="card-title">Seller Log In</h4>
                                    <p class="card-category"></p>
                                </div>
                                @if($errors->any())
                                    @if ($errors->first() == "incorrect")
                                        <div class="col-11 col-md-4 alert alert-warning custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Warning - </b> User Name and Password do not match. Please try again. Forgot Password?</span>
                                        </div>
                                    @endif
                                    @if ($errors->first() == "notallowed")
                                        <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Alert - </b> The account has not been verified. Please expect an email confirmation from the Betsy team soon.</span>
                                        </div>
                                    @endif
                                @endif
                                {{-- @if (isset($signup))
                                    <div class="col-11 col-md-4 alert alert-success custom-alert-animation-manualclose custom-alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                        </button>
                                        <span><b> Successful - </b> Your account has been created </span>
                                    </div>
                                @endif --}}
                                <div class="card-body">
                            <form method="POST" action="{{ route('vendor.submit') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Username</label>
                                            <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                            @if (isset($message))
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Password</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div> -->

                                {{-- <div class="form-group row mb-0"> --}}
                                        <a href="/signup" class="pull-left">Sign Up</a>
                                        <button type="submit" class="btn btn-admin pull-right">
                                            {{ __('Login') }}
                                        </button>

                                        <!-- @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif -->
                                    
                                {{-- </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection
