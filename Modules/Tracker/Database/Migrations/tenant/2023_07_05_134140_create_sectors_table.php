<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dm_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('phone');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('dm_provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dm_sectors');
    }
}
