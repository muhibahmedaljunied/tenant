<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcCostCenBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_cost_cen_branches', function (Blueprint $table) {
            $table->id();
            $table->string('cost_code')->nullable();
            $table->string('cost_description');
            $table->bigInteger('parent_cost_no')->nullable();
            $table->tinyInteger('cost_level')->default(1);
            $table->integer('business_id')->unsigned()->default(1);
            $table->foreign('business_id')->references('id')->on('business');

            $table->softDeletes();
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
        Schema::dropIfExists('ac_cost_cen_branches');
    }
}
