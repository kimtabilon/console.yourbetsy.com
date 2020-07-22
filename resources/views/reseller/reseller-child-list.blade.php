@extends('layout.reseller.reseller-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/vendor/secondary-vendor-management/secondary-list">Additional Users<div class="ripple-container"></div></a>
@endsection

@section('content')

<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">Additional Users</h4>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Inactive Additional Users: </label>
                                <input type="checkbox" class="js-switch"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="pending_tbl">
                            <table class="table" id="active_child_tbl">
                                <thead class=" text-primary">
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Date Sign Up</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($child_active as $info)
                                    <tr>
                                        <td>{{$info->reseller->username}}</td>
                                        <td>{{$info->reseller_name}}</td>
                                        <td>{{$info->created_at}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_suspend_disable_modal({{$info->status->id}})" class="btn btn-danger btn-link" title="Suspend/Disable">
                                                <div class="custom-icon-remove-person">
                                                    <i class="material-icons">person</i>
                                                    <i class="material-icons">clear</i>
                                                </div>
                                              <div class="ripple-container"></div></button>
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->username_id}})" class="btn btn-info btn-link" title="View">
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
                            <table class="table" id="inactive_child_tbl">
                                <thead class=" text-primary">
                                    <th>Username</th>
                                    <th>Child's Name</th>
                                    <th>Date Sign Up</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($child_inactive as $info)
                                    <tr>
                                        <td>{{$info->reseller->username}}</td>
                                        <td>{{$info->reseller_name}}</td>
                                        <td>{{$info->created_at}}</td>
                                        <td class="td-actions text-center">
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->username_id}})" class="btn btn-info btn-link">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button onclick="open_modal_add()" class="btn btn-primary reseller-color-bg pull-right">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}

{{-- Add Modal --}}
<div class="modal fade modal-custom" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Secondary Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="add_childvendor_frm">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Name</label>
                            <input class="form-control" type="text" name="vendor_name" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Email Address</label>
                            <input class="form-control" type="email" name="email_address" aria-required="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Primary Contact Number</label>
                            <input class="form-control" type="number" name="mobile_number" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Contact Person</label>
                            <input class="form-control" type="text" name="contact_person" aria-required="true">
                        </div>
                    </div>
                    
                    
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Address</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="bmd-label-floating">Username</label>
                            <input class="form-control" type="text" name="username" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="bmd-label-floating">Password</label>
                            <input class="form-control" type="password" name="password" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="bmd-label-floating">Confirm Password</label>
                            <input class="form-control" type="password" name="password_confirmation" aria-required="true">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Security Question's Answer</label>
                            <textarea name="security_answer" class="form-control {{$errors->has('security_answer')? "required-error": "" }}" rows="3" >{{ old('security_answer') }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div>
                </button>
                <button type="submit" form="add_childvendor_frm" class="btn btn-success btn-link">Save
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-custom" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
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
                        {{-- <div class="form-group">
                            <label>Landline Number</label>
                            <input class="form-control" type="text" name="landline_no_v" aria-required="true" readonly>
                        </div> --}}
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

{{-- Suspend/Disable Modal --}}
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
                </form>
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
        </div>
    </div>
</div>

@endsection 

@section('script')
<script>
    $('#child_management').addClass('active');
    $('#child_management').find('.collapser').addClass('collapsed');
    $('#child_management').find('.collapse').addClass('show');
    $('#child_management').find('#child_management_childlist_subitem').addClass('active');
</script>

<script>
    $(document).ready(function() {
        $('#active_child_tbl').DataTable();
        $('#inactive_child_tbl').DataTable();

        var switchery = new Switchery(document.querySelector('.js-switch'), { size: 'small' });

        switch_tbl();

        // saving_modal("show");
    });

    function open_modal_add() {
        $('#modal_add').modal('show');
    }
    
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

    function show_suspend_disable_modal(id) {
        $('[name="verify_reseller_id"]').val(id);
        $('#modal_suspend_disable').modal('show');
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
            url:'/vendor/secondary-vendor-management/secondary-vendor-list/view',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                $('[name="username_v"]').val(data.username);
                $('[name="name_v"]').val(data.reseller_name);
                $('[name="address_v"]').text(data.address);
                $('[name="email_address_v"]').val(data.email_address);
                $('[name="contact_person_v"]').val(data.contact_person);
                $('[name="mobile_no_v"]').val(data.mobile_number);
            }
        });
    }

    $('#add_childvendor_frm').on('submit', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#modal_add').modal('hide');
        saving_modal("show");

        $.ajax({
            type: "PUT",
            url: '/vendor/child-management/child-list/add-child',
            data: $(this).serialize(),   
            dataType: 'json',   
            success: function(result){
                
                var error = result.errors;
                $('.form-control').removeClass("required-error");
                if (result.status == "error") {
                    
                    var error_message = "";
                    $.each( error, function( key, value ) {
                        $('[name="'+key+'"]').addClass("required-error");
                        error_message += value+"<br/>";
                    });
                    modalAlert({"type":"error","message":error_message, 
                        "action": function(){ 
                            saving_modal("hide"); 
                            $('#modal_add').modal('show');
                            
                        }
                    });
                }else if(result.status == "sucess") {
                    setTimeout(() => {
                        saving_modal("hide");
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                location.reload(); 
                            }
                        });
                     }, 2000);
                }
                
            }
        });
    });

    $("#reseller_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_suspend_disable').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type:'PUT',
            url:'/vendor/child-management/change_status',
            data:{
                verify_reseller_id: $('[name="verify_reseller_id"]').val(),
                status: $('[name="status"]').val(),
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

        $('.modal-custom-alert').on('hide.bs.modal', function (e) {
            location.reload();     
        })
    });
</script>


@endsection 