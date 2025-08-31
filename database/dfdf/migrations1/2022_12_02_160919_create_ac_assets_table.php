<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name_ar');
            $table->string('asset_name_en')->nullable();

            $table->unsignedBigInteger('asset_classes_id')->nullable(); //  تصنيف الاصل
            // $table->foreign('asset_classes_id')->references('id')->on('ac_asset_classes');

            $table->string('asset_description')->nullable();

            $table->integer('product_id')->unsigned()->nullable();
            // $table->foreign('product_id')->references('id')->on('products');
            // complet it now


            $table->bigInteger('asset_account')->nullable(); //  asset_account account_number foreign to ac_masters
            // $table->foreign('asset_account')->references('account_number')->on('ac_masters');

            $table->bigInteger('asset_expense_account')->nullable(); //  asset_account account_number foreign to ac_masters
            // $table->foreign('asset_expense_account')->references('account_number')->on('ac_masters');

            $table->bigInteger('accumulated_consumption_account')->nullable(); //  asset_account account_number foreign to ac_masters
            // $table->foreign('accumulated_consumption_account')->references('account_number')->on('ac_masters');


            
            $table->string('asset_value')->default(0); // قيمة الاصل
            $table->string('scrap_value')->default(0);  // قيمة الخردة
            $table->string('current_value')->default(0);  // القيمة الفعلية الحالية 
            
            $table->integer('business_id')->unsigned()->default(1);
            // $table->foreign('business_id')->references('id')->on('business');
            
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
        Schema::dropIfExists('ac_assets');
    }
}
