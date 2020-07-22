<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderShipmentTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipment_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('entity_id'); 
            $table->bigInteger('ship_id');
            $table->bigInteger('order_id');
            $table->dateTime('track_date_added');
            $table->text('description');
            $table->text('track_number');
            $table->text('title');
            $table->text('carrier_code');
            $table->bigInteger('weight');
            $table->bigInteger('qty');
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
        Schema::dropIfExists('order_shipment_tracks');
    }
}
