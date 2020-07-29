@extends('layout.admin.admin-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/admin/vendor/verify">Vendor Information Update Request<div class="ripple-container"></div></a>
@endsection

@section('content')

<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-admin">
                        <h4 class="card-title ">Profile Update Pending/Declined Request List</h4>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Declined/Approved Vendors: </label>
                                <input type="checkbox" class="js-switch"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="pending_tbl">
                            <table class="table" id="pending_request_tbl">
                                <thead class=" text-primary">
                                    <th>Username</th>
                                    <th>Vendor Name</th>
                                    <th>Vendor Type</th>
                                    <th>Date Request</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($pending as $info)
                                    <tr>
                                        <td>{{ $info->username }}</td>
                                        <td>{{ $info->vendor_name }}</td>
                                        <td>{{ $info->vendor_type }}</td>
                                        <td>{{ $info->date_request }}</td>
                                        <td>{{ $info->status }}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_approve_modal({{$info->id}})" class="btn btn-success btn-link" title="Approve">
                                                <i class="material-icons">check</i>
                                            <div class="ripple-container"></div></button>
                                            <button type="button" rel="tooltip" onclick="show_declined_modal({{$info->id}})" class="btn btn-danger btn-link" title="Decline">
                                                <i class="material-icons">clear</i>
                                            <div class="ripple-container"></div></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" id="declined_tbl" style="display: none">
                            <table class="table" id="declined_request_tbl">
                                <thead class=" text-primary">
                                    <th>Username</th>
                                    <th>Vendor Name</th>
                                    <th>Vendor Type</th>
                                    <th>Date Request</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($approved_declined as $info)
                                        <tr>
                                            <td>{{ $info->username }}</td>
                                            <td>{{ $info->vendor_name }}</td>
                                            <td>{{ $info->vendor_type }}</td>
                                            <td>{{ $info->date_request }}</td>
                                            <td>{{ $info->status }}</td>
                                            <td class="td-actions text-center">
                                                <button type="button" rel="tooltip" onclick="show_view_modal({{$info->id}})" class="btn btn-info btn-link" title="View">
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
<div class="modal fade modal-custom" id="modal_action_request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="request_action_msg"></span> Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>Are you sure you want to <span class="request_action_msg"></span> this request?</p>
                    </div>
                </div>
                <div class="row custom-divider"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group custom-details-header">
                            <label>Vendor Name: <span id="approve_name"></span></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 email_address_app">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control" type="text" name="email_address_app" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 address_app">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address_app" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 contact_person_app">
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input class="form-control" type="text" name="contact_person_app" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-6 primary_contact_num_app">
                        <div class="form-group">
                            <label>Primary Contact Number</label>
                            <input class="form-control" type="text" name="primary_contact_num_app" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 secondary_contact_num_app">
                        <div class="form-group">
                            <label>Secondary Contact Number</label>
                            <input class="form-control" type="text" name="secondary_contact_num_app" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
            </div>
                <form id="action_request_frm" method="POST">
                    {{ method_field('PATCH')}}
                    {{ csrf_field() }}
                    <input type="hidden" name="request_id">
                    <input type="hidden" name="status" value="0">
                
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="action_request_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- View Modal --}}
<div class="modal fade modal-custom" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Request Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group custom-details-header">
                            <label>Vendor Name: <span id="approve_name_view"></span></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 email_address_view">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control" type="text" name="email_address_view" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 address_view">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address_view" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 contact_person_view">
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input class="form-control" type="text" name="contact_person_view" aria-required="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 primary_contact_num_view">
                        <div class="form-group">
                            <label>Primary Contact Number</label>
                            <input class="form-control" type="text" name="primary_contact_num_view" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 status_view">
                        <div class="form-group">
                            <label>Status</label>
                            <input class="form-control" type="text" name="status_view" aria-required="true" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
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
    $('#reseller_management_item').find('#reseller_updateprofile_req_subitem').addClass('active');
</script>

<script>
    $(document).ready(function() {
        $('#pending_request_tbl').DataTable();
        $('#declined_request_tbl').DataTable();

        var switchery = new Switchery(document.querySelector('.js-switch'), { size: 'small' });

        switch_tbl();
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

    function show_approve_modal(id) {
        $('[name="request_id"]').val(id);
        $('[name="status"]').val(0);
        $('.request_action_msg').html('Approve');
        $('#modal_action_request').modal('show');
        show_details_modal(id);
    }

    function show_declined_modal(id) {
        $('[name="request_id"]').val(id);
        $('[name="status"]').val(2);
        $('.request_action_msg').html('Decline');
        $('#modal_action_request').modal('show');
        show_details_modal(id);
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
            url:'/admin/vendor/profile-update-request/view-requested',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                var requested_details = data.data.details_requested;
                var data_profile = data.data.reseller_profile;
                hide_input([
                    "email_address_view",
                    "address_view",
                    "contact_person_view",
                    "primary_contact_num_view",
                    "status_view"
                ])
                $("#approve_name_view").html(data_profile.reseller_name);
                if (requested_details.email_address) {
                    $('[name="email_address_view"]').val(requested_details.email_address);
                    $(".email_address_view").show();
                }
                if (requested_details.address) {
                    $('[name="address_view"]').text(requested_details.address);
                    $(".address_view").show();
                }
                if (requested_details.contact_person) {
                    $('[name="contact_person_view"]').val(requested_details.contact_person);
                    $(".contact_person_view").show();
                }
                if (requested_details.primary_contact_number) {
                    $('[name="primary_contact_num_view"]').val(requested_details.primary_contact_number);
                    $(".primary_contact_num_view").show();
                }
                if (data.data.status) {
                    $('[name="status_view"]').val(data.data.status);
                    $(".status_view").show();
                }
                
            }
        });
    }

    function show_details_modal(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/admin/vendor/profile-update-request/view-requested',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                var requested_details = data.data.details_requested;
                var data_profile = data.data.reseller_profile;
                hide_input([
                    "email_address_app",
                    "address_app",
                    "contact_person_app",
                    "primary_contact_num_app",
                    "secondary_contact_num_app"
                ])
                $("#approve_name").html(data_profile.reseller_name);
                if (requested_details.email_address) {
                    $('[name="email_address_app"]').val(requested_details.email_address);
                    $(".email_address_app").show();
                }
                if (requested_details.address) {
                    $('[name="address_app"]').text(requested_details.address);
                    $(".address_app").show();
                }
                if (requested_details.contact_person) {
                    $('[name="contact_person_app"]').val(requested_details.contact_person);
                    $(".contact_person_app").show();
                }
                if (requested_details.primary_contact_number) {
                    $('[name="primary_contact_num_app"]').val(requested_details.primary_contact_number);
                    $(".primary_contact_num_app").show();
                }
                if (requested_details.secondary_contact_number) {
                    $('[name="secondary_contact_num_app"]').val(requested_details.secondary_contact_number);
                    $(".secondary_contact_num_app").show();
                }
                
            }
        });
    }

    function hide_input(inputs) {
        $.each(inputs, function( index, value ) {
            $("."+value).hide();
        });
       
    }

    $("#action_request_frm").submit(function( e ) {
        e.preventDefault();

        $('#modal_action_request').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type:'PUT',
            url:'/admin/vendor/profile-update-request',
            data:{
                request_id: $('[name="request_id"]').val(),
                status: $('[name="status"]').val()
            },
            dataType: 'json',
            success:function(data) {
                
                setTimeout(function() {
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
                }, 3000);
                
                
                
            }
        });
        
    });
</script>


@endsection 