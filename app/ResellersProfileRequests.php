<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResellersProfileRequests extends Model
{
    public function resellerDetails() {
        return $this->hasOne(Resellers::class, 'id', 'username_id');
    }

    public function resellerProfile() {
        return $this->hasOne(ResellersProfiles::class, 'username_id', 'username_id');
    }
}
