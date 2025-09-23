<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('business_id');
            $table->string('reference_number',255)->unsigned();
            $table->integer('contact_id')->unsigned();
            $table->text('description');
            $table->string('type',255);
            $table->date('date');
            $table->decimal('amount',10,0);
            $table->string('contact_type',255);
            $table->integer('acount_id')->unsigned();
            $table->integer('branch_cost_center_id')->unsigned();
            $table->integer('extra_cost_center_id')->unsigned();
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
        Schema::dropIfExists('payments');
    }
}
