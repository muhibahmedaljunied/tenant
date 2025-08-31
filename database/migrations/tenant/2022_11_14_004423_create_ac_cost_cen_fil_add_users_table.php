<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcCostCenFilAddUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_cost_cen_fil_add_users', function (Blueprint $table) {

            $table->bigInteger('ac_cost_field_id')->unsigned();
            $table->foreign('ac_cost_field_id')->references('id')->on('ac_cost_cen_field_add');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            // ----------------------------------------------------------
            $table->integer('sequence')->nullable();
            // ----------------------------------------------------------


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_cost_cen_fil_add_users');
    }
}
