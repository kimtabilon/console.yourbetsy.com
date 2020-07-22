@extends('layout.admin.admin-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/admin/vendor/verify">Verify Vendor<div class="ripple-container"></div></a>
@endsection

@section('content')

<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- @if(session()->get('data'))
                        @if (session()->get('data')['status'] = "successful")
                            <div class="col-11 col-md-4 alert alert-success custom-alert-animation custom-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                                <span><b> Success - </b> Reseller {{session()->get('data')['reseller_name']}} account has been successfully verified</span>
                            </div>
                            
                        @elseif(session()->get('data')['status'] = "unsuccessful")
                            <div class="col-11 col-md-4 alert alert-danger custom-alert-animation custom-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                                <span><b> Error - </b> Verifying reseller {{session()->get('data')['reseller_name']}} unsuccesful</span>
                            </div>
                        @endif
                        @php session()->forget('data');@endphp
                    @endif --}}
                    <div class="card-header card-header-admin">
                        <h4 class="card-title ">Pending Vendor List</h4>
                        {{-- <p class="card-category"> Resellers pending for verification</p> --}}
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Declined Vendors: </label>
                                <input type="checkbox" class="js-switch"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="pending_tbl">
                            <table class="table" id="pending_resellers_tbl">
                                <thead class=" text-primary">
                                    <th>Vendor Type</th>
                                    <th>Username</th>
                                    <th>Vendor's Name</th>
                                    <th>Date Sign Up</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($resellers_pending as $info)
                                    <tr>
                                        <td>{{ Reseller_type($info->reseller_type) }}</td>
                                        <td>{{$info->username}}</td>
                                        <td>{{$info->reseller_name}}</td>
                                        <td>{{$info->signup_date}}</td>
                                        <td>{{Status_type($info->status)}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_verify_modal({{$info->reseller_status_id}})" class="btn btn-success btn-link" title="Approve">
                                                <i class="material-icons">how_to_reg</i>
                                            <div class="ripple-container"></div></button>
                                            <button type="button" rel="tooltip" onclick="show_disapprove_modal({{$info->reseller_status_id}})" class="btn btn-danger btn-link" title="Decline">
                                                <i class="material-icons">clear</i>
                                            <div class="ripple-container"></div></button>
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->reseller_status_id}})" class="btn btn-info btn-link" title="View">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="declined_tbl" style="display: none">
                            <table class="table" id="declined_resellers_tbl">
                                <thead class=" text-primary">
                                    <th>Vendor Type</th>
                                    <th>Username</th>
                                    <th>Vendor's Name</th>
                                    <th>Date Sign Up</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($resellers_declined as $info)
                                    <tr>
                                        <td>{{ Reseller_type($info->reseller_type) }}</td>
                                        <td>{{$info->username}}</td>
                                        <td>{{$info->reseller_name}}</td>
                                        <td>{{$info->signup_date}}</td>
                                        <td>{{Status_type($info->status)}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_verify_modal({{$info->reseller_status_id}})" class="btn btn-success btn-link" title="Approve">
                                                <i class="material-icons">how_to_reg</i>
                                            <div class="ripple-container"></div></button>
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->reseller_status_id}})" class="btn btn-info btn-link" title="View">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}
{{-- Approved Modal --}}
<div class="modal fade" id="modal_verify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="verify_decline_msg"></span> Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span class="verify_decline_msg"></span> the account of this vendor?</p>
            </div>
                <form id="verify_reseller_frm" method="POST">
                    {{ method_field('PATCH')}}
                    {{ csrf_field() }}
                    <input type="hidden" name="verify_reseller_id">
                    <input type="hidden" name="status" value="0">
                </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="verify_reseller_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade modal-custom-alert" id="alert_success" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
               
                <div class="thank-you-pop">
                    <i class="material-icons icon-alert-success">check</i>
                    <h1>Successful!</h1>
                    <button type="button" class="btn btn-success btn-link" data-dismiss="modal">
                        OK
                        <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                 </div>
                 
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade modal-custom-alert" id="alert_danger" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
               
                <div class="thank-you-pop">
                    <i class="material-icons icon-alert-danger">clear</i>
                    <h1>Failed!</h1>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                        OK
                        <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                 </div>
                 
            </div>
            
        </div>
    </div>
</div> --}}

{{-- View Modal --}}
<div class="modal fade modal-custom" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Vendor Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vendor Position Type</label>
                            <input class="form-control" type="text" name="vendor_position_type_v" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sign up as old member</label>
                            <input class="form-control" type="text" name="singup_type_v" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vendor Type</label>
                            <input class="form-control" type="text" name="reseller_type_v" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="business_permit_number_container">
                            <label>Business Permit Number</label>
                            <input class="form-control" type="text" name="business_permit_number_v" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Username</label>
                            <input class="form-control" type="text" name="username_v" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" name="name_v" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address_v" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control" type="text" name="email_address_v" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input class="form-control" type="text" name="contact_person_v" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Primary Contact Number</label>
                            <input class="form-control" type="text" name="mobile_no_v" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Secondary Contact Number</label>
                            <input class="form-control" type="text" name="secondary_contact_no_v" aria-required="true" readonly>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('script')
<script>
    $('#reseller_management_item').addClass('active');
    $('#reseller_management_item').find('.collapser').addClass('collapsed');
    $('#reseller_management_item').find('.collapse').addClass('show');
    $('#reseller_management_item').find('#reseller_verify_subitem').addClass('active');
</script>

<script>
    $(document).ready(function() {
        $('#pending_resellers_tbl').DataTable();
        $('#declined_resellers_tbl').DataTable();

        var switchery = new Switchery(document.querySelector('.js-switch'), { size: 'small' });

        switch_tbl();

        // saving_modal("show");
    });
    
    function switch_tbl() {
        $('.js-switch').on('change', function() {
            if ($(this).is(":checked")) {
                $('#pending_tbl').hide();
                $('#declined_tbl').fadeIn();
            } else {
                $('#pending_tbl').fadeIn();
                $('#declined_tbl').hide();
            }
        });
    }

    function show_verify_modal(id) {
        
        $('[name="verify_reseller_id"]').val(id);
        $('[name="status"]').val(0);
        $('.verify_decline_msg').html('Verify');
        $('#modal_verify').modal('show');
    }

    function show_disapprove_modal(id) {
        $('[name="verify_reseller_id"]').val(id);
        $('[name="status"]').val(2);
        $('.verify_decline_msg').html('Decline');
        $('#modal_verify').modal('show');
    }

    

    function show_view_modal(id) {
        $('#modal_view').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/admin/vendor/verify/view',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                $('[name="username_v"]').val(data.username);
                $('[name="name_v"]').val(data.reseller_name);
                $('[name="address_v"]').text(data.address);
                $('[name="email_address_v"]').val(data.email_address);
                $('[name="contact_person_v"]').val(data.contact_person);
                $('[name="mobile_no_v"]').val(data.mobile_number);
                $('[name="secondary_contact_no_v"]').val(data.secondary_number);

                var reseller_type;
                if(data.reseller_type == 1) {
                    $('#business_permit_number_container').show();
                    reseller_type = "Business";
                    $('[name="business_permit_number_v"]').val(data.business_permit_number);
                }else{
                    $('#business_permit_number_container').hide();
                    reseller_type = "Individual"
                }
                $('[name="reseller_type_v"]').val(reseller_type);


                var vendor_position_type;
                if(data.reseller_position == 0) {
                    vendor_position_type = "Admin Vendor";
                }else {
                    vendor_position_type = "Secondary Vendor";
                }
                $('[name="vendor_position_type_v"]').val(vendor_position_type);

                var singup_type;
                if(data.already_member == 0) {
                    singup_type = "Yes";
                }else {
                    singup_type = "No";
                }
                $('[name="singup_type_v"]').val(singup_type);

            }
        });
    }

    $("#verify_reseller_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_verify').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type:'PUT',
            url:'/admin/vendor/change_status',
            data:{
                verify_reseller_id: $('[name="verify_reseller_id"]').val(),
                status: $('[name="status"]').val()
            },
            dataType: 'json',
            success:function(data) {
                saving_modal("hide");
                if (data.status == "successful") {
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            location.reload(); 
                        }
                    });
                    
                }else{
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            location.reload(); 
                        }
                    });
                }
                
            }
        });
        
    });
</script>


@endsection 