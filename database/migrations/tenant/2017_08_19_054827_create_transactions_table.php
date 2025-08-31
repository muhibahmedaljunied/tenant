<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business');

            $table->integer('store_id')->unsigned();
            // $table->foreign('store_id')->references('id')->on('stores');

            $table->enum('type', [
                'purchase',
                'sell',
                'expense',
                'stock_adjustment',
                'sell_transfer',
                'purchase_transfer',
                'opening_stock',
                'sell_return',
                'opening_balance',
                'purchase_return'
            ])->default(null);
            $table->string('status');
            $table->enum('payment_status', ['paid', 'due', 'paid', 'due', 'partial', 'installmented']);
            $table->string('adjustment_type', 20)->nullable();
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->string('invoice_no')->nullable();
            $table->string('ref_no')->nullable();
            $table->dateTime('transaction_date');
            $table->decimal('total_before_tax', 22, 4)->default(0)
                ->comment('Total before the purchase/invoice tax, this includeds the indivisual product tax');
            $table->integer('tax_id')->unsigned()->nullable();
            $table->foreign('tax_id')->references('id')->on('tax_rates');
            $table->decimal('tax_amount', 22, 4)->default(0);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 22, 4)->default(0);
            $table->string('shipping_details')->nullable();
            $table->decimal('shipping_charges', 22, 4)->default(0);
            $table->text('additional_notes')->nullable();
            $table->text('staff_note')->nullable();
            $table->decimal('final_total', 22, 4)->default(0);
            $table->integer('created_by')->unsigned();
            $table->integer('return_parent_id')->unsigned();

            $table->tinyInteger('is_export')->default(0);


            $table->decimal('so_quantity_invoiced',22,4)->default(0.0000);

   








            // $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();

            //Indexing
            $table->index('business_id');
            $table->index('store_id');
            $table->index('type');
            $table->index('contact_id');
            $table->index('transaction_date');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
