<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellersProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reseller_name')->unique();
            $table->string('contact_person');
            $table->tinyInteger('reseller_type')->comment('0 individual, 1 business');
            $table->tinyInteger('reseller_position')->default(0)->comment('0 Parent, 1 Child');
            $table->text('parent')->nullable();
            $table->text('business_permit_number')->nullable();
            $table->tinyInteger('already_member')->comment('Already Member 0 Yes 1 No');
            $table->bigInteger('username_id');
            $table->text('ip');
            /* $table->tinyInteger('status')->default(1)->comment('0 active, 1 pending, 2 decline, 3 suspended, 4 disable'); */
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
        Schema::dropIfExists('resellers_profiles');
    }
}
