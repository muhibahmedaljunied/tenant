<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatchNumberToPurchaseLines extends Migration
{
    public function up()
    {
        Schema::table('purchase_lines', function (Blueprint $table) {
            $table->string('patch_number')->nullable();
        });
    }
    public function down()
    {
        Schema::table('purchase_lines', function (Blueprint $table) {
            $table->dropColumn('patch_number');
        });
    }
}
