<?php

namespace App\Tenancy;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class SQLServerDatabaseManager implements TenantDatabaseManager
{
    protected string $connection;

    public function __construct()
    {
        $this->connection = config('database.default', 'sqlsrv');
    }




    protected function database(): Connection
    {
        Log::info('📡 SQLServerDatabaseManager::database() called');

        return DB::connection($this->connection ?? config('database.default', 'sqlsrv'));
    }



    public function setConnection(string $connection): void
    {
        $this->connection = $connection;
    }

    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        $database = $tenant->database()->getName();
        Log::info("🛠️ Creating SQL Server database '{$database}'");

        try {
            return $this->database()->statement("CREATE DATABASE [$database]");
        } catch (\Exception $e) {
            Log::error("❌ Failed to create database '{$database}': " . $e->getMessage());
            return false;
        }
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        $database = $tenant->database()->getName();

        // Check if the database exists
        $exists = $this->database()->select("SELECT name FROM sys.databases WHERE name = ?", [$database]);
        // $exists = DB::select("SELECT name FROM sys.databases WHERE name = ?", [$database]);

        if (empty($exists)) {
            Log::warning("⚠️ Cannot drop database '{$database}': it does not exist.");
            return false;
        }

        // Optional: terminate active connections to the database
        try {
            $this->database()->unprepared(" ALTER DATABASE [$database] SET SINGLE_USER WITH ROLLBACK IMMEDIATE");


            // DB::statement("
            //     ALTER DATABASE [$database] SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
            // ");
        } catch (\Exception $e) {
            Log::error("❌ Failed to set SINGLE_USER mode for '{$database}': " . $e->getMessage());
            return false;
        }

        // Attempt to drop the database
        try {
            $this->database()->unprepared("DROP DATABASE [$database]");

            // DB::statement("DROP DATABASE [$database]");
            Log::info("✅ Successfully dropped database '{$database}'.");
            return true;
        } catch (\Exception $e) {
            Log::error("❌ Failed to drop database '{$database}': " . $e->getMessage());
            return false;
        }
    }

    public function databaseExists(string $name): bool
    {
        $result = $this->database()->select("SELECT name FROM sys.databases WHERE name = ?", [$name]);

        return !empty($result);
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        $baseConfig['database'] = $databaseName;

        return $baseConfig;
    }
}
