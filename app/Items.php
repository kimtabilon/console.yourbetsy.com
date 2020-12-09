<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Items extends Model
{
    public function profile() {
        return $this->hasOne(ResellersProfiles::class, 'username_id', 'username_id');
    }

    public function email_add() {
        return $this->hasOne(ResellersEmailAddresses::class, 'username_id', 'username_id');
    }

    public function item_histories() {
        return $this->hasMany(ItemsHistories::class, 'item_id', 'id');
    }

    public function items_sub_categories() {
        return $this->hasOne(ItemsSubCategories::class, 'id', 'sub_category_id');
    }
    
    public function shipping_policy() {
        return $this->hasOne(ResellersShippingPolicies::class, 'username_id', 'username_id');
    }

    public function return_policy() {
        return $this->hasOne(ResellersReturnPolicies::class, 'username_id', 'username_id');
    }

    public function payment_informations() {
        return $this->hasOne(ResellersPaymentInformations::class, 'username_id', 'username_id');
    }

    public static function getSKUsByUserId($user_id) {
        return DB::table('items')
                ->select('sku')
                ->where('username_id', $user_id)
                ->get();
    }
}
