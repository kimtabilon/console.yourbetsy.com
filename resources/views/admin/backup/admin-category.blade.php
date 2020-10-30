@extends('layout.admin.admin-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/admin/category">Category Management<div class="ripple-container"></div></a>
@endsection

@section('content')

<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-admin">
                        <h4 class="card-title ">Category</h4>
                        {{-- <p class="card-category"> Resellers pending for verification</p> --}}
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Inactive Category: </label>
                                <input type="checkbox" class="js-switch switch-category"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="active_tbl">
                            <table class="table" id="active_category_tbl">
                                <thead class=" text-primary">
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($active as $info)
                                    <tr>
                                        <td>{{ $info->category_name }}</td>
                                        <td>{{ Status_type_category($info->status)}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_update_modal({{$info->id}},'{{ $info->category_name }}')" class="btn btn-success btn-link" title="Update">
                                                <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div></button>
                                            {{-- @php 
                                                if ($info->sub_category) {
                                                    $disable_deactivation = "";
                                                }else{
                                                    $disable_deactivation = "disabled";
                                                }
                                            @endphp --}}
                                            <button type="button" rel="tooltip" onclick="show_remove_modal({{$info->id}})" class="btn btn-danger btn-link" title="Deactivate">
                                                <i class="material-icons">clear</i>
                                            <div class="ripple-container"></div></button>
                                            {{-- <button type="button" rel="tooltip" onclick="show_delete_modal({{$info->id}})" class="btn btn-danger btn-link">
                                                <i class="material-icons">delete</i>
                                            <div class="ripple-container"></div></button> --}}
                                          <button type="button" rel="tooltip" onclick="show_view_modal({{$info->id}},'{{ $info->category_name }}')" class="btn btn-info btn-link" title="View">
                                                <i class="material-icons">local_hospital</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="inactive_tbl" style="display: none">
                            <table class="table" id="inactive_category_tbl">
                                <thead class=" text-primary">
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($inactive as $info)
                                    <tr>
                                        <td>{{ $info->category_name }}</td>
                                        <td>{{ Status_type_category($info->status)}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_reactivate_modal({{$info->id}})" class="btn btn-success btn-link" title="Reactivate">
                                                <i class="material-icons">autorenew</i>
                                            <div class="ripple-container"></div></button>
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
{{-- Approved Modal --}}
<div class="modal fade" id="add_edit_verify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="add_edit_title"></span> Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_edit_frm" method="POST">
                    <input type="hidden" name="category_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input class="form-control uppercase-first-input" type="text" name="category_name" aria-required="true">
                            </div>
                        </div>
                    </div>
                
            </div>
                
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="add_edit_frm" class="btn btn-success btn-link">Save
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- Deactivate Modal --}}
<div class="modal fade" id="modal_deactivate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="deactivate_msg"></span> Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span class="deactivate_msg"></span> this category?</p>
            </div>
            <form id="deact_frm" method="POST">
                <input type="hidden" name="deact_category_id">
                <input type="hidden" name="status">
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="deact_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- Delete Category Modal --}}
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete permanently this category?, Warning deleting main category will delete it's sub category also.</p>
            </div>
            <form id="delete_frm" method="POST">
                <input type="hidden" name="del_category_id">
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="delete_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- Delete Sub Category Modal --}}
<div class="modal fade" id="modal_delete_sub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Sub Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete permanently this sub category?</p>
            </div>
            <form id="delete_frm_sub" method="POST">
                <input type="hidden" name="del_sub_category_id">
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="delete_frm_sub" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>


{{-- View Modal --}}
<div class="modal fade modal-custom" id="modal_add_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add <span id="title_category" style="color: #fa5f09"></span> Sub Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Show Inactive Sub-Category: </label>
                        <input type="checkbox" class="js-switch switch-sub-category"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="subcategory_active_tbl_c">
                            <table class="table" id="subcategory_active_tbl">
                                <thead class="text-primary">
                                    <th>Sub-Category Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" id="subcategory_inactive_tbl_c" style="display: none">
                            <table class="table" id="subcategory_inactive_tbl">
                                <thead class="text-primary">
                                    <th>Sub-Category Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <button onclick="open_modal_add_subcat()" id="btn_addedit_subcat" class="btn btn-primary reseller-color-bg pull-right">
                    Add
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-custom" id="add_edit_subcat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="addedit_title_subcat"></span> Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addedit_subcat_frm" method="POST">
                    <input type="hidden" name="sub_category_id">
                    <input type="hidden" name="category_id_SB">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input class="form-control uppercase-first-input" type="text" name="SB_category_name" readonly>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Sub Category Name</label>
                                <input class="form-control uppercase-first-input" type="text" name="sub_category_name" aria-required="true">
                            </div>
                        </div>
                    </div>
                
            </div>
                
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="addedit_subcat_frm" class="btn btn-success btn-link">Save
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- Deactivate Sub Category Modal --}}
<div class="modal fade" id="modal_deactivate_subcat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="deactivate_msg_subcat"></span> Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span class="deactivate_msg_subcat"></span> this Sub category?</p>
            </div>
            <form id="deact_frm_subcat" method="POST">
                <input type="hidden" name="deact_sub_category_id">
                <input type="hidden" name="status_subcat">
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="deact_frm_subcat" class="btn btn-success btn-link">Yes
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
    $('#category_item').addClass('active');
</script>

<script>
    var save_method = "";
    var save_method_subcat = "";
    var view_modal_id,view_categoryname;
    $(document).ready(function() {
        $('#active_category_tbl').DataTable();
        $('#inactive_category_tbl').DataTable();
        $('#subcategory_active_tbl').DataTable();
        $('#subcategory_inactive_tbl').DataTable();

        var switchery = new Switchery(document.querySelector('.switch-category'), { size: 'small' });
        var switchery_2 = new Switchery(document.querySelector('.switch-sub-category'), { size: 'small' });

        switch_tbl();
        switch_tbl_subcat();

        // saving_modal("show");

        $("#add_edit_subcat").on('hidden.bs.modal', function (e) {
            show_view_modal(view_modal_id,view_categoryname);
        });
        $("#modal_deactivate_subcat").on('hidden.bs.modal', function (e) {
            show_view_modal(view_modal_id,view_categoryname);
        });
        $("#modal_delete_sub").on('hidden.bs.modal', function (e) {
            show_view_modal(view_modal_id,view_categoryname);
        });
    });
    
    function switch_tbl() {
        $('.switch-category').on('change', function() {
            if ($(this).is(":checked")) {
                $('#active_tbl').hide();
                $('#inactive_tbl').fadeIn();
            } else {
                $('#active_tbl').fadeIn();
                $('#inactive_tbl').hide();
            }
        });
    }

    function switch_tbl_subcat() {
        $('.switch-sub-category').on('change', function() {
            if ($(this).is(":checked")) {
                $('#subcategory_active_tbl_c').hide();
                $('#subcategory_inactive_tbl_c').fadeIn();
            } else {
                $('#subcategory_active_tbl_c').fadeIn();
                $('#subcategory_inactive_tbl_c').hide();
            }
        });
    }

    function open_modal_add() {
        $('#add_edit_frm')[0].reset();
        save_method = "Add";
        $('.add_edit_title').html(save_method);
        $('#add_edit_verify').modal('show');
    }

    function open_modal_add_subcat(id,category_name) {
        $("#modal_add_category").modal("hide");
        $('#addedit_subcat_frm')[0].reset();
        save_method_subcat = "Add";
        $('.addedit_title_subcat').html(save_method_subcat);
        $('#add_edit_subcat').modal('show');
        $('[name="SB_category_name"]').val(category_name);
        $('[name="category_id_SB"]').val(id);
    }

    function show_edit_modal_subcat(id,sub_category_name,cat_id,category_name) {
        $("#modal_add_category").modal("hide");
        $('#addedit_subcat_frm')[0].reset();
        save_method_subcat = "Edit";
        $('.addedit_title_subcat').html(save_method_subcat);
        $('#add_edit_subcat').modal('show');

        $('[name="SB_category_name"]').val(category_name);
        $('[name="category_id_SB"]').val(cat_id);
        $('[name="sub_category_id"]').val(id);
        $('[name="sub_category_name"]').val(sub_category_name);
    }

    function show_update_modal(id,category_name) {
        $('#add_edit_frm')[0].reset();
        save_method = "Edit";
        $('.add_edit_title').html(save_method);
        $('[name="category_id"]').val(id);
        $('[name="category_name"]').val(category_name);
        $('#add_edit_verify').modal('show');
    }

    function show_remove_modal(id) {
        $('.deactivate_msg').html("Deactivate");
        $('[name="deact_category_id"]').val(id);
        $('[name="status"]').val(1);
        $('#modal_deactivate').modal('show');
    }

    function show_delete_modal(id) {
        $('[name="del_category_id"]').val(id);
        $('#modal_delete').modal('show');
    }
    function show_delete_modal_sub(id) {
        $("#modal_add_category").modal("hide");
        $('[name="del_sub_category_id"]').val(id);
        $('#modal_delete_sub').modal('show');
    }

    function show_reactivate_modal(id) {
        $('.deactivate_msg').html("Activate");
        $('[name="deact_category_id"]').val(id);
        $('[name="status"]').val(0);
        $('#modal_deactivate').modal('show');
    }

    function show_remove_modal_subcat(id) {
        $("#modal_add_category").modal("hide");
        $('.deactivate_msg_subcat').html("Deactivate");
        $('[name="deact_sub_category_id"]').val(id);
        $('[name="status_subcat"]').val(1);
        $('#modal_deactivate_subcat').modal('show');
    }

    function show_reactivate_modal_subcat(id) {
        $("#modal_add_category").modal("hide");
        $('.deactivate_msg_subcat').html("Activate");
        $('[name="deact_sub_category_id"]').val(id);
        $('[name="status_subcat"]').val(0);
        $('#modal_deactivate_subcat').modal('show');
    }

    function show_view_modal(id,category_name) {
        $('#modal_add_category').modal('show');
        view_modal_id = id;
        view_categoryname = category_name;
        $('#title_category').html(category_name);
        $('#btn_addedit_subcat').attr("onclick","open_modal_add_subcat("+id+",\'"+category_name+"\')");


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/admin/category/sub-category-list',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                var subcat_tbl_active = $('#subcategory_active_tbl').DataTable({
                                    "destroy": true,
                                    "pageLength": 5
                                });

                                subcat_tbl_active.clear().draw();
                $.each( data.active_subcat, function( key, value ) {
                    /* var action = "\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_edit_modal_subcat('"+value.id+"','"+value.sub_category_name+"','"+value.category_id+"','"+value.category.category_name+"')\" class=\"btn btn-info btn-link btn_table_modal\">\
                            <i class=\"material-icons\">edit</i>\
                        <div class=\"ripple-container\"></div>\
                        </button>\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_remove_modal_subcat('"+value.id+"')\" class=\"btn btn-danger btn-link btn_table_modal\">\
                            <i class=\"material-icons\">clear</i>\
                        <div class=\"ripple-container\"></div></button>\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_delete_modal_sub('"+value.id+"')\" class=\"btn btn-danger btn-link btn_table_modal\">\
                            <i class=\"material-icons\">delete</i>\
                        <div class=\"ripple-container\"></div></button>"; */
                    var action = "\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_edit_modal_subcat('"+value.id+"','"+value.sub_category_name+"','"+value.category_id+"','"+value.category.category_name+"')\" class=\"btn btn-info btn-link btn_table_modal\" title=\"Update\">\
                            <i class=\"material-icons\">edit</i>\
                        <div class=\"ripple-container\"></div>\
                        </button>\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_remove_modal_subcat('"+value.id+"')\" class=\"btn btn-danger btn-link btn_table_modal\" title=\"Deactivate\">\
                            <i class=\"material-icons\">clear</i>\
                        <div class=\"ripple-container\"></div></button>";
                        subcat_tbl_active.row.add([
                            value.sub_category_name,
                            Status_type_category(value.status),
                            action,
                        ]).draw();
                });

                var subcat_tbl_inactive = $('#subcategory_inactive_tbl').DataTable({
                                    "destroy": true,
                                    "pageLength": 5
                                });

                                subcat_tbl_inactive.clear().draw();
                $.each( data.inactive_subcat, function( key, value ) {
                    var action = "\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_reactivate_modal_subcat('"+value.id+"')\" class=\"btn btn-success btn-link\" title=\"Reactivate\">\
                            <i class=\"material-icons\">autorenew</i>\
                        <div class=\"ripple-container\"></div></button>";
                        subcat_tbl_inactive.row.add([
                            value.sub_category_name,
                            Status_type_category(value.status),
                            action,
                        ]).draw();
                });
            }
        });
    }

    $("#add_edit_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#add_edit_verify').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var url;
        if (save_method == "Add") {
            url = "/admin/category/add";
        }else {
            url = "/admin/category/update";
        }


        $.ajax({
            type: "POST",
            url: url,
            data: new FormData($('#add_edit_frm')[0]),   
            dataType: 'json',
            processData: false,
			contentType: false,
            success:function(result) {
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
                            $('#add_edit_verify').modal('show');
                            saving_modal("hide"); 
                        }
                    });
                }else if(result.status == "success") {
                    setTimeout(function() {
                        saving_modal("hide");
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                location.reload(); 
                            }
                        });
                     }, 3000);
                }
                
            }
        });
        
    });


    $("#deact_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_deactivate').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type: "POST",
            url: "/admin/category/change_status",
            data: new FormData($('#deact_frm')[0]),   
            dataType: 'json',
            processData: false,
			contentType: false,
            success:function(result) {
                saving_modal("hide");
                if (result.status == "success") {
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

    $("#delete_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_delete').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type: "POST",
            url: "/admin/category/category-delete",
            data: new FormData($('#delete_frm')[0]),   
            dataType: 'json',
            processData: false,
			contentType: false,
            success:function(result) {
                saving_modal("hide");
                if (result.status == "success") {
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

    $("#delete_frm_sub" ).submit(function( e ) {
        e.preventDefault();

        // $('#modal_delete_sub').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type: "POST",
            url: "/admin/category/sub-category-delete",
            data: new FormData($('#delete_frm_sub')[0]),   
            dataType: 'json',
            processData: false,
			contentType: false,
            success:function(result) {
                saving_modal("hide");
                if (result.status == "success") {
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            $('#modal_delete_sub').modal('hide');
                        }
                    });
                    
                }else{
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            $('#modal_delete_sub').modal('hide');
                        }
                    });
                }
                
            }
        });
        
    });

    $("#addedit_subcat_frm" ).submit(function( e ) {
        e.preventDefault();

        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var url;
        if (save_method_subcat == "Add") {
            url = "/admin/category/add-sub";
        }else {
            url = "/admin/category/update-sub";
        }


        $.ajax({
            type: "POST",
            url: url,
            data: new FormData($('#addedit_subcat_frm')[0]),   
            dataType: 'json',
            processData: false,
			contentType: false,
            success:function(result) {
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
                        }
                    });
                }else if(result.status == "success") {
                    setTimeout(function() {
                        saving_modal("hide");
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                $('#add_edit_subcat').modal('hide');
                            }
                        });
                     }, 3000);
                }
                
            }
        });
        
    });

    $("#deact_frm_subcat" ).submit(function( e ) {
        e.preventDefault();

        
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type: "POST",
            url: "/admin/category/sub-change_status",
            data: new FormData($('#deact_frm_subcat')[0]),   
            dataType: 'json',
            processData: false,
			contentType: false,
            success:function(result) {
                
                if (result.status == "success") {
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            saving_modal("hide");
                            $('#modal_deactivate_subcat').modal('hide');
                        }
                    });
                    
                }else{
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            saving_modal("hide");
                            $('#modal_deactivate_subcat').modal('hide');
                        }
                    });
                }
                
            }
        });
        
    });
</script>


@endsection 