<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepreciationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depreciations', function (Blueprint $table) {

            $table->increments('id');
            $table->string('reference_number');
            $table->integer('asset_class_id ')->unsigned();
            $table->string('asset_id')->unsigned();
            $table->string('depreciation_period');
            $table->integer('from_year');
            $table->integer('from');
            $table->integer('to_year');
            $table->integer('to');
            $table->integer('business_id')->unsigned();
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
        Schema::dropIfExists('depreciations');
    }
}
