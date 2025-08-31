<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostCenterFieldAddToAcJournalEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_journal_entry_details', function (Blueprint $table) {
            
            $table->bigInteger('cost_cen_field_id')->unsigned()->nullable()->after('ac_journal_entries_id'); // account_number foreign to ac_masters
            $table->foreign('cost_cen_field_id')->references('id')->on('ac_cost_cen_field_add')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_journal_entry_details', function (Blueprint $table) {
            //
        });
    }
}
