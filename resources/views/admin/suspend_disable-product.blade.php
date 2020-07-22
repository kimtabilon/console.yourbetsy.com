@extends('layout.admin.admin-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/admin/product-management/verify">Suspend/Disable Products<div class="ripple-container"></div></a>
@endsection

@section('content')

<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-admin">
                        <h4 class="card-title ">Active Products List</h4>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Suspend/Disabled Products: </label>
                                <input type="checkbox" class="js-switch"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="pending_tbl">
                            <table class="table" id="active_products_tbl">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendor Name</th>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($active_products as $info)
                                        <tr>
                                            <td>{{$info->id}}</td>
                                            <td>{{$info->profile->reseller_name}}</td>
                                            <td>{{$info->sku}}</td>
                                            <td>{{$info->product_name}}</td>
                                            <td>{{$info->price}}</td>
                                            <td>{{$info->quantity}}</td>
                                            <td>{{Status_type($info->status)}}</td>
                                            <td class="td-actions text-center">
                                                <button type="button" rel="tooltip" onclick="show_sus_dis_modal({{$info->id}})" class="btn btn-danger btn-link" title="Suspend/Disable">
                                                    <i class="material-icons">clear</i>
                                                <div class="ripple-container"></div></button>
                                                <button type="button" rel="tooltip" onclick="show_viewpending_modal({{$info->id}})" class="btn btn-info btn-link" title="View">
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
                            <table class="table" id="declined_products_tbl" style="width: 100%;">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendor Name</th>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sus_dis_products as $info)
                                        <tr>
                                            <td>{{$info->id}}</td>
                                            <td>{{$info->profile->reseller_name}}</td>
                                            <td>{{$info->sku}}</td>
                                            <td>{{$info->product_name}}</td>
                                            <td>{{$info->price}}</td>
                                            <td>{{$info->quantity}}</td>
                                            <td>{{Status_type($info->status)}}</td>
                                            <td class="td-actions text-center">
                                                <button type="button" rel="tooltip" onclick="show_verify_modal({{$info->id}})" class="btn btn-success btn-link" title="Reactivate">
                                                    <i class="material-icons">assignment_turned_in</i>
                                                <div class="ripple-container"></div></button>
                                                <button type="button" rel="tooltip" onclick="show_viewpending_modal({{$info->id}})" class="btn btn-info btn-link" title="View">
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
{{-- Suspend Disable Modal --}}
<div class="modal fade" id="modal_sus_dis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="sus_dis_msg"></span> Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span class="sus_dis_msg"></span> this product?</p>
                    <br>
                    <form id="sus_dis_product_frm" method="POST">
                        {{ method_field('PATCH')}}
                    {{ csrf_field() }}
                    <input type="hidden" name="product_id">
                    <input type="hidden" name="action">
                    <div class="row" id="dropdown">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="sus_dis_product_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </div>
    </div>
</div>

{{-- Approved Modal --}}
<div class="modal fade" id="modal_verify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="verify_decline_msg"></span> Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span class="verify_decline_msg"></span> this product?</p>
            </div>
                <form id="verify_product_frm" method="POST">
                    {{ method_field('PATCH')}}
                    {{ csrf_field() }}
                    <input type="hidden" name="verify_product_id">
                    <input type="hidden" name="status_verify" value="0">
                    <input type="hidden" name="action_verify">
                </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="verify_product_frm" class="btn btn-success btn-link">Yes
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </div>
    </div>
</div>

{{-- View Modal --}}
<div class="modal fade modal-custom" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Item Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills nav-pills-warning" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link nav-info active show" data-toggle="tab" href="#link1_view" role="tablist">
                            Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-images" data-toggle="tab" href="#link2_view" role="tablist">
                            Image/s
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#link3_view" role="tablist">
                            History
                        </a>
                    </li>
                </ul>
                <div class="tab-content tab-space">
                    <div class="tab-pane active show" id="link1_view">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category</label>
                                    <input class="form-control" type="text" name="category_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sub Category</label>
                                    <input class="form-control" type="text" name="sub_category_v" aria-required="true" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKU</label>
                                    <input class="form-control uppercase-all-input" type="text" name="sku_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input class="form-control uppercase-first-input" type="text" name="product_name_v" aria-required="true" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description_v" class="form-control" rows="3" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea name="short_description_v" class="form-control" rows="3" readonly></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input class="form-control" type="number" name="price_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Special Price (Optional)</label>
                                    <input class="form-control" type="number" name="special_price_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input class="form-control" type="number" name="quantity_v" aria-required="true" readonly>
                                </div>
                            </div>
        
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Handling Time</label>
                                    <input class="form-control" type="text" name="handling_time_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date Start (Optional)</label>
                                    <input class="form-control" type="text" name="date_start_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date End (Optional)</label>
                                    <input class="form-control" type="text" name="date_end_v" aria-required="true" readonly>
                                </div>
                            </div>
                        </div>
        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Made In (Optional)</label>
                                    <input class="form-control" type="text" name="made_in_v" aria-required="true" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="link2_view">
                        {{-- <input class="file-upload-input" type="file" multiple name="product_images[]" onchange="readURL(this);" accept="image/*" /> --}}
                        <div class="file-upload" id="view_product_img" style="cursor: default !important;">
                            {{-- <span class="file-upload-direction">Click here or Drag and drop images here</span> --}}
                            
                        </div>
                    </div>
                    <div class="tab-pane" id="link3_view">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="item_history_tbl">
                                        <thead class="text-primary">
                                            <th>Modified by</th>
                                            <th>Action</th>
                                            <th class="text-center">Description</th>
                                            <th>Status</th>
                                            <th>Date Modified</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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


<div class="modal fade" id="modal_view_changes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="decline_msg"></span> Description</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                
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
    $('#product_management_item').addClass('active');
    $('#product_management_item').find('.collapser').addClass('collapsed');
    $('#product_management_item').find('.collapse').addClass('show');
    $('#product_management_item').find('#product_suspenddisable_subitem').addClass('active');
</script>

<script>
    $(document).ready(function() {
        $('#active_products_tbl').DataTable({
            "order": [[ 1, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });
        $('#declined_products_tbl').DataTable({
            "order": [[ 1, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });

        var switchery = new Switchery(document.querySelector('.js-switch'), { size: 'small' });

        switch_tbl();

        // saving_modal("show");


        $('[name="status"]').on('change', function() {
            var val = $(this).val();
            if (val == 3) {
                $('[name="action"]').val("Approved");
            }else {
                $('[name="action"]').val("Disable");
            }
        });
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

    function show_sus_dis_modal(id) {
        
        $('[name="product_id"]').val(id);
        $('[name="status"]').val(3);
        $('.sus_dis_msg').html('Suspend/Disable');
        $('#modal_sus_dis').modal('show');
    }

    function show_verify_modal(id) {
        $('[name="verify_product_id"]').val(id);
        $('[name="status_verify"]').val(0);
        $('[name="action_verify"]').val("Reactivate");
        $('.verify_decline_msg').html('Reactivate');
        $('#modal_verify').modal('show');
    }

    function show_viewpending_modal(id) {
        $("#modal_view").modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/admin/product-management/verify/view',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                data = data.data;
                $('[name="sku_v"]').val(data.sku);
                $('[name="category_v"]').val(data.category);
                $('[name="sub_category_v"]').val(data.items_sub_categories.sub_category_name);
                $('[name="product_name_v"]').val(data.product_name);
                $('[name="description_v"]').text(data.product_desc);
                $('[name="short_description_v"]').text(data.product_shortdesc);
                $('[name="price_v"]').val(data.price);
                $('[name="special_price_v"]').val(data.special_price);
                $('[name="quantity_v"]').val(data.quantity);
                $('[name="handling_time_v"]').val(data.handling_time);
                $('[name="date_start_v"]').val(data.date_start);
                $('[name="date_end_v"]').val(data.date_end);
                $('[name="made_in_v"]').val(data.made_in);
                
                $('#view_product_img').empty();
                $.each( data.img, function( key, value ) {
                    $('#view_product_img').append("<div class='img-container'><img src='"+value+"'/></div>");
                });

                var item_history_tbl = $('#item_history_tbl').DataTable({
                    "destroy": true,
                    "order": [[ 4, "desc" ]],
                    /* "paging":   true,
                    "ordering": true,
                    "info":     true,
                    "searching": false, */
                });

                item_history_tbl.clear().draw();
                $.each( data.history, function( key, value ) {
                    var desc_td;
                    if (value.new_val == "" && value.old_val == "") {
                        desc_td = "Empty";
                    }else{
                        desc_td = "\
                        <button type=\"button\" rel=\"tooltip\" onclick=\"show_changes_modal('"+value.new_val+"<br>"+value.old_val+"')\" class=\"btn btn-info btn-link btn_table_modal\">\
                            <i class=\"material-icons\">list</i>\
                        <div class=\"ripple-container\"></div>\
                        </button>";
                    }
                    item_history_tbl.row.add( [
                            value.modifier,
                            value.action,
                            desc_td,
                            value.status,
                            value.date,
                        ] )
                        .draw();
                });
                
            }
        });
    }

    function show_changes_modal(val) {
        $("#modal_view_changes").modal("show")
                                .find(".modal-body").empty().append(val);
    }

    $("#sus_dis_product_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_sus_dis').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type:'PUT',
            url:'/admin/product-management/verify/change_status',
            data:{
                verify_product_id: $('[name="product_id"]').val(),
                status: $('[name="status"]').val(),
                action: $('[name="action"]').val()
            },
            dataType: 'json',
            success:function(data) {
                // saving_modal("hide");
                if (data.status == "successful") {
                    saving_modal("hide");
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            location.reload();
                            saving_modal("hide"); 
                        }
                    });
                    
                }else{
                    saving_modal("hide");
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            location.reload(); 
                            saving_modal("hide");
                        }
                    });
                }
                
            }
        });
        
    });

    $("#verify_product_frm" ).submit(function( e ) {
        e.preventDefault();

        $('#modal_verify').modal('hide');
        saving_modal("show");

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            type:'PUT',
            url:'/admin/product-management/verify/change_status',
            data:{
                verify_product_id: $('[name="verify_product_id"]').val(),
                status: $('[name="status_verify"]').val(),
                action: $('[name="action_verify"]').val()
            },
            dataType: 'json',
            success:function(data) {
                // saving_modal("hide");
                if (data.status == "successful") {
                    saving_modal("hide");
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            location.reload();
                            saving_modal("hide"); 
                        }
                    });
                    
                }else{
                    saving_modal("hide");
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            location.reload(); 
                            saving_modal("hide");
                        }
                    });
                }
                
            }
        });
        
    });
</script>


@endsection 