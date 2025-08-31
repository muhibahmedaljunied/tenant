<?php


use App\User;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Broadcast::routes();
    Broadcast::channel('messages.{thread_id}.{tenant_id}', function (User $user, $thread_id) {
        return Participant::where('thread_id', $thread_id)->where('user_id', $user->id)->exists();
    });
});

