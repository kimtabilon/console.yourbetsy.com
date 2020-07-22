<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellersStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('username_id');
            $table->tinyInteger('status')->default(1)->comment('0 active, 1 pending, 2 decline, 3 suspended, 4 disable');
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
        Schema::dropIfExists('resellers_statuses');
    }
}
