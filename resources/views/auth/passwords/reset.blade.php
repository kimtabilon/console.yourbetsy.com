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
                                    <h4 class="card-title">Reset Password</h4>
                                    <p class="card-category"></p>
                                </div>
                                <div class="card-body">
                                @if($errors->any())
                                    @if ($errors->first() == "no_email")
                                        <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Alert - </b> The email address is not recognised as a Betsy account.</span>
                                        </div>
                                    @endif
                                    @if ($errors->first() == "wrong_question")
                                        <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Alert - </b> Security Question and Answer do not match. Please try again.</span>
                                        </div>
                                    @endif
                                    @if ($errors->first() == "wrong_password")
                                        <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Alert - </b> Your password and confirmation password do not match. Please try again.</span>
                                        </div>
                                    @endif
                                    @if ($errors->first() == "insuff_password")
                                        <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Alert - </b> The password must be at least 6 characters.</span>
                                        </div>
                                    @endif
                                @endif

                                @if (session()->has('input_pass'))
                                        <div class="col-11 col-md-4 alert alert-success custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Success - </b> Please Input your new password.</span>
                                        </div>
                                @endif

                                @if (session()->has('change_success'))
                                        <div class="col-11 col-md-4 alert alert-success custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Success - </b> Your password has been changed successfully.</span>
                                        </div>
                                @endif

                                    <form method="POST" action="/forgotpassword">

                                    @if (session()->has('input_pass') OR session()->has('repassword'))
                                       
                                            @csrf
                                                <input id="email" type="hidden" class="form-control" name="email" value="{{session()->get('email')}}">
                                                <input id="security_question" type="hidden" class="form-control" name="security_question" value="{{session()->get('security_question')}}">
                                                <input id="security_answer" type="hidden" class="form-control" name="security_answer" value="{{session()->get('security_answer')}}">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">{{ __('New Password') }}</label>
                                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">{{ __('Confirm New Password') }}</label>
                                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                                    </div>
                                                    </div>
                                                </div>

                                    @else

                                      

                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Email Address</label>
                                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                        @if (isset($message))
                                                        @error('email')
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
                                                        <label class="bmd-label-floating">Security Question</label>
                                                        <select class="form-control {{$errors->has('security_question')? "required-error": "" }}" name="security_question" >
                                                            <option value="" disabled selected>Select Security Question</option>
                                                            <option value="1" {{ old( "security_question" ) == 1 ? "selected" : "" }} >What was your childhood nickname?</option>
                                                            <option value="2" {{ old( "security_question" ) == 2 ? "selected" : "" }} >In what city did you meet your spouse/significant other?</option>
                                                            <option value="3" {{ old( "security_question" ) == 3 ? "selected" : "" }} >What is the name of your favorite childhood friend?</option>
                                                            <option value="4" {{ old( "security_question" ) == 4 ? "selected" : "" }} >What is your preferred musical genre?</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Security Question's Answer</label>
                                                        <textarea name="security_answer" class="form-control {{$errors->has('security_answer')? "required-error": "" }}" rows="3" >{{ old('security_answer') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>



 
                                            
                                    @endif

                                            <div class="form-group row mb-0">
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-admin">
                                                        {{ __('Reset') }}
                                                    </button>
                                                </div>
                                            </div>

                                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection
