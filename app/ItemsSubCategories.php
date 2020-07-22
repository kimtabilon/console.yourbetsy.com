<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemsSubCategories extends Model
{
    public function category() {
        return $this->hasOne(ItemsCategories::class, 'id', 'category_id');
    }
}
