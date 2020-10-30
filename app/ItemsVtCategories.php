<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ItemsVtCategories extends Model
{
    public static function getByID($id) {
        return DB::table('items_vt_categories')
                  ->where('id', $id)
                  ->get();
    }

    public static function getByParentID($parent_id) {
        return DB::table('items_vt_categories')
                  ->where('related_category_id', $parent_id)
                  ->get();
    }

    public static function getByIDs($ids) {
        return DB::table('items_vt_categories')
                  ->whereIn('id', $ids)
                  ->get();
    }

}
