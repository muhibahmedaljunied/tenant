<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dm_distribution_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('phone');
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('business_id');
            $table->json('points');
            $table->timestamps();

            $table->foreign('sector_id')->references('id')->on('dm_sectors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dm_distribution_areas');
    }
}
