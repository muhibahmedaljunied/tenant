<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZatcaPhaseToBusinessTable extends Migration
{
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->enum('zatca_phase', ['phase_1', 'phase_2'])->nullable();
        });
    }

    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn('zatca_phase');
        });
    }
}
