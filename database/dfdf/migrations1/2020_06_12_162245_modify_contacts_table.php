<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('prefix')->after('name')->nullable();
            $table->string('first_name')->after('prefix')->nullable();
            $table->string('middle_name')->after('first_name')->nullable();
            $table->string('last_name')->after('middle_name')->nullable();
            $table->text('address_line_2')->after('landmark')->nullable();
            $table->string('zip_code')->after('address_line_2')->nullable();
            $table->date('dob')->after('zip_code')->nullable();
        });

        // DB::statement("ALTER TABLE contacts CHANGE landmark address_line_1 text;");

        // DB::statement("UPDATE contacts SET first_name=name;");


        // Rename column 'landmark' to 'address_line_1' and change its type to TEXT
        DB::statement("EXEC sp_rename 'contacts.landmark', 'address_line_1', 'COLUMN'");
        DB::statement("ALTER TABLE contacts ALTER COLUMN address_line_1 VARCHAR(MAX)");

        // Update 'first_name' with the value from 'name'
        DB::statement("UPDATE contacts SET first_name = name");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
}
