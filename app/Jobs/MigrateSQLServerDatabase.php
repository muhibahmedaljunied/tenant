<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class MigrateSQLServerDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TenantWithDatabase $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
        Log::info('✅ MigrateSQLServerDatabase job instantiated for tenant: ' . $tenant->getTenantKey());
    }

    public function handle()
    {


        Log::info('🔧 current Connected to tenant DB: ' . DB::connection()->getDatabaseName());

        // Initialize tenancy context
        app(Tenancy::class)->initialize($this->tenant);

        Log::info('🚀 Running migration for tenant: '.$this->tenant->getTenantKey() . DB::connection()->getDatabaseName());


        Artisan::call('tenants:migrate', [
            '--tenants' => [$this->tenant->getTenantKey()],
            '--database' => 'sqlsrv',
            '--force' => true,
        ]);


        // Optionally end tenancy after migration
        app(Tenancy::class)->end();
        Log::info('🚀 end tenancy after migration');
    }
}
