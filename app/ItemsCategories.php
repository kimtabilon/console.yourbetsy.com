<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemsCategories extends Model
{
    public function items_sub_categories() {
        return $this->hasMany(ItemsSubCategories::class, 'category_id', 'id');
    }
}
