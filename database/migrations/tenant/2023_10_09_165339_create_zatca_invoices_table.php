<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZatcaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zatca_invoices', function (Blueprint $table) {
            $table->id();
            // !------------------------------------------! //
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business');
            // !------------------------------------------! //
            $table->string('invoiceID');
            $table->string('invoiceNumber');
            // !------------------------------------------! //
            $table->uuid('invoiceUUid');
            // !------------------------------------------! //
            $table->dateTime('issue_at');
            // $table->time('invoiceIssueTime');
            // !------------------------------------------! //
            $table->date('invoiceActualDeliveryDate')->nullable();
            $table->date('invoiceLatestDeliveryDate')->nullable();
            // !------------------------------------------! //
            $table->double('total', 30, 2);
            $table->double('tax_total', 30, 2);
            $table->double('total_discount', 30, 2);
            $table->double('total_net', 30, 2);
            // !------------------------------------------! //
            $table->datetime('signing_time')->nullable();
            $table->string('hash')->nullable();
            // !------------------------------------------! //
            $table->enum('document_type', config('zatca.invoices_documents_types'));
            $table->enum('invoice_type', config('zatca.invoices_purpose_types'));
            // !------------------------------------------! //
            $table->boolean('sent_to_zatca')->default(false);
            // !------------------------------------------! //
            $table->string('sent_to_zatca_status')->nullable();
            $table->string('currency')->default('SAR');
            $table->longText('xml')->nullable();
            // !------------------------------------------! //
            $table->foreignId('zatca_id')->constrained('zatcas');
            $table->integer('transaction_id')->unsigned();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreignId('parent_id')->nullable()->constrained('zatca_invoices');
            // !------------------------------------------! //
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
        Schema::dropIfExists('zatca_invoices');
    }
}
