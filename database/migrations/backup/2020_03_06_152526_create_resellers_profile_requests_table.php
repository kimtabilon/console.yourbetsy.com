<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResellersProfileRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('resellers_profile_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('username_id');
            $table->text('requested_data')->comment('json_format');
            $table->tinyInteger('status')->default(1)->comment('0 approved, 1 pending, 2 decline');
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
        Schema::dropIfExists('resellers_profile_requests');
    }
}
