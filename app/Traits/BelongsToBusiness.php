<?php

namespace App\Traits;

use App\Scopes\BusinessScope;

trait BelongsToBusiness
{
    protected static function bootBelongsToBusiness()
    {
        static::addGlobalScope(new BusinessScope);
        static::creating(function ($account) {
            if(!$account->business_id && request()->session()->get('user.business_id')) {
                $account->business_id = request()->session()->get('user.business_id');
            }
        });
    }
}