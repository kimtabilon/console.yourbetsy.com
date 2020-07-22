<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->text('order_id');
            $table->bigInteger('order_item_id');
            $table->text('sku');
            $table->text('name');
            $table->bigInteger('price');
            $table->bigInteger('orginal_price');
            $table->bigInteger('qty_canceled');
            $table->bigInteger('qty_invoiced');
            $table->bigInteger('qty_ordered');
            $table->bigInteger('qty_refunded');
            $table->bigInteger('qty_shipped');
            $table->bigInteger('row_total');
            $table->bigInteger('tax_amount');
            $table->bigInteger('tax_percent');
            $table->bigInteger('discount_amount');
            $table->bigInteger('amount_refunded');
            $table->bigInteger('product_id');
            $table->bigInteger('store_id');
            $table->text('status');
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
        Schema::dropIfExists('order_items');
    }
}
