<?php

namespace App\Jobs;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Database\DatabaseManager;
use Stancl\Tenancy\Events\CreatingDatabase;
use Stancl\Tenancy\Events\DatabaseCreated;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class CreateSQLServerDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Tenant $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
        Log::info('✅ CreateSQLServerDatabase job instantiated for tenant: ' . $tenant->getTenantKey());
    }

    public function handle(DatabaseManager $databaseManager)
    {

        log::info('vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv'.config('tenancy.cache.tag_base'));
        event(new CreatingDatabase($this->tenant));


        // Terminate execution of this job & other jobs in the pipeline
        if ($this->tenant->getInternal('create_database') === false) {
            return false;
        }
        $this->tenant->makeCredentials();
        // $this->tenant->database()->makeCredentials();

        Log::info('🔧 current Connected to tenant DB: 2');

        $databaseManager->ensureTenantCanBeCreated($this->tenant);
        // Log::info('🔧 current Connected to tenant DB: 3');
        $this->tenant->database()->manager()->createDatabase($this->tenant);
        // Log::info('🔧 current Connected to tenant DB: 4');
        event(new DatabaseCreated($this->tenant));
    }
}
