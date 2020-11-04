<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Resellers extends Authenticatable
{
    use Notifiable;

    protected $guard = 'reseller';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function status() {
        return $this->hasOne(ResellersStatuses::class, 'username_id');
    }

    public function profile() {
        return $this->hasOne(ResellersProfiles::class, 'username_id');
    }

    public function email_address() {
        return $this->hasOne(ResellersEmailAddresses::class, 'username_id');
    }

    public function mobile_numbers() {
        return $this->hasOne(ResellersMobileNumbers::class, 'username_id');
    }

    public function secondary_contact_numbers() {
        return $this->hasOne(ResellersSecondaryNumbers::class, 'username_id');
    }

    public function address() {
        return $this->hasOne(ResellersAddresses::class, 'username_id');
    }

    public function about_us() {
        return $this->hasOne(ResellersAboutUses::class, 'username_id');
    }

    public function shipping_policy() {
        return $this->hasOne(ResellersShippingPolicies::class, 'username_id');
    }

    public function return_policy() {
        return $this->hasOne(ResellersReturnPolicies::class, 'username_id');
    }

    public function payment_information() {
        return $this->hasOne(ResellersPaymentInformations::class, 'username_id');
    }

    public static function pending_resellers($status) {
        return DB::table('resellers')
                ->select('resellers.username','resellers.created_at as signup_date','resellers_profiles.*','resellers_statuses.*','resellers_statuses.id as reseller_status_id','resellers_statuses.username_id as rs_username_id')
                ->join('resellers_statuses', 'resellers.id', '=', 'resellers_statuses.username_id')
                ->join('resellers_profiles', 'resellers.id', '=', 'resellers_profiles.username_id')
                ->whereIn('resellers_statuses.status', $status)
                ->get();
    }

    public static function resellers_by_userID($id) {
        /* return DB::table('resellers')
                ->select('resellers.username','resellers.created_at as signup_date',
                        'resellers_profiles.*','resellers_statuses.*',
                        'resellers_statuses.id as reseller_status_id',
                        'resellers_email_addresses.email_address',
                        'resellers_landline_numbers.landline_number','resellers_mobile_numbers.mobile_number',
                        'resellers_addresses.address')
                ->join('resellers_statuses', 'resellers.id', '=', 'resellers_statuses.username_id')
                ->join('resellers_profiles', 'resellers.id', '=', 'resellers_profiles.username_id')
                ->join('resellers_email_addresses', 'resellers.id', '=', 'resellers_email_addresses.username_id')
                ->join('resellers_landline_numbers', 'resellers.id', '=', 'resellers_landline_numbers.username_id')
                ->join('resellers_mobile_numbers', 'resellers.id', '=', 'resellers_mobile_numbers.username_id')
                ->join('resellers_addresses', 'resellers.id', '=', 'resellers_addresses.username_id')
                ->where('resellers.id', $id)
                ->first(); */
                return DB::table('resellers')
                ->select('resellers.username','resellers.created_at as signup_date',
                        'resellers_profiles.*','resellers_statuses.*',
                        'resellers_statuses.id as reseller_status_id',
                        'resellers_email_addresses.email_address',
                        'resellers_mobile_numbers.mobile_number',
                        'resellers_addresses.address',
                        'resellers_secondary_numbers.secondary_number')
                ->join('resellers_statuses', 'resellers.id', '=', 'resellers_statuses.username_id')
                ->join('resellers_profiles', 'resellers.id', '=', 'resellers_profiles.username_id')
                ->join('resellers_email_addresses', 'resellers.id', '=', 'resellers_email_addresses.username_id')
                ->join('resellers_mobile_numbers', 'resellers.id', '=', 'resellers_mobile_numbers.username_id')
                ->join('resellers_addresses', 'resellers.id', '=', 'resellers_addresses.username_id')
                ->leftJoin('resellers_secondary_numbers', 'resellers.id','resellers_secondary_numbers.username_id')
                ->where('resellers.id', $id)
                ->first();
    }
}
