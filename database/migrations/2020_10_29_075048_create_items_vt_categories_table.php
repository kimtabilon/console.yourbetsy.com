<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsVtCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_vt_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name', 255)->unique();
            $table->tinyInteger('status')->default(0)->comment('0 active, 1 inactive');
            $table->bigInteger('store_cat_id')->comment('category id in store');
            $table->bigInteger('related_category_id')->default(0)->comment('related category id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_vt_categories');
    }
}
