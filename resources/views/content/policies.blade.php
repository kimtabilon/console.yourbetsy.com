@if ($data->profile)
    <div>
        <h2>Seller name: {{$data->profile->reseller_name}}</h2>
    </div>
    <hr>
    <br>
@endif
@if ($data->shipping_policy)
    <div>
        <h2>Shipping Policy</h2>
        <p>{!!$data->shipping_policy->shipping_policy!!}</p>
    </div>
    <hr>
    <br>
@endif
@if ($data->payment_informations)
    <div>
        <h2>Payment Information</h2>
        <p>{!!$data->payment_informations->payment_information!!}</p>
    </div>
    <hr>
    <br>
@endif
@if ($data->return_policy)
    <div>
        <h2>Return Policy</h2>
        <p>{!!$data->return_policy->return_policy!!}</p>
    </div>
    <hr>
    <br>
@endif