<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostCenterToAcJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_journal_entries', function (Blueprint $table) {
            $table->bigInteger('cost_cen_branche_id')->unsigned()->nullable()->after('opening_account'); // account_number foreign to ac_masters
            $table->foreign('cost_cen_branche_id')->references('id')->on('ac_cost_cen_branches');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_journal_entries', function (Blueprint $table) {
            //
        });
    }
}
