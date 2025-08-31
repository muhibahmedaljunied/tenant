<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountNumberToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->bigInteger('account_number_customer')->nullable()->after('name'); // account_number foreign to ac_masters
            // $table->foreign('account_number_customer')->references('account_number')->on('ac_masters');

            $table->bigInteger('account_number_supplier')->nullable()->after('account_number_customer'); // account_number foreign to ac_masters
            // $table->foreign('account_number_supplier')->references('account_number')->on('ac_masters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('account_number_customer');
            $table->dropColumn('account_number_supplier');
        });
    }
}
