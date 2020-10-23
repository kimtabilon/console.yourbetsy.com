@extends('layout.reseller.reseller-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/vendor/update/primary-information">Update Primary Information<div class="ripple-container"></div></a>
@endsection

@section('content')
<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">Update Primary Information</h4>
                        {{-- <p class="card-category"> Resellers pending for verification</p> --}}
                    </div>
                    <div class="card-body">
                        {{-- {{dd($data->secondary_no->id)}} --}}
                        <form method="POST" id="update_profile_frm" enctype="multipart/form-data">
                        <input type="hidden" name="username_id" value="{{$data->id}}">
                        <input type="hidden" name="contact_person_id" value="{{$data->profile_details->id}}">
                        <input type="hidden" name="email_address_id" value="{{$data->email_address->id}}">
                        <input type="hidden" name="primary_contact_number_id" value="{{$data->mobile_no->id}}">
                        <input type="hidden" name="secondary_contact_number_id" value="{{isset($data->secondary_no)? $data->secondary_no->id : ""}}">
                        <input type="hidden" name="address_id" value="{{$data->address->id}}">
                        <input type="hidden" name="edited_content" value="">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group profile-field">
                                    <label>Profile</label>
                                    <div class="img-upload profile-upload">
                                        <input type="file" id="profile_upload" onchange="readURL(this);" name="profile_upload" accept="image/*" />
                                        <div class="upload-text">
                                            upload
                                        </div>
                                        <img id="profile_photo" src="{{$data->profile_img}}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group profile-field">
                                    <label>Store Banner Page</label>
                                    <div class="img-upload banner-upload">
                                        <input type="file" id="banner_upload" onchange="readURL(this);" name="banner_upload" accept="image/*" />
                                        <div class="upload-text">
                                            upload
                                        </div>
                                        <img id="banner_photo" src="{{$data->banner_img}}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <div class="input-group custom-input-group">
                                        <input type="text" class="form-control" name="email_address" value="{{ $data->email_address->email_address}}" disabled>
                                        <span class="input-group-addon email_address_edit" onclick="enable_field('email_address')">
                                            <i class="material-icons">edit</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <div class="input-group custom-input-group">
                                        <textarea name="address" class="form-control" rows="3" disabled>{{ $data->address->address}}</textarea>
                                        <span class="input-group-addon address_edit" onclick="enable_field('address')">
                                            <i class="material-icons">edit</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <div class="input-group custom-input-group">
                                        <input type="text" class="form-control" name="contact_person" value="{{ $data->profile_details->contact_person}}" disabled>
                                        <span class="input-group-addon contact_person_edit" onclick="enable_field('contact_person')">
                                            <i class="material-icons">edit</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Primary Contact Number</label>
                                    <div class="input-group custom-input-group">
                                        <input type="number" class="form-control" name="primary_contact_number" value="{{ $data->mobile_no->mobile_number}}" disabled>
                                        <span class="input-group-addon primary_contact_number_edit" onclick="enable_field('primary_contact_number')">
                                            <i class="material-icons">edit</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Secondary Contact Number</label>
                                    <div class="input-group custom-input-group">
                                        <input type="number" class="form-control" name="secondary_contact_number" value="{{ isset($data->secondary_no)? $data->secondary_no->secondary_number : ""}}" disabled>
                                        <span class="input-group-addon secondary_contact_number_edit" onclick="enable_field('secondary_contact_number')">
                                            <i class="material-icons">edit</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Social Media Link</label>
                                    <div class="input-group custom-input-group">
                                        <textarea name="social_media_url" class="form-control" rows="3" disabled>{{ $data->profile_details->socail_media_url}}</textarea>
                                        <span class="input-group-addon social_media_url_edit" onclick="enable_field('social_media_url')">
                                            <i class="material-icons">edit</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="btn_submit" form="update_profile_frm" class="btn btn-success reseller-color-bg pull-right" disabled>Update Profile</button>
                        <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('script')
<script>
    $('#profile_item').addClass('active');
    $('#profile_item').find('.collapser').addClass('collapsed');
    $('#profile_item').find('.collapse').addClass('show');
    $('#profile_item').find('#reseller_updateprofile_subitem').addClass('active');
</script>

<script>
    var enabled_field_count = 0;
    $(document).ready(function() {

        $(document).on('click', '.upload-text', function() {
            $(this).prev('input').click();
            /* $('#profile_upload').click(); */
        })
    });
    

    function enable_field(field_name) {
        $('[name="'+field_name+'"]').prop('disabled',false).focus();
        $('[name="'+field_name+'"]').prop('required',true);
        $('.'+field_name+'_edit').hide();

        if (field_name != "social_media_url") {
            enabled_field_count++;  
        }
        $('[name="edited_content"]').val(enabled_field_count);
        
        if ($('#btn_submit').prop('disabled') == true) {
            $('#btn_submit').prop('disabled',false);
        }
    }

    $('#update_profile_frm').on('submit', function(e) {
        var formData = new FormData($('#update_profile_frm')[0]);

        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            // type: "PUT",
            type: "POST",
            url: '/vendor/profile/update_profile',
            // data: $(this).serialize(),   
            data: formData,
            dataType: 'json',   
            processData: false,
			contentType: false,
            success: function(result){

                if (result.status == "not allowed") {
                    modalAlert({"type":"error","message":"Updates to your profile are still under review. Please expect an email confirmation from the Betsy team soon.", 
                        "action": function(){ 
                            location.reload(); 
                        }
                    });
                }else {
                    var error = result.errors;
                    $('.form-control').removeClass("required-error");
                    if (result.status == "error") {
                        var error_message = "";
                        $.each( error, function( key, value ) {
                            $('[name="'+key+'"]').addClass("required-error");
                            error_message += value+"<br/>";
                        });
                        console.log('ERRRRRR');
                        modalAlert({"type":"error","message":error_message, 
                            "action": function(){ 
                                location.reload(); 
                            }
                        });
                    }else if(result.status == "sucess") {
                        console.log('DITO');
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                location.reload(); 
                            }
                        });
                    }
                }
            }
        });
    }); 
    
    function readURL(input) {
        var input_id = $(input).attr('id');
        console.log(input_id)
        var img_id = '';
        var default_img = '';
        if (input_id == "profile_upload") {
            img_id = 'profile_photo';
            default_img = '/storage/avatars/default.png';
        }else{
            img_id = 'banner_photo';
            default_img = '/storage/seller-banner/banner-default.png';
        }
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#'+img_id).attr('src', e.target.result);
            }
            $('#btn_submit').prop('disabled',false);
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }else {
            $('#'+img_id).attr('src', default_img);
            
            if (enabled_field_count <= 0) {
                $('#btn_submit').prop('disabled',true);
            }
        }
    }
</script>


@endsection 