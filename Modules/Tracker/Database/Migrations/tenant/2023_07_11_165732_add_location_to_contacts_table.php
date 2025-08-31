<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->unsignedBigInteger('distribution_area_id')->nullable();
            $table->unsignedBigInteger('track_id')->nullable();
            $table->json('location')->nullable();

            $table->foreign('province_id')->references('id')->on('dm_provinces')->onDelete('set null');
            $table->foreign('sector_id')->references('id')->on('dm_sectors')->onDelete('set null');
            $table->foreign('distribution_area_id')->references('id')->on('dm_distribution_areas')->onDelete('set null');
            $table->foreign('track_id')->references('id')->on('dm_tracks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['province_id', 'sector_id', 'distribution_area_id', 'track_id']);

            $table->removeColumn('location');
            $table->removeColumn('province_id');
            $table->removeColumn('sector_id');
            $table->removeColumn('distribution_area_id');
            $table->removeColumn('track_id');


        });
    }
}
