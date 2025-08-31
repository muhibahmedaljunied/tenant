<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_masters', function (Blueprint $table) {
            $table->id();
            $table->string('account_name_ar');
            $table->string('account_name_en')->nullable();
            $table->bigInteger('account_number')->primary();
            $table->bigInteger('parent_acct_no')->nullable();
            $table->tinyInteger('account_level')->default(1);
            $table->boolean('pay_collect')->default(0);  //Is it possible to pay and collect this account?
            $table->string('account_type')->nullable(); // account_type((creditorدائن + ) (debtorمدين -))
            $table->boolean('transaction_made')->default(0);  //Have any transactions been made on this account?
            $table->boolean('archived')->default(0);  //Is the account archived?
            $table->float('current_balance',8,2)->default(0);
            $table->enum('account_status',['active','inactive'])->default('active');
            $table->boolean('stop_flag')->default(0);
            $table->string('com_code')->default('111');
            $table->unsignedBigInteger('business_id')->nullable();

            $table->index(['account_number']);
            
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
        Schema::dropIfExists('ac_masters');
    }
}
