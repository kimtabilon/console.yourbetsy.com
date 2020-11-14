@extends('layout.reseller.reseller-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/vendor/order">Order<div class="ripple-container"></div></a>
@endsection

@section('content')
<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">Order List</h4>
                        {{-- <p class="card-category"> Resellers pending for verification</p> --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="order_tbl">
                                <thead class=" text-primary">
                                    <th>Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Customer Email Address</th>
                                    <th>Date Ordered</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                
                                @foreach ($data as $items)
                                {{-- {{dd($items)}} --}}
                                @php
                                    $btn_behavior = '';
                                    if ($items['status'] == "complete") {
                                        $btn_behavior = "disabled";
                                    }
                                @endphp
                                    <tr>
                                        <td>{{$items['order_id']}}</td>
                                        <td>{{$items['customer_firstname'].' '.$items['customer_lastname']}}</td>
                                        <td>{{$items['customer_email']}}</td>
                                        <td>{{$items['date_ordered']}}</td>
                                        <td>{{$items['status']}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" onclick="open_modal_view('{{$items['order_id']}}')" class="btn btn-info btn-link" title="View">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                            <button type="button" onclick="ship_modal('{{$items['order_id']}}')" class="btn btn-success btn-link" title="Ship" {{$btn_behavior}}>
                                                <i class="material-icons">local_shipping</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                            <button type="button" onclick="cancel_modal('{{$items['order_id']}}')" class="btn btn-danger btn-link" title="Cancel Order Item" {{$btn_behavior}}>
                                                <i class="material-icons">cancel</i>
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
{{-- SHIP --}}
<div class="modal fade modal-custom" id="modal_ship" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="ship_msg"></span> Orders</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <form id="ship_frm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action_type">
                    <input type="hidden" name="order_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tracking Number</label>
                                <input class="form-control" type="number" name="tracking_number" aria-required="true" required>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table" id="order_items_tbl_ship">
                                    <thead class=" text-primary">
                                        <th></th>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
            </div>
            
            {{-- <form id="ship_frm" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tracking Number</label>
                                <input class="form-control" type="number" name="tracking_number" aria-required="true" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
            </form> --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="ship_frm" class="btn btn-success btn-link">Submit
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- SHIP --}}
<div class="modal fade modal-custom" id="modal_cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cancel Orders</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <form id="cancel_frm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="order_id_cancel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table" id="order_items_tbl_cancel">
                                    <thead class=" text-primary">
                                        <th></th>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="cancel_frm" class="btn btn-success btn-link">Submit
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-custom" id="modal_order_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body" style="max-height: 700px;overflow: auto;">

                <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                            <div class="admin__page-section-title">
                              <div class="title">Order Information</div>
                            </div>

                        <div class="form-group">
                            <div class="title">Order Date: <label id="order_date"></label></div>
                            <div  class="title" >Order Status: <label id="order_status"></label></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                            <div class="admin__page-section-title">
                              <div class="title">Account Information</div>
                            </div>
                        <div class="form-group">
                            <div class="title">Customer Name: <label id="customer_name"></label></div>
                            <div class="title">Customer Email: <label id="customer_email"></label></div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="admin__page-section-title">
                        <div class="title">Address Information</div>
                        </div>
                        <div class="form-group">
                            <div class="title">Billing Address: </div>
                            <label id="billing_address"></label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="admin__page-section-title">
                          <div class="title">Shipping Information</div>
                        </div>
                        <div class="form-group">
                            <div class="title">Shipping Address: </div>
                            <label id="shipping_address"></label>
                        </div>
                    </div>
                </div>

                <hr>


                <div class="row">
                    <div class="col-md-6">
                        <div class="admin__page-section-title">
                        <div class="title">Payment Information</div>
                        </div>
                        <div class="form-group">
                            <label id="additional_information"></label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="admin__page-section-title">
                          <div class="title">Shipping & Handling Information</div>
                        </div>
                        <div class="form-group">
                            <label id="shipping_information"></label>
                        </div>
                    </div>
                </div>

                <hr>


                <div class="row">
                    <div class="col-md-12">
                        <div class="admin__page-section-title">
                            <div class="title">Items Ordered</div>
                        </div>

                        <div class="form-group">
                            <div class="table-responsive">
                                <table class="table" id="items_tbl">
                                    <thead class=" text-primary">
                                        <th>Product</th>
                                        <th>Item Status</th>
                                        <th>Original Price</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Tax Amount</th>
                                        <th>Tax Percent</th>
                                        <th>Discount Amount</th>
                                        <th>Row Total</th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="admin__page-section-title">
                        <span class="title">Order Totals</span>
                        </div>

                        <div class="form-group">
                            <div class="title">Subtotal: <label id="total_subtotal"></label></div>
                            <div class="title">Shipping & Handling: <label id="total_shipping"></label></div>
                            <hr>
                            <div class="title">Grand Total: <label id="total_grand" class="title"></label></div>
                            <div class="title">Total Paid: <label id="total_paid" class="title"></label></div>
                            <div class="title">Total Refund: <label id="total_refund" class="title"></label></div>
                            <div class="title">Total Due: <label id="total_due" class="title"></label></div>
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
            </div>
        </div>
    </div>
</div>

@endsection 

@section('script')
<script>
    $('#profile_item').find('#order').addClass('active');
</script>

<script>
    $(document).ready(function() {
        
        $('#order_tbl').DataTable({
            "destroy": true,
            "order": [[ 0, "desc" ]],
            /* "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }] */
        });
    });

    function ship_modal(order_id) {
        $('#modal_ship').modal('show');
        $('.ship_msg').html('Ship');
        $('[name="order_id"]').val(order_id);
        $('[name="action_type"]').val('ship');
        order_items(order_id, 'ship');
    }

    function cancel_modal(order_id) {
        $('#modal_cancel').modal('show');
        $('[name="order_id_cancel"]').val(order_id);
        order_items(order_id, 'cancel');
    }

    function order_items(order_id,action) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/vendor/order-items',
            data: {
                order_id: order_id
            },
            dataType: 'json',
            success: function(result){
                var subcat_tbl_active = $('#order_items_tbl_'+action).DataTable({
                                    "destroy": true,
                                    "pageLength": 5
                                });

                                subcat_tbl_active.clear().draw();
                $.each( result, function( key, value ) {
                    var qty = 0;
                    
                    if (value.status == 'canceled') {
                        qty = value.qty_canceled;
                    }else if(value.status == 'processing') {
                        qty = value.qty_invoiced;
                    }else if(value.status == 'shipped') {
                        qty = value.qty_shipped;
                    }else if(value.status == 'pending') {
                        qty = value.qty_ordered;
                    }else if(value.status == 'refunded') {
                        qty = value.qty_refunded;
                    }
                    
                    var disable_chck = '';
                    if (value.status != 'processing' && action == "ship") {
                        disable_chck = 'disabled';
                    }else if(value.status != 'processing' && action == "cancel") {
                        disable_chck = 'disabled';
                    }
                    subcat_tbl_active.row.add([
                        '<input type="checkbox" name="skus_'+action+'[]" value="'+value.sku+'" '+disable_chck+'>',
                        value.sku,
                        value.name,
                        qty,
                        value.price,
                        value.status,
                    ]).draw();
                });
            }
        });
    }

    $('#ship_frm').on('submit', function(e) {
        var formData = new FormData($('#ship_frm')[0]);
        e.preventDefault();

        var skus = [];
        $(':checkbox:checked').each(function(i){
            skus[i] = $(this).val();
        });
        console.log(skus);
        // console.log($('#summernote').summernote('code'));
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        formData.append("skus",JSON.stringify(skus));
        var url = '';
        if ($('[name="action_type"]').val() == 'ship') {
            url = '/vendor/order-ship';
        }
        if (skus.length == 0) {
            modalAlert({"type":"error","message":"Please select items"});
        }else{
            $('#modal_ship').modal('hide');
            saving_modal("show");
            $.ajax({
                type: "POST",
                url: url,
                data: formData,   
                dataType: 'json',
                processData: false,
                contentType: false,   
                success: function(result){
                    console.log(result);
                    if (result.status == "error") {
                        modalAlert({"type":"error","message":"", 
                            "action": function(){ 
                                location.reload(); 
                                saving_modal("hide"); 
                            }
                        });
                    }else if(result.status == "success") {
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                location.reload(); 
                                saving_modal("hide"); 
                            }
                        });
                    }
                    
                }
            });
        }
        
    }); 

    $('#cancel_frm').on('submit', function(e) {
        var formData = new FormData($('#cancel_frm')[0]);
        e.preventDefault();

        var skus = [];
        $(':checkbox:checked').each(function(i){
            skus[i] = $(this).val();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        formData.append("skus",JSON.stringify(skus));

        if (skus.length == 0) {
            modalAlert({"type":"error","message":"Please select items"});
        }else{
            $('#modal_cancel').modal('hide');
            saving_modal("show");
            
            $.ajax({
                type: "POST",
                url: '/vendor/order-refund',
                data: formData,   
                dataType: 'json',
                processData: false,
                contentType: false,   
                success: function(result){

                    if (result.status == "error") {
                        modalAlert({"type":"error","message":"", 
                            "action": function(){ 
                                location.reload(); 
                                saving_modal("hide"); 
                            }
                        });
                    }else if(result.status == "success") {
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                location.reload(); 
                                saving_modal("hide"); 
                            }
                        });
                    }
                    
                }
            });
        }
    }); 

    function open_modal_view(order_id) {
    $('#modal_order_view').modal('show');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: '/vendor/order-details',
        data: {
            order_id: order_id
        }, 
        dataType: 'json',
        success: function(result){

            $.each( result.order_details, function( key, value ) {
                
                $('#order_date').text(value.created_at);
                $('#order_status').text(result.stat);

                $('#customer_name').text(value.customer_firstname+" "+value.customer_lastname);
                $('#customer_email').text(value.customer_email);

                var billing = JSON.parse(value.billing_address);
                var company_billing = typeof billing.company != 'undefined'? "<br>"+billing.company : '';

                $('#billing_address').text(billing.firstname+" "+billing.lastname);
                $('#billing_address').append(company_billing); 
                $.each( billing.street, function( key_street, value_street ) {
                    $('#billing_address_street').append("<br>"+value_street); 
                });
                $('#billing_address').append("<br>"+billing.city+", "+billing.postcode); 
                $('#billing_address').append("<br>Philippines"); 
                $('#billing_address').append("<br>T: "+billing.telephone); 

                var shipping = JSON.parse(value.shipping_address);
                var company_shipping = typeof shipping.company != 'undefined'? "<br>"+shipping.company : '';
                $('#shipping_address').text(shipping.firstname+" "+shipping.lastname);
                $('#shipping_address').append(company_shipping); 
                $.each( shipping.street, function( key_street_ship, value_street_ship ) {
                    $('#shipping_address_street').append("<br>"+value_street_ship); 
                });
                $('#shipping_address').append("<br>"+shipping.city+", "+shipping.postcode); 
                $('#shipping_address').append("<br>Philippines"); 
                $('#shipping_address').append("<br>T: "+shipping.telephone); 

                    var payment_information = JSON.parse(value.payment_information);
                    $('#additional_information').text("");
                    $.each(payment_information.additional_information, function( key_info, value_info ) {
                    $('#additional_information').append(value_info+"<br>"); 
                    });
                    $('#additional_information').append(payment_information.method); 
                    $('#shipping_information').text(value.shipping_description+" "+formatMoney(value.shipping_incl_tax)); 

                    var total_subtotal = value.grand_total - value.shipping_incl_tax;

                    $('#total_subtotal').text(formatMoney(total_subtotal)); 
                    $('#total_shipping').text(formatMoney(value.shipping_incl_tax)); 
                    $('#total_grand').text(formatMoney(value.grand_total)); 
                    $('#total_paid').text(formatMoney(value.total_paid)); 
                    $('#total_refund').text(formatMoney(value.refunded)); 
                    $('#total_due').text(formatMoney(value.total_due)); 

            });

            var items_tbl_active = $('#items_tbl').DataTable({
                                "destroy": true,
                                "pageLength": 5
                            });

            items_tbl_active.clear().draw();
                            

            $.each( result.order_items, function( key, value ) {
                var qty = "";
                if (value.qty_ordered != '0') {
                    qty += 'ordered '+value.qty_ordered;
                }
                if(value.qty_invoiced != '0') {
                    qty += '<br> invoiced '+value.qty_ordered;
                }
                if(value.qty_canceled != '0' ) {
                    qty += '<br> cancelled '+value.qty_ordered;
                }
                if(value.qty_refunded != '0') {
                    qty += '<br> refunded '+value.qty_ordered;
                }
                if(value.qty_shipped != '0') {
                    qty += '<br> shipped '+value.qty_ordered;
                }

                items_tbl_active.row.add([
                    value.name,
                    value.status,
                    formatMoney(value.orginal_price),
                    formatMoney(value.price),
                    qty,
                    formatMoney(value.price),
                    formatMoney(value.tax_amount),
                    value.tax_percent+"%",
                    formatMoney(value.discount_amount),
                    formatMoney(value.row_total),
                ]).draw();
            });
        }
    });


}

function formatMoney(number, decPlaces, decSep, thouSep) {
      decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
      decSep = typeof decSep === "undefined" ? "." : decSep;
      thouSep = typeof thouSep === "undefined" ? "," : thouSep;
      var sign = number < 0 ? "-" : "₱";
      var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
      var j = (j = i.length) > 3 ? j % 3 : 0;

      return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}
    
</script>


@endsection 