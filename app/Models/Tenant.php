<?php

namespace App\Models;

use App\Models\TenantDomain;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\LogOptions;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $casts = [
        'data' => 'array',
    ];
    
    // protected $guarded = ['id'];
    protected $guarded = [];

    public function domain()
    {
        return $this->hasOne(TenantDomain::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }

    public function makeCredentials(): void
    {
        Log::info('✅ Custom makeCredentials() called');
    
        if (!$this->tenancy_db_name) {
            $this->tenancy_db_name = 'tenant' . $this->getTenantKey();
        }
    
        $credentials = $this->database()->manager()->makeConnectionConfig(
            config('database.connections.sqlsrv'),
            $this->tenancy_db_name
        );
    
        $this->syncOriginal(); // ✅ Reset dirty tracking
    
        $data = $this->data;
        $data['database'] = $credentials;
        $this->data = $data;
    
        Log::info('Dirty attributes before update:', $this->data);
    
        $this->updateQuietly([
            'tenancy_db_name' => $this->tenancy_db_name,
            'data' => $this->data,
        ]);
    }
    
    
    
}
