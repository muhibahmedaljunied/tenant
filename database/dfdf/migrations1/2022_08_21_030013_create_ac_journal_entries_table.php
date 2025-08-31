<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_journal_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_no'); // entry number
            $table->date('entry_date'); // entry date
            $table->text('entry_desc')->nullable(); // entry description
            $table->enum('entry_type',['daily','opening'])->default('daily'); // entry type 'dailyيومية عادية' Or 'openingقيد افتتاحى'
            
            $table->bigInteger('opening_account')->nullable();
            // $table->foreign('opening_account')->references('account_number')->on('ac_masters');
            // $table->bigInteger('opening_account');
 
            // $table->foreign('opening_account')->references('account_number')->on('ac_masters');

            // $table->bigInteger('opening_account_number')->nullable(); // entry description
            // $table->foreign('opening_account_number')->references('account_number')->on('ac_masters');
            
            $table->string('com_code')->default('111');
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
        Schema::dropIfExists('ac_journal_entries');
    }
}
