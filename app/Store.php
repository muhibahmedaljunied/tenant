<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public static function forDropdown(
        $business_id,

    ) {


        $query = Store::where('stores.business_id', $business_id)
            ->select();
        $result = $query->get();
        $stores = $result->pluck('name', 'id');
        return $stores;
    }
}
