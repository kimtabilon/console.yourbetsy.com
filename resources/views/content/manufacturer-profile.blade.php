<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
<style>

    .label_info_bold {
        font-weight: bold;
    }

    .seller_name_label {
        margin-right: 10px;
    }

    img {
        width: 100px
    }
</style>
<div class="row">
    <div class="col-md-12 form-group">
        <img src="{{getProfilePhoto($data['id'])}}" alt="">
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <label for="" class="seller_name_label label_info_bold">Seller Name: </label><span>{{$data['reseller_name']}}</span>
    </div>
    {{-- <dt class="col-md-2 col-sm-2">Seller Name:</dt>
    <dd class="col-sm-9">{{$data['reseller_name']}}</dd> --}}
    {{-- <div class="col-md-6 form-group">
        <label for="">Manufacturer Name:</label> --}}
        {{-- <label for="">{{$data['reseller_name']}}</label> --}}
    {{-- </div> --}}
</div>
<hr>
<div class="row">
    <div class="col-md-12 form-group">
        <label for="" class="label_info_bold">About Us</label>
        <div class="">{!! $data['about_us']['about_us'] !!}</div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12 form-group">
        <label for="" class="label_info_bold">Shipping Policy</label>
        <div class="">{!! $data['shipping_policy']['shipping_policy'] !!}</div>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-12 form-group">
        <label for="" class="label_info_bold">Return Policy</label>
        <div class="">{!! $data['return_policy']['return_policy'] !!}</div>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-12 form-group">
        <label for="" class="label_info_bold">Payment Information</label>
        <div class="">{!! $data['payment_information']['payment_information'] !!}</div>
        
    </div>
</div>
