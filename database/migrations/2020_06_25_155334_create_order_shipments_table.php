<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ship_id')->comment('entity_id');
            $table->text('increment_id');
            $table->text('increment_order_id');
            $table->bigInteger('order_id');
            $table->bigInteger('shipping_address_id');
            $table->bigInteger('billing_address_id');
            $table->dateTime('ship_date');
            $table->bigInteger('total_qty');
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
        Schema::dropIfExists('order_shipments');
    }
}
