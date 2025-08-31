<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_settings', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('current_period_profit_loss')->default(3401)->comment(' ارباح او خسائر الفترة الحالية'); // account_number foreign to ac_masters
            // $table->foreign('current_period_profit_loss')->references('account_number')->on('ac_masters');
            $table->boolean('current_period_profit_loss_status')->default(1);

            $table->bigInteger('stage_period_profit_loss')->default(3402)->comment('الارباح والخسائر المرحلة'); // account_number foreign to ac_masters
            // $table->foreign('stage_period_profit_loss')->references('account_number')->on('ac_masters');
            $table->boolean('stage_period_profit_loss_status')->default(1);

            $table->bigInteger('customers')->default(110201)->comment('العملاء '); // account_number foreign to ac_masters
            // $table->foreign('customers')->references('account_number')->on('ac_masters');
            $table->boolean('customers_status')->default(1);

            $table->bigInteger('sold_goods_cost')->default(5101)->comment('تكلفة البضاعة المباعة '); // account_number foreign to ac_masters
            // $table->foreign('sold_goods_cost')->references('account_number')->on('ac_masters');
            $table->boolean('sold_goods_cost_status')->default(1);

            $table->bigInteger('inventory')->default(1103)->comment('المخزون '); // account_number foreign to ac_masters
            // $table->foreign('inventory')->references('account_number')->on('ac_masters');
            $table->boolean('inventory_status')->default(1);

            $table->bigInteger('sales_service_revenue')->default(4101)->comment('ايرادات المبيعات او الخدمات '); // account_number foreign to ac_masters
            // $table->foreign('sales_service_revenue')->references('account_number')->on('ac_masters');
            $table->boolean('sales_service_revenue_status')->default(1);

            $table->bigInteger('vat_due')->default(210501)->comment('ضريبة القيمة المضافة المستحقة  '); // account_number foreign to ac_masters
            // $table->foreign('vat_due')->references('account_number')->on('ac_masters');
            $table->boolean('vat_due_status')->default(1);

            $table->bigInteger('cash_equivalents')->default(1101)->comment(' النقدية وما يعادلها '); // account_number foreign to ac_masters
            // $table->foreign('cash_equivalents')->references('account_number')->on('ac_masters');
            $table->boolean('cash_equivalents_status')->default(1);

            $table->bigInteger('suppliers')->default(210101)->comment('المورديين  '); // account_number foreign to ac_masters
            // $table->foreign('suppliers')->references('account_number')->on('ac_masters');
            $table->boolean('suppliers_status')->default(1);
            //for income statement
            $table->bigInteger('operating_income')->default(41)->comment('ايرادات تشغيلية '); // account_number foreign to ac_masters
            // $table->foreign('operating_income')->references('account_number')->on('ac_masters');
            $table->boolean('operating_income_status')->default(1);

            $table->bigInteger('direct_expenses')->default(51)->comment('المصاريف المباشرة  '); // account_number foreign to ac_masters
            // $table->foreign('direct_expenses')->references('account_number')->on('ac_masters');
            $table->boolean('direct_expenses_status')->default(1);

            $table->bigInteger('non_operating_income')->default(42)->comment(' ايرادات غير تشغيلية  '); // account_number foreign to ac_masters
            // $table->foreign('non_operating_income')->references('account_number')->on('ac_masters');
            $table->boolean('non_operating_income_status')->default(1);

            $table->bigInteger('operating_expenses')->default(52)->comment(' مصاريف تشغيلية '); // account_number foreign to ac_masters
            // $table->foreign('operating_expenses')->references('account_number')->on('ac_masters');
            $table->boolean('operating_expenses_status')->default(1);

            $table->bigInteger('non_operating_expenses')->default(53)->comment(' مصاريف غير تشغيلية  '); // account_number foreign to ac_masters
            // $table->foreign('non_operating_expenses')->references('account_number')->on('ac_masters');
            $table->boolean('non_operating_expenses_status')->default(1);

            // for balance sheet
            $table->bigInteger('assets')->default(1)->comment(' الاصول '); // account_number foreign to ac_masters
            // $table->foreign('assets')->references('account_number')->on('ac_masters');
            $table->boolean('assets_status')->default(1);
            
            $table->bigInteger('liabilities')->default(2)->comment(' الخصوم '); // account_number foreign to ac_masters
            // $table->foreign('liabilities')->references('account_number')->on('ac_masters');
            $table->boolean('liabilities_status')->default(1);

            $table->bigInteger('equity')->default(3)->comment(' حقوق الملكية '); // account_number foreign to ac_masters
            // $table->foreign('equity')->references('account_number')->on('ac_masters');
            $table->boolean('equity_status')->default(1);



            $table->integer('business_id')->unsigned()->default(1);
            // $table->foreign('business_id')->references('id')->on('business');


            
            // --------------------------------muhib add this ------------------------

            $table->integer('payment_method_card')->nullable();
            $table->integer('payment_method_cash');
            $table->integer('payment_method_cheque');
            $table->integer('payment_method_bank_transfer');
            $table->integer('payment_method_other');
            $table->integer('payment_method_custom_pay_1');
            $table->integer('sales_services_return')->nullable();
            $table->integer('sales_return')->nullable();
            $table->integer('depreciation_expense')->nullable();
            $table->integer('accumulated_depreciation')->nullable();
            $table->integer('branch_cost_center_id')->nullable();
            $table->integer('extra_cost_center_id')->nullable();
            $table->integer('stock_opening_account')->nullable();
            // $table->bigInteger('transactions_inventory');
            // $table->tinyInteger('transactions_inventory_status');
            // $table->bigInteger('beginning_inventory');
            // $table->tinyInteger('beginning_inventory_status');
            // $table->bigInteger('ending_stock_inventory');
            // $table->bigInteger('ending_stock_inventory_status');
            // $table->bigInteger('disability_inventory_id');
            // $table->bigInteger('increase_inventory_id ');
            // $table->tinyInteger('disability_inventory_status');
            // $table->tinyInteger('increase_inventory_status');

            // --------------------------------------------


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
        Schema::dropIfExists('ac_settings');
    }
}
