<?php

namespace App\Listeners;

use App\Events\TenantDeletingIDCardsThemeDir;
use App\Events\TenantDeletingStorageDir;
use App\Events\TenantIDCardsThemeDirDeleted;
use App\Events\TenantStorageDirDeleted;
use App\Notifications\SendTenantEvent;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Events\CreatingDatabase;
use Stancl\Tenancy\Events\CreatingDomain;
use Stancl\Tenancy\Events\CreatingTenant;
use Stancl\Tenancy\Events\DatabaseCreated;
use Stancl\Tenancy\Events\DatabaseDeleted;
use Stancl\Tenancy\Events\DatabaseMigrated;
use Stancl\Tenancy\Events\DatabaseSeeded;
use Stancl\Tenancy\Events\DeletingDomain;
use Stancl\Tenancy\Events\DeletingTenant;
use Stancl\Tenancy\Events\DomainCreated;
use Stancl\Tenancy\Events\DomainDeleted;
use Stancl\Tenancy\Events\MigratingDatabase;
use Stancl\Tenancy\Events\SeedingDatabase;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Events\TenantDeleted;

class TenantEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            TenantDeletingIDCardsThemeDir::class => 'handleDeletingThemeDir',
            TenantDeletingStorageDir::class => 'handleDeletingStorageDir',
            TenantIDCardsThemeDirDeleted::class => 'handleThemeDirDeleted',
            TenantStorageDirDeleted::class => 'handleStorageDirDeleted',
            CreatingTenant::class => 'handleCreatingTenant',
            TenantCreated::class => 'handleTenantCreated',
            DeletingTenant::class => 'handleDeletingTenant',
            TenantDeleted::class => 'handleTenantDeleted',
            CreatingDomain::class => 'handleCreatingDomain',
            DomainCreated::class => 'handleDomainCreated',
            DeletingDomain::class => 'handleDeletingDomain',
            DomainDeleted::class => 'handleDomainDeleted',
            CreatingDatabase::class => 'handleCreatingDB',
            DatabaseCreated::class => 'handleDBCreated',
            MigratingDatabase::class => 'handleDBMigration',
            DatabaseMigrated::class => 'handleDBMigrated',
            SeedingDatabase::class => 'handleSeedingDB',
            DatabaseSeeded::class => 'handleDBSeeded',
            DatabaseDeleted::class => 'handleDBDeleted',
        ];
    }

    public function handleDeletingThemeDir(TenantDeletingIDCardsThemeDir $event): void
    {
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';
        
        $event->data = ['msg' => 'Deleting tenant ID Cards theme dir...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDeletingStorageDir(TenantDeletingStorageDir $event): void
    {
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Deleting tenant storage dir...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleThemeDirDeleted(TenantIDCardsThemeDirDeleted $event): void
    {
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant ID Cards theme deleted.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleStorageDirDeleted(TenantStorageDirDeleted $event): void
    {
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant storage dir deleted.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleCreatingTenant(CreatingTenant $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleCreatingTenant');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Creating tenant...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleTenantCreated(TenantCreated $event): void
    {

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';


        Log::info('Listener triggered for SomeEvent  handleTenantCreated');
        $event->data = ['msg' => 'Tenant created.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDeletingTenant(DeletingTenant $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleDeletingTenant');
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Deleting tenant...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleTenantDeleted(TenantDeleted $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleTenantDeleted');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant deleted.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDBDeleted(DatabaseDeleted $event): void
    {
        Log::info('Listener triggered for SomeEvent  handleDBDeleted');


        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant database deleted.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleCreatingDomain(CreatingDomain $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleCreatingDomain');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Creating tenant domain...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDomainCreated(DomainCreated $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleDomainCreated');
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant domain created.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDeletingDomain(DeletingDomain $event): void
    {

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Deleting tenant domain...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDomainDeleted(DomainDeleted $event): void
    {

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant domain deleted.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleCreatingDB(CreatingDatabase $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleCreatingDB');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Creating tenant database...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDBMigration(MigratingDatabase $event): void
    {

        Log::info('Listener triggered for SomeEvent  handleDBMigration');
        // log::info(DB::connection());
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Migrating tenant database...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDBMigrated(DatabaseMigrated $event): void
    {
        
        Log::info('Listener triggered for SomeEvent  handleDBMigrated');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant database migrated.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleSeedingDB(SeedingDatabase $event): void
    {
        
        Log::info('Listener triggered for SomeEvent  handleSeedingDB');
        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Seeding tenant database...', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDBSeeded(DatabaseSeeded $event): void
    {
        Log::info('Listener triggered for SomeEvent  handleDBSeeded');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant database seeded.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }

    public function handleDBCreated(DatabaseCreated $event): void
    {
        Log::info('Listener triggered for SomeEvent  handleDBCreated');

        $url = Route::has('tenants.index') ? route('tenants.index') : '/';

        $event->data = ['msg' => 'Tenant database created.', 'url' => $url];
        Notification::sendNow(auth()->user() ?? [], new SendTenantEvent($event));
    }
}
