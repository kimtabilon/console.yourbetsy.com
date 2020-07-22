<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipment_items', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ship_id');
            $table->bigInteger('entity_id');
            $table->bigInteger('order_id');
            $table->bigInteger('product_id');
            $table->text('sku');
            $table->bigInteger('qty');
            $table->bigInteger('weight');
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
        Schema::dropIfExists('order_shipment_items');
    }
}
