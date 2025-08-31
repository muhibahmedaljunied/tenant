<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dm_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');

            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('distribution_area_id');

            $table->foreign('province_id')->references('id')->on('dm_provinces');
            $table->foreign('sector_id')->references('id')->on('dm_sectors');
            $table->foreign('distribution_area_id')->references('id')->on('dm_distribution_areas');

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
        Schema::dropIfExists('dm_tracks');
    }
}
