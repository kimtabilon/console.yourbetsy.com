@extends('layout.admin.admin-sidebar-layout')

@section('breadcrumb')<a class="navbar-brand" href="/admin/vendor/suspend-disable">Suspend/Disable Vendor<div class="ripple-container"></div></a>@endsection

@section('content')
<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-admin">
                        <h4 class="card-title ">Active Vendor List</h4>
                        {{-- <p class="card-category"> Active Resellers</p> --}}
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Suspend/Disable Vendors: </label>
                                <input type="checkbox" class="js-switch"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="active_tbl">
                            <table class="table" id="active_resellers_tbl">
                                <thead class=" text-primary">
                                    <th>Vendor Type</th>
                                    <th>Username</th>
                                    <th>Vendor's Name</th>
                                    <th>Date Sign Up</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($resellers_active as $info)
                                    <tr>
                                        <td>{{ Reseller_type($info->reseller_type) }}</td>
                                        <td>{{$info->username}}</td>
                                        <td>{{$info->reseller_name}}</td>
                                        <td>{{$info->signup_date}}</td>
                                        <td>{{Status_type($info->status)}}</td>
                                        <td class="td-actions text-center"><button type="button" rel="tooltip" onclick="show_suspend_disable_modal({{$info->reseller_status_id}})" class="btn btn-danger btn-link" title="Suspend/Disable">
                                            <div class="custom-icon-remove-person">
                                                <i class="material-icons">
                                                    person
                                                    </i>
                                                <i class="material-icons">
                                                    clear
                                                    </i>
                                            </div>
                                            
                                          <div class="ripple-container"></div></button>
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->reseller_status_id}},'{{$info->reseller_name}}')" class="btn btn-info btn-link" title="View">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="suspenddisabled_tbl" style="display: none">
                            <table class="table" id="suspenddisable_resellers_tbl">
                                <thead class=" text-primary">
                                    <th>Vendor Type</th>
                                    <th>Username</th>
                                    <th>Vendor's Name</th>
                                    <th>Date Sign Up</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($resellers_suspenddisabled as $info)
                                    <tr>
                                        <td>{{ Reseller_type($info->reseller_type) }}</td>
                                        <td>{{$info->username}}</td>
                                        <td>{{$info->reseller_name}}</td>
                                        <td>{{$info->signup_date}}</td>
                                        <td>{{Status_type($info->status)}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_verify_modal({{$info->reseller_status_id}})" class="btn btn-success btn-link" title="Reactivate">
                                                <i class="material-icons">check</i>
                                            <div class="ripple-container"></div></button>
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->reseller_status_id}},'{{$info->reseller_name}}')" class="btn btn-info btn-link" title="View">
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
<div class="modal fade" id="modal_suspend_disable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Suspend/Disable Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p>Are you sure you want to Suspend or Disable the account of this vendor?</p>
                          </div>
                    </div>
                </div>
                <form id="reseller_frm" method="POST">
                    {{ method_field('PATCH')}}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Choose Status</label>
                                <select class="form-control" name="status">
                                    <option value="3">Suspend</option>
                                    <option value="4">Disable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <input type="hidden" name="verify_reseller_id">
                
            </div>
                
                    
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Cancel
                    <div class="ripple-container"></div>
                    <div class="ripple-decorator"></div>
                </button>
                <button type="submit" form="reseller_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div>
                </button>
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
                    <input type="hidden" name="reactivate_reseller_id">
                    <input type="hidden" name="reactivate_status" value="0">
                
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="verify_reseller_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection 

@section('script')

<script>
    $('#reseller_management_item').addClass('active');
    $('#reseller_management_item').find('.collapser').addClass('collapsed');
    $('#reseller_management_item').find('.collapse').addClass('show');
    $('#reseller_management_item').find('#reseller_suspenddisable_subitem').addClass('active');
</script>

<script>
    $(document).ready(function() {
        $('#active_resellers_tbl').DataTable();
        $('#suspenddisable_resellers_tbl').DataTable();

        var switchery = new Switchery(document.querySelector('.js-switch'), { size: 'small' });

        switch_tbl();
    });

    function switch_tbl() {
        $('.js-switch').on('change', function() {
            if ($(this).is(":checked")) {
                $('#active_tbl').hide();
                $('#suspenddisabled_tbl').fadeIn();
            } else {
                $('#active_tbl').fadeIn();
                $('#suspenddisabled_tbl').hide();
            }
        });
    }

    function show_verify_modal(id) {
        $('[name="reactivate_reseller_id"]').val(id);
        $('[name="reactivate_status"]').val(30);
        $('.verify_decline_msg').html('Activate');
        $('#modal_verify').modal('show');
    }

    function show_suspend_disable_modal(id) {
        $('[name="verify_reseller_id"]').val(id);
        $('#modal_suspend_disable').modal('show');
    }

    function show_view_modal(status_id,id) {
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

    $("#reseller_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_suspend_disable').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type:'PUT',
            url:'/admin/vendor/change_status',
            data:{
                verify_reseller_id: $('[name="verify_reseller_id"]').val(),
                status: $('[name="status"]').val(),
            },
            dataType: 'json',
            success:function(data) {
                
                if (data.status == "successful") {
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            saving_modal("hide");
                            location.reload(); 
                            
                        }
                    });
                    
                }else{
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            saving_modal("hide");
                            location.reload(); 
                        }
                    });
                }

                
            }
        });

        $('.modal-custom-alert').on('hide.bs.modal', function (e) {
            location.reload();     
        })
    });

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
                verify_reseller_id: $('[name="reactivate_reseller_id"]').val(),
                status: $('[name="reactivate_status"]').val(),
            },
            dataType: 'json',
            success:function(data) {
                
                if (data.status == "successful") {
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            
                            location.reload(); 
                            saving_modal("hide");
                        }
                    });
                    
                }else{
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            location.reload(); 
                            saving_modal("hide");
                        }
                    });
                }

                
            }
        });

        $('.modal-custom-alert').on('hide.bs.modal', function (e) {
            location.reload();     
        })
    });
</script>
@endsection 