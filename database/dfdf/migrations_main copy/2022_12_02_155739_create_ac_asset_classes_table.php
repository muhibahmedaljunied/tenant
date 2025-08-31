<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcAssetClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_asset_classes', function (Blueprint $table) {
            $table->id();
            $table->string('asset_class_name_ar');
            $table->string('asset_class_name_en')->nullable();
            $table->boolean('is_depreciable')->default(0);  //Is it possible to depreciable this asset_class?
            $table->enum('useful_life_type',['years','percent'])->default('years'); // نوع العمر الانتاجي
            $table->string('useful_life')->default(0); 

            $table->bigInteger('asset_account')->nullable(); // حساب الاصل
            $table->foreign('asset_account')->references('account_number')->on('ac_masters')->onDelete('cascade');


            $table->bigInteger('asset_expense_account')->nullable(); // حساب مصروف الاصل
            $table->foreign('asset_expense_account')->references('account_number')->on('ac_masters')->onDelete('cascade');

            $table->bigInteger('accumulated_consumption_account')->nullable(); // حساب مجمع الاستهلاك
            $table->foreign('accumulated_consumption_account')->references('account_number')->on('ac_masters')->onDelete('cascade');


            $table->integer('business_id')->unsigned()->default(1);
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');

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
        Schema::dropIfExists('ac_asset_classes');
    }
}
