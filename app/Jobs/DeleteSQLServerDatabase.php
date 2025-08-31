<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use App\Tenancy\SQLServerDatabaseManager;

class DeleteSQLServerDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TenantWithDatabase $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
        Log::info('🧨 DeleteSQLServerDatabase job instantiated for tenant: ' . $tenant->getTenantKey());
    }

    public function handle()
    {
        Log::info('🧨 Executing DeleteSQLServerDatabase job for tenant: ' . $this->tenant->getTenantKey());

        $manager = app(SQLServerDatabaseManager::class);
        $manager->deleteDatabase($this->tenant);
    }
}
