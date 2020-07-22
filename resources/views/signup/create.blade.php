
@extends('layout.layout')

@section('content')
<div class="wrapper sign-up">
    <div class="main-panel">
      	<div class="content">
        	<div class="container-fluid">
          		<div class="row">
            		<div class="col-md-6">
						<img class="signup-logo" src="/assets/img/company-logo.png" alt="Betsy">
              			<div class="card">
                			<div class="card-header card-header-admin">
                  				<h4 class="card-title">Sign Up</h4>
                  				<p class="card-category">Seller Account</p>
                			</div>
                			<div class="card-body">

                                @if ($errors->any())
                                    @php
                                        $required_error = 0;
                                        $unique_error = 0;
                                        foreach($errors->all() as $err) {
                                            if($err == "Required") {
                                                $required_error++;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if ($required_error > 0)
                                        <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span><b> Required - </b> All fields in Red are required  </span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                            </button>
                                            <span></span>
                                            <ul>
                                                @foreach($errors->all() as $err) 
                                                    <li>{{$err}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
   
                                @endif
								{{-- {{dd($errors->has('reseller_name.unique'))}} --}}
								<div class="row custom-row-checkbox">
									<div class="col-md-6">
										<div class="form-check">
											<label class="form-check-label">
												Are you already a Betsy Seller?
											<input class="form-check-input" type="checkbox" form="signup_frm" name="already_member" {{ old( "already_member" ) == "on" ? "checked" : "" }}>
											<span class="form-check-sign">
												<span class="check"></span>
											</span>
											</label>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
                  				<form id="signup_frm" method="post" action="/signup">
								  {{csrf_field()}}
								  	
									<div class="row custom-row-checkbox">
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Seller Type</label>
												<select class="form-control" name="reseller_type">
													<option value="0" {{ old( "reseller_type" ) == 0 ? "selected" : "" }}>Individual</option>
													<option value="1" {{ old( "reseller_type" ) == 1 ? "selected" : "" }}>Business</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
										</div>
										{{-- <div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Account Type</label>
												<select class="form-control" name="account_type">
													<option value="0" {{ old( "account_type" ) == 0 ? "selected" : "" }}>Child</option>
													<option value="1" {{ old( "account_type" ) == 1 ? "selected" : "" }}>Parent</option>
												</select>
											</div>
										</div> --}}
									</div>
									{{-- <div class="row" id="row_vendors_parent" style="display: {{old('account_type') == 0 ? "none" : "block"}};">
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Vendors Parent Name</label>
												<input name="parent_vendor" type="text" class="form-control uppercase-input {{$errors->has('parent_vendor')? "required-error": "" }}" value="{{old('parent_vendor')}}"  >
											</div>
										</div>
										<div class="col-md-6"></div>
									</div> --}}
									<div class="row">
										<div class="col-md-6" id="reseller_col">
											<div class="form-group">
                                                <label class="bmd-label-floating">Seller Name</label>
											<input name="seller_name" type="text" class="form-control uppercase-input {{$errors->has('seller_name')? "required-error": "" }}" value="{{old('seller_name')}}"  >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group" id="business_permit_no_container" style="display: {{old('reseller_type') == 0 ? "none" : "block"}};">
                                                <label class="bmd-label-floating">Business Permit Number</label>
                                                <input name="business_permit_number" type="text" class="form-control uppercase-input {{$errors->has('business_permit_number')? "required-error": "" }}" value="{{old('business_permit_number')}}"  >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="bmd-label-floating">Address</label>
												<textarea name="address" class="form-control {{$errors->has('address')? "required-error": "" }}" rows="3" >{{old('address')}}</textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Email address</label>
                                                <input type="email" name="email_address" class="form-control {{$errors->has('email_address')? "required-error": "" }}" value="{{old('email_address')}}" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Contact Person</label>
                                                <input type="text" name="contact_person" class="form-control uppercase-input {{$errors->has('contact_person')? "required-error": "" }}"  value="{{old('contact_person')}}" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Primary Contact Number</label>
                                                <input type="number" name="mobile_number" class="form-control {{$errors->has('mobile_number')? "required-error": "" }}"  value="{{old('mobile_number')}}" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Secondary Contact Number(Optional)</label>
                                                <input type="number" name="secondary_contact_number" class="form-control" value="{{old('secondary_contact_number')}}" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Username</label>
                                                <input type="text" name="username" class="form-control {{$errors->has('username')? "required-error": "" }}" value="{{old('username')}}" >
											</div>
										</div>
										<div class="col-md-6">
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Password</label>
                                                <input type="password" name="password" class="form-control {{$errors->has('password')? "required-error": "" }}" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
                                                <label class="bmd-label-floating">Confirm Password</label>
                                                <input type="password" name="password_confirmation" class="form-control">
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
									<button type="submit" class="btn btn-admin">Sign Up</button>
                                </div>
							<div class="clearfix"></div>
						</form>
					</div>
              	</div>
            </div>
        </div>
    </div>
</div>
      
  </div>

  {{-- <div class="modal" tabindex="-1" role="dialog" id="signup_modal">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title">Modal title</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
		  <p>SUCCESS.</p>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-primary" onclick="redirect_to_login()">Ok</button>
		</div>
	  </div>
	</div>
  </div> --}}
  @endsection 
  @section('script')

  <script>

	$(document).ready(function() {
		$('[name="reseller_type"]').on('change', function() {
			if($(this).val() == 1) {
				$('#business_permit_no_container').fadeIn();
			}else{
				$('#business_permit_no_container').fadeOut();
			}

			
	  	})
		  
	});
		$(document).on('change','[name="already_member"]', function(e) {
			// e.stopPropagation;
			if ($(this).is(":checked")) {
				
				$('.sign-up').append("\
						<div class=\"col-11 col-md-4 alert alert-warning custom-alert-animation custom-alert\">\
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\
								<i class=\"material-icons\">close</i>\
							</button>\
							<span> Please provide your Seller Name </span>\
						</div>\
				");

				$('[name="seller_name"]').addClass('required-error');
			}else{
				$('[name="seller_name"]').removeClass('required-error');
			}
		});
	  
  </script>
  @endsection 
