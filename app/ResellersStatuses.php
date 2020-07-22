<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResellersStatuses extends Model
{
    public function reseller_info() {
        return $this->hasOne(Resellers::class, 'id', 'username_id');
    }
}
