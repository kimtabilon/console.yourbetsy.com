<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->text('order_id');
            $table->bigInteger('entity_id');
            $table->dateTime('date_ordered');
            $table->text('customer_email');
            $table->text('customer_firstname');
            $table->text('customer_lastname');
            $table->text('billing_address');
            $table->text('shipping_address');
            $table->text('payment_information')->comment('json format');
            $table->text('shipping_description');
            $table->bigInteger('shipping_incl_tax');
            $table->bigInteger('shipping_invoiced');
            $table->bigInteger('grand_total');
            $table->bigInteger('total_paid');
            $table->bigInteger('total_due');
            $table->bigInteger('total_refunded');
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
        Schema::dropIfExists('orders');
    }
}
