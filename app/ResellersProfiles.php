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

    public static function resellers_infos($name) {
                return DB::table('resellers')
                ->select(
                    'resellers.id',
                    'resellers_profiles.reseller_name',
                    'resellers_email_addresses.email_address',
                    'resellers_addresses.address',
                    'resellers_mobile_numbers.mobile_number',
                    'resellers_about_uses.about_us',
                    'resellers_shipping_policies.shipping_policy',
                    'resellers_return_policies.return_policy',
                    'resellers_payment_informations.payment_information'
                )
                /* ->select('resellers.username','resellers.created_at as signup_date',
                        'resellers_profiles.*','resellers_statuses.*',
                        'resellers_statuses.id as reseller_status_id',
                        'resellers_email_addresses.email_address',
                        'resellers_mobile_numbers.mobile_number',
                        'resellers_addresses.address',
                        'resellers_secondary_numbers.secondary_number') */
                /* ->join('resellers_statuses', 'resellers.id', '=', 'resellers_statuses.username_id') */
                ->join('resellers_profiles', 'resellers.id', '=', 'resellers_profiles.username_id')
                ->join('resellers_email_addresses', 'resellers.id', '=', 'resellers_email_addresses.username_id')
                ->join('resellers_mobile_numbers', 'resellers.id', '=', 'resellers_mobile_numbers.username_id')
                ->join('resellers_addresses', 'resellers.id', '=', 'resellers_addresses.username_id')
                ->join('resellers_about_uses', 'resellers.id', '=', 'resellers_about_uses.username_id')
                ->join('resellers_shipping_policies', 'resellers.id', '=', 'resellers_shipping_policies.username_id')
                ->join('resellers_return_policies', 'resellers.id', '=', 'resellers_return_policies.username_id')
                ->join('resellers_payment_informations', 'resellers.id', '=', 'resellers_payment_informations.username_id')
                /* ->join('resellers_email_addresses', 'resellers.id', '=', 'resellers_email_addresses.username_id')
                ->join('resellers_mobile_numbers', 'resellers.id', '=', 'resellers_mobile_numbers.username_id')
                ->join('resellers_addresses', 'resellers.id', '=', 'resellers_addresses.username_id')
                ->leftJoin('resellers_secondary_numbers', 'resellers.id','resellers_secondary_numbers.username_id') */
                ->where('resellers_profiles.reseller_name', $name)
                ->first();
    }
}
