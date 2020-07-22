<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use\DB;

class OrderStatuses extends Model
{
    public static function order_status_list($order_ids) {
        return DB::table('order_statuses')
                ->select('order_id', 'status')
                ->whereIn('order_id', $order_ids)
                ->get();
    }
}
