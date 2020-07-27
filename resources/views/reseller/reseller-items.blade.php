@extends('layout.reseller.reseller-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/vendor/items">Items<div class="ripple-container"></div></a>
@endsection

@section('content')

<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">Item List</h4>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-12">
                                <label>Show Inactive Items: </label>
                                <input type="checkbox" class="js-switch" id="pending_declined_switch"/>
                            </div> --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Filter by Status :</label>
                                    <select class="form-control" name="filter_status">
                                        <option value="Active">Active</option>
                                        <option value="Pending" selected>Pending</option>
                                        <option value="Declined">Declined</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Disabled">Disabled</option>
                                        {{-- <option value="Declined - Re Submit">Declined - Re Submit</option>
                                        <option value="Pending">Pending - Resubmit</option> --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" id="pending_tbl">
                            {{-- <table class="table" id="pending_item_tbl"> --}}
                            <table class="table" id="item_tbl">
                                <thead class=" text-primary">
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    {{-- <th>Short Description</th> --}}
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Handling Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($pending_item as $info)
                                    <tr>
                                        <td>{{$info->id}}</td>
                                        <td>{{$info->sku}}</td>
                                        <td>{{$info->product_name}}</td>
                                        {{-- <td>{{$info->product_shortdesc}}</td> --}}
                                        <td>{{$info->price}}</td>
                                        <td>{{$info->quantity}}</td>
                                        <td>{{$info->handling_time}}</td>
                                        <td>{{$info->status}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_viewpending_modal({{$info->id}})" class="btn btn-info btn-link" title="View">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>

                                            @if ($info->status == "Declined - Re Submit" || $info->status == "Pending")
                                                <button type="button" rel="tooltip" onclick="show_edit_modal({{$info->id}})" class="btn btn-info btn-link btn-custom-edit" {{$info->status == "Declined - Re Submit"? "" : "disabled"}} title="Update">
                                                    <i class="material-icons orange-icon-color">edit</i>
                                                <div class="ripple-container"></div>
                                                </button>
                                            @endif
                                            @if ($info->status == "Active")
                                                <button type="button" rel="tooltip" onclick="show_edit_active_modal({{$info->id}})" class="btn btn-info btn-link" title="Update">
                                                    <i class="material-icons orange-icon-color">edit</i>
                                                <div class="ripple-container"></div>
                                                </button>
                                            @endif
                                            
                                            
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="table-responsive" id="declined_tbl" style="display: none">
                            <table class="table" id="declined_item_tbl" style="width: 100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Handling Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($declined_item as $info)
                                    <tr>
                                        <td>{{$info->id}}</td>
                                        <td>{{$info->sku}}</td>
                                        <td>{{$info->product_name}}</td>
                                        <td>{{$info->price}}</td>
                                        <td>{{$info->quantity}}</td>
                                        <td>{{$info->handling_time}}</td>
                                        <td>{{$info->status}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_viewpending_modal({{$info->id}})" class="btn btn-info btn-link">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                        <button onclick="open_modal_add()" class="btn btn-primary reseller-color-bg pull-right">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">Active Item List</h4>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Show Inactive Items: </label>
                                <input type="checkbox" class="js-switch" id="active_inactive_switch"/>
                            </div>
                        </div>
                        <div class="table-responsive" id="active_tbl">
                            <table class="table" id="active_item_tbl">
                                <thead class=" text-primary">
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Handling Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($active_item as $info)
                                    <tr>
                                        <td>{{$info->id}}</td>
                                        <td>{{$info->sku}}</td>
                                        <td>{{$info->product_name}}</td>
                                        <td>{{$info->price}}</td>
                                        <td>{{$info->quantity}}</td>
                                        <td>{{$info->handling_time}}</td>
                                        <td>{{Status_type($info->status)}}</td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" onclick="show_viewpending_modal({{$info->id}})" class="btn btn-info btn-link">
                                                <i class="material-icons">list</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        <button type="button" rel="tooltip" onclick="show_edit_active_modal({{$info->id}},'{{$info->price}}','{{$info->special_price}}','{{$info->quantity}}')" class="btn btn-info btn-link">
                                                <i class="material-icons orange-icon-color">edit</i>
                                            <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="inactive_tbl" style="display: none">
                            <table class="table" id="inactive_items_tbl" style="width: 100%">
                                <thead class=" text-primary">
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    <th>Short Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Handling Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($inactive_item as $info)
                                        <tr>
                                            <td>{{$info->id}}</td>
                                            <td>{{$info->sku}}</td>
                                            <td>{{$info->product_name}}</td>
                                            <td>{{$info->product_shortdesc}}</td>
                                            <td>{{$info->price}}</td>
                                            <td>{{$info->quantity}}</td>
                                            <td>{{$info->handling_time}}</td>
                                            <td>{{Status_type($info->status)}}</td>
                                            <td class="td-actions text-center">
                                                <button type="button" rel="tooltip" onclick="show_viewpending_modal({{$info->id}})" class="btn btn-info btn-link">
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
        </div> --}}
    </div>
</div>

{{-- MODALS --}}

{{-- Add Modal --}}
<div class="modal fade modal-custom" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills nav-pills-warning" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link nav-info active show" data-toggle="tab" href="#link1" role="tablist">
                            Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-images" data-toggle="tab" href="#link2" role="tablist">
                            Image/s
                        </a>
                    </li>
                </ul>
                <form method="POST" id="add_item_frm" enctype="multipart/form-data">
                    <input type="hidden" name="item_id">
                  <div class="tab-content tab-space">
                    
                    <div class="tab-pane active show" id="link1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Category</label>
                                    <select class="form-control" name="category" >
                                        <option value="" selected>Select Category</option>
                                        @foreach ($category as $item)
                                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Sub Category</label>
                                    <select class="form-control" name="sub_category" >
                                        <option value="" selected>Select Sub Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">SKU/Product ID</label>
                                    <input class="form-control uppercase-all-input" type="text" name="sku" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Product Name</label>
                                    <input class="form-control uppercase-first-input" type="text" name="product_name" aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Description</label>
                                    <textarea name="description" class="form-control" rows="3" ></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="3" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Price</label>
                                    <input class="form-control" type="number" name="price" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Special Price (Optional)</label>
                                    <input class="form-control" type="number" name="special_price" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Quantity On Hand</label>
                                    <input class="form-control" type="number" name="quantity" aria-required="true">
                                </div>
                            </div>
        
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Handling Time (in Days)</label>
                                    <input class="form-control" type="number" name="handling_time" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Special Price Date Start (Optional)</label>
                                    <input class="form-control" type="text" name="date_start" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Special Price Date End (Optional)</label>
                                    <input class="form-control" type="text" name="date_end" aria-required="true">
                                </div>
                            </div>
                        </div>
        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Made In (Optional)</label>
                                    <select class="form-control" name="made_in" >
                                        <option value="" selected>Select Country</option>
                                        @foreach (Country_list() as $item)
                                            <option value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="link2">
                        {{-- <span id="click_upload" class="file-upload-instruction">Click inside the box to upload image</span> --}}
                        <div class="row file-upload-container">
                            <div class="col-md-6" style="margin-bottom: 1rem;">
                                <div class="custom-file">
                                    {{-- <input type="file" class="custom-file-input" id="customFile"> --}}
                                    <input class="file-upload-input custom-file-input" type="file" id="customFile" multiple name="product_images[]" onchange="readURL(this);" accept="image/*" />
                                    <label class="custom-file-label" for="customFile">Browse</label>
                                    
                                </div>
                                <small>Please note: When you click the button browse, this will always replace current image. You always need to choose all images desired to upload.</small>
                            </div>
                        </div>
                        
                        <div class="file-upload" id="file_upload_container_addedit">
                            {{-- <span class="file-upload-instruction">Click here or Drag and drop images here</span> --}}
                            
                        </div>
                    </div>
                  </div>
                

                

                
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Close
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div>
                </button>
                <button type="submit" form="add_item_frm" class="btn btn-success btn-link">Save
                    <div class="ripple-container"></div>
                    <div class="ripple-container"></div></button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade modal-custom" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-modal-xl">
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
                    {{-- <li class="nav-item">
                        <a class="nav-link nav-history" data-toggle="tab" href="#link3_view" role="tablist">
                            Approval History
                        </a>
                    </li> --}}
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
                                    <label>SKU/Product ID</label>
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
                                    <label>Special Price Date Start (Optional)</label>
                                    <input class="form-control" type="text" name="date_start_v" aria-required="true" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Special Price Date End (Optional)</label>
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
                    {{-- <div class="tab-pane" id="link3_view">
                        <div id="history">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="label-w-status">Status: <span>Declined</span> Date: <span>03/25/2020 13:22</span></label>
                                </div>
                            </div>
                            <div class="row col-md-6 custom-divider"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <textarea class="form-control" rows="3" readonly> Inappropriate Product Name</textarea>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="label-w-status">Status: <span>Declined</span> Date: <span>03/25/2020 13:22</span></label>
                                </div>
                            </div>
                            <div class="row col-md-6 custom-divider"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <textarea class="form-control" rows="3" readonly> Inappropriate Product Name</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button onclick="open_modal_add()" class="btn btn-primary reseller-color-bg pull-left">
                            Resubmit
                        <div class="ripple-container"></div></button>

                    </div> --}}
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

{{-- Approved Modal --}}
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="update_item_frm">
                    <input type="hidden" name="item_id_update">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Price</label>
                                <input class="form-control" type="number" name="price_update" aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Special Price (Optional)</label>
                                <input class="form-control" type="number" name="special_price_update" aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input class="form-control" type="number" name="quantity_update" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Special Price Date Start (Optional)</label>
                                <input class="form-control bg-c-unset" type="text" name="date_start_update" aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Special Price Date End (Optional)</label>
                                <input class="form-control bg-c-unset" type="text" name="date_end_update" aria-required="true">
                            </div>
                        </div>
                    </div>
                
            </div>
                
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">
                    Cancel
                    <div class="ripple-container"><div class="ripple-decorator ripple-on ripple-out"></div></div></button>
                <button type="submit" form="update_item_frm" class="btn btn-success btn-link">Yes
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
    $('#items').addClass('active');
</script>

<script>
    var action = "";
    var deleted_img_arr = [];
    var tbl_item =  $('#item_tbl').DataTable();
    $(document).ready(function() {
        /* MAIN TABLE */
        tbl_item = $('#item_tbl').DataTable({
            "destroy": true,
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });

        tbl_item.columns( 6 )
            .search( "Pending" )
            .draw();

        $('[name="filter_status"]').on("change", function() {
            tbl_item.columns( 6 )
            .search( this.value )
            .draw();
        });
        /* MAIN TABLE */

        $('#pending_item_tbl').DataTable({
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });

        $('#declined_item_tbl').DataTable({
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });

        $('#active_item_tbl').DataTable({
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });
        $('#inactive_items_tbl').DataTable({
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false
            }]
        });

        /* var switchery = new Switchery(document.querySelector('#active_inactive_switch'), { size: 'small' });
        var switchery_2 = new Switchery(document.querySelector('#pending_declined_switch'), { size: 'small' }); */

        /* switch_tbl();
        switch_tbl_PD(); */
        
        /* $('[name="date_start"]').datepicker();
        $('[name="date_end"]').datepicker(); */
        /* $('[name="date_start_update"]').datepicker();
        $('[name="date_end_update"]').datepicker(); */

        date_range_format('date_start','date_end');
        date_range_format('date_start_update','date_end_update');
        

    });

    $(document).on('change','[name="category"]', function() {
        subcategory_Bycategory($(this).val());
    });

    function open_modal_add() {
        action = "add";
        reset_field();
        tab_select_first();
        /* $("#add_item_frm").find("label").addClass("bmd-label-floating");
        $("#add_item_frm").find(".form-group").addClass("bmd-form-group"); */
        // $(".file-upload").html('<span class="file-upload-direction">Click here to upload image</span>');
       /*  $(".file-upload").prop("onclick","trigger_upload();"); */
        $('#modal_add').modal('show');
    }

    /* function trigger_upload() {
        $('.custom-file-label').trigger("click");
    } */

    function show_edit_modal(id) {
        deleted_img_arr = [];
        action = "update";
        reset_field();
        tab_select_first();

        $("#add_item_frm").find(".form-group").addClass("is-filled");
        /* $(".file-upload").prop("onclick",""); */

        $('#modal_add').modal('show');
        $('[name="item_id"]').val(id);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/vendor/items/view',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                data = data.data;
                $('[name="sku"]').val(data.sku).prop("readonly",true);
                $('[name="product_name"]').val(data.product_name);
                $('[name="description"]').text(data.product_desc);
                $('[name="short_description"]').text(data.product_shortdesc);
                $('[name="price"]').val(data.price);
                $('[name="special_price"]').val(data.special_price);
                $('[name="quantity"]').val(data.quantity);
                $('[name="handling_time"]').val(data.handling_time);
                $('[name="date_start"]').val(data.date_start);
                $('[name="date_end"]').val(data.date_end);
                $('[name="made_in"]').val(data.made_in);
                
                $('.file-upload').empty();
                var img_count = 1;
                $.each( data.img, function( key, value ) {
                    $('.file-upload').append("\
                        <div class=\"img-container\" id=\"img_"+img_count+"\">\
                            <img src=\""+value.url+"\"/>\
                            <i class=\"material-icons\" onclick=\"remove_img("+img_count+",'"+value.filename+"')\">clear</i>\
                        <div>\
                    ");
                    img_count++;
                });
            }
        });
    }

    function remove_img(count,filename) {
        console.log(filename);
        $("#img_"+count).remove();
        deleted_img_arr.push(filename);
    }

    function reset_field() {
        $('#add_item_frm')[0].reset();
        $("#add_item_frm").find("input,textarea").removeClass("required-error");
        $("#add_item_frm").find("textarea").text("");
        /* $(".file-upload").find("img").remove();
        $(".file-upload").find("span").show(); */
        $(".file-upload").empty();
    }
    
    function switch_tbl() {
        $('#active_inactive_switch').on('change', function() {
            if ($(this).is(":checked")) {
                $('#active_tbl').hide();
                $('#inactive_tbl').fadeIn();
            } else {
                $('#active_tbl').fadeIn();
                $('#inactive_tbl').hide();
            }
        });
    }

    function switch_tbl_PD() {
        $('#pending_declined_switch').on('change', function() {
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

    function show_edit_active_modal(id) {
        $('[name="item_id_update"]').val(id);
        $('#modal_update').modal('show');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/vendor/items/view',
            data:{id: id},
            dataType: 'json',
            success:function(data) {
                data = data.data;
                var date_s = "";
                var date_e = "";
                if (data.date_start) {
                    // date_s = moment(data.date_start).format('MM/DD/YYYY HH:mm');
                    date_s = moment(data.date_start).format('MM/DD/YYYY');
                }
                if (data.date_end) {
                    // date_e = moment(data.date_end).format('MM/DD/YYYY HH:mm');
                    date_e = moment(data.date_end).format('MM/DD/YYYY');
                }
                $('[name="price_update"]').val(data.price);
                $('[name="special_price_update"]').val(data.special_price);
                $('[name="quantity_update"]').val(data.quantity);
                
                $('[name="date_start_update"]').val(date_s);
                $('[name="date_end_update"]').val(date_e);
            }
        });
    }

    function tab_select_first() {
        $(".nav-pills").find(".active").removeClass("active show");
        $(".nav-pills .nav-item:nth-child(1)").find(".nav-link").addClass("active show");
        $(".tab-content").find(".active").removeClass("active show");
        $(".tab-content .tab-pane:nth-child(1)").addClass("active show");
    }

    function show_viewpending_modal(id) {
        $('#modal_view').modal('show');
        tab_select_first();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:'/vendor/items/view',
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
                /* $.each( data.img, function( key, value ) {
                    $('#view_product_img').append("<img src='"+value+"'/>");
                }); */
                $.each( data.img, function( key, value ) {
                    $('#view_product_img').append("\
                        <div class=\"img-container\" >\
                            <img src=\""+value.url+"\"/>\
                        <div>\
                    ");
                });

                /* if (data.status != 1) {
                    $(".nav-history").show();
                    $('#history').empty();
                    $.each( data.reasons, function( key, value ) {
                        $('#history').append("<div class=\"row\">\
                                <div class=\"col-md-6\">\
                                    <label class=\"label-w-status\">Status: <span>\""+Status_type(value.status)+"\"</span> Date: <span>\""+value.created_at+"\"</span></label>\
                                </div>\
                            </div>\
                            <div class=\"row col-md-6 custom-divider\"></div>\
                            <div class=\"row\">\
                                <div class=\"col-md-6\">\
                                    <div class=\"form-group\">\
                                        <label>Reason</label>\
                                        <textarea class=\"form-control\" rows=\"3\" readonly>\""+value.reason+"\"</textarea>\
                                    </div>\
                                </div>\
                            </div>");
                    });
                }else{
                    $(".nav-history").hide();
                } */
                
            }
        });
    }

    $('#add_item_frm').on('submit', function(e) {
        console.log('test');
        var formData = new FormData($('#add_item_frm')[0]);
        /* $(".nav-info,#link1").removeClass("active show");
        $(".nav-images,#link2").addClass("active show"); */
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var url;
        if (action == "add") {
            url = '/vendor/items/add';
        }else{
            var img_count = $("#file_upload_container_addedit").find("img").length;
            formData.append("remove_img_num",JSON.stringify(deleted_img_arr));
            formData.append("img_count",img_count);
            url = '/vendor/items/update';
        }

        $('#modal_add').modal('hide');
        saving_modal("show");

        $.ajax({
            type: "POST",
            url: url,
            data: formData,   
            dataType: 'json',
            processData: false,
			contentType: false,
            success: function(result){
                
                var error = result.errors;
                $('.form-control').removeClass("required-error");
                if (result.status == "error") {
                    
                    var error_message = "";
                    $.each( error, function( key, value ) {
                        $('[name="'+key+'"]').addClass("required-error");
                        if (value == "The selected price is invalid.") {
                            error_message += "Item Price needs to be greater than 0<br/>";
                        }else if (value == "The selected quantity is invalid.") {
                            error_message += "Item Quantity needs to be greater than 0<br/>";
                        }else if (value == "The selected handling time is invalid.") {
                            error_message += "Item Handling Time needs to be greater than 0<br/>";
                        }else if (value == "The selected special price is invalid.") {
                            error_message += "Item Special Price needs to be greater than 0<br/>";
                        }else{
                            error_message += value+"<br/>";
                        }
                        
                    });
                    modalAlert({"type":"error","message":error_message, 
                        "action": function(){ 
                            $('#modal_add').modal('show');
                            saving_modal("hide"); 
                        }
                    });
                }else if(result.status == "sucess") {
                    setTimeout(function () {
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
    // }
    });


    $('#update_item_frm').on('submit', function(e) {
        var formData = new FormData($('#update_item_frm')[0]);
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#modal_update').modal('hide');
        saving_modal("show");

        $.ajax({
            type: "POST",
            url: '/vendor/items/update_pq',
            data: formData,   
            dataType: 'json',
            processData: false,
			contentType: false,
            success: function(result){
                
                var error = result.errors;
                $('.form-control').removeClass("required-error");
                if (result.status == "error") {
                    
                    var error_message = "";
                    $.each( error, function( key, value ) {
                        var val = value;
                        val = val[0].replace("update", "");
                        $('[name="'+key+'"]').addClass("required-error");
                        if (value == "The selected price update is invalid.") {
                            error_message += "Item Price update needs to be greater than 0<br/>";
                        }else if (value == "The selected quantity update is invalid.") {
                            error_message += "Item Quantity update needs to be greater than 0<br/>";
                        }else if (value == "The selected special price update is invalid.") {
                            error_message += "Item Special Price update needs to be greater than 0<br/>";
                        }else{
                            error_message += value+"<br/>";
                        }
                        /* error_message += val+"<br/>"; */
                    });
                    modalAlert({"type":"error","message":error_message, 
                        "action": function(){ 
                            $('#modal_update').modal('show');
                            saving_modal("hide"); 
                        }
                    });
                }else if(result.status == "sucess") {
                    setTimeout(function () {
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

    function readURL(input) {
        if (action == "add") {
            $('.file-upload').empty();
        }
        if (input.files && input.files[0]) {

            
            /* 
            var reader = new FileReader();
            reader.onload = function(e) {
                var i = 0;
                $.each(e, function( index, value ) {
                    console.log("i = "+i+" value = "+value.target.result);
                    i++;
                });
            $('.image-upload-wrap').hide();

            $('.file-upload-image').attr('src', e.);
            $('.file-upload-content').show();

            $('.image-title').html(input.files[0].name);
            }; 
            reader.readAsDataURL(input.files[0]);
            
            */

            if (input.files) {
            var filesAmount = input.files.length;
            var img_count = 1;

            
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {

                    // $(".file-upload-direction").hide();
                    

                    if (action == "update") {
                        $('.file-upload').append("\
                            <div class=\"img-container\" id=\"item_img_"+img_count+"\">\
                                <img src=\""+event.target.result+"\"/>\
                                <i class=\"material-icons\" onclick=\"remove_img("+img_count+")\">clear</i>\
                            <div>\
                        ");
                    }else{
                        $('.file-upload').append("<div class='img-container'><img src='"+event.target.result+"'/><div>");
                    }
                    
                    img_count++;
                    // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

            

        } else {
            removeUpload();
        }
    }

function removeUpload() {
  $('.file-upload-input').replaceWith($('.file-upload-input').clone());
  $('.file-upload-content').hide();
  $('.image-upload-wrap').show();
}
$('.image-upload-wrap').bind('dragover', function () {
		$('.image-upload-wrap').addClass('image-dropping');
	});
	$('.image-upload-wrap').bind('dragleave', function () {
		$('.image-upload-wrap').removeClass('image-dropping');
});


function subcategory_Bycategory(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url:'/vendor/items/sub-category',
        data:{id: id},
        dataType: 'json',
        success:function(data) {
            $('[name="sub_category"]').empty().append('<option value="" selected>Select Sub Category</option>');
            $.each( data, function( key, value ) {
                $('[name="sub_category"]').append('<option value="'+value.id+'">'+value.sub_category_name+'</option>');
            });
        }
    });

}

function date_range_format(start,end) {
    var startDateTextBox = $('[name="'+start+'"]');
    var endDateTextBox = $('[name="'+end+'"]');

    startDateTextBox.datepicker({ 
        timeFormat: 'HH:mm',
        onClose: function(dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            }
            else {
                endDateTextBox.val(dateText);
            }
            $('[name="'+end+'"]').parent(".form-group").addClass("is-filled");
        },
        onSelect: function (selectedDateTime){
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate') );
        }
    });
    endDateTextBox.datepicker({ 
        timeFormat: 'HH:mm',
        onClose: function(dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            }
            else {
                startDateTextBox.val(dateText);
            }

            $('[name="'+start+'"]').parent(".form-group").addClass("is-filled");
        },
        onSelect: function (selectedDateTime){
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate') );
        }
    });
}
</script>
@endsection 