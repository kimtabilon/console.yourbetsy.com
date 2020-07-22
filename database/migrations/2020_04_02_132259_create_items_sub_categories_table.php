<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sub_category_name', 255)->unique();
            $table->bigInteger('category_id');
            $table->tinyInteger('status')->default(0)->comment('0 active, 1 inactive');
            $table->timestamps();
            $table->bigInteger('store_subcat_id')->comment('sub category id in store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_sub_categories');
    }
}
