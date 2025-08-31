<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnablePatchNumberToBusiness extends Migration
{

    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->boolean('enable_patch_number')->default(0);
        });
    }


    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn('enable_patch_number');
        });
    }
}
