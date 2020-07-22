<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use\DB;

class Orders extends Model
{
    public static function order_list($order_ids) {
        return DB::table('order_statuses')
                ->select('*', 'orders.status as order_status')
                ->join('orders', 'orders.order_id', '=', 'order_statuses.order_id')
                ->whereIn('order_statuses.order_id', $order_ids)
                ->get();
    }

    public static function order_by_status($data) {
        return DB::table('orders')
                ->where('order_id', $data->order_id)
                ->first();
    }
}
