
@extends('layout.layout')

@section('content')
<div class="wrapper sign-up">
    <div class="main-panel">
      	<div class="content">
        	<div class="container-fluid">
          		<div class="row">
            		<div class="col-md-6">
              			<div class="card">
                			<div class="card-header card-header-admin">
                  				<h4 class="card-title">Sign Up</h4>
                  				<p class="card-category">Seller's Account</p>
                			</div>
                			<div class="card-body">
									<div class="row">
										<div class="col-md-12">
											<h4>You have successfully signed up for a Betsy account. Pending approval from the administrator</h4>
										</div>
									</div>
									<button type="submit" class="btn btn-admin" onclick="redirect_to_login()">Go to login</button>
                                </div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function redirect_to_login() {
		window.location.href = '/';
	}
</script>
  @endsection 
