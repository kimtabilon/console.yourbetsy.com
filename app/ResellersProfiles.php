<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
class ResellersProfiles extends Model
{
    public function emailaddress() {
        return $this->hasOne(ResellersEmailAddresses::class, 'username_id','username_id');
    }

    public function address() {
        return $this->hasOne(ResellersAddresses::class, 'username_id','username_id');
    }
    
    public function mobile_no() {
        return $this->hasOne(ResellersMobileNumbers::class, 'username_id','username_id');
    }

    public function reseller() {
        return $this->hasOne(Resellers::class, 'id','username_id');
    }

    public function status() {
        return $this->hasOne(ResellersStatuses::class, 'username_id','username_id');
    }

    public function about_us() {
        return $this->hasOne(ResellersAboutUses::class, 'username_id','username_id');
    }

    public function shipping_policy() {
        return $this->hasOne(ResellersShippingPolicies::class, 'username_id','username_id');
    }

    public function return_policy() {
        return $this->hasOne(ResellersReturnPolicies::class, 'username_id','username_id');
    }

    public function payment_information() {
        return $this->hasOne(ResellersPaymentInformations::class, 'username_id','username_id');
    }
}
