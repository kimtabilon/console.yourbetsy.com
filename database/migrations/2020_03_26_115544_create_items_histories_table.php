<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('item_id');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0 active, 1 pending, 2 decline, 3 suspended, 4 disable, 5 Resubmit');
            $table->dateTime('date_modified')->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->timestamps();
            $table->text('action')->nullable();
            $table->text('type_of_modifier')->default("Admin");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_histories');
    }
}
