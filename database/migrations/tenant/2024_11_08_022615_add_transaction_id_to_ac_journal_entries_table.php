<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionIdToAcJournalEntriesTable extends Migration
{
    public function up()
    {
        Schema::table('ac_journal_entries', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id')->nullable()->after('id');
            $table->foreign('transaction_id')->references("id")->on('transactions')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('ac_journal_entries', function (Blueprint $table) {
            $table->dropForeign('transaction_id');
            $table->dropColumn('transaction_id');
        });
    }
}
