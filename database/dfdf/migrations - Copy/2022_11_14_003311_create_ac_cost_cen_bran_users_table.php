<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcCostCenBranUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_cost_cen_bran_users', function (Blueprint $table) {
            
            $table->bigInteger('ac_cost_branches_id')->unsigned();
            $table->foreign('ac_cost_branches_id')->references('id')->on('ac_cost_cen_branches')->onDelete('cascade');

            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_cost_cen_bran_users');
    }
}
