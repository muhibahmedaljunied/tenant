<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateTransactionsTableForSqlServer extends Migration
{
    public function up()
    {
        // 🧹 Drop index before altering 'contact_id' (SQL Server requires this)
        DB::statement("DROP INDEX transactions_contact_id_index ON transactions");

        // ✅ Alter 'contact_id' to BIGINT and nullable
        DB::statement("ALTER TABLE transactions ALTER COLUMN contact_id BIGINT NULL");

        // ✅ Recreate index after altering
        DB::statement("CREATE INDEX transactions_contact_id_index ON transactions(contact_id)");

        // ✅ Replace ENUM with VARCHAR + CHECK constraint
        DB::statement("ALTER TABLE transactions ALTER COLUMN type VARCHAR(20)");
        DB::statement("ALTER TABLE transactions ADD CONSTRAINT chk_transaction_type CHECK (type IN ('purchase','sell','expense'))");

        // ✅ Add new columns + foreign keys
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_category_id')->nullable()->after('final_total');
            $table->foreign('expense_category_id')->references('id')->on('expense_categories');

            $table->unsignedBigInteger('expense_for')->nullable()->after('expense_category_id');
            $table->foreign('expense_for')->references('id')->on('users');

            $table->index('expense_category_id');
        });
    }

    public function down()
    {
        // 🔄 Rollback logic
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['expense_category_id']);
            $table->dropForeign(['expense_for']);
            $table->dropIndex(['expense_category_id']);
            $table->dropColumn(['expense_category_id', 'expense_for']);
        });

        DB::statement("ALTER TABLE transactions DROP CONSTRAINT chk_transaction_type");
        DB::statement("DROP INDEX transactions_contact_id_index ON transactions");
        DB::statement("ALTER TABLE transactions ALTER COLUMN contact_id INT NULL"); // Optional: revert to INT
        DB::statement("CREATE INDEX transactions_contact_id_index ON transactions(contact_id)");
    }
}

