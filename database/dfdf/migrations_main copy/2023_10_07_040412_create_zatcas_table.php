<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZatcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zatcas', function (Blueprint $table) {
            $table->id();
            // !------------------------------------------! //
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            // !------------------------------------------! //
            $table->string('name');
            $table->string('mobile');
            // !------------------------------------------! //
            $table->bigInteger('trn')->comment('Tax Registration Number');
            $table->bigInteger('crn')->comment('Commercial Registration Number');
            // !------------------------------------------! //
            $table->string('street_name');
            $table->integer('building_number');
            $table->integer('plot_identification');
            $table->string('region');
            $table->string('city');
            $table->integer('postal_number');
            $table->string('egs_serial_number');
            $table->string('business_category');
            $table->string('common_name');
            $table->string('organization_unit_name');
            $table->string('organization_name');
            // !------------------------------------------! //
            $table->string('country_name')->comment('Country code')->default('SA');
            $table->string('registered_address');
            $table->string('otp');
            $table->string('email_address');
            $table->enum('invoice_type', config('zatca.invoices_issuing_types'));
            $table->boolean('is_production')->default(false);
            // !------------------------------------------! //
            $table->longText('cnf')->nullable();
            $table->longText('private_key')->nullable();
            $table->longText('public_key')->nullable();
            $table->longText('csr_request')->nullable();
            $table->longText('certificate')->nullable();
            $table->string('secret')->nullable();
            $table->string('csid')->nullable();
            // !------------------------------------------! //
            $table->longText('production_certificate')->nullable();
            $table->string('production_secret')->nullable();
            $table->string('production_csid')->nullable();
            // !------------------------------------------! //
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // !------------------------------------------! //
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
        Schema::dropIfExists('zatcas');
    }
}
