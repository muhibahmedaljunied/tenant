<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcJournalEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_journal_entry_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('account_number')->nullable(); // account_number foreign to ac_masters
            // $table->foreign('account_number')->references('account_number')->on('ac_masters');

            $table->unsignedBigInteger('ac_journal_entries_id');
            $table->foreign('ac_journal_entries_id')->references('id')->on('ac_journal_entries');

            $table->text('entry_desc')->nullable(); // entry description
            $table->string('amount');

            // ------------------------------------
            $table->integer('business_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('location_id')->nullable();
            //-----------------------------------

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
        Schema::dropIfExists('ac_journal_entry_details');
    }
}
