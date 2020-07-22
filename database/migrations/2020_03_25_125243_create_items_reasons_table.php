<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('item_id');
            $table->text('reason')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0 active, 1 pending, 2 decline, 3 suspended, 4 disable');
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
        Schema::dropIfExists('items_reasons');
    }
}
