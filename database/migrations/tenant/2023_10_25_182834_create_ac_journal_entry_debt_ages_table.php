<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

            $table->unsignedBigInteger('ac_journal_entries_id');
            $table->foreign('ac_journal_entries_id')->references('id')->on('ac_journal_entries');

            // $table->foreignId('ac_journal_entries_id')->constrained('ac_journal_entries');

            // -------------------
            // $table->unsignedDecimal('amount', 13, 4);
            $table->decimal('amount', 13, 4);

            // ----------------------------------------------- //
            $table->date('debtages_date');
            // ----------------------------------------------- //

            $table->timestamps();
        });
        // DB::statement('ALTER TABLE ac_journal_entry_debt_ages ADD CONSTRAINT check_amount_non_negative CHECK (amount >= 0)');
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
