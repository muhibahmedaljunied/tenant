<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcJournalEntryDebtAgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_journal_entry_debt_ages', function (Blueprint $table) {
            $table->id();
            // ----------------------------------------------- //
            $table->foreignId('ac_journal_entries_id')->constrained('ac_journal_entries')->cascadeOnDelete();
            $table->unsignedDecimal('amount',13,4);
            // ----------------------------------------------- //
            $table->date('debtages_date');
            // ----------------------------------------------- //

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
        Schema::dropIfExists('ac_journal_entry_debt_ages');
    }
}
