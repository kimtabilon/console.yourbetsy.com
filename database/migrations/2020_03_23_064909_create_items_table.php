<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->text('sku');
            $table->bigInteger('sub_category_id');
            $table->text('product_name');
            $table->text('product_desc');
            $table->text('product_shortdesc');
            $table->bigInteger('price');
            $table->bigInteger('quantity');
            $table->bigInteger('handling_time');
            $table->bigInteger('special_price')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->text('made_in')->nullable();
            $table->bigInteger('username_id');
            $table->tinyInteger('status')->default(1)->comment('0 active, 1 pending, 2 decline, 3 suspended, 4 disable, 5 Resubmit');
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
        Schema::dropIfExists('items');
    }
}
